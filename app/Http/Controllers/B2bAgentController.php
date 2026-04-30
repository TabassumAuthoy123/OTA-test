<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\CompanyProfile;
use App\Models\User;

class B2bAgentController extends Controller
{
    // ── B2B Agent Dashboard ───────────────────────────────────────────────────

    public function agentDashboard()
    {
        $uid = Auth::id();

        $total     = DB::table('flight_bookings')->where('booked_by', $uid)->count();
        $issued    = DB::table('flight_bookings')->where('booked_by', $uid)->where('status', 2)->count();
        $pending   = DB::table('flight_bookings')->where('booked_by', $uid)->where('status', 0)->count();
        $cancelled = DB::table('flight_bookings')->where('booked_by', $uid)->whereIn('status', [3, 4])->count();

        $fulfillmentRate  = $total > 0 ? round(($issued    / $total) * 100, 1) : 0;
        $pendingWorkload  = $total > 0 ? round(($pending   / $total) * 100, 1) : 0;
        $cancellationRate = $total > 0 ? round(($cancelled / $total) * 100, 1) : 0;

        $monthly = DB::table('flight_bookings')
            ->where('booked_by', $uid)
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->select(DB::raw("DATE_FORMAT(created_at,'%Y-%m') as month"), DB::raw('COUNT(*) as bookings'), DB::raw('SUM(CASE WHEN status=2 THEN 1 ELSE 0 END) as issued'), DB::raw('SUM(CASE WHEN status IN(3,4) THEN 1 ELSE 0 END) as cancelled'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('b2b_portal.b2b_dashboard', compact('total', 'issued', 'pending', 'cancelled', 'fulfillmentRate', 'pendingWorkload', 'cancellationRate', 'monthly'));
    }

    // ── My Account ────────────────────────────────────────────────────────────

    public function myAccount()
    {
        $user           = Auth::user();
        $companyProfile = CompanyProfile::where('user_id', $user->id)->first();
        return view('b2b_portal.my_account', compact('user', 'companyProfile'));
    }

    public function updateProfileAjax(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $user  = User::find(Auth::id());
        $image = $user->image;

        if ($request->hasFile('photo')) {
            $file      = $request->file('photo');
            $fileName  = Str::random(5) . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('userImages/'), $fileName);
            if ($image && file_exists(public_path($image))) {
                unlink(public_path($image));
            }
            $image = 'userImages/' . $fileName;
        }

        $user->update([
            'name'               => $request->name,
            'phone'              => $request->phone,
            'image'              => $image,
            'two_factor_enabled' => $request->boolean('two_factor_enabled'),
        ]);

        return response()->json(['success' => true, 'message' => 'Profile updated successfully.', 'name' => $user->name, 'phone' => $user->phone, 'two_factor_enabled' => $user->two_factor_enabled, 'image' => $image ? asset($image) : null]);
    }

    public function changePasswordAjax(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6',
        ]);

        if (!Hash::check($request->old_password, Auth::user()->password)) {
            return response()->json(['success' => false, 'message' => 'Current password is incorrect.'], 422);
        }

