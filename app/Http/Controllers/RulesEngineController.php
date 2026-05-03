<?php

namespace App\Http\Controllers;

use App\Models\CommissionRule;
use App\Models\MarkupRule;
use App\Models\BlockingRule;
use App\Models\User;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Yajra\DataTables\DataTables;

class RulesEngineController extends Controller
{
    /**
     * Show the unified Rules Engine dashboard
     */
    public function index()
    {
        $commissionCount = CommissionRule::active()->count();
        $markupCount = MarkupRule::active()->count();
        $blockingCount = BlockingRule::active()->count();

        return view('settings.rules_engine', compact('commissionCount', 'markupCount', 'blockingCount'));
    }

    // ─── COMMISSION RULES ───

    public function commissionRules(Request $request)
    {
        if ($request->ajax()) {
            $data = CommissionRule::orderBy('priority', 'asc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('commission_value', fn($d) => $d->commission_type === 'percentage' ? $d->commission_value . '%' : '৳' . number_format($d->commission_value))
                ->editColumn('is_active', fn($d) => $d->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>')
                ->editColumn('gds', fn($d) => strtoupper($d->gds))
                ->editColumn('airline_code', fn($d) => $d->airline_code ?? '<span class="text-muted">All</span>')
                ->editColumn('route_from', fn($d) => $d->route_from ? $d->route_from . ' → ' . ($d->route_to ?? 'Any') : '<span class="text-muted">All Routes</span>')
                ->editColumn('cabin_class', fn($d) => $d->cabin_class ?? '<span class="text-muted">All</span>')
                ->editColumn('pax_type', fn($d) => $d->pax_type ?? '<span class="text-muted">All</span>')
                ->addColumn('agent_name', fn($d) => $d->agent_id ? ($d->agent ? $d->agent->name : 'Agent #' . $d->agent_id) : '<span class="text-muted">Global</span>')
                ->addColumn('action', function ($d) {
                    return '<a href="javascript:void(0)" data-id="' . $d->id . '" class="btn-sm btn-warning editCommissionBtn"><i class="fas fa-edit"></i></a>
                            <a href="javascript:void(0)" data-id="' . $d->id . '" class="btn-sm btn-danger deleteCommissionBtn"><i class="fas fa-trash"></i></a>';
                })
                ->rawColumns(['is_active', 'airline_code', 'route_from', 'cabin_class', 'pax_type', 'agent_name', 'action'])
                ->make(true);
        }
    }

    public function storeCommission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'commission_type' => 'required|in:percentage,fixed',
            'commission_value' => 'required|numeric|min:0',
        ]);

        CommissionRule::updateOrCreate(
            ['id' => $request->rule_id],
            [
                'name' => $request->name,
                'gds' => $request->gds ?? 'all',
                'airline_code' => $request->airline_code ?: null,
                'route_from' => $request->route_from ?: null,
                'route_to' => $request->route_to ?: null,
                'cabin_class' => $request->cabin_class ?: null,
                'pax_type' => $request->pax_type ?: null,
                'agent_id' => $request->agent_id ?: null,
                'commission_type' => $request->commission_type,
                'commission_value' => $request->commission_value,
                'is_active' => $request->has('is_active'),
                'priority' => $request->priority ?? 100,
            ]
        );

