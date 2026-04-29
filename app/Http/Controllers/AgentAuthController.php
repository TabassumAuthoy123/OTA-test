<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AgentAuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {
            return redirect('/home');
        }
        return view('auth.agent_login');
    }

    public function showRegister()
    {
        return view('auth.agent_register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'company_name'  => 'required|string|max:255',
            'contact_name'  => 'required|string|max:255',
            'email'         => 'required|email|unique:agent_registrations,email|unique:users,email',
            'phone'         => 'required|string|max:20',
            'address'       => 'nullable|string',
            'password'      => 'required|string|min:8|confirmed',
            'user_photo'    => 'nullable|image|max:2048',
            'agency_logo'   => 'nullable|image|max:2048',
            'trade_license' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'nid_document'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'civil_aviation'=> 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        $userPhoto = $this->uploadFile($request, 'user_photo', 'agentPhotos');
        $agencyLogo = $this->uploadFile($request, 'agency_logo', 'agentLogos');
        $tradeLicense = $this->uploadFile($request, 'trade_license', 'agentDocs');
        $nidDoc = $this->uploadFile($request, 'nid_document', 'agentDocs');
        $civilAviation = $this->uploadFile($request, 'civil_aviation', 'agentDocs');

        DB::table('agent_registrations')->insert([
            'company_name'   => $request->company_name,
            'contact_name'   => $request->contact_name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'password'       => Hash::make($request->password),
            'user_photo'     => $userPhoto,
            'agency_logo'    => $agencyLogo,
            'trade_license'  => $tradeLicense,
            'nid_document'   => $nidDoc,
            'civil_aviation' => $civilAviation,
            'status'         => 0,
            'created_at'     => Carbon::now(),
            'updated_at'     => Carbon::now(),
        ]);

        return redirect()->route('agent.login')->with('success', 'Registration submitted! You will be notified once your account is approved.');
    }

    public function showForgotPassword()
    {
        return view('auth.agent_forgot_password');
    }

    public function viewAgentRegistrations(Request $request)
    {
        $registrations = DB::table('agent_registrations')
            ->orderByDesc('id')
            ->paginate(20);
        return view('admin.agent_registrations', compact('registrations'));
    }

    public function approveAgentRegistration($id)
    {
        $reg = DB::table('agent_registrations')->where('id', $id)->first();
        if (!$reg || $reg->status != 0) {
            return back()->with('error', 'Invalid request.');
        }

        $userId = DB::table('users')->insertGetId([
            'name'           => $reg->contact_name,
            'email'          => $reg->email,
            'phone'          => $reg->phone,
            'password'       => $reg->password,
            'image'          => $reg->user_photo,
            'user_type'      => 2, // B2B
            'status'         => 1,
            'search_status'  => 1,
            'booking_status' => 1,
            'ticket_status'  => 1,
            'comission'      => 0,
            'created_at'     => Carbon::now(),
            'updated_at'     => Carbon::now(),
        ]);

        DB::table('company_profiles')->insert([
            'user_id'    => $userId,
            'name'       => $reg->company_name,
            'email'      => $reg->email,
            'phone'      => $reg->phone,
            'address'    => $reg->address,
            'logo'       => $reg->agency_logo,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('agent_registrations')->where('id', $id)->update([
            'status'     => 1,
            'updated_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Agent approved and B2B account created.');
    }

    public function rejectAgentRegistration(Request $request, $id)
    {
        DB::table('agent_registrations')->where('id', $id)->update([
            'status'           => 2,
            'rejection_reason' => $request->rejection_reason,
            'updated_at'       => Carbon::now(),
        ]);
        return back()->with('success', 'Registration rejected.');
    }

    private function uploadFile(Request $request, string $field, string $folder): ?string
    {
        if (!$request->hasFile($field)) {
            return null;
        }
        $file = $request->file($field);
        $name = Str::random(6) . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($folder . '/'), $name);
        return $folder . '/' . $name;
    }
}
