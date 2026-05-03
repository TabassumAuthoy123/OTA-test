<?php

namespace App\Http\Controllers;

use App\Models\LedgerEntry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

class LedgerController extends Controller
{
    // ─── ADMIN: All parties summary ────────────────────────────────────────────

    public function adminSummary(Request $request)
    {
        $search = $request->search;

        $agents = User::where('user_type', 2)
            ->when($search, fn($q) => $q->where(fn($q2) =>
                $q2->where('name', 'like', "%$search%")
                   ->orWhere('email', 'like', "%$search%")
            ))
            ->withCount(['ledgerEntries as total_debit' => fn($q) => $q->where('entry_type', 'debit')->select(DB::raw('SUM(amount)'))])
            ->withCount(['ledgerEntries as total_credit' => fn($q) => $q->where('entry_type', 'credit')->select(DB::raw('SUM(amount)'))])
            ->paginate(30);

        return view('ledger.admin_summary', compact('agents'));
    }

    // ─── ADMIN: Per-agent ledger detail ────────────────────────────────────────

    public function adminDetail(Request $request, int $userId)
    {
        $agent   = User::findOrFail($userId);
        $entries = LedgerEntry::where('party_id', $userId)
            ->with(['booking'])
            ->when($request->start_date, fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
            ->when($request->end_date,   fn($q) => $q->whereDate('created_at', '<=', $request->end_date))
            ->orderBy('id', 'desc')
            ->paginate(50);

        $summary = LedgerEntry::summary($userId);

        return view('ledger.admin_detail', compact('agent', 'entries', 'summary'));
    }

    // ─── ADMIN: Manual ledger entry (credit/debit) ─────────────────────────────

    public function adminAddEntry(Request $request)
    {
        $request->validate([
            'party_id'   => 'required|exists:users,id',
            'entry_type' => 'required|in:debit,credit',
            'amount'     => 'required|numeric|min:0.01',
            'description'=> 'required|string|max:255',
        ]);

        $user = User::findOrFail($request->party_id);

        if ($request->entry_type === 'credit') {
            LedgerEntry::credit($user->id, $request->amount, $request->description, null, $request->reference_no);
            $user->balance += $request->amount;
        } else {
            LedgerEntry::debit($user->id, $request->amount, $request->description, null, $request->reference_no);
            $user->balance -= $request->amount;
        }
        $user->save();

        Toastr::success('Ledger entry added.');
        return back();
    }

    // ─── B2B: Agent's own ledger ────────────────────────────────────────────────

    public function myLedger(Request $request)
    {
        $entries = LedgerEntry::where('party_id', Auth::id())
            ->with(['booking'])
            ->when($request->start_date, fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
            ->when($request->end_date,   fn($q) => $q->whereDate('created_at', '<=', $request->end_date))
            ->orderBy('id', 'desc')
            ->paginate(50);

        $summary = LedgerEntry::summary(Auth::id());

        return view('b2b_portal.my_ledger', compact('entries', 'summary'));
    }
}