        return response()->json(['success' => 'Commission rule saved successfully.']);
    }

    public function getCommission($id)
    {
        return response()->json(CommissionRule::find($id));
    }

    public function deleteCommission($id)
    {
        CommissionRule::destroy($id);
        return response()->json(['success' => 'Rule deleted.']);
    }

    /**
     * Set commission % for a single agent (updates users.comission directly).
     */
    public function setAgentCommission(Request $request)
    {
        $request->validate([
            'agent_id'   => 'required|exists:users,id',
            'commission' => 'required|numeric|min:0|max:100',
        ]);

        User::where('id', $request->agent_id)->update(['comission' => $request->commission]);

        Toastr::success('Commission updated for agent.');
        return back();
    }

    /**
     * Bulk: set same commission % for ALL B2B agents.
     */
    public function setGlobalCommission(Request $request)
    {
        $request->validate([
            'global_commission' => 'required|numeric|min:0|max:100',
        ]);

        $count = User::where('user_type', 2)->update(['comission' => $request->global_commission]);

        Toastr::success("Commission set to {$request->global_commission}% for {$count} agents.");
        return back();
    }

    // ─── MARKUP RULES ───

    public function markupRules(Request $request)
    {
        if ($request->ajax()) {
            $data = MarkupRule::orderBy('priority', 'asc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('markup_value', fn($d) => $d->markup_type === 'percentage' ? $d->markup_value . '%' : '৳' . number_format($d->markup_value))
                ->editColumn('is_active', fn($d) => $d->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>')
                ->editColumn('channel', fn($d) => strtoupper($d->channel))
                ->editColumn('gds', fn($d) => strtoupper($d->gds))
                ->editColumn('airline_code', fn($d) => $d->airline_code ?? '<span class="text-muted">All</span>')
                ->editColumn('route_from', fn($d) => $d->route_from ? $d->route_from . ' → ' . ($d->route_to ?? 'Any') : '<span class="text-muted">All Routes</span>')
                ->editColumn('cabin_class', fn($d) => $d->cabin_class ?? '<span class="text-muted">All</span>')
                ->editColumn('pax_type', fn($d) => $d->pax_type ?? '<span class="text-muted">All</span>')
                ->addColumn('action', function ($d) {
                    return '<a href="javascript:void(0)" data-id="' . $d->id . '" class="btn-sm btn-warning editMarkupBtn"><i class="fas fa-edit"></i></a>
                            <a href="javascript:void(0)" data-id="' . $d->id . '" class="btn-sm btn-danger deleteMarkupBtn"><i class="fas fa-trash"></i></a>';
                })
                ->rawColumns(['is_active', 'airline_code', 'route_from', 'cabin_class', 'pax_type', 'action'])
                ->make(true);
        }
    }

    public function storeMarkup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'markup_type' => 'required|in:percentage,fixed',
            'markup_value' => 'required|numeric|min:0',
        ]);

        MarkupRule::updateOrCreate(
            ['id' => $request->rule_id],
            [
                'name' => $request->name,
                'channel' => $request->channel ?? 'all',
                'gds' => $request->gds ?? 'all',
                'airline_code' => $request->airline_code ?: null,
                'route_from' => $request->route_from ?: null,
                'route_to' => $request->route_to ?: null,
                'cabin_class' => $request->cabin_class ?: null,
                'pax_type' => $request->pax_type ?: null,
                'agent_id' => $request->agent_id ?: null,
                'markup_type' => $request->markup_type,
                'markup_value' => $request->markup_value,
                'is_active' => $request->has('is_active'),
                'priority' => $request->priority ?? 100,
            ]
        );

        return response()->json(['success' => 'Markup rule saved successfully.']);
    }

    public function getMarkup($id)
    {
        return response()->json(MarkupRule::find($id));
    }

    public function deleteMarkup($id)
    {
        MarkupRule::destroy($id);
        return response()->json(['success' => 'Rule deleted.']);
    }

    // ─── BLOCKING RULES ───

    public function blockingRules(Request $request)
    {
        if ($request->ajax()) {
            $data = BlockingRule::orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('is_active', fn($d) => $d->is_active ? '<span class="badge bg-danger">Blocked</span>' : '<span class="badge bg-secondary">Inactive</span>')
                ->editColumn('gds', fn($d) => strtoupper($d->gds))
                ->editColumn('block_type', fn($d) => '<span class="badge bg-dark">' . strtoupper($d->block_type) . '</span>')
                ->editColumn('airline_code', fn($d) => $d->airline_code ?? '<span class="text-muted">Any</span>')
                ->editColumn('route_from', fn($d) => $d->route_from ? $d->route_from . ' → ' . ($d->route_to ?? 'Any') : '<span class="text-muted">N/A</span>')
                ->editColumn('cabin_class', fn($d) => $d->cabin_class ?? '<span class="text-muted">Any</span>')
                ->addColumn('action', function ($d) {
                    return '<a href="javascript:void(0)" data-id="' . $d->id . '" class="btn-sm btn-warning editBlockBtn"><i class="fas fa-edit"></i></a>
                            <a href="javascript:void(0)" data-id="' . $d->id . '" class="btn-sm btn-danger deleteBlockBtn"><i class="fas fa-trash"></i></a>';
                })
                ->rawColumns(['is_active', 'block_type', 'airline_code', 'route_from', 'cabin_class', 'action'])
                ->make(true);
        }
    }

    public function storeBlocking(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'block_type' => 'required|in:airline,route,class,combo',
        ]);

        BlockingRule::updateOrCreate(
            ['id' => $request->rule_id],
            [
                'name' => $request->name,
                'gds' => $request->gds ?? 'all',
                'airline_code' => $request->airline_code ?: null,
                'route_from' => $request->route_from ?: null,
                'route_to' => $request->route_to ?: null,
                'cabin_class' => $request->cabin_class ?: null,
                'block_type' => $request->block_type,
                'reason' => $request->reason ?: null,
                'is_active' => $request->has('is_active'),
            ]
        );

        return response()->json(['success' => 'Blocking rule saved successfully.']);
    }

    public function getBlocking($id)
    {
        return response()->json(BlockingRule::find($id));
    }

    public function deleteBlocking($id)
    {
        BlockingRule::destroy($id);
        return response()->json(['success' => 'Rule deleted.']);
    }
}
