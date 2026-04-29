<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class B2bAgentController extends Controller
{
    public function tourBookings(Request $request)
    {
        $q = DB::table('tour_bookings')->where('b2b_user_id', Auth::id());

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('booking_id', 'like', "%$s%")
                  ->orWhere('name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%");
            });
        }
        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $q->where('status', $request->filter_status);
        }
        if ($request->filled('start_date')) {
            $q->whereDate('travel_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $q->whereDate('travel_date', '<=', $request->end_date);
        }

        $bookings  = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
        $total     = DB::table('tour_bookings')->where('b2b_user_id', Auth::id())->count();
        $pageTitle = $request->input('_page_title', 'All Tour Bookings');
        return view('b2b_portal.tour_bookings', compact('bookings', 'total', 'pageTitle'));
    }

    public function visaApplications(Request $request)
    {
        $q = DB::table('visa_applications')->where('user_id', Auth::id());

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('applicant_name', 'like', "%$s%")
                  ->orWhere('passport_no', 'like', "%$s%")
                  ->orWhere('destination_country', 'like', "%$s%");
            });
        }
        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $q->where('status', $request->filter_status);
        }

        $applications = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('b2b_portal.visa_applications', compact('applications'));
    }

    public function createVisa()
    {
        return view('b2b_portal.visa_create');
    }

    public function storeVisa(Request $request)
    {
        $request->validate([
            'applicant_name'     => 'required|string|max:200',
            'destination_country'=> 'required|string|max:100',
            'visa_type'          => 'required|in:tourist,business,student,work,medical,other',
        ]);

        DB::table('visa_applications')->insert([
            'user_id'             => Auth::id(),
            'applicant_name'      => $request->applicant_name,
            'passport_no'         => $request->passport_no,
            'nationality'         => $request->nationality,
            'destination_country' => $request->destination_country,
            'visa_type'           => $request->visa_type,
            'travel_date'         => $request->travel_date ?: null,
            'passport_expiry'     => $request->passport_expiry ?: null,
            'contact_no'          => $request->contact_no,
            'email'               => $request->email,
            'notes'               => $request->notes,
            'status'              => 'pending',
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        return redirect(url('my/visa-applications'))->with('success', 'Visa application submitted successfully.');
    }

    // ── My Flight Bookings ──────────────────────────────────────────────────

    public function myBookings(Request $request)
    {
        return $this->flightBookingsList($request, null, 'All Booking List', 'MyBookings');
    }

    public function myPendingBookings(Request $request)
    {
        return $this->flightBookingsList($request, [0], 'Flight Bookings On Hold', 'MyPendingBookings');
    }

    public function myApprovedBookings(Request $request)
    {
        return $this->flightBookingsList($request, [1, 2], 'Flight Bookings Issued', 'MyApprovedBookings');
    }

    public function bookingDetail($id)
    {
        $booking = DB::table('flight_bookings')
            ->where('id', $id)
            ->where('booked_by', Auth::id())
            ->first();
        if (!$booking) abort(404);

        $segments   = DB::table('flight_segments')->where('flight_booking_id', $id)->get();
        $passengers = DB::table('flight_passengers')->where('flight_booking_id', $id)->get();

        return view('b2b_portal.booking_detail', compact('booking', 'segments', 'passengers'));
    }

    private function flightBookingsList(Request $request, ?array $statuses, string $title, string $activeRoute)
    {
        $q = DB::table('flight_bookings')->where('booked_by', Auth::id());
        if ($statuses !== null) {
            $q->whereIn('status', $statuses);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('booking_no', 'like', "%$s%")
                  ->orWhere('pnr_id', 'like', "%$s%")
                  ->orWhere('airlines_pnr', 'like', "%$s%")
                  ->orWhere('departure_location', 'like', "%$s%")
                  ->orWhere('arrival_location', 'like', "%$s%")
                  ->orWhereExists(function ($sub) use ($s) {
                      $sub->from('flight_passengers')
                          ->whereColumn('flight_passengers.flight_booking_id', 'flight_bookings.id')
                          ->where(function ($pp) use ($s) {
                              $pp->where('first_name', 'like', "%$s%")
                                 ->orWhere('last_name', 'like', "%$s%")
                                 ->orWhere('document_no', 'like', "%$s%");
                          });
                  });
            });
        }
        if ($request->filled('start_date')) {
            $q->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $q->whereDate('created_at', '<=', $request->end_date);
        }
        $bookings = $q->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('b2b_portal.my_bookings', compact('bookings', 'title', 'activeRoute'));
    }

    // ── Reissued ─────────────────────────────────────────────────────────────

    public function reissueNew(Request $request)
    {
        return $this->requestTicketList($request, 'reissue', ['open'], 'Reissued – New Request', 'MyReissueNew');
    }

    public function reissueInProcess(Request $request)
    {
        return $this->requestTicketList($request, 'reissue', ['in_progress'], 'Reissued – In Process', 'MyReissueInProcess');
    }

    public function reissueConfirmed(Request $request)
    {
        return $this->requestTicketList($request, 'reissue', ['resolved', 'closed'], 'Reissued – Confirmed', 'MyReissueConfirmed');
    }

    public function createReissue()
    {
        return view('b2b_portal.request_create', ['type' => 'reissue', 'typeLabel' => 'Reissue', 'backRoute' => 'MyReissueNew']);
    }

    public function storeReissue(Request $request)
    {
        return $this->storeRequest($request, 'reissue', 'my/reissue/new');
    }

    // ── Refunded ─────────────────────────────────────────────────────────────

    public function refundNew(Request $request)
    {
        return $this->requestTicketList($request, 'refund', ['open'], 'Refunded – New Request', 'MyRefundNew');
    }

    public function refundInProcess(Request $request)
    {
        return $this->requestTicketList($request, 'refund', ['in_progress'], 'Refunded – In Process', 'MyRefundInProcess');
    }

    public function refundConfirmed(Request $request)
    {
        return $this->requestTicketList($request, 'refund', ['resolved', 'closed'], 'Refunded – Confirmed', 'MyRefundConfirmed');
    }

    public function createRefund()
    {
        return view('b2b_portal.request_create', ['type' => 'refund', 'typeLabel' => 'Refund', 'backRoute' => 'MyRefundNew']);
    }

    public function storeRefund(Request $request)
    {
        return $this->storeRequest($request, 'refund', 'my/refund/new');
    }

    // ── Void Request ─────────────────────────────────────────────────────────

    public function voidNew(Request $request)
    {
        return $this->requestTicketList($request, 'void', ['open'], 'Void Request – New Request', 'MyVoidNew');
    }

    public function voidInProcess(Request $request)
    {
        return $this->requestTicketList($request, 'void', ['in_progress'], 'Void Request – In Process', 'MyVoidInProcess');
    }

    public function voidConfirmed(Request $request)
    {
        return $this->requestTicketList($request, 'void', ['resolved', 'closed'], 'Void Request – Confirmed', 'MyVoidConfirmed');
    }

    public function createVoid()
    {
        return view('b2b_portal.request_create', ['type' => 'void', 'typeLabel' => 'Void', 'backRoute' => 'MyVoidNew']);
    }

    public function storeVoid(Request $request)
    {
        return $this->storeRequest($request, 'void', 'my/void/new');
    }

    private function requestTicketList(Request $request, string $issueType, array $statuses, string $title, string $activeRoute)
    {
        $q = DB::table('booking_support_tickets')
            ->where('user_id', Auth::id())
            ->where('issue_type', $issueType)
            ->whereIn('status', $statuses);

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('subject', 'like', "%$s%")
                  ->orWhere('booking_ref', 'like', "%$s%");
            });
        }
        if ($request->filled('start_date')) {
            $q->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $q->whereDate('created_at', '<=', $request->end_date);
        }
        $tickets = $q->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('b2b_portal.request_tickets', compact('tickets', 'title', 'activeRoute', 'issueType', 'statuses'));
    }

    private function storeRequest(Request $request, string $issueType, string $redirectPath)
    {
        $request->validate([
            'subject'     => 'required|string|max:255',
            'description' => 'required|string',
            'booking_ref' => 'nullable|string|max:100',
        ]);

        DB::table('booking_support_tickets')->insert([
            'user_id'     => Auth::id(),
            'booking_ref' => $request->booking_ref,
            'issue_type'  => $issueType,
            'subject'     => $request->subject,
            'description' => $request->description,
            'status'      => 'open',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect(url($redirectPath))->with('success', 'Request submitted successfully.');
    }

    // ── Tour sub-filters ─────────────────────────────────────────────────────

    public function tourBookingsApproved(Request $request)
    {
        $request->merge(['filter_status' => '1', '_page_title' => 'Approved Tour Bookings']);
        return $this->tourBookings($request);
    }

    public function tourBookingsPending(Request $request)
    {
        $request->merge(['filter_status' => '0', '_page_title' => 'Pending Tour Bookings']);
        return $this->tourBookings($request);
    }

    // ── Administrator ─────────────────────────────────────────────────────────

    public function agencyUsers()
    {
        $companyProfile = DB::table('company_profiles')->where('user_id', Auth::id())->first();
        return view('b2b_portal.agency_users', compact('companyProfile'));
    }

    public function agencyRoles()
    {
        return view('b2b_portal.agency_roles');
    }

    public function bookingSupport(Request $request)
    {
        $q = DB::table('booking_support_tickets')->where('user_id', Auth::id());

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('subject', 'like', "%$s%")
                  ->orWhere('booking_ref', 'like', "%$s%");
            });
        }
        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $q->where('status', $request->filter_status);
        }

        $tickets = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('b2b_portal.booking_support', compact('tickets'));
    }

    public function createSupportTicket()
    {
        return view('b2b_portal.support_create');
    }

    public function storeSupportTicket(Request $request)
    {
        $request->validate([
            'subject'     => 'required|string|max:255',
            'description' => 'required|string',
            'issue_type'  => 'required|in:ticket_issue,refund,reissue,void,others',
        ]);

        DB::table('booking_support_tickets')->insert([
            'user_id'     => Auth::id(),
            'booking_ref' => $request->booking_ref,
            'issue_type'  => $request->issue_type,
            'subject'     => $request->subject,
            'description' => $request->description,
            'status'      => 'open',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect(url('my/booking-support'))->with('success', 'Support ticket submitted. We will respond shortly.');
    }
}
