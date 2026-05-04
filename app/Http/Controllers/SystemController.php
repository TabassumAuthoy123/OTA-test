<?php

namespace App\Http\Controllers;

use App\Helpers\EmailHelper;
use App\Mail\DepartureReminderAgent;
use App\Mail\DepartureReminderPassenger;
use App\Models\Config;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateSmsGatewayRequest;
use App\Http\Requests\UpdateEmailConfigRequest;
use App\Models\FlightNotificationLog;
use App\Models\SmsGateway;
use App\Models\EmailConfigure;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;

class SystemController extends Controller
{
    public function viewSmsGateways()
    {
        // Ensure the 3 required gateway rows always exist
        SmsGateway::firstOrCreate(['id' => 1], ['provider_name' => 'ElitBuzz',   'api_endpoint' => '', 'status' => 0]);
        SmsGateway::firstOrCreate(['id' => 2], ['provider_name' => 'ReveSMS',    'api_endpoint' => '', 'status' => 0]);
        SmsGateway::firstOrCreate(['id' => 3], ['provider_name' => 'KhudeBarta', 'api_endpoint' => '', 'status' => 0]);

        $gateways = SmsGateway::orderBy('id', 'asc')->get();
        return view('system.sms_gateway', compact('gateways'));
    }

    public function updateSmsGatewayInfo(UpdateSmsGatewayRequest $request)
    {

        $provider = $request->provider;

        DB::table('sms_gateways')->update([
            'status' => 0,
            'updated_at' => Carbon::now()
        ]);

        if ($provider == 'elitbuzz') { //ID 1 => Elitbuzz
            SmsGateway::where('id', 1)->update([
                'api_endpoint' => $request->api_endpoint,
                'api_key' => $request->api_key,
                'sender_id' => $request->sender_id,
                'status' => 1,
                'updated_at' => Carbon::now()
            ]);
        }

        if ($provider == 'revesms') { //ID 2 => Revesms
            SmsGateway::where('id', 2)->update([
                'api_endpoint' => $request->api_endpoint,
                'api_key' => $request->api_key,
                'secret_key' => $request->secret_key,
                'sender_id' => $request->sender_id,
                'status' => 1,
                'updated_at' => Carbon::now()
            ]);
        }

        if ($provider == 'khudebarta') { //ID 2 => Revesms
            SmsGateway::where('id', 3)->update([
                'api_endpoint' => $request->api_endpoint,
                'api_key' => $request->api_key,
                'secret_key' => $request->secret_key,
                'sender_id' => $request->sender_id,
                'status' => 1,
                'updated_at' => Carbon::now()
            ]);
        }

        Toastr::success('Info Updated', 'Success');
        return back();
    }

    public function changeGatewayStatus($provider)
    {

        DB::table('sms_gateways')->update([
            'status' => 0,
            'updated_at' => Carbon::now()
        ]);

        if ($provider == 'elitbuzz') { //ID 1 => Elitbuzz
            SmsGateway::where('id', 1)->update([
                'status' => 1,
                'updated_at' => Carbon::now()
            ]);
        }

        if ($provider == 'revesms') { //ID 2 => Revesms
            SmsGateway::where('id', 2)->update([
                'status' => 1,
                'updated_at' => Carbon::now()
            ]);
        }

        if ($provider == 'khudebarta') { //ID 2 => Revesms
            SmsGateway::where('id', 3)->update([
                'status' => 1,
                'updated_at' => Carbon::now()
            ]);
        }

        return response()->json(['success' => 'Updated Successfully.']);

    }

    public function viewEmailConfig()
    {
        $config = EmailConfigure::firstOrCreate(
            ['id' => 1],
            ['host' => '', 'port' => 587, 'email' => '', 'password' => '', 'mail_from_name' => '', 'mail_from_email' => '', 'encryption' => 0]
        );
        return view('system.email_config', compact('config'));
    }

    public function updateEmailConfig(UpdateEmailConfigRequest $request)
    {

        EmailConfigure::where('id', 1)->update([
            'host' => $request->host,
            'port' => $request->port,
            'email' => $request->email,
            'password' => $request->password,
            'mail_from_name' => $request->mail_from_name,
            'mail_from_email' => $request->mail_from_email,
            'encryption' => $request->encryption,
            'created_at' => Carbon::now()
        ]);

        return redirect()->back()->withErrors(['success_message' => 'Email Config Updated']);
    }

    public function searchResultsViewConfig()
    {
        $config = Config::firstOrCreate(['id' => 1], ['search_results_view' => 1]);
        return view('system.search_results_view', compact('config'));
    }

    public function changeSearchResultsView($value)
    {
        Config::where('id', 1)->update([
            'search_results_view' => $value,
            'updated_at' => Carbon::now()
        ]);
        return response()->json(['success' => 'Updated Successfully.']);
    }

    public function testSendEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $testData = [
            'booking_id'         => 0,
            'booking_no'         => 'TEST-001',
            'pnr'                => 'TESTPNR',
            'airlines_pnr'       => 'AIRPNR',
            'traveller_name'     => 'Test Passenger',
            'departure_location' => 'DAC',
            'arrival_location'   => 'DXB',
            'departure_date'     => now()->addHours(10)->format('d M Y, h:i A'),
            'governing_carriers' => 'Test Airline',
            'contact'            => '+8801700000000',
            'total_fare'         => 25000,
            'passenger_names'    => 'Test Passenger',
            'agent_name'         => auth()->user()->name,
            'agent_email'        => $request->email,
            'agent_code'         => 'B2B-001',
        ];

        $type = $request->input('type', 'passenger');
        $mailable = $type === 'agent'
            ? new DepartureReminderAgent($testData)
            : new DepartureReminderPassenger($testData);

        $ok = EmailHelper::send($request->email, $mailable);

        return response()->json([
            'success' => $ok,
            'message' => $ok
                ? "Test email sent to {$request->email}"
                : 'Failed to send. Check Mail Server Config and laravel.log.',
        ]);
    }

    public function notificationLogs(Request $request)
    {
        $query = FlightNotificationLog::with('booking')
            ->orderBy('created_at', 'desc');

        if ($request->filled('booking_no')) {
            $query->whereHas('booking', fn($q) => $q->where('booking_no', 'like', '%' . $request->booking_no . '%'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $logs  = $query->paginate(50)->withQueryString();
        $total = FlightNotificationLog::count();
        $sent  = FlightNotificationLog::where('status', 'sent')->count();
        $failed = FlightNotificationLog::where('status', 'failed')->count();

        return view('system.notification_logs', compact('logs', 'total', 'sent', 'failed'));
    }

}
