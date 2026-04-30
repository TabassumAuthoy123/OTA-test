<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\FlightBooking;
use App\Models\FlyhubFlightBooking;
use App\Models\FlyhubFlightTicketIssue;
use App\Models\FlyhubGdsConfig;
use App\Models\Gds;
use App\Models\SavedPassenger;
use App\Models\User;
use Yajra\DataTables\DataTables;
use App\Models\FlightPassenger;
use App\Models\FlightSegment;
use App\Models\SabreBookingDetails;
use App\Models\SabreFlightBooking;
use App\Models\SabreFlightTicketIssue;
use App\Models\SabreGdsConfig;
use App\Services\Gds\SabreService;
use App\Services\Gds\FlyhubService;
use App\Services\BookingService;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Enums\UserType;
use App\Helpers\EmailHelper;
use App\Mail\BookingConfirmationEmail;
use App\Mail\TicketIssuedEmail;

class FlightBookingController extends Controller
{
    protected SabreService $sabreService;
    protected FlyhubService $flyhubService;
    protected BookingService $bookingService;

    public function __construct(SabreService $sabreService, FlyhubService $flyhubService, BookingService $bookingService)
    {
        $this->sabreService = $sabreService;
        $this->flyhubService = $flyhubService;
        $this->bookingService = $bookingService;
    }
    public function bookFlightWithPnrSabre(Request $request)
    {
        if (!isset($request->first_name[0]) || !$request->first_name[0] || !isset($request->last_name[0]) || !$request->last_name[0] || !$request->traveller_contact || !$request->traveller_email || !isset($request->titles[0]) || !$request->titles[0] || !isset($request->dob[0]) || !$request->dob[0]) {
            Toastr::error('Passenger Information Missing', 'Failed');
            return redirect('/home');
        }

        $result = $this->bookingService->createSabreBooking($request);

        if ($result['status'] == 0) {
            Toastr::success('Flight Booking Request Sent', 'Success');
        } else {
            Toastr::success('Flight Booked Successfully', 'Success');
        }

        // Send booking confirmation email
        EmailHelper::send(Auth::user()->email, new BookingConfirmationEmail([
            'agent_name' => Auth::user()->name,
            'booking_no' => $result['booking_no'] ?? '',
            'pnr' => $result['pnr'] ?? '',
            'route' => $request->route_info ?? '',
            'passenger_count' => count($request->first_name ?? []),
            'status' => $result['status'] == 0 ? 'Pending' : 'Confirmed',
        ]));

        $dest = Auth::user()->user_type == 2 ? '/my/bookings' : '/view/all/booking';
        return redirect($dest);
    }

    public function bookFlightWithPnr(Request $request)
    {
        $request->validate([
            'first_name' => 'required|array|min:1',
            'first_name.*' => 'required|string|max:100',
            'last_name' => 'required|array|min:1',
            'last_name.*' => 'required|string|max:100',
            'titles' => 'required|array|min:1',
            'titles.*' => 'required|string|in:Mr,Mrs,Ms,Mstr,Miss',
            'dob' => 'required|array|min:1',
            'dob.*' => 'required|date',
            'traveller_contact' => 'required|string|max:50',
            'traveller_email' => 'required|email|max:255',
        ]);

        $result = $this->bookingService->createFlyhubBooking($request);

        if (!$result['success']) {
            Toastr::error($result['error'] ?? 'Failed to Book this Flight');
            return back();
        }

        // Send booking confirmation email
        EmailHelper::send(Auth::user()->email, new BookingConfirmationEmail([
            'agent_name' => Auth::user()->name,
            'booking_no' => $result['booking_no'] ?? '',
            'pnr' => $result['pnr'] ?? '',
            'route' => $request->route_info ?? '',
            'passenger_count' => count($request->first_name ?? []),
            'status' => 'Pending',
        ]));

        Toastr::success('Flight Booking Request Sent', 'Success');
        $dest = Auth::user()->user_type == 2 ? '/my/bookings' : '/view/all/booking';
        return redirect($dest);
    }

