@extends('master')
@section('header_css')
<style>
.br-wrap{background:#fff;border-radius:10px;box-shadow:0 2px 12px rgba(0,0,0,.08);overflow:hidden;}
.br-topbar{display:flex;justify-content:space-between;align-items:center;padding:18px 24px;border-bottom:1px solid #e8ecf0;}
.br-topbar h5{margin:0;font-size:18px;font-weight:700;color:#1a3a5c;}
.br-topbar small{font-size:12px;color:#888;display:block;margin-top:2px;}
.btn-add-br{background:#f0a500;color:#fff;border:none;padding:8px 18px;border-radius:6px;font-size:13px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:5px;}
.btn-add-br:hover{background:#d4911a;color:#fff;}
.br-search{padding:12px 20px;border-bottom:1px solid #e8ecf0;display:flex;align-items:center;gap:8px;}
.br-search input{font-size:13px;padding:6px 12px;border:1px solid #ced4da;border-radius:5px;height:34px;width:280px;}
.br-search button{background:#1a3a5c;color:#fff;border:none;padding:6px 14px;border-radius:5px;height:34px;cursor:pointer;}
.br-table{width:100%;border-collapse:collapse;}
.br-table th{background:#1a3a5c;color:#fff;font-size:12px;padding:10px 12px;white-space:nowrap;font-weight:600;text-align:center;}
.br-table td{font-size:12px;padding:9px 12px;vertical-align:middle;border-bottom:1px solid #f0f0f0;text-align:center;}
.br-table tr:hover td{background:#f8fbff;}
.br-table td:nth-child(2),.br-table td:nth-child(3),.br-table td:nth-child(4){text-align:left;}
.yn-yes{background:#d4edda;color:#155724;padding:2px 10px;border-radius:6px;font-size:11px;font-weight:700;display:inline-block;}
.yn-no{background:#f8d7da;color:#721c24;padding:2px 10px;border-radius:6px;font-size:11px;font-weight:700;display:inline-block;}
.br-status-active{background:#d4edda;color:#155724;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:700;display:inline-block;}
.br-status-inactive{background:#f8d7da;color:#721c24;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:700;display:inline-block;}
.btn-edit-br{background:#f0a500;color:#fff;border:none;width:30px;height:28px;border-radius:5px;font-size:13px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;}
.btn-del-br{background:#dc3545;color:#fff;border:none;width:28px;height:28px;border-radius:5px;font-size:13px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;}
.br-footer{display:flex;align-items:center;justify-content:space-between;padding:10px 20px;border-top:1px solid #eee;}
/* Toggle button groups */
.toggle-group{display:inline-flex;border:1px solid #dee2e6;border-radius:6px;overflow:hidden;}
.toggle-group label{padding:6px 16px;font-size:13px;font-weight:600;cursor:pointer;margin:0;border-right:1px solid #dee2e6;}
.toggle-group label:last-child{border-right:none;}
.toggle-group input[type=radio]{display:none;}
.toggle-group input[type=radio]:checked + span{background:#1a3a5c;color:#fff;}
.toggle-group label span{display:block;padding:6px 16px;font-size:13px;font-weight:600;transition:background .15s,color .15s;}
.toggle-group label:has(input:checked) span{background:#1a3a5c;color:#fff;}
/* simpler approach */
.tgl-btn{display:inline-flex;border:1.5px solid #1a3a5c;border-radius:6px;overflow:hidden;gap:0;}
.tgl-btn input[type=radio]{display:none;}
.tgl-btn label{padding:5px 16px;font-size:13px;font-weight:600;cursor:pointer;margin:0;color:#1a3a5c;background:#fff;transition:background .15s,color .15s;}
.tgl-btn input[type=radio]:checked + label{background:#1a3a5c;color:#fff;}
.tgl-status input[type=radio]:checked + label{background:#f0a500;color:#fff;}
.tgl-status{border-color:#f0a500;}
.tgl-status label{color:#f0a500;}
/* modal */
.br-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9990;display:none;align-items:center;justify-content:center;}
.br-modal-overlay.show{display:flex;}
.br-modal{background:#fff;border-radius:10px;width:560px;max-width:96vw;box-shadow:0 8px 30px rgba(0,0,0,.2);overflow:hidden;}
.br-modal-hdr{background:#fff;padding:16px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #eee;}
.br-modal-hdr h6{margin:0;font-size:16px;font-weight:700;color:#1a3a5c;}
.br-modal-hdr button{background:none;border:none;font-size:22px;cursor:pointer;color:#666;line-height:1;}
.br-modal-body{padding:20px;}
.br-modal-fld label{font-size:12px;font-weight:700;color:#555;margin-bottom:4px;display:block;}
.br-modal-fld label span{color:#dc3545;}
.br-modal-fld select,.br-modal-fld input{font-size:13px;padding:6px 10px;border:1px solid #ced4da;border-radius:5px;width:100%;height:36px;}
.br-modal-row{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:14px;}
.br-modal-row2{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:14px;}
.br-modal-row3{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;}
.btn-update-br{background:#f0a500;color:#fff;border:none;padding:10px 24px;border-radius:6px;font-size:14px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;}
.btn-update-br:hover{background:#d4911a;}
/* del confirm */
.del-overlay{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;display:none;align-items:center;justify-content:center;}
.del-overlay.show{display:flex;}
.del-box{background:#fff;border-radius:10px;padding:28px 32px;min-width:300px;text-align:center;box-shadow:0 8px 30px rgba(0,0,0,.2);}
.del-box i{font-size:28px;color:#f0a500;display:block;margin-bottom:10px;}
.del-box p{font-size:14px;font-weight:600;color:#333;margin-bottom:20px;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  <div class="br-wrap">

    {{-- Top bar --}}
    <div class="br-topbar">
      <div>
        <h5>Block Route List</h5>
        <small>Dashboard &rsaquo; Configuration &rsaquo; Block-route</small>
      </div>
      <a href="{{ route('ConfigBlockRoutesCreate') }}" class="btn-add-br">+ Add Block Route</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success mx-3 mt-3 py-2 mb-0">{{ session('success') }}</div>
    @endif

    {{-- Search --}}
    <form method="GET" action="{{ url('configuration/block-routes') }}">
      <div class="br-search">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search...">
        <button type="submit"><i class="fas fa-search"></i></button>
        @if(request('search'))
        <a href="{{ url('configuration/block-routes') }}" style="font-size:12px;color:#888;text-decoration:none;">Clear</a>
        @endif
      </div>
    </form>

    {{-- Table --}}
    <div class="table-responsive">
      <table class="br-table">
        <thead>
          <tr>
            <th>SL</th>
            <th>Departure</th>
            <th>Arrival</th>
            <th>Airline</th>
            <th>One way block</th>
            <th>Round trip block</th>
            <th>Booking block</th>
            <th>Full block</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rules as $i => $r)
          <tr>
            <td>{{ $rules->firstItem() + $i }}</td>
            <td><strong>{{ $r->departure ?: '—' }}</strong></td>
            <td><strong>{{ $r->arrival   ?: '—' }}</strong></td>
            <td>{{ $r->airline_code ?: 'N/A' }}</td>
            <td><span class="{{ $r->one_way       ? 'yn-yes':'yn-no' }}">{{ $r->one_way       ?'Yes':'No' }}</span></td>
            <td><span class="{{ $r->round_trip    ? 'yn-yes':'yn-no' }}">{{ $r->round_trip    ?'Yes':'No' }}</span></td>
            <td><span class="{{ $r->booking_block ? 'yn-yes':'yn-no' }}">{{ $r->booking_block ?'Yes':'No' }}</span></td>
            <td><span class="{{ $r->full_block    ? 'yn-yes':'yn-no' }}">{{ $r->full_block    ?'Yes':'No' }}</span></td>
            <td><span class="{{ $r->is_active ? 'br-status-active':'br-status-inactive' }}">{{ $r->is_active?'Active':'Inactive' }}</span></td>
            <td style="white-space:nowrap;">
              <button class="btn-edit-br br-action" data-action="edit"
                data-id="{{ $r->id }}"
                data-dep="{{ $r->departure }}"
                data-arr="{{ $r->arrival }}"
                data-airline="{{ $r->airline_code }}"
                data-ow="{{ $r->one_way    ? 'true':'false' }}"
                data-rt="{{ $r->round_trip ? 'true':'false' }}"
                data-bb="{{ $r->booking_block ? 'true':'false' }}"
                data-fb="{{ $r->full_block    ? 'true':'false' }}"
                data-status="{{ $r->is_active ? 'enable':'disable' }}"
                title="Edit">&#9998;</button>
            </td>
          </tr>
          @empty
          <tr><td colspan="10" class="text-center py-5 text-muted">No block routes found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="br-footer">
      <div>{{ $rules->links() }}</div>
      <div style="font-size:12px;color:#888;">{{ $rules->total() }} record(s)</div>
    </div>
  </div>
</div></div>

{{-- ══ EDIT MODAL ══ --}}
<div class="br-modal-overlay" id="editOverlay">
  <div class="br-modal">
    <div class="br-modal-hdr">
      <h6>Update Block Route</h6>
      <button onclick="closeEditModal()">&times;</button>
    </div>
    <div class="br-modal-body">
      <div class="br-modal-row">
        <div class="br-modal-fld">
          <label>Departure <span>*</span></label>
          <select id="em_dep">
            <option value="">Select...</option>
            @foreach($airports as $ap)
            <option value="{{ $ap->airport_code }}">{{ $ap->airport_code }} – {{ $ap->city_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="br-modal-fld">
          <label>Arrival <span>*</span></label>
          <select id="em_arr">
            <option value="">Select...</option>
            @foreach($airports as $ap)
            <option value="{{ $ap->airport_code }}">{{ $ap->airport_code }} – {{ $ap->city_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="br-modal-fld">
          <label>Select Airline</label>
          <select id="em_airline">
            <option value="">Any Airline</option>
            @foreach($airlines as $al)
            <option value="{{ $al->iata }}">{{ $al->iata }} – {{ $al->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="br-modal-row2">
        <div class="br-modal-fld">
          <label>One Way <span>*</span></label>
          <div class="tgl-btn" id="em_ow">
            <input type="radio" name="em_ow" id="em_ow_t" value="true"><label for="em_ow_t">True</label>
            <input type="radio" name="em_ow" id="em_ow_f" value="false" checked><label for="em_ow_f">False</label>
          </div>
        </div>
        <div class="br-modal-fld">
          <label>Round Way <span>*</span></label>
          <div class="tgl-btn" id="em_rt">
            <input type="radio" name="em_rt" id="em_rt_t" value="true"><label for="em_rt_t">True</label>
            <input type="radio" name="em_rt" id="em_rt_f" value="false" checked><label for="em_rt_f">False</label>
          </div>
        </div>
        <div class="br-modal-fld">
          <label>Booking Block <span>*</span></label>
          <div class="tgl-btn" id="em_bb">
            <input type="radio" name="em_bb" id="em_bb_t" value="true"><label for="em_bb_t">True</label>
            <input type="radio" name="em_bb" id="em_bb_f" value="false" checked><label for="em_bb_f">False</label>
          </div>
        </div>
      </div>

      <div class="br-modal-row3">
        <div class="br-modal-fld">
          <label>Full Block <span>*</span></label>
          <div class="tgl-btn" id="em_fb">
            <input type="radio" name="em_fb" id="em_fb_t" value="true"><label for="em_fb_t">True</label>
            <input type="radio" name="em_fb" id="em_fb_f" value="false" checked><label for="em_fb_f">False</label>
          </div>
        </div>
        <div class="br-modal-fld">
          <label>Status <span>*</span></label>
          <div class="tgl-btn tgl-status" id="em_st">
            <input type="radio" name="em_st" id="em_st_en" value="enable" checked><label for="em_st_en">Enable</label>
            <input type="radio" name="em_st" id="em_st_dis" value="disable"><label for="em_st_dis">Disable</label>
          </div>
        </div>
      </div>

      <div style="text-align:right;">
        <button class="btn-update-br" id="btnUpdateBr">&#9658; Update Block Route</button>
      </div>
    </div>
  </div>
</div>

{{-- ══ DELETE CONFIRM ══ --}}
<div class="del-overlay" id="delOverlay">
  <div class="del-box">
    <i class="fas fa-exclamation-circle"></i>
    <p>Delete this block route?</p>
    <div style="display:flex;gap:10px;justify-content:center;">
      <button onclick="document.getElementById('delOverlay').classList.remove('show')" style="padding:7px 20px;border-radius:6px;border:1px solid #ccc;background:#fff;font-size:13px;font-weight:600;cursor:pointer;">Cancel</button>
      <button id="delConfirmBtn" style="padding:7px 20px;border-radius:6px;border:none;background:#f0a500;color:#fff;font-size:13px;font-weight:700;cursor:pointer;">OK</button>
    </div>
  </div>
</div>

@endsection
@section('footer_js')
<script>
const CSRF  = '{{ csrf_token() }}';
const BASE  = '{{ url("configuration/block-routes") }}';
let editId  = null;
let delId   = null;

// ── Open edit modal ───────────────────────────────────────────────────────────
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.br-action');
  if (!btn) return;
  const d = btn.dataset;
  editId = d.id;

  // populate selects
  setSelect('em_dep',    d.dep);
  setSelect('em_arr',    d.arr);
  setSelect('em_airline', d.airline || '');

  // populate toggles
  setRadio('em_ow',  d.ow);
  setRadio('em_rt',  d.rt);
  setRadio('em_bb',  d.bb);
  setRadio('em_fb',  d.fb);
  setRadio('em_st',  d.status);

  document.getElementById('editOverlay').classList.add('show');
});

function setSelect(id, val) {
  const el = document.getElementById(id);
  if (!el) return;
  el.value = val || '';
}

function setRadio(name, val) {
  const radios = document.querySelectorAll('[name=' + name + ']');
  radios.forEach(r => { r.checked = r.value === val; });
}

function getRadio(name) {
  const checked = document.querySelector('[name=' + name + ']:checked');
  return checked ? checked.value : null;
}

function closeEditModal() {
  document.getElementById('editOverlay').classList.remove('show');
  editId = null;
}

// click outside modal to close
document.getElementById('editOverlay').addEventListener('click', function(e) {
  if (e.target === this) closeEditModal();
});

// ── Save edit ─────────────────────────────────────────────────────────────────
document.getElementById('btnUpdateBr').addEventListener('click', function() {
  if (!editId) return;
  const data = {
    departure:     document.getElementById('em_dep').value,
    arrival:       document.getElementById('em_arr').value,
    airline_code:  document.getElementById('em_airline').value,
    one_way:       getRadio('em_ow'),
    round_trip:    getRadio('em_rt'),
    booking_block: getRadio('em_bb'),
    full_block:    getRadio('em_fb'),
    status:        getRadio('em_st'),
  };
  fetch(BASE + '/' + editId, {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-HTTP-Method-Override':'PUT'},
    body: JSON.stringify(data)
  })
  .then(r => r.json())
  .then(res => { if (res.success) location.reload(); else alert('Update failed.'); })
  .catch(() => alert('Network error.'));
});

// ── Delete ────────────────────────────────────────────────────────────────────
document.getElementById('delConfirmBtn').addEventListener('click', function() {
  if (!delId) return;
  fetch(BASE + '/' + delId, {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-HTTP-Method-Override':'DELETE'},
    body: '{}'
  })
  .then(r => r.json())
  .then(res => {
    if (res.success) {
      document.getElementById('delOverlay').classList.remove('show');
      location.reload();
    }
  })
  .catch(() => alert('Delete failed.'));
});
</script>
@endsection
