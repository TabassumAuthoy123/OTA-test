@extends('master')
@section('header_css')
<style>
.ppr-wrap{background:#fff;border-radius:10px;box-shadow:0 2px 12px rgba(0,0,0,.08);overflow:hidden;}
.ppr-topbar{display:flex;justify-content:space-between;align-items:center;padding:18px 24px;border-bottom:1px solid #e8ecf0;}
.ppr-topbar h5{margin:0;font-size:18px;font-weight:700;color:#1a3a5c;}
.ppr-topbar small{font-size:12px;color:#888;display:block;margin-top:2px;}
.btn-add-rule{background:#f0a500;color:#fff;border:none;padding:8px 18px;border-radius:6px;font-size:13px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:5px;}
.btn-add-rule:hover{background:#d4911a;color:#fff;}
.ppr-filters{background:#f8f9fa;padding:12px 20px;border-bottom:1px solid #e8ecf0;display:flex;align-items:center;gap:10px;}
.ppr-filters label{font-size:11px;font-weight:600;color:#666;margin-bottom:2px;display:block;}
.ppr-filters select,.ppr-filters input{font-size:12px;padding:5px 10px;border:1px solid #ced4da;border-radius:5px;height:32px;}
.ppr-table{width:100%;border-collapse:collapse;}
.ppr-table th{background:#1a3a5c;color:#fff;font-size:11px;padding:10px 10px;white-space:nowrap;font-weight:600;text-align:center;}
.ppr-table th:first-child,.ppr-table td:first-child{text-align:center;}
.ppr-table td{font-size:12px;padding:9px 10px;vertical-align:middle;border-bottom:1px solid #f0f0f0;text-align:center;}
.ppr-table tr:hover td{background:#f8fbff;}
.yn-yes{background:#d4edda;color:#155724;padding:2px 10px;border-radius:10px;font-size:11px;font-weight:700;display:inline-block;}
.yn-no{background:#f8d7da;color:#721c24;padding:2px 10px;border-radius:10px;font-size:11px;font-weight:700;display:inline-block;}
.api-badge{padding:2px 10px;border-radius:4px;font-size:11px;font-weight:700;text-transform:uppercase;}
.api-sabre{background:#dc3545;color:#fff;}
.api-flyhub{background:#0d6efd;color:#fff;}
.api-all{background:#6c757d;color:#fff;}
.status-active{background:#d4edda;color:#155724;padding:3px 10px;border-radius:10px;font-size:11px;font-weight:700;display:inline-block;}
.status-inactive{background:#f8d7da;color:#721c24;padding:3px 10px;border-radius:10px;font-size:11px;font-weight:700;display:inline-block;}
.btn-edit-row{background:#f0a500;color:#fff;border:none;padding:4px 12px;border-radius:5px;font-size:11px;font-weight:600;cursor:pointer;}
.btn-del-row{background:#dc3545;color:#fff;border:none;padding:4px 10px;border-radius:5px;font-size:11px;font-weight:600;cursor:pointer;}
.btn-save-row{background:#28a745;color:#fff;border:none;padding:4px 10px;border-radius:5px;font-size:11px;font-weight:600;cursor:pointer;}
.btn-cancel-row{background:#6c757d;color:#fff;border:none;padding:4px 10px;border-radius:5px;font-size:11px;font-weight:600;cursor:pointer;}
.edit-inp{font-size:11px;padding:3px 6px;border:1px solid #ced4da;border-radius:4px;height:28px;}
.edit-sel{font-size:11px;padding:2px 4px;border:1px solid #ced4da;border-radius:4px;height:28px;}
.edit-num{font-size:11px;padding:3px 5px;border:1px solid #ced4da;border-radius:4px;height:28px;width:54px;}
tr.edit-mode td{background:#fffbf0 !important;}
.del-confirm-overlay{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;display:flex;align-items:center;justify-content:center;}
.del-confirm-box{background:#fff;border-radius:10px;padding:28px 32px;min-width:320px;text-align:center;box-shadow:0 8px 30px rgba(0,0,0,.2);}
.del-confirm-box p{font-size:14px;font-weight:600;margin-bottom:20px;color:#333;}
.del-confirm-box i{font-size:28px;color:#f0a500;display:block;margin-bottom:10px;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">

  <div class="ppr-wrap">

    {{-- Top bar --}}
    <div class="ppr-topbar">
      <div>
        <h5>Flight Partial Payment Rules</h5>
        <small>Dashboard &rsaquo; Configuration &rsaquo; Flight-partial-payment-rule</small>
      </div>
      <button class="btn-add-rule" onclick="document.getElementById('addModal').style.display='flex'">
        + Add Partial Rule
      </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success m-3 py-2 mb-0">{{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <form method="GET" action="{{ url('configuration/partial-payment-rules') }}">
      <div class="ppr-filters">
        <div>
          <label>Filter by API</label>
          <select name="filter_api" onchange="this.form.submit()" style="width:130px;">
            @foreach($gds as $val => $label)
            <option value="{{ $val }}" {{ request('filter_api')==$val?'selected':'' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label>Status</label>
          <select name="filter_status" onchange="this.form.submit()" style="width:110px;">
            <option value="all">All</option>
            <option value="1" {{ request('filter_status')=='1'?'selected':'' }}>Active</option>
            <option value="0" {{ request('filter_status')=='0'?'selected':'' }}>Inactive</option>
          </select>
        </div>
        @if(request()->hasAny(['filter_api','filter_status']))
        <div style="align-self:flex-end;">
          <a href="{{ url('configuration/partial-payment-rules') }}" class="btn btn-secondary btn-sm" style="height:32px;font-size:12px;">Clear</a>
        </div>
        @endif
      </div>
    </form>

    {{-- Table --}}
    <div class="table-responsive">
      <table class="ppr-table">
        <thead>
          <tr>
            <th>SL</th>
            <th>Created Date</th>
            <th>API</th>
            <th>Airline</th>
            <th>From DAC</th>
            <th>To DAC</th>
            <th>Domestic</th>
            <th>SOTO</th>
            <th>One Way</th>
            <th>Round Trip</th>
            <th>Travel Date<br>From Now</th>
            <th>Payment<br>Before</th>
            <th>Payment %</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="pprTableBody">
          @forelse($rules as $i => $r)
          <tr id="row-{{ $r->id }}" data-id="{{ $r->id }}">
            {{-- DISPLAY cells --}}
            <td class="d-cell">{{ $rules->firstItem() + $i }}</td>
            <td class="d-cell">{{ $r->created_at ? date('m/d/Y', strtotime($r->created_at)) : 'N/A' }}</td>
            <td class="d-cell">
              <span class="api-badge api-{{ $r->flight_api }}">{{ strtoupper($r->flight_api) }}</span>
            </td>
            <td class="d-cell">{{ $r->airline_code ?: 'N/A' }}</td>
            <td class="d-cell"><span class="{{ $r->from_dac ? 'yn-yes':'yn-no' }}">{{ $r->from_dac?'Yes':'No' }}</span></td>
            <td class="d-cell"><span class="{{ $r->to_dac   ? 'yn-yes':'yn-no' }}">{{ $r->to_dac  ?'Yes':'No' }}</span></td>
            <td class="d-cell"><span class="{{ $r->domestic  ? 'yn-yes':'yn-no' }}">{{ $r->domestic ?'Yes':'No' }}</span></td>
            <td class="d-cell"><span class="{{ $r->soto      ? 'yn-yes':'yn-no' }}">{{ $r->soto     ?'Yes':'No' }}</span></td>
            <td class="d-cell"><span class="{{ $r->one_way   ? 'yn-yes':'yn-no' }}">{{ $r->one_way  ?'Yes':'No' }}</span></td>
            <td class="d-cell"><span class="{{ $r->round_trip? 'yn-yes':'yn-no' }}">{{ $r->round_trip?'Yes':'No' }}</span></td>
            <td class="d-cell">{{ $r->travel_date_from_now }} days</td>
            <td class="d-cell">{{ $r->payment_before_days }} days</td>
            <td class="d-cell">{{ number_format($r->payment_percent, 2) }}%</td>
            <td class="d-cell"><span class="{{ $r->is_active?'status-active':'status-inactive' }}">{{ $r->is_active?'Active':'Inactive' }}</span></td>
            <td class="d-cell" style="white-space:nowrap;">
              <button class="btn-edit-row btn-row-action" data-action="edit" data-id="{{ $r->id }}">&#9998; Edit</button>
              <button class="btn-del-row btn-row-action" data-action="delete" data-id="{{ $r->id }}">&#128465; Delete</button>
            </td>
            {{-- EDIT cells (hidden) --}}
            <td class="e-cell" style="display:none;"></td>{{-- SL placeholder --}}
            <td class="e-cell" style="display:none;"></td>{{-- Date placeholder --}}
            <td class="e-cell" style="display:none;"></td>{{-- API placeholder --}}
            <td class="e-cell" style="display:none;">
              <select class="edit-sel" style="width:80px;" name="airline_code">
                <option value="">N/A</option>
                @foreach($airlines as $al)
                <option value="{{ $al->iata }}" {{ $r->airline_code==$al->iata?'selected':'' }}>{{ $al->iata }}</option>
                @endforeach
              </select>
            </td>
            @foreach(['from_dac','to_dac','domestic','soto','one_way','round_trip'] as $field)
            <td class="e-cell" style="display:none;">
              <select class="edit-sel" name="{{ $field }}">
                <option value="yes" {{ $r->$field?'selected':'' }}>Yes</option>
                <option value="no"  {{ !$r->$field?'selected':'' }}>No</option>
              </select>
            </td>
            @endforeach
            <td class="e-cell" style="display:none;"><input type="number" class="edit-num" name="travel_date_from_now" value="{{ $r->travel_date_from_now }}" min="0"> Days</td>
            <td class="e-cell" style="display:none;"><input type="number" class="edit-num" name="payment_before_days" value="{{ $r->payment_before_days }}" min="0"> Days</td>
            <td class="e-cell" style="display:none;"><input type="number" class="edit-num" style="width:60px;" name="payment_percent" value="{{ $r->payment_percent }}" min="0" max="100" step="0.01"> %</td>
            <td class="e-cell" style="display:none;">
              <select class="edit-sel" name="is_active">
                <option value="active"   {{ $r->is_active?'selected':'' }}>Active</option>
                <option value="inactive" {{ !$r->is_active?'selected':'' }}>Inactive</option>
              </select>
            </td>
            <td class="e-cell" style="display:none;white-space:nowrap;">
              <button class="btn-save-row btn-row-action" data-action="save" data-id="{{ $r->id }}">&#128190; Save</button>
              <button class="btn-cancel-row btn-row-action" data-action="cancel" data-id="{{ $r->id }}">&#10007; Cancel</button>
            </td>
          </tr>
          @empty
          <tr><td colspan="15" class="text-center py-5 text-muted">No partial payment rules found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $rules->links() }}</div>
  </div>

</div></div>

{{-- ══ ADD MODAL ══ --}}
<div id="addModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9990;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:10px;width:560px;max-width:96vw;box-shadow:0 8px 30px rgba(0,0,0,.2);overflow:hidden;">
    <div style="background:#1a3a5c;color:#fff;padding:16px 20px;display:flex;justify-content:space-between;align-items:center;">
      <h6 style="margin:0;font-size:16px;font-weight:700;">Create Flight Partial Payment Rule</h6>
      <button onclick="document.getElementById('addModal').style.display='none'" style="background:none;border:none;color:#fff;font-size:20px;cursor:pointer;">&times;</button>
    </div>
    <form method="POST" action="{{ url('configuration/partial-payment-rules') }}">
      @csrf
      <div style="padding:20px;display:grid;grid-template-columns:1fr 1fr;gap:14px;">

        <div style="grid-column:1/-1;">
          <label style="font-size:12px;font-weight:700;color:#555;">Flight API <span class="text-danger">*</span></label>
          <select name="flight_api" class="form-select form-select-sm">
            @foreach($gds as $val => $label)
            <option value="{{ $val }}">{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <div style="grid-column:1/-1;">
          <label style="font-size:12px;font-weight:700;color:#555;">Select Airline</label>
          <select name="airline_code" class="form-select form-select-sm">
            <option value="">Select Airline</option>
            @foreach($airlines as $al)
            <option value="{{ $al->iata }}">{{ $al->name }} ({{ $al->iata }})</option>
            @endforeach
          </select>
        </div>

        @foreach([
          ['from_dac','From DAC'],['to_dac','To DAC'],
          ['domestic','Domestic'],['soto','SOTO'],
          ['one_way','One Way'],['round_trip','Round Trip']
        ] as [$fname, $flabel])
        <div>
          <label style="font-size:12px;font-weight:700;color:#555;">{{ $flabel }} <span class="text-danger">*</span></label>
          <select name="{{ $fname }}" class="form-select form-select-sm">
            <option value="yes">Yes</option>
            <option value="no" selected>No</option>
          </select>
        </div>
        @endforeach

        <div>
          <label style="font-size:12px;font-weight:700;color:#555;">Travel Date From Now <span class="text-danger">*</span></label>
          <div class="input-group input-group-sm">
            <input type="number" name="travel_date_from_now" class="form-control" value="0" min="0" required>
            <span class="input-group-text">Days</span>
          </div>
        </div>

        <div>
          <label style="font-size:12px;font-weight:700;color:#555;">Payment After Issued <span class="text-danger">*</span></label>
          <div class="input-group input-group-sm">
            <input type="number" name="payment_before_days" class="form-control" value="0" min="0" required>
            <span class="input-group-text">Days</span>
          </div>
        </div>

        <div>
          <label style="font-size:12px;font-weight:700;color:#555;">Payment % <span class="text-danger">*</span></label>
          <div class="input-group input-group-sm">
            <input type="number" name="payment_percent" class="form-control" value="0" min="0" max="100" step="0.01" required>
            <span class="input-group-text">%</span>
          </div>
        </div>

      </div>
      <div style="padding:12px 20px;border-top:1px solid #eee;display:flex;justify-content:flex-start;">
        <button type="submit" style="background:#f0a500;color:#fff;border:none;padding:8px 22px;border-radius:6px;font-size:13px;font-weight:700;cursor:pointer;">+ Create</button>
      </div>
    </form>
  </div>
</div>

{{-- ══ DELETE CONFIRM OVERLAY ══ --}}
<div id="delOverlay" class="del-confirm-overlay" style="display:none;">
  <div class="del-confirm-box">
    <i class="fas fa-exclamation-circle"></i>
    <p>Are you sure you want to delete this<br>partial payment rule?</p>
    <div style="display:flex;gap:10px;justify-content:center;">
      <button onclick="document.getElementById('delOverlay').style.display='none'" style="padding:7px 22px;border-radius:6px;border:1px solid #ccc;background:#fff;font-size:13px;font-weight:600;cursor:pointer;">Cancel</button>
      <button id="delConfirmBtn" style="padding:7px 22px;border-radius:6px;border:none;background:#f0a500;color:#fff;font-size:13px;font-weight:700;cursor:pointer;">OK</button>
    </div>
  </div>
</div>

@endsection
@section('footer_js')
<script>
const CSRF = '{{ csrf_token() }}';
const BASE = '{{ url("configuration/partial-payment-rules") }}';
let pendingDelId = null;

// ── Event delegation for all row buttons ─────────────────────────────────────
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.btn-row-action');
  if (!btn) return;
  const id = btn.dataset.id;
  const action = btn.dataset.action;
  if (action === 'edit')   startEdit(id);
  if (action === 'cancel') cancelEdit(id);
  if (action === 'save')   saveEdit(id);
  if (action === 'delete') { pendingDelId = id; document.getElementById('delOverlay').style.display = 'flex'; }
});

function startEdit(id) {
  const row = document.getElementById('row-' + id);
  row.querySelectorAll('.d-cell').forEach(td => td.style.display = 'none');
  row.querySelectorAll('.e-cell').forEach(td => td.style.display = '');
  row.classList.add('edit-mode');
}

function cancelEdit(id) {
  const row = document.getElementById('row-' + id);
  row.querySelectorAll('.d-cell').forEach(td => td.style.display = '');
  row.querySelectorAll('.e-cell').forEach(td => td.style.display = 'none');
  row.classList.remove('edit-mode');
}

function saveEdit(id) {
  const row = document.getElementById('row-' + id);
  const data = {};
  row.querySelectorAll('.e-cell [name]').forEach(el => { data[el.name] = el.value; });

  fetch(BASE + '/' + id, {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-HTTP-Method-Override':'PUT'},
    body: JSON.stringify(data)
  })
  .then(r => r.json())
  .then(res => {
    if (res.success) { location.reload(); }
    else { alert('Save failed. Please try again.'); }
  })
  .catch(() => alert('Network error. Please try again.'));
}

// ── Delete confirm ────────────────────────────────────────────────────────────
document.getElementById('delConfirmBtn').addEventListener('click', function() {
  if (!pendingDelId) return;
  fetch(BASE + '/' + pendingDelId, {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-HTTP-Method-Override':'DELETE'},
    body: '{}'
  })
  .then(r => r.json())
  .then(res => {
    if (res.success) {
      const row = document.getElementById('row-' + pendingDelId);
      if (row) row.remove();
      document.getElementById('delOverlay').style.display = 'none';
      pendingDelId = null;
    }
  })
  .catch(() => alert('Delete failed.'));
});

// ── Close add modal on backdrop click ─────────────────────────────────────────
document.getElementById('addModal').addEventListener('click', function(e) {
  if (e.target === this) this.style.display = 'none';
});
</script>
@endsection