    public function viewAllBooking(Request $request)
    {

        if ($request->ajax()) {

            // removing log coloumns
            $columns = Schema::getColumnListing('flight_bookings');
            $excluded = ['booking_request', 'booking_response', 'get_booking_response', 'ticketing_response', 'ticketing_cancel_response'];
            $columns = array_diff($columns, $excluded);
            $columns = array_map(function ($col) {
                return "flight_bookings.$col";
            }, $columns);

            $query = DB::table('flight_bookings')
                ->leftJoin('users', 'flight_bookings.booked_by', '=', 'users.id')
                ->select([...$columns, 'users.name as b2b_user'])
                ->where(function ($q) {
                    $q->where('flight_bookings.status', 1)
                        ->orWhere('flight_bookings.status', 0);
                })
                ->orderBy('flight_bookings.id', 'desc');

            if (Auth::user()->user_type != UserType::Admin->value && Auth::user()->user_type != UserType::SuperAdmin->value) {
                $query->where('flight_bookings.booked_by', Auth::user()->id);
            }

            return Datatables::of($query)
                ->filterColumn('b2b_user', function ($query, $keyword) {
                    $query->where('users.name', 'like', "%{$keyword}%");
                })
                ->addColumn('flight_routes', function ($data) {
                    $routeString = $data->departure_location . " - " . $data->arrival_location;
                    if ($data->flight_type == 2) {
                        $routeString .= " - " . $data->departure_location;
                    }
                    return $routeString;
                })
                ->editColumn('created_at', function ($data) {
                    return date("Y-m-d h:i a", strtotime($data->created_at));
                })
                ->editColumn('total_fare', function ($data) {
                    return $data->currency . " " . number_format($data->total_fare);
                })
                ->editColumn('status', function ($data) {
                    if ($data->status == 0)
                        return "<span style='font-weight:600; color:goldenrod'>Booking Request</span>";
                    if ($data->status == 1)
                        return "<span style='font-weight:600; color:green'>Booked</span>";
                    if ($data->status == 2)
                        return "<span style='font-weight:600; color:green'>Issued</span>";
                    if ($data->status == 3)
                        return "<span style='font-weight:600; color:red'>Cancelled</span>";
                    if ($data->status == 4)
                        return "<span style='font-weight:600; color:red'>Cancelled</span>";

                })
                ->addColumn('total_passengers', function ($data) {
                    return $data->adult + $data->child + $data->infant;
                })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = ' <a href="' . url('flight/booking/details') . "/" . $data->booking_no . '" class="btn-sm btn-info text-white rounded d-inline-block mb-1"><i class="fas fa-eye"></i></a>';
                    // $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->id.'" data-original-title="Delete" class="btn-sm btn-danger rounded d-inline-block deleteBtn"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('booking.view');

    }

    public function viewCancelBooking(Request $request)
    {

        if ($request->ajax()) {

            // removing log coloumns
            $columns = Schema::getColumnListing('flight_bookings');
            $excluded = ['booking_request', 'booking_response', 'get_booking_response', 'ticketing_response', 'ticketing_cancel_response'];
            $columns = array_diff($columns, $excluded);
            $columns = array_map(function ($col) {
                return "flight_bookings.$col";
            }, $columns);

            $query = DB::table('flight_bookings')
                ->leftJoin('users', 'flight_bookings.booked_by', '=', 'users.id')
                ->select([...$columns, 'users.name as b2b_user'])
                ->where('flight_bookings.status', 3)
                ->orderBy('flight_bookings.id', 'desc');

            if (Auth::user()->user_type != UserType::Admin->value && Auth::user()->user_type != UserType::SuperAdmin->value) {
                $query->where('flight_bookings.booked_by', Auth::user()->id);
            }

            return Datatables::of($query)
                ->filterColumn('b2b_user', function ($query, $keyword) {
                    $query->where('users.name', 'like', "%{$keyword}%");
                })
                ->addColumn('flight_routes', function ($data) {
                    $routeString = $data->departure_location . " - " . $data->arrival_location;
                    if ($data->flight_type == 2) {
                        $routeString .= " - " . $data->departure_location;
                    }
                    return $routeString;
                })
                ->editColumn('created_at', function ($data) {
                    return date("Y-m-d h:i a", strtotime($data->created_at));
                })
                ->editColumn('total_fare', function ($data) {
                    return $data->currency . " " . number_format($data->total_fare);
                })
                ->editColumn('status', function ($data) {
                    if ($data->status == 1)
                        return "<span style='font-weight:600; color:green'>Booked</span>";
                    if ($data->status == 2)
                        return "<span style='font-weight:600; color:green'>Issued</span>";
                    if ($data->status == 3)
                        return "<span style='font-weight:600; color:red'>Cancelled</span>";
                    if ($data->status == 4)
                        return "<span style='font-weight:600; color:red'>Cancelled</span>";

                })
                ->addColumn('total_passengers', function ($data) {
                    return $data->adult + $data->child + $data->infant;
                })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = ' <a href="' . url('flight/booking/details') . "/" . $data->booking_no . '" class="btn-sm btn-info text-white rounded d-inline-block mb-1"><i class="fas fa-eye"></i></a>';
                    // $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->id.'" data-original-title="Cancel" class="btn-sm btn-danger rounded d-inline-block cancelBtn"><i class="fas fa-times-circle"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('booking.cancelled');

    }

    public function flightBookingDetails($bookingNo)
    {

        $flightBookingDetails = FlightBooking::where('booking_no', $bookingNo)->first();

        if ($flightBookingDetails->gds == "Sabre" && ($flightBookingDetails->status == 1 || $flightBookingDetails->status == 2)) {
            SabreBookingDetails::getBookingDetails($flightBookingDetails->pnr_id);
            $flightBookingDetails = FlightBooking::where('booking_no', $bookingNo)->first();
        }

        $bookingResSegs = null;
        if ($flightBookingDetails->booking_response) {
            $bookingRes = json_decode($flightBookingDetails->booking_response, true);
            if (isset($bookingRes['CreatePassengerNameRecordRS']['TravelItineraryRead']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item'])) {
                $bookingResSegs = $bookingRes['CreatePassengerNameRecordRS']['TravelItineraryRead']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item'];
            }
        }

        $flightSegments = FlightSegment::where('flight_booking_id', $flightBookingDetails->id)->get();
        $flightpassengers = FlightPassenger::where('flight_booking_id', $flightBookingDetails->id)->get();
        $auditLogs = \App\Models\BookingAuditLog::where('flight_booking_id', $flightBookingDetails->id)->orderBy('id', 'desc')->get();
        return view('booking.details', compact('flightBookingDetails', 'flightSegments', 'flightpassengers', 'bookingResSegs', 'auditLogs'));
    }

    public function cancelFlightBooking($booking_no)
    {
        $flightBookingInfo = FlightBooking::where('booking_no', $booking_no)->first();

        // Determine which GDS service to use
        if ($this->sabreService->isActive()) {
            $result = $this->sabreService->cancelBooking($flightBookingInfo);
        } elseif ($this->flyhubService->isActive()) {
            $result = $this->flyhubService->cancelBooking($flightBookingInfo);
        } else {
            Toastr::error('No active GDS provider found', 'Failed');
            return back();
        }

        if ($result['success']) {
            $flightBookingInfo->status = 3;
            $flightBookingInfo->booking_cancelled_at = Carbon::now();
            $flightBookingInfo->save();

            \App\Models\BookingAuditLog::logAction($flightBookingInfo->id, 'CANCEL_BOOKING', 'Booking cancelled successfully via API');

            Toastr::success('Flight Booking Cancelled Successfully', 'Cancelled');
        } else {
            Toastr::error('Failed to Cancel Booking', 'Failed');
        }

        return back();
    }

    public function cancelIssuedTicket($booking_no)
    {
        if (Auth::user()->ticket_status == 0) {
            Toastr::error('Ticketing Related Permission Denied');
            return back();
        }

        $flightBookingInfo = FlightBooking::where('booking_no', $booking_no)->first();
        $ticketNumbers = FlightPassenger::where('flight_booking_id', $flightBookingInfo->id)->pluck('ticket_no')->toArray();

        // Determine which GDS service to use
        if ($flightBookingInfo->gds == 'Sabre') {
            $result = $this->sabreService->cancelTicket($flightBookingInfo, $ticketNumbers);
        } elseif ($this->flyhubService->isActive()) {
            $result = $this->flyhubService->cancelTicket($flightBookingInfo, $ticketNumbers);
        } else {
            Toastr::error('No active GDS provider found', 'Failed');
            return back();
        }

        // Store the raw response regardless of outcome
        $flightBookingInfo->ticketing_cancel_response = $result['raw_response'];

        if ($result['success'] && ($result['all_voided'] ?? $result['success'])) {
            $flightBookingInfo->status = 4; // ticket cancelled
            $flightBookingInfo->ticket_cancelled_at = Carbon::now();
            $flightBookingInfo->save();
            
            \App\Models\BookingAuditLog::logAction($flightBookingInfo->id, 'VOID', 'Tickets successfully voided/cancelled via ' . $flightBookingInfo->gds);
            Toastr::success('All ticket numbers were successfully voided', 'Voided');
        } else {
            $flightBookingInfo->save();
            Toastr::error('Failed to void tickets', 'Failed');
        }

        return back();
    }

    public function bookingPreview($bookingNo)
    {
        $flightBookingDetails = FlightBooking::where('booking_no', $bookingNo)->first();
        $flightSegments = FlightSegment::where('flight_booking_id', $flightBookingDetails->id)->get();
        $flightpassengers = FlightPassenger::where('flight_booking_id', $flightBookingDetails->id)->get();
        $companyProfile = CompanyProfile::where('user_id', Auth::user()->id)->first();

        $bookingResSegs = null;
        if ($flightBookingDetails->booking_response) {
            $bookingRes = json_decode($flightBookingDetails->booking_response, true);
            if (isset($bookingRes['CreatePassengerNameRecordRS']['TravelItineraryRead']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item'])) {
                $bookingResSegs = $bookingRes['CreatePassengerNameRecordRS']['TravelItineraryRead']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item'];
            }
        }

        $pdf = Pdf::loadView('booking.preview', compact('flightBookingDetails', 'flightSegments', 'flightpassengers', 'companyProfile', 'bookingResSegs'));
        return $pdf->stream($flightBookingDetails->booking_no . '.pdf');
    }

    public function issueFlightTicket($booking_no)
    {
        if (Auth::user()->ticket_status == 0) {
            Toastr::error('Ticket Issue Permission Denied');
            return back();
        }

        $flightBookingInfo = FlightBooking::where('booking_no', $booking_no)->first();
        $base_fare_amount = $flightBookingInfo->base_fare_amount;

        if (Auth::user()->user_type == UserType::B2B->value) {
            if (Auth::user()->balance < ($base_fare_amount - (($base_fare_amount * Auth::user()->comission) / 100))) {
                Toastr::error('Not Enough Balance', 'Please Recharge');
                return back();
            }
        }

        // Determine which GDS service to use
        if ($this->sabreService->isActive() && $flightBookingInfo->gds == 'Sabre') {
            $result = $this->sabreService->issueTicket($flightBookingInfo);
        } elseif ($this->flyhubService->isActive()) {
            $result = $this->flyhubService->issueTicket($flightBookingInfo);
        } else {
            Toastr::error('No active GDS provider found', 'Failed');
            return back();
        }

        // Store ticketing response regardless of outcome
        $flightBookingInfo->ticketing_response = $result['raw_response'];

        if ($result['success']) {
            // Deduct B2B user balance
            if (Auth::user()->user_type == UserType::B2B->value) {
                $user = User::where('id', Auth::user()->id)->first();
                $user->balance = $user->balance - ($base_fare_amount - (($base_fare_amount * Auth::user()->comission) / 100));
                $user->save();
            }

            // Append FH to ticket numbers
            $passengers = FlightPassenger::where('flight_booking_id', $flightBookingInfo->id)->get();
            foreach($passengers as $pax) {
                if ($pax->ticket_no && !Str::startsWith($pax->ticket_no, 'FH')) {
                    $pax->ticket_no = 'FH' . $pax->ticket_no;
                    $pax->save();
                }
            }

            $flightBookingInfo->status = 2;
            $flightBookingInfo->ticket_issued_at = Carbon::now();
            $flightBookingInfo->save();

            \App\Models\BookingAuditLog::logAction($flightBookingInfo->id, 'ISSUE', 'Ticket auto-issued successfully via ' . $flightBookingInfo->gds);

            // Send ticket issued email
            EmailHelper::send(Auth::user()->email, new TicketIssuedEmail([
                'agent_name' => Auth::user()->name,
                'booking_no' => $flightBookingInfo->booking_no,
                'pnr' => $flightBookingInfo->pnr_id ?? '',
                'route' => '',
                'ticket_number' => $result['ticket_number'] ?? '',
            ]));

            $dest = Auth::user()->user_type == 2 ? 'my/bookings/approved' : 'view/issued/tickets';
            return redirect($dest);
        }

        $flightBookingInfo->save();
        Toastr::error('Failed to issue Ticket', 'Failed');
        return back();
    }

    public function viewIssuedTickets(Request $request)
    {
        if ($request->ajax()) {

            // removing log coloumns
            $columns = Schema::getColumnListing('flight_bookings');
            $excluded = ['booking_request', 'booking_response', 'get_booking_response', 'ticketing_response', 'ticketing_cancel_response'];
            $columns = array_diff($columns, $excluded);
            $columns = array_map(function ($col) {
                return "flight_bookings.$col";
            }, $columns);

            $query = DB::table('flight_bookings')
                ->leftJoin('users', 'flight_bookings.booked_by', '=', 'users.id')
                ->select([...$columns, 'users.name as b2b_user'])
                ->where('flight_bookings.status', 2)
                ->where('flight_bookings.departure_date', '>=', Carbon::today()->toDateString())
                ->orderBy('flight_bookings.id', 'desc');

            if (Auth::user()->user_type != UserType::Admin->value && Auth::user()->user_type != UserType::SuperAdmin->value) {
                $query->where('flight_bookings.booked_by', Auth::user()->id);
            }

            return Datatables::of($query)
                ->filterColumn('b2b_user', function ($query, $keyword) {
                    $query->where('users.name', 'like', "%{$keyword}%");
                })
                ->addColumn('flight_routes', function ($data) {
                    $routeString = $data->departure_location . " - " . $data->arrival_location;
                    if ($data->flight_type == 2) {
                        $routeString .= " - " . $data->departure_location;
                    }
                    return $routeString;
                })
                ->editColumn('created_at', function ($data) {
                    return date("Y-m-d h:i a", strtotime($data->created_at));
                })
                ->editColumn('total_fare', function ($data) {
                    return $data->currency . " " . number_format($data->total_fare);
                })
                ->editColumn('status', function ($data) {
                    if ($data->status == 1)
                        return "<span style='font-weight:600; color:green'>Booked</span>";
                    if ($data->status == 2)
                        return "<span style='font-weight:600; color:green'>Issued</span>";
                    if ($data->status == 3)
                        return "<span style='font-weight:600; color:red'>Cancelled</span>";
                    if ($data->status == 4)
                        return "<span style='font-weight:600; color:red'>Cancelled</span>";

                })
                ->addColumn('total_passengers', function ($data) {
                    return $data->adult + $data->child + $data->infant;
                })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = ' <a href="' . url('flight/booking/details') . "/" . $data->booking_no . '" class="btn-sm btn-info text-white rounded d-inline-block mb-1"><i class="fas fa-eye"></i></a>';
                    // $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->id.'" data-original-title="Cancel" class="btn-sm btn-danger rounded d-inline-block cancelBtn"><i class="fas fa-times-circle"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('booking.issued_ticket');
    }

    public function archivedIssuedTickets(Request $request)
    {
        if ($request->ajax()) {

            // removing log coloumns
            $columns = Schema::getColumnListing('flight_bookings');
            $excluded = ['booking_request', 'booking_response', 'get_booking_response', 'ticketing_response', 'ticketing_cancel_response'];
            $columns = array_diff($columns, $excluded);
            $columns = array_map(function ($col) {
                return "flight_bookings.$col";
            }, $columns);

            $query = DB::table('flight_bookings')
                ->leftJoin('users', 'flight_bookings.booked_by', '=', 'users.id')
                ->select([...$columns, 'users.name as b2b_user'])
                ->where('flight_bookings.status', 2)
                ->where('flight_bookings.departure_date', '<', Carbon::today()->toDateString())
                ->orderBy('flight_bookings.id', 'desc');

            if (Auth::user()->user_type != UserType::Admin->value && Auth::user()->user_type != UserType::SuperAdmin->value) {
                $query->where('flight_bookings.booked_by', Auth::user()->id);
            }

            return Datatables::of($query)
                ->filterColumn('b2b_user', function ($query, $keyword) {
                    $query->where('users.name', 'like', "%{$keyword}%");
                })
                ->addColumn('flight_routes', function ($data) {
                    $routeString = $data->departure_location . " - " . $data->arrival_location;
                    if ($data->flight_type == 2) {
                        $routeString .= " - " . $data->departure_location;
                    }
                    return $routeString;
                })
                ->editColumn('created_at', function ($data) {
                    return date("Y-m-d h:i a", strtotime($data->created_at));
                })
                ->editColumn('total_fare', function ($data) {
                    return $data->currency . " " . number_format($data->total_fare);
                })
                ->editColumn('status', function ($data) {
                    if ($data->status == 1)
                        return "<span style='font-weight:600; color:green'>Booked</span>";
                    if ($data->status == 2)
                        return "<span style='font-weight:600; color:green'>Issued</span>";
                    if ($data->status == 3)
                        return "<span style='font-weight:600; color:red'>Cancelled</span>";
                    if ($data->status == 4)
                        return "<span style='font-weight:600; color:red'>Cancelled</span>";

                })
                ->addColumn('total_passengers', function ($data) {
                    return $data->adult + $data->child + $data->infant;
                })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = ' <a href="' . url('flight/booking/details') . "/" . $data->booking_no . '" class="btn-sm btn-info text-white rounded d-inline-block mb-1"><i class="fas fa-eye"></i></a>';
                    // $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->id.'" data-original-title="Cancel" class="btn-sm btn-danger rounded d-inline-block cancelBtn"><i class="fas fa-times-circle"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('booking.archived_issued_tickets');
    }

    public function viewCancelledTickets(Request $request)
    {
        if ($request->ajax()) {

            // removing log coloumns
            $columns = Schema::getColumnListing('flight_bookings');
            $excluded = ['booking_request', 'booking_response', 'get_booking_response', 'ticketing_response', 'ticketing_cancel_response'];
            $columns = array_diff($columns, $excluded);
            $columns = array_map(function ($col) {
                return "flight_bookings.$col";
            }, $columns);

            $query = DB::table('flight_bookings')
                ->leftJoin('users', 'flight_bookings.booked_by', '=', 'users.id')
                ->select([...$columns, 'users.name as b2b_user'])
                ->where('flight_bookings.status', 4)
                ->orderBy('flight_bookings.id', 'desc');

            if (Auth::user()->user_type != UserType::Admin->value && Auth::user()->user_type != UserType::SuperAdmin->value) {
                $query->where('flight_bookings.booked_by', Auth::user()->id);
            }

            return Datatables::of($query)
                ->filterColumn('b2b_user', function ($query, $keyword) {
                    $query->where('users.name', 'like', "%{$keyword}%");
                })
                ->addColumn('flight_routes', function ($data) {
                    $routeString = $data->departure_location . " - " . $data->arrival_location;
                    if ($data->flight_type == 2) {
                        $routeString .= " - " . $data->departure_location;
                    }
                    return $routeString;
                })
                ->editColumn('created_at', function ($data) {
                    return date("Y-m-d h:i a", strtotime($data->created_at));
                })
                ->editColumn('total_fare', function ($data) {
                    return $data->currency . " " . number_format($data->total_fare);
                })
                ->editColumn('status', function ($data) {
                    if ($data->status == 1)
                        return "<span style='font-weight:600; color:green'>Booked</span>";
                    if ($data->status == 2)
                        return "<span style='font-weight:600; color:green'>Issued</span>";
                    if ($data->status == 3)
                        return "<span style='font-weight:600; color:red'>Cancelled</span>";
                    if ($data->status == 4)
                        return "<span style='font-weight:600; color:red'>Cancelled</span>";

                })
                ->addColumn('total_passengers', function ($data) {
                    return $data->adult + $data->child + $data->infant;
                })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = ' <a href="' . url('flight/booking/details') . "/" . $data->booking_no . '" class="btn-sm btn-info text-white rounded d-inline-block mb-1"><i class="fas fa-eye"></i></a>';
                    // $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->id.'" data-original-title="Cancel" class="btn-sm btn-danger rounded d-inline-block cancelBtn"><i class="fas fa-times-circle"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('booking.cancelled_ticket');
    }

    public function updatePnrBooking(Request $request)
    {
        $request->validate([
            'booking_no' => 'required|string|max:50',
            'pnr_id' => 'required|string|max:50',
            'status' => 'required|integer|in:0,1,2,3,4',
        ]);

        FlightBooking::where('booking_no', $request->booking_no)->update([
            'pnr_id' => $request->pnr_id,
            'status' => $request->status,
            'created_at' => Carbon::now(),
        ]);

        Toastr::success('Flight Booked Successfully', 'Successful');
        return back();
    }

    /**
     * Export booking history as CSV
     */
    public function exportBookingsCsv(Request $request)
    {
        $query = FlightBooking::query();

        // Filter by user if B2B agent
        if (Auth::user()->user_type == UserType::B2B->value) {
            $query->where('user_id', Auth::user()->id);
        }

        // Optional date range filter
        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Optional status filter
        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('id', 'desc')->get();

        $filename = 'booking_history_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, [
                'Booking No',
                'PNR',
                'GDS',
                'Traveller Name',
                'Traveller Email',
                'Traveller Contact',
                'Base Fare',
                'Tax',
                'Total Fare',
                'Status',
                'Booking Date',
                'Ticket Issued At',
            ]);

            // CSV Data
            foreach ($bookings as $booking) {
                $statusLabels = [0 => 'Pending', 1 => 'Confirmed', 2 => 'Ticketed', 3 => 'Cancelled', 4 => 'Expired'];

                fputcsv($file, [
                    $booking->booking_no,
                    $booking->pnr_id ?? '',
                    $booking->gds ?? '',
                    $booking->traveller_name ?? '',
                    $booking->traveller_email ?? '',
                    $booking->traveller_contact ?? '',
                    $booking->base_fare_amount ?? 0,
                    $booking->tax_amount ?? 0,
                    $booking->total_fare ?? 0,
                    $statusLabels[$booking->status] ?? 'Unknown',
                    $booking->created_at ? $booking->created_at->format('Y-m-d H:i') : '',
                    $booking->ticket_issued_at ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
