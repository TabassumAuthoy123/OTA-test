@extends('master')
@section('header_css')
<style>
.frs-wrap{background:#fff;border-radius:10px;box-shadow:0 2px 12px rgba(0,0,0,.08);overflow:hidden;}
.frs-topbar{display:flex;justify-content:space-between;align-items:center;padding:18px 24px;border-bottom:1px solid #e8ecf0;}
.frs-topbar h5{margin:0;font-size:18px;font-weight:700;color:#1a3a5c;}
.frs-topbar small{font-size:12px;color:#888;display:block;margin-top:2px;}
.btn-add-sup{background:#f0a500;color:#fff;border:none;padding:8px 18px;border-radius:6px;font-size:13px;font-weight:700;cursor:pointer;}
.btn-add-sup:hover{background:#d4911a;}
.frs-table{width:100%;border-collapse:collapse;}
.frs-table th{background:#f8f9fa;color:#1a3a5c;font-size:12px;padding:10px 12px;font-weight:700;border-bottom:2px solid #dee2e6;text-align:center;white-space:nowrap;}
.frs-table td{font-size:12px;padding:10px 12px;border-bottom:1px solid #f0f0f0;vertical-align:middle;text-align:center;}
.frs-table tr:hover td{background:#f8fbff;}
.api-badge{padding:3px 10px;border-radius:4px;font-size:11px;font-weight:700;text-transform:uppercase;}
.api-sabre{background:#dc3545;color:#fff;}
.api-flyhub{background:#0d6efd;color:#fff;}
.api-all{background:#6c757d;color:#fff;}
.type-badge{background:#e8ecf0;color:#1a3a5c;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;}
.s-active{background:#d4edda;color:#155724;padding:2px 10px;border-radius:10px;font-size:11px;font-weight:700;}
.s-inactive{background:#f8d7da;color:#721c24;padding:2px 10px;border-radius:10px;font-size:11px;font-weight:700;}
.btn-del-sup{background:#dc3545;color:#fff;border:none;padding:4px 10px;border-radius:5px;font-size:11px;font-weight:600;cursor:pointer;}
.empty-state{text-align:center;padding:50px 20px;}
.empty-state i{font-size:36px;color:#dee2e6;display:block;margin-bottom:10px;}
/* modal */
.sup-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9990;display:none;align-items:center;justify-content:center;}
.sup-overlay.show{display:flex;}
.sup-modal{background:#fff;border-radius:10px;width:620px;max-width:96vw;box-shadow:0 8px 30px rgba(0,0,0,.2);overflow:hidden;max-height:90vh;overflow-y:auto;}
.sup-modal-hdr{padding:18px 22px;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:flex-start;position:sticky;top:0;background:#fff;z-index:1;}
.sup-modal-hdr h6{margin:0;font-size:16px;font-weight:700;color:#1a3a5c;}
.sup-modal-hdr p{margin:2px 0 0;font-size:12px;color:#888;}
.sup-modal-hdr button{background:none;border:none;font-size:22px;cursor:pointer;color:#666;}
.sup-modal-body{padding:20px 22px;}
.sup-fld label{font-size:12px;font-weight:700;color:#555;margin-bottom:4px;display:block;}
.sup-fld label span{color:#dc3545;}
.sup-fld select,.sup-fld input[type=number]{width:100%;font-size:13px;padding:7px 10px;border:1px solid #ced4da;border-radius:5px;height:36px;}
.section-hdr{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #eee;margin-bottom:12px;cursor:pointer;}
.section-hdr span{font-size:13px;font-weight:700;color:#1a3a5c;}
.section-hdr .toggle-btn{background:#f0f4ff;border:1px solid #c8d6f0;color:#1a3a5c;padding:3px 10px;border-radius:5px;font-size:12px;font-weight:600;cursor:pointer;}
.section-body{display:none;}
.section-body.open{display:block;}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;}
.grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:12px;}
.sup-modal-footer{padding:14px 22px;border-top:1px solid #eee;display:flex;justify-content:flex-end;gap:10px;position:sticky;bottom:0;background:#fff;}
.btn-reset{background:#fff;color:#333;border:1px solid #ccc;padding:7px 18px;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;}
.btn-submit-yellow{background:#f0a500;color:#fff;border:none;padding:8px 20px;border-radius:6px;font-size:13px;font-weight:700;cursor:pointer;}
/* del confirm */
.del-overlay{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;display:none;align-items:center;justify-content:center;}
.del-overlay.show{display:flex;}
.del-box{background:#fff;border-radius:10px;padding:28px 32px;min-width:300px;text-align:center;box-shadow:0 8px 30px rgba(0,0,0,.2);}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  <div class="frs-wrap">

    {{-- Topbar --}}
    <div class="frs-topbar">
      <div>
        <h5>Fare Rules Supplier List - {{ $set->name }}</h5>
        <small>Dashboard &rsaquo; Configuration &rsaquo; Fare-rules-supplier</small>
      </div>
      <div style="display:flex;gap:10px;align-items:center;">
        <a href="{{ url('configuration/dynamic-fare-rules') }}" style="font-size:12px;color:#888;text-decoration:none;">&#8592; Back to Sets</a>
        <button class="btn-add-sup" onclick="document.getElementById('addSupModal').classList.add('show')">+ Add to Supplier</button>
      </div>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
      <table class="frs-table">
        <thead>
          <tr>
            <th>SL</th>
            <th>API</th>
            <th>Pax Markup</th>
            <th>Commission</th>
            <th>Commission Type</th>
            <th>Markup</th>
            <th>Markup Type</th>
            <th>Seg. Commission</th>
            <th>Seg. Comm. Type</th>
            <th>Seg. Markup</th>
            <th>Seg. Markup Type</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($suppliers as $i => $sup)
          <tr id="suprow-{{ $sup->id }}">
            <td>{{ $i + 1 }}</td>
            <td><span class="api-badge api-{{ $sup->api_type }}">{{ strtoupper($sup->api_type) }}</span></td>
            <td>{{ number_format($sup->pax_markup_value, 2) }}</td>
            <td>{{ number_format($sup->commission_value, 2) }}</td>
            <td><span class="type-badge">{{ ucfirst($sup->commission_type) }}</span></td>
            <td>{{ number_format($sup->markup_value, 2) }}</td>
            <td><span class="type-badge">{{ ucfirst($sup->markup_type) }}</span></td>
            <td>{{ number_format($sup->segment_commission_value, 2) }}</td>
            <td><span class="type-badge">{{ ucfirst($sup->segment_commission_type) }}</span></td>
            <td>{{ number_format($sup->segment_markup_value, 2) }}</td>
            <td><span class="type-badge">{{ ucfirst($sup->segment_markup_type) }}</span></td>
            <td><span class="{{ $sup->is_active ? 's-active':'s-inactive' }}">{{ $sup->is_active?'Active':'Inactive' }}</span></td>
            <td>
              <button class="btn-del-sup" data-sid="{{ $sup->id }}" onclick="confirmDelSup(this.dataset.sid)">&#128465; Del</button>
            </td>
          </tr>
          @empty
          <tr><td colspan="13">
            <div class="empty-state">
              <i class="fas fa-inbox"></i>
              <div style="color:#888;font-size:13px;">No data</div>
            </div>
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3" style="font-size:12px;color:#888;">{{ $suppliers->count() }} supplier(s) in this set</div>
  </div>
</div></div>

{{-- ══ ADD SUPPLIER MODAL ══ --}}
<div class="sup-overlay" id="addSupModal">
  <div class="sup-modal">
    <div class="sup-modal-hdr">
      <div>
        <h6>Add To Supplier</h6>
        <p>Assign fare rules to a supplier with pricing configurations</p>
      </div>
      <button onclick="document.getElementById('addSupModal').classList.remove('show')">&times;</button>
    </div>
    <div class="sup-modal-body">

      <div class="sup-fld" style="margin-bottom:14px;">
        <label>Supplier <span>*</span></label>
        <select id="sup_api_type">
          <option value="">Select a supplier</option>
          @foreach($gds as $val => $label)
          @if($val !== 'all')
          <option value="{{ $val }}">{{ $label }}</option>
          @endif
          @endforeach
          <option value="all">All API</option>
        </select>
      </div>

      {{-- Commission Settings --}}
      <div>
        <div class="section-hdr" onclick="toggleSection('commSection')">
          <span>Commission Settings</span>
          <button type="button" class="toggle-btn" id="commToggleBtn">Add Commission</button>
        </div>
        <div class="section-body" id="commSection">
          <div class="grid-2">
            <div class="sup-fld">
              <label>Commission Value</label>
              <input type="number" id="sup_commission_value" value="0" min="0" step="0.01">
            </div>
            <div class="sup-fld">
              <label>Commission Type</label>
              <select id="sup_commission_type">
                <option value="flat">Flat Amount</option>
                <option value="percentage">Percentage</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      {{-- Markup Settings --}}
      <div>
        <div class="section-hdr" onclick="toggleSection('markSection')">
          <span>Markup Settings</span>
          <button type="button" class="toggle-btn" id="markToggleBtn">Add Markup</button>
        </div>
        <div class="section-body" id="markSection">
          <div class="grid-2">
            <div class="sup-fld">
              <label>Markup Value</label>
              <input type="number" id="sup_markup_value" value="0" min="0" step="0.01">
            </div>
            <div class="sup-fld">
              <label>Markup Type</label>
              <select id="sup_markup_type">
                <option value="flat">Flat Amount</option>
                <option value="percentage">Percentage</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      {{-- Per Pax & Segment --}}
      <div style="margin-top:14px;">
        <div style="font-size:13px;font-weight:700;color:#1a3a5c;margin-bottom:12px;padding-bottom:8px;border-bottom:1px solid #eee;">Per Pax &amp; Segment Setup</div>
        <div class="grid-3">
          <div class="sup-fld">
            <label>Pax Markup Value <span>*</span></label>
            <input type="number" id="sup_pax_markup" value="0" min="0" step="0.01">
          </div>
          <div class="sup-fld">
            <label>Segment Commission Value <span>*</span></label>
            <input type="number" id="sup_seg_comm_val" value="0" min="0" step="0.01">
          </div>
          <div class="sup-fld">
            <label>Segment Commission Type <span>*</span></label>
            <select id="sup_seg_comm_type">
              <option value="flat">Flat Amount</option>
              <option value="percentage">Percentage</option>
            </select>
          </div>
        </div>
        <div class="grid-2">
          <div class="sup-fld">
            <label>Segment Markup Value <span>*</span></label>
            <input type="number" id="sup_seg_mark_val" value="0" min="0" step="0.01">
          </div>
          <div class="sup-fld">
            <label>Segment Markup Type <span>*</span></label>
            <select id="sup_seg_mark_type">
              <option value="flat">Flat Amount</option>
              <option value="percentage">Percentage</option>
            </select>
          </div>
        </div>
      </div>

    </div>
    <div class="sup-modal-footer">
      <button class="btn-reset" onclick="resetSupForm()">Reset</button>
      <button class="btn-submit-yellow" onclick="submitSupplier()">+ Submit</button>
    </div>
  </div>
</div>

{{-- ══ DELETE CONFIRM ══ --}}
<div class="del-overlay" id="delSupOverlay">
  <div class="del-box">
    <div style="font-size:28px;color:#f0a500;margin-bottom:10px;">&#9888;</div>
    <p style="font-size:14px;font-weight:600;color:#333;margin-bottom:20px;">Delete this supplier configuration?</p>
    <div style="display:flex;gap:10px;justify-content:center;">
      <button onclick="document.getElementById('delSupOverlay').classList.remove('show')" style="padding:7px 20px;border-radius:6px;border:1px solid #ccc;background:#fff;font-size:13px;font-weight:600;cursor:pointer;">Cancel</button>
      <button id="delSupConfirmBtn" style="padding:7px 20px;border-radius:6px;border:none;background:#dc3545;color:#fff;font-size:13px;font-weight:700;cursor:pointer;">Yes, Delete</button>
    </div>
  </div>
</div>

@endsection
@section('footer_js')
<script>
const CSRF    = '{{ csrf_token() }}';
const SET_ID  = {{ $set->id }};
const BASE    = '{{ url("configuration/dynamic-fare-rules") }}';
let pendingDelSup = null;

// ── Section toggle ────────────────────────────────────────────────────────────
function toggleSection(id) {
  const sec = document.getElementById(id);
  const isOpen = sec.classList.contains('open');
  sec.classList.toggle('open');
  const btn = id === 'commSection'
    ? document.getElementById('commToggleBtn')
    : document.getElementById('markToggleBtn');
  btn.textContent = isOpen ? (id === 'commSection' ? 'Add Commission' : 'Add Markup') : 'Remove';
}

// ── Submit supplier ───────────────────────────────────────────────────────────
function submitSupplier() {
  const api = document.getElementById('sup_api_type').value;
  if (!api) { alert('Please select a supplier.'); return; }

  const data = {
    api_type:                  api,
    pax_markup_value:          document.getElementById('sup_pax_markup').value,
    commission_value:          document.getElementById('sup_commission_value').value,
    commission_type:           document.getElementById('sup_commission_type').value,
    markup_value:              document.getElementById('sup_markup_value').value,
    markup_type:               document.getElementById('sup_markup_type').value,
    segment_commission_value:  document.getElementById('sup_seg_comm_val').value,
    segment_commission_type:   document.getElementById('sup_seg_comm_type').value,
    segment_markup_value:      document.getElementById('sup_seg_mark_val').value,
    segment_markup_type:       document.getElementById('sup_seg_mark_type').value,
  };

  fetch(BASE + '/' + SET_ID + '/suppliers', {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
    body: JSON.stringify(data)
  })
  .then(r => r.json())
  .then(res => { if (res.success) location.reload(); else alert('Failed to add supplier.'); })
  .catch(() => alert('Network error.'));
}

function resetSupForm() {
  ['sup_api_type','sup_commission_type','sup_markup_type','sup_seg_comm_type','sup_seg_mark_type']
    .forEach(id => { document.getElementById(id).selectedIndex = 0; });
  ['sup_pax_markup','sup_commission_value','sup_markup_value','sup_seg_comm_val','sup_seg_mark_val']
    .forEach(id => { document.getElementById(id).value = '0'; });
}

// ── Delete supplier ───────────────────────────────────────────────────────────
function confirmDelSup(id) {
  pendingDelSup = id;
  document.getElementById('delSupOverlay').classList.add('show');
}

document.getElementById('delSupConfirmBtn').addEventListener('click', function() {
  fetch(BASE + '/suppliers/' + pendingDelSup, {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-HTTP-Method-Override':'DELETE'},
    body: '{}'
  })
  .then(r => r.json())
  .then(res => {
    if (res.success) {
      const row = document.getElementById('suprow-' + pendingDelSup);
      if (row) row.remove();
      document.getElementById('delSupOverlay').classList.remove('show');
    }
  })
  .catch(() => alert('Delete failed.'));
});

// ── Close modal on backdrop ───────────────────────────────────────────────────
document.getElementById('addSupModal').addEventListener('click', function(e) {
  if (e.target === this) this.classList.remove('show');
});
document.getElementById('delSupOverlay').addEventListener('click', function(e) {
  if (e.target === this) this.classList.remove('show');
});
</script>
@endsection
