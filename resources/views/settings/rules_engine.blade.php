@extends('master')

@section('header_css')
    <link href="{{ url('dataTable') }}/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="{{ url('dataTable') }}/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .rules-header { background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%); color: #fff; border-radius: 8px; padding: 20px 24px; margin-bottom: 20px; }
        .rules-header h4 { font-weight: 700; margin: 0; }
        .rules-header p { margin: 4px 0 0; opacity: 0.8; font-size: 14px; }

        .stat-card { background: #fff; border-radius: 8px; padding: 16px 20px; border-left: 4px solid; box-shadow: 0 1px 4px rgba(0,0,0,0.06); }
        .stat-card.commission { border-color: #38a169; }
        .stat-card.markup { border-color: #3182ce; }
        .stat-card.blocking { border-color: #e53e3e; }
        .stat-card h2 { font-size: 28px; font-weight: 800; margin: 0; }
        .stat-card p { font-size: 13px; color: #666; margin: 2px 0 0; }

        .nav-tabs-rules { border-bottom: 2px solid #e2e8f0; margin-bottom: 16px; }
        .nav-tabs-rules .nav-link { border: none; color: #555; font-weight: 600; font-size: 14px; padding: 10px 20px; border-bottom: 3px solid transparent; }
        .nav-tabs-rules .nav-link.active { color: #1e3a5f; border-bottom-color: #1e3a5f; background: transparent; }
        .nav-tabs-rules .nav-link:hover { color: #1e3a5f; }

        .badge.bg-success { background-color: #38a169 !important; color: #fff; }
        .badge.bg-secondary { background-color: #a0aec0 !important; }
        .badge.bg-danger { background-color: #e53e3e !important; color: #fff; }
        .badge.bg-dark { background-color: #2d3748 !important; color: #fff; }

        .modal-header-rules { background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%); color: #fff; border-radius: 8px 8px 0 0; }
        .modal-header-rules .btn-close { filter: invert(1); }

        .form-section-label { font-size: 12px; font-weight: 700; color: #1e3a5f; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; margin-bottom: 12px; margin-top: 16px; }

        table.dataTable tbody td { vertical-align: middle; font-size: 13px; }
        table.dataTable thead th { font-size: 12px; text-transform: uppercase; letter-spacing: 0.3px; background: #f7fafc; }

        .dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0px; border-radius: 4px; }
    </style>
@endsection

@section('content')

    {{-- Header --}}
    <div class="rules-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4>⚙️ Rules Engine</h4>
                <p>Commission, Markup & Blocking — Multi-Level Configuration</p>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row mb-3">
        <div class="col-lg-4">
            <div class="stat-card commission">
                <h2>{{ $commissionCount }}</h2>
                <p>Active Commission Rules</p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="stat-card markup">
                <h2>{{ $markupCount }}</h2>
                <p>Active Markup Rules</p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="stat-card blocking">
                <h2>{{ $blockingCount }}</h2>
                <p>Active Blocking Rules</p>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-rules" id="rulesTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="commission-tab" data-toggle="tab" href="#commissionPane" role="tab">💰 Commission Rules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="markup-tab" data-toggle="tab" href="#markupPane" role="tab">📈 Markup Rules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="blocking-tab" data-toggle="tab" href="#blockingPane" role="tab">🚫 Blocking Rules</a>
                </li>
            </ul>

            <div class="tab-content" id="rulesTabContent">

                {{-- ═══ COMMISSION TAB ═══ --}}
                <div class="tab-pane fade show active" id="commissionPane" role="tabpanel">
                    <div class="d-flex justify-content-between mb-3">
                        <h6 class="font-weight-bold text-success"><i class="fas fa-percentage"></i> Airline / Agent Commission Configuration</h6>
                        <button class="btn btn-success btn-sm" id="addCommissionBtn"><i class="fas fa-plus"></i> Add Commission Rule</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="commissionTable">
                            <thead>
                                <tr>
                                    <th>#</th><th>Name</th><th>GDS</th><th>Airline</th><th>Route</th><th>Class</th><th>PAX</th><th>Agent</th><th>Value</th><th>Priority</th><th>Status</th><th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                {{-- ═══ MARKUP TAB ═══ --}}
                <div class="tab-pane fade" id="markupPane" role="tabpanel">
                    <div class="d-flex justify-content-between mb-3">
                        <h6 class="font-weight-bold text-primary"><i class="fas fa-chart-line"></i> B2C / B2B Markup Configuration</h6>
                        <button class="btn btn-primary btn-sm" id="addMarkupBtn"><i class="fas fa-plus"></i> Add Markup Rule</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="markupTable">
                            <thead>
                                <tr>
                                    <th>#</th><th>Name</th><th>Channel</th><th>GDS</th><th>Airline</th><th>Route</th><th>Class</th><th>PAX</th><th>Value</th><th>Priority</th><th>Status</th><th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                {{-- ═══ BLOCKING TAB ═══ --}}
                <div class="tab-pane fade" id="blockingPane" role="tabpanel">
                    <div class="d-flex justify-content-between mb-3">
                        <h6 class="font-weight-bold text-danger"><i class="fas fa-ban"></i> Airline / Route Blocking Rules</h6>
                        <button class="btn btn-danger btn-sm" id="addBlockBtn"><i class="fas fa-plus"></i> Add Blocking Rule</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="blockingTable">
                            <thead>
                                <tr>
                                    <th>#</th><th>Name</th><th>GDS</th><th>Type</th><th>Airline</th><th>Route</th><th>Class</th><th>Reason</th><th>Status</th><th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- ═══ COMMISSION MODAL ═══ --}}
    <div class="modal fade" id="commissionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-rules">
                    <h5 class="modal-title">💰 Commission Rule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="commissionForm">
                        <input type="hidden" id="comm_rule_id">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Rule Name <span class="text-danger">*</span></label>
                                    <input type="text" id="comm_name" class="form-control" placeholder="e.g. BG Domestic 7%">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Priority</label>
                                    <input type="number" id="comm_priority" class="form-control" value="100" min="1">
                                    <small class="text-muted">Lower = Higher priority</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-section-label">Matching Filters</div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>GDS</label>
                                    <select id="comm_gds" class="form-control">
                                        <option value="all">All GDS</option>
                                        @foreach(\App\Models\Gds::where('status', 1)->get() as $gdsItem)
                                            <option value="{{ $gdsItem->code }}">{{ ucfirst($gdsItem->code) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Airline Code</label>
                                    <input type="text" id="comm_airline" class="form-control" placeholder="BG, BS" maxlength="10">
                                    <small class="text-muted">Empty = All Airlines</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Route From</label>
                                    <input type="text" id="comm_route_from" class="form-control" placeholder="DAC" maxlength="5">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Route To</label>
                                    <input type="text" id="comm_route_to" class="form-control" placeholder="DXB" maxlength="5">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Cabin Class</label>
                                    <select id="comm_cabin" class="form-control">
                                        <option value="">All Classes</option>
                                        <option value="Y">Economy (Y)</option>
                                        <option value="C">Business (C)</option>
                                        <option value="F">First (F)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>PAX Type</label>
                                    <select id="comm_pax" class="form-control">
                                        <option value="">All PAX</option>
                                        <option value="ADT">Adult (ADT)</option>
                                        <option value="CHD">Child (CHD)</option>
                                        <option value="INF">Infant (INF)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Agent (Optional)</label>
                                    <input type="number" id="comm_agent_id" class="form-control" placeholder="Leave empty for Global rule">
                                    <small class="text-muted">Enter agent User ID for agent-specific rule</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-section-label">Commission Value</div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Type</label>
                                    <select id="comm_type" class="form-control">
                                        <option value="percentage">Percentage (%)</option>
                                        <option value="fixed">Fixed Amount (BDT)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Value</label>
                                    <input type="number" step="0.01" min="0" id="comm_value" class="form-control" placeholder="7.00">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3 pt-4">
                                    <label style="cursor:pointer">
                                        <input type="checkbox" id="comm_is_active" checked> Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="saveCommissionBtn" class="btn btn-success"><i class="fas fa-save"></i> Save Rule</button>
                </div>
            </div>
        </div>
    </div>


    {{-- ═══ MARKUP MODAL ═══ --}}
    <div class="modal fade" id="markupModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-rules">
                    <h5 class="modal-title">📈 Markup Rule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="markupForm">
                        <input type="hidden" id="mkp_rule_id">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Rule Name <span class="text-danger">*</span></label>
                                    <input type="text" id="mkp_name" class="form-control" placeholder="e.g. B2C Default 5%">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Priority</label>
                                    <input type="number" id="mkp_priority" class="form-control" value="100" min="1">
                                </div>
                            </div>
                        </div>

                        <div class="form-section-label">Matching Filters</div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Channel</label>
                                    <select id="mkp_channel" class="form-control">
                                        <option value="all">All Channels</option>
                                        <option value="b2c">B2C</option>
                                        <option value="b2b">B2B</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>GDS</label>
                                    <select id="mkp_gds" class="form-control">
                                        <option value="all">All GDS</option>
                                        @foreach(\App\Models\Gds::where('status', 1)->get() as $gdsItem)
                                            <option value="{{ $gdsItem->code }}">{{ ucfirst($gdsItem->code) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Airline Code</label>
                                    <input type="text" id="mkp_airline" class="form-control" placeholder="BG" maxlength="10">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Cabin Class</label>
                                    <select id="mkp_cabin" class="form-control">
                                        <option value="">All Classes</option>
                                        <option value="Y">Economy (Y)</option>
                                        <option value="C">Business (C)</option>
                                        <option value="F">First (F)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Route From</label>
                                    <input type="text" id="mkp_route_from" class="form-control" placeholder="DAC" maxlength="5">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Route To</label>
                                    <input type="text" id="mkp_route_to" class="form-control" placeholder="DXB" maxlength="5">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>PAX Type</label>
                                    <select id="mkp_pax" class="form-control">
                                        <option value="">All PAX</option>
                                        <option value="ADT">Adult (ADT)</option>
                                        <option value="CHD">Child (CHD)</option>
                                        <option value="INF">Infant (INF)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Agent ID</label>
                                    <input type="number" id="mkp_agent_id" class="form-control" placeholder="Global">
                                </div>
                            </div>
                        </div>

                        <div class="form-section-label">Markup Value</div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Type</label>
                                    <select id="mkp_type" class="form-control">
                                        <option value="percentage">Percentage (%)</option>
                                        <option value="fixed">Fixed Amount (BDT)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Value</label>
                                    <input type="number" step="0.01" min="0" id="mkp_value" class="form-control" placeholder="5.00">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3 pt-4">
                                    <label style="cursor:pointer">
                                        <input type="checkbox" id="mkp_is_active" checked> Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="saveMarkupBtn" class="btn btn-primary"><i class="fas fa-save"></i> Save Rule</button>
                </div>
            </div>
        </div>
    </div>


    {{-- ═══ BLOCKING MODAL ═══ --}}
    <div class="modal fade" id="blockingModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-rules">
                    <h5 class="modal-title">🚫 Blocking Rule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="blockingForm">
                        <input type="hidden" id="blk_rule_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Rule Name <span class="text-danger">*</span></label>
                                    <input type="text" id="blk_name" class="form-control" placeholder="e.g. Block AI on Sabre">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Block Type <span class="text-danger">*</span></label>
                                    <select id="blk_block_type" class="form-control">
                                        <option value="airline">Airline</option>
                                        <option value="route">Route</option>
                                        <option value="class">Class</option>
                                        <option value="combo">Combo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>GDS</label>
                                    <select id="blk_gds" class="form-control">
                                        <option value="all">All GDS</option>
                                        @foreach(\App\Models\Gds::where('status', 1)->get() as $gdsItem)
                                            <option value="{{ $gdsItem->code }}">{{ ucfirst($gdsItem->code) }} Only</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Airline Code</label>
                                    <input type="text" id="blk_airline" class="form-control" placeholder="AI" maxlength="10">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Route From</label>
                                    <input type="text" id="blk_route_from" class="form-control" placeholder="DAC" maxlength="5">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Route To</label>
                                    <input type="text" id="blk_route_to" class="form-control" placeholder="DXB" maxlength="5">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Cabin Class</label>
                                    <select id="blk_cabin" class="form-control">
                                        <option value="">Any</option>
                                        <option value="Y">Economy (Y)</option>
                                        <option value="C">Business (C)</option>
                                        <option value="F">First (F)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label>Reason (Optional)</label>
                                    <textarea id="blk_reason" class="form-control" rows="2" placeholder="Why this is blocked..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3 pt-4">
                                    <label style="cursor:pointer">
                                        <input type="checkbox" id="blk_is_active" checked> <strong class="text-danger">Block Active</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="saveBlockBtn" class="btn btn-danger"><i class="fas fa-ban"></i> Save Block Rule</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('footer_js')
    <script src="{{ url('dataTable') }}/js/jquery.validate.js"></script>
    <script src="{{ url('dataTable') }}/js/jquery.dataTables.min.js"></script>
    <script src="{{ url('dataTable') }}/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        // ═══ COMMISSION DATATABLE ═══
        var commissionTable = $("#commissionTable").DataTable({
            processing: true, serverSide: true,
            ajax: "{{ route('CommissionRulesList') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'name', name: 'name' },
                { data: 'gds', name: 'gds' },
                { data: 'airline_code', name: 'airline_code' },
                { data: 'route_from', name: 'route_from' },
                { data: 'cabin_class', name: 'cabin_class' },
                { data: 'pax_type', name: 'pax_type' },
                { data: 'agent_name', name: 'agent_name' },
                { data: 'commission_value', name: 'commission_value' },
                { data: 'priority', name: 'priority' },
                { data: 'is_active', name: 'is_active' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
        });

        // ═══ MARKUP DATATABLE ═══
        var markupTable = $("#markupTable").DataTable({
            processing: true, serverSide: true,
            ajax: "{{ route('MarkupRulesList') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'name', name: 'name' },
                { data: 'channel', name: 'channel' },
                { data: 'gds', name: 'gds' },
                { data: 'airline_code', name: 'airline_code' },
                { data: 'route_from', name: 'route_from' },
                { data: 'cabin_class', name: 'cabin_class' },
                { data: 'pax_type', name: 'pax_type' },
                { data: 'markup_value', name: 'markup_value' },
                { data: 'priority', name: 'priority' },
                { data: 'is_active', name: 'is_active' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
        });

        // ═══ BLOCKING DATATABLE ═══
        var blockingTable = $("#blockingTable").DataTable({
            processing: true, serverSide: true,
            ajax: "{{ route('BlockingRulesList') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'name', name: 'name' },
                { data: 'gds', name: 'gds' },
                { data: 'block_type', name: 'block_type' },
                { data: 'airline_code', name: 'airline_code' },
                { data: 'route_from', name: 'route_from' },
                { data: 'cabin_class', name: 'cabin_class' },
                { data: 'reason', name: 'reason' },
                { data: 'is_active', name: 'is_active' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
        });


        // ═══ COMMISSION CRUD ═══
        $('#addCommissionBtn').click(function() { $('#commissionForm').trigger('reset'); $('#comm_rule_id').val(''); $('#comm_is_active').prop('checked', true); $('#commissionModal').modal('show'); });

        $('#saveCommissionBtn').click(function(e) {
            e.preventDefault();
            var formData = new FormData();
            formData.append('rule_id', $('#comm_rule_id').val());
            formData.append('name', $('#comm_name').val());
            formData.append('gds', $('#comm_gds').val());
            formData.append('airline_code', $('#comm_airline').val());
            formData.append('route_from', $('#comm_route_from').val());
            formData.append('route_to', $('#comm_route_to').val());
            formData.append('cabin_class', $('#comm_cabin').val());
            formData.append('pax_type', $('#comm_pax').val());
            formData.append('agent_id', $('#comm_agent_id').val());
            formData.append('commission_type', $('#comm_type').val());
            formData.append('commission_value', $('#comm_value').val());
            formData.append('priority', $('#comm_priority').val());
            if ($('#comm_is_active').is(':checked')) formData.append('is_active', '1');

            $.ajax({ url: "{{ route('CommissionRuleStore') }}", type: 'POST', data: formData, contentType: false, processData: false,
                success: function() { $('#commissionModal').modal('hide'); commissionTable.draw(false); toastr.success('Commission rule saved!'); },
                error: function(xhr) { toastr.error(xhr.responseJSON?.message || 'Error saving rule'); }
            });
        });

        $('body').on('click', '.editCommissionBtn', function() {
            $.get("{{ url('rules/commission') }}/" + $(this).data('id'), function(d) {
                $('#comm_rule_id').val(d.id); $('#comm_name').val(d.name); $('#comm_gds').val(d.gds);
                $('#comm_airline').val(d.airline_code); $('#comm_route_from').val(d.route_from); $('#comm_route_to').val(d.route_to);
                $('#comm_cabin').val(d.cabin_class || ''); $('#comm_pax').val(d.pax_type || ''); $('#comm_agent_id').val(d.agent_id || '');
                $('#comm_type').val(d.commission_type); $('#comm_value').val(d.commission_value); $('#comm_priority').val(d.priority);
                $('#comm_is_active').prop('checked', d.is_active); $('#commissionModal').modal('show');
            });
        });

        $('body').on('click', '.deleteCommissionBtn', function() {
            if (confirm('Delete this commission rule?')) {
                $.ajax({ url: "{{ url('rules/commission') }}/" + $(this).data('id'), type: 'DELETE',
                    success: function() { commissionTable.draw(false); toastr.success('Rule deleted'); }
                });
            }
        });


        // ═══ MARKUP CRUD ═══
        $('#addMarkupBtn').click(function() { $('#markupForm').trigger('reset'); $('#mkp_rule_id').val(''); $('#mkp_is_active').prop('checked', true); $('#markupModal').modal('show'); });

        $('#saveMarkupBtn').click(function(e) {
            e.preventDefault();
            var formData = new FormData();
            formData.append('rule_id', $('#mkp_rule_id').val());
            formData.append('name', $('#mkp_name').val());
            formData.append('channel', $('#mkp_channel').val());
            formData.append('gds', $('#mkp_gds').val());
            formData.append('airline_code', $('#mkp_airline').val());
            formData.append('route_from', $('#mkp_route_from').val());
            formData.append('route_to', $('#mkp_route_to').val());
            formData.append('cabin_class', $('#mkp_cabin').val());
            formData.append('pax_type', $('#mkp_pax').val());
            formData.append('agent_id', $('#mkp_agent_id').val());
            formData.append('markup_type', $('#mkp_type').val());
            formData.append('markup_value', $('#mkp_value').val());
            formData.append('priority', $('#mkp_priority').val());
            if ($('#mkp_is_active').is(':checked')) formData.append('is_active', '1');

            $.ajax({ url: "{{ route('MarkupRuleStore') }}", type: 'POST', data: formData, contentType: false, processData: false,
                success: function() { $('#markupModal').modal('hide'); markupTable.draw(false); toastr.success('Markup rule saved!'); },
                error: function(xhr) { toastr.error(xhr.responseJSON?.message || 'Error saving rule'); }
            });
        });

        $('body').on('click', '.editMarkupBtn', function() {
            $.get("{{ url('rules/markup') }}/" + $(this).data('id'), function(d) {
                $('#mkp_rule_id').val(d.id); $('#mkp_name').val(d.name); $('#mkp_channel').val(d.channel); $('#mkp_gds').val(d.gds);
                $('#mkp_airline').val(d.airline_code); $('#mkp_route_from').val(d.route_from); $('#mkp_route_to').val(d.route_to);
                $('#mkp_cabin').val(d.cabin_class || ''); $('#mkp_pax').val(d.pax_type || ''); $('#mkp_agent_id').val(d.agent_id || '');
                $('#mkp_type').val(d.markup_type); $('#mkp_value').val(d.markup_value); $('#mkp_priority').val(d.priority);
                $('#mkp_is_active').prop('checked', d.is_active); $('#markupModal').modal('show');
            });
        });

        $('body').on('click', '.deleteMarkupBtn', function() {
            if (confirm('Delete this markup rule?')) {
                $.ajax({ url: "{{ url('rules/markup') }}/" + $(this).data('id'), type: 'DELETE',
                    success: function() { markupTable.draw(false); toastr.success('Rule deleted'); }
                });
            }
        });


        // ═══ BLOCKING CRUD ═══
        $('#addBlockBtn').click(function() { $('#blockingForm').trigger('reset'); $('#blk_rule_id').val(''); $('#blk_is_active').prop('checked', true); $('#blockingModal').modal('show'); });

        $('#saveBlockBtn').click(function(e) {
            e.preventDefault();
            var formData = new FormData();
            formData.append('rule_id', $('#blk_rule_id').val());
            formData.append('name', $('#blk_name').val());
            formData.append('gds', $('#blk_gds').val());
            formData.append('block_type', $('#blk_block_type').val());
            formData.append('airline_code', $('#blk_airline').val());
            formData.append('route_from', $('#blk_route_from').val());
            formData.append('route_to', $('#blk_route_to').val());
            formData.append('cabin_class', $('#blk_cabin').val());
            formData.append('reason', $('#blk_reason').val());
            if ($('#blk_is_active').is(':checked')) formData.append('is_active', '1');

            $.ajax({ url: "{{ route('BlockingRuleStore') }}", type: 'POST', data: formData, contentType: false, processData: false,
                success: function() { $('#blockingModal').modal('hide'); blockingTable.draw(false); toastr.success('Blocking rule saved!'); },
                error: function(xhr) { toastr.error(xhr.responseJSON?.message || 'Error saving rule'); }
            });
        });

        $('body').on('click', '.editBlockBtn', function() {
            $.get("{{ url('rules/blocking') }}/" + $(this).data('id'), function(d) {
                $('#blk_rule_id').val(d.id); $('#blk_name').val(d.name); $('#blk_gds').val(d.gds); $('#blk_block_type').val(d.block_type);
                $('#blk_airline').val(d.airline_code); $('#blk_route_from').val(d.route_from); $('#blk_route_to').val(d.route_to);
                $('#blk_cabin').val(d.cabin_class || ''); $('#blk_reason').val(d.reason);
                $('#blk_is_active').prop('checked', d.is_active); $('#blockingModal').modal('show');
            });
        });

        $('body').on('click', '.deleteBlockBtn', function() {
            if (confirm('Delete this blocking rule?')) {
                $.ajax({ url: "{{ url('rules/blocking') }}/" + $(this).data('id'), type: 'DELETE',
                    success: function() { blockingTable.draw(false); toastr.success('Rule deleted'); }
                });
            }
        });
    </script>
@endsection
