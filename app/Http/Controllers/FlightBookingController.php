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

        return redirect('/view/all/booking');
    }

    public function bookFlightWithPnr(Request $request)
    {
        $result = $this->bookingService->createFlyhubBooking($request);

        if (!$result['success']) {
            Toastr::error($result['error'] ?? 'Failed to Book this Flight');
            return back();
        }

        Toastr::success('Flight Booking Request Sent', 'Success');
        return redirect('/view/all/booking');
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

            if (Auth::user()->user_type != UserType::Admin->value) {
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

            if (Auth::user()->user_type != UserType::Admin->value) {
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
            $bookingResSegs = $bookingRes['CreatePassengerNameRecordRS']['TravelItineraryRead']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item'];
        }

        $flightSegments = FlightSegment::where('flight_booking_id', $flightBookingDetails->id)->get();
        $flightPassengers = FlightPassenger::where('flight_booking_id', $flightBookingDetails->id)->get();
        return view('booking.details', compact('flightBookingDetails', 'flightSegments', 'flightPassengers', 'bookingResSegs'));
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
        $flightPassengers = FlightPassenger::where('flight_booking_id', $flightBookingDetails->id)->get();
        $companyProfile = CompanyProfile::where('user_id', Auth::user()->id)->first();

        $bookingResSegs = null;
        if ($flightBookingDetails->booking_response) {
            $bookingRes = json_decode($flightBookingDetails->booking_response, true);
            $bookingResSegs = $bookingRes['CreatePassengerNameRecordRS']['TravelItineraryRead']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item'];
        }

        $pdf = Pdf::loadView('booking.preview', compact('flightBookingDetails', 'flightSegments', 'flightPassengers', 'companyProfile', 'bookingResSegs'));
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

            $flightBookingInfo->status = 2;
            $flightBookingInfo->ticket_issued_at = Carbon::now();
            $flightBookingInfo->save();
            return redirect('view/issued/tickets');
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

            if (Auth::user()->user_type != UserType::Admin->value) {
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

            if (Auth::user()->user_type != UserType::Admin->value) {
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

            if (Auth::user()->user_type != UserType::Admin->value) {
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
        FlightBooking::where('booking_no', $request->booking_no)->update([
            'pnr_id' => $request->pnr_id,
            'status' => $request->status,
            'created_at' => Carbon::now(),
        ]);

        Toastr::success('Flight Booked Successfully', 'Successful');
        return back();
    }
}