        User::where('id', Auth::id())->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['success' => true, 'message' => 'Password changed successfully.']);
    }

    // ── Tour Search ───────────────────────────────────────────────────────────

    public function tourSearch(Request $request)
    {
        $countries = DB::table('tour_packages')->where('status', 1)->distinct()->orderBy('country')->pluck('country');
        $visaTypes = ['tourist', 'business', 'student', 'pilgrimage', 'medical'];

        $packages = collect();
        $searched = false;

        if ($request->isMethod('get') && ($request->filled('country') || $request->filled('visa_type') || $request->filled('start_date'))) {
            $searched = true;
            $q = DB::table('tour_packages')->where('status', 1);

            if ($request->filled('country') && $request->country !== 'all') {
                $q->where('country', $request->country);
            }
            if ($request->filled('visa_type') && $request->visa_type !== 'all') {
                $q->where('visa_type', $request->visa_type);
            }
            if ($request->filled('start_date')) {
                $q->where(function ($w) use ($request) {
                    $w->whereNull('end_date')->orWhere('end_date', '>=', $request->start_date);
                });
            }
            if ($request->filled('end_date')) {
                $q->where(function ($w) use ($request) {
                    $w->whereNull('start_date')->orWhere('start_date', '<=', $request->end_date);
                });
            }

            $packages = $q->orderBy('price')->get();
        } else {
            $packages = DB::table('tour_packages')->where('status', 1)->orderBy('price')->get();
        }

        return view('b2b_portal.tour_search', compact('countries', 'visaTypes', 'packages', 'searched'));
    }

    // ── Visa Search ───────────────────────────────────────────────────────────

    public function visaSearch(Request $request)
    {
        // Build country list from tour_packages destinations + visa_applications destinations
        $fromTours = DB::table('tour_packages')->whereNotNull('country')->where('status', 1)->distinct()->pluck('country');
        $fromVisa  = DB::table('visa_applications')->whereNotNull('destination_country')->distinct()->pluck('destination_country');
        $countries = $fromTours->merge($fromVisa)->unique()->sort()->values();

        // If both empty, use a sensible default list
        if ($countries->isEmpty()) {
            $countries = collect(['Bangladesh', 'India', 'Thailand', 'Malaysia', 'Singapore', 'Saudi Arabia', 'UAE', 'Qatar', 'Nepal', 'UK', 'USA', 'Canada', 'Australia']);
        }

        $results  = collect();
        $searched = false;

        if ($request->filled('country')) {
            $searched = true;
            $results  = DB::table('visa_applications')
                ->where('destination_country', $request->country)
                ->select('visa_type', DB::raw('COUNT(*) as total'), DB::raw('SUM(CASE WHEN status="approved" THEN 1 ELSE 0 END) as approved'), DB::raw('SUM(CASE WHEN status="pending" THEN 1 ELSE 0 END) as pending'), DB::raw('SUM(CASE WHEN status="rejected" THEN 1 ELSE 0 END) as rejected'))
                ->groupBy('visa_type')
                ->get();
        }

        return view('b2b_portal.visa_search', compact('countries', 'results', 'searched'));
    }

    // ── Tour Bookings ────────────────────────────────────────────────────────

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

        if ($request->export === 'excel') {
            $rows = (clone $q)->orderByDesc('created_at')->get();
            return $this->streamCsv('tour_bookings_' . date('Y-m-d') . '.csv',
                ['ID', 'Booking Ref', 'Name', 'Email', 'Tour Type', 'Travel Date', 'Amount (BDT)', 'Status', 'Created At'],
                $rows->map(fn($r) => [
                    $r->id, $r->booking_id ?? '', $r->name, $r->email,
                    ucfirst($r->tour_type ?? ''),
                    $r->travel_date ? date('d-m-Y', strtotime($r->travel_date)) : '',
                    number_format($r->amount ?? 0, 2),
                    $r->status == 1 ? 'Confirmed' : ($r->status == 2 ? 'Cancelled' : 'Pending'),
                    $r->created_at ? date('d-m-Y', strtotime($r->created_at)) : '',
                ])->toArray()
            );
        }

        $bookings  = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
        $total     = DB::table('tour_bookings')->where('b2b_user_id', Auth::id())->count();
        $pageTitle = $request->input('_page_title', 'All Tour Bookings');
        return view('b2b_portal.tour_bookings', compact('bookings', 'total', 'pageTitle'));
    }

    // ── Visa Applications ─────────────────────────────────────────────────────

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

        if ($request->export === 'excel') {
            $rows = (clone $q)->orderByDesc('created_at')->get();
            return $this->streamCsv('visa_applications_' . date('Y-m-d') . '.csv',
                ['Applicant Name', 'Passport No', 'Nationality', 'Destination', 'Visa Type', 'Travel Date', 'Passport Expiry', 'Status', 'Applied At'],
                $rows->map(fn($r) => [
                    $r->applicant_name, $r->passport_no ?? '', $r->nationality ?? '',
                    $r->destination_country, ucfirst($r->visa_type),
                    $r->travel_date ? date('d-m-Y', strtotime($r->travel_date)) : '',
                    $r->passport_expiry ? date('d-m-Y', strtotime($r->passport_expiry)) : '',
                    strtoupper($r->status),
                    $r->created_at ? date('d-m-Y', strtotime($r->created_at)) : '',
                ])->toArray()
            );
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

        if ($request->export === 'excel') {
            $statusMap = [0=>'Booking Hold',1=>'Booking Success',2=>'Ticket Issued',3=>'Cancelled',4=>'Refunded',5=>'Voided'];
            $rows = (clone $q)->orderByDesc('created_at')->get();
            return $this->streamCsv('flight_bookings_' . date('Y-m-d') . '.csv',
                ['Booking Ref', 'PNR', 'Airlines PNR', 'Route', 'Journey Type', 'Fare (BDT)', 'Status', 'Booking Date'],
                $rows->map(function ($b) use ($statusMap) {
                    $jt = (int)($b->journey_type ?? 1);
                    $jtText = $jt === 2 ? 'Round Trip' : ($jt === 3 ? 'Multi City' : 'One Way');
                    $dep = strtoupper(substr($b->departure_location ?? '', 0, 3));
                    $arr = strtoupper(substr($b->arrival_location ?? '', 0, 3));
                    return [
                        $b->booking_no,
                        $b->pnr_id ?? '',
                        $b->airlines_pnr ?? '',
                        "$dep-$arr",
                        $jtText,
                        number_format($b->total_fare ?? 0, 2),
                        $statusMap[$b->status] ?? ucfirst((string)$b->status),
                        $b->created_at ? date('d-m-Y', strtotime($b->created_at)) : '',
                    ];
                })->toArray()
            );
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

        if ($request->export === 'excel') {
            $rows = (clone $q)->orderByDesc('created_at')->get();
            return $this->streamCsv(
                $issueType . '_requests_' . date('Y-m-d') . '.csv',
                ['Ref No', 'Booking Ref', 'Subject', 'Status', 'Submitted At'],
                $rows->map(fn($r) => [
                    '#' . str_pad($r->id, 3, '0', STR_PAD_LEFT),
                    $r->booking_ref ?? '',
                    $r->subject,
                    strtoupper(str_replace('_', ' ', $r->status)),
                    $r->created_at ? date('d-m-Y H:i', strtotime($r->created_at)) : '',
                ])->toArray()
            );
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

    // ── Booking Support ───────────────────────────────────────────────────────

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

        if ($request->export === 'excel') {
            $rows = (clone $q)->orderByDesc('created_at')->get();
            return $this->streamCsv('booking_support_' . date('Y-m-d') . '.csv',
                ['Ticket #', 'Booking Ref', 'Issue Type', 'Subject', 'Status', 'Submitted At'],
                $rows->map(fn($r) => [
                    '#' . str_pad($r->id, 3, '0', STR_PAD_LEFT),
                    $r->booking_ref ?? '',
                    ucfirst(str_replace('_', ' ', $r->issue_type)),
                    $r->subject,
                    strtoupper(str_replace('_', ' ', $r->status)),
                    $r->created_at ? date('d-m-Y H:i', strtotime($r->created_at)) : '',
                ])->toArray()
            );
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

    // ── CSV Export Helper ─────────────────────────────────────────────────────

    private function streamCsv(string $filename, array $headers, array $rows)
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM for Excel
            fputcsv($out, $headers);
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
