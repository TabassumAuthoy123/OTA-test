@extends('master')
@section('header_css')
<style>
.frs-wrap{background:#fff;border-radius:10px;box-shadow:0 2px 12px rgba(0,0,0,.08);overflow:hidden;}
.frs-topbar{display:flex;justify-content:space-between;align-items:center;padding:18px 24px;border-bottom:1px solid #e8ecf0;}
.frs-topbar h5{margin:0;font-size:18px;font-weight:700;color:#1a3a5c;}
.frs-topbar small{font-size:12px;color:#888;display:block;margin-top:2px;}
.btn-create-set{background:#f0a500;color:#fff;border:none;padding:8px 18px;border-radius:6px;font-size:13px;font-weight:700;cursor:pointer;}
.btn-create-set:hover{background:#d4911a;}
.frs-search{padding:12px 20px;border-bottom:1px solid #e8ecf0;}
.frs-search input{font-size:13px;padding:6px 12px;border:1px solid #ced4da;border-radius:5px;height:34px;width:280px;}
.frs-table{width:100%;border-collapse:collapse;}
.frs-table th{background:#f8f9fa;color:#1a3a5c;font-size:13px;padding:11px 14px;font-weight:700;border-bottom:2px solid #dee2e6;text-align:center;}
.frs-table th:nth-child(3){text-align:left;}
.frs-table td{font-size:13px;padding:11px 14px;border-bottom:1px solid #f0f0f0;vertical-align:middle;text-align:center;}
.frs-table td:nth-child(3){text-align:left;}
.frs-table tr:hover td{background:#f8fbff;}
.frs-table td:nth-child(2){color:#888;font-size:12px;}
/* action buttons */
.btn-act{padding:5px 12px;border-radius:5px;border:none;font-size:12px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:4px;}
.btn-act-edit{background:#f0a500;color:#fff;}
.btn-act-edit:hover{background:#d4911a;}
.btn-act-view{background:#fff;color:#1a3a5c;border:1px solid #1a3a5c;}
.btn-act-view:hover{background:#f0f4ff;}
.btn-act-clone{background:#fff;color:#1a3a5c;border:1px solid #1a3a5c;}
.btn-act-clone:hover{background:#f0f4ff;}
.btn-act-del{background:#dc3545;color:#fff;}
.btn-act-del:hover{background:#b02a37;}
/* inline edit */
.inline-edit-input{font-size:13px;padding:5px 10px;border:1.5px solid #f0a500;border-radius:5px;width:240px;outline:none;}
.btn-save-inline{background:#28a745;color:#fff;border:none;padding:5px 12px;border-radius:5px;font-size:12px;font-weight:600;cursor:pointer;}
.btn-cancel-inline{background:#6c757d;color:#fff;border:none;padding:5px 12px;border-radius:5px;font-size:12px;font-weight:600;cursor:pointer;}
/* modals */
.frs-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9990;display:none;align-items:center;justify-content:center;}
.frs-overlay.show{display:flex;}
.frs-modal{background:#fff;border-radius:10px;width:460px;max-width:96vw;box-shadow:0 8px 30px rgba(0,0,0,.2);overflow:hidden;}
.frs-modal-hdr{padding:18px 22px;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:flex-start;}
.frs-modal-hdr h6{margin:0;font-size:16px;font-weight:700;color:#1a3a5c;}
.frs-modal-hdr p{margin:4px 0 0;font-size:12px;color:#888;}
.frs-modal-hdr button{background:none;border:none;font-size:22px;cursor:pointer;color:#666;line-height:1;margin-top:-2px;}
.frs-modal-body{padding:20px 22px;}
.frs-modal-fld label{font-size:12px;font-weight:700;color:#555;margin-bottom:5px;display:block;}
.frs-modal-fld label span{color:#dc3545;}
.frs-modal-fld input{width:100%;font-size:13px;padding:8px 12px;border:1px solid #ced4da;border-radius:6px;outline:none;}
.frs-modal-fld input:focus{border-color:#f0a500;}
.frs-modal-footer{padding:14px 22px;border-top:1px solid #eee;display:flex;justify-content:flex-end;gap:10px;}
.btn-reset{background:#fff;color:#333;border:1px solid #ccc;padding:7px 18px;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;}
.btn-submit-yellow{background:#f0a500;color:#fff;border:none;padding:8px 20px;border-radius:6px;font-size:13px;font-weight:700;cursor:pointer;}
.btn-submit-yellow:hover{background:#d4911a;}
.btn-yes-del{background:#dc3545;color:#fff;border:none;padding:8px 18px;border-radius:6px;font-size:13px;font-weight:700;cursor:pointer;}
/* del icon warning */
.del-icon-warn{font-size:28px;color:#f0a500;display:block;text-align:center;margin-bottom:8px;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  <div class="frs-wrap">

    {{-- Topbar --}}
    <div class="frs-topbar">
      <div>
        <h5>Fare Rules Set Name List</h5>
        <small>Dashboard &rsaquo; Configuration &rsaquo; Fare-rules-set</small>
      </div>
      <button class="btn-create-set" onclick="openModal('createModal')">+ Create Fare Rules Set</button>
    </div>

    @if(session('success'))
    <div class="alert alert-success mx-3 mt-3 py-2 mb-0">{{ session('success') }}</div>
    @endif

    {{-- Search --}}
    <form method="GET" action="{{ url('configuration/dynamic-fare-rules') }}">
      <div class="frs-search" style="display:flex;align-items:center;gap:8px;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search...">
        <button type="submit" style="background:#1a3a5c;color:#fff;border:none;padding:6px 14px;border-radius:5px;height:34px;cursor:pointer;font-size:13px;">Search</button>
        @if(request('search'))
        <a href="{{ url('configuration/dynamic-fare-rules') }}" style="font-size:12px;color:#888;text-decoration:none;">Clear</a>
        @endif
      </div>
    </form>

    {{-- Table --}}
    <div class="table-responsive">
      <table class="frs-table">
        <thead>
          <tr>
            <th style="width:60px;">SL</th>
            <th style="width:160px;">Created Date</th>
            <th>Fare Rules Name</th>
            <th style="width:320px;">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($sets as $i => $set)
          <tr id="row-{{ $set->id }}">
            <td class="d-cell">{{ $sets->firstItem() + $i }}</td>
            <td class="d-cell">{{ $set->created_at ? date('d-m-Y', strtotime($set->created_at)) : 'N/A' }}</td>
            <td class="d-cell">{{ $set->name }}</td>
            <td class="d-cell" style="white-space:nowrap;">
              <button class="btn-act btn-act-edit frs-row-action" data-action="edit" data-id="{{ $set->id }}" data-name="{{ $set->name }}">&#9998; Edit</button>
              <a href="{{ url('configuration/dynamic-fare-rules/'.$set->id.'/suppliers') }}" class="btn-act btn-act-view">&#128065; View</a>
              <button class="btn-act btn-act-clone frs-row-action" data-action="clone" data-id="{{ $set->id }}" data-name="{{ $set->name }}">&#128203; Clone</button>
              <button class="btn-act btn-act-del frs-row-action" data-action="delete" data-id="{{ $set->id }}" data-name="{{ $set->name }}">&#128465; Delete</button>
            </td>
            {{-- Inline edit cells (hidden) --}}
            <td class="e-cell" style="display:none;"></td>
            <td class="e-cell" style="display:none;"></td>
            <td class="e-cell" style="display:none;">
              <input type="text" class="inline-edit-input" data-editfor="{{ $set->id }}" value="{{ $set->name }}">
            </td>
            <td class="e-cell" style="display:none;">
              <button class="btn-save-inline frs-row-action" data-action="save" data-id="{{ $set->id }}">&#128190; Save</button>
              <button class="btn-cancel-inline frs-row-action" data-action="cancel" data-id="{{ $set->id }}" style="margin-left:4px;">&#10007; Cancel</button>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" class="text-center py-5 text-muted">No fare rule sets found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $sets->links() }}</div>
  </div>
</div></div>

{{-- ══ CREATE MODAL ══ --}}
<div class="frs-overlay" id="createModal">
  <div class="frs-modal">
    <div class="frs-modal-hdr">
      <div><h6>Create Fare Rules Set</h6></div>
      <button onclick="closeModal('createModal')">&times;</button>
    </div>
    <form method="POST" action="{{ url('configuration/dynamic-fare-rules') }}">
      @csrf
      <div class="frs-modal-body">
        <div class="frs-modal-fld">
          <label>Rules Set Name <span>*</span></label>
          <input type="text" name="name" placeholder="Enter fare rules set name" required autocomplete="off">
        </div>
      </div>
      <div class="frs-modal-footer">
        <button type="reset" class="btn-reset" onclick="closeModal('createModal')">Reset</button>
        <button type="submit" class="btn-submit-yellow">+ Create Fare Rules Set</button>
      </div>
    </form>
  </div>
</div>

{{-- ══ CLONE MODAL ══ --}}
<div class="frs-overlay" id="cloneModal">
  <div class="frs-modal">
    <div class="frs-modal-hdr">
      <div>
        <h6>&#128203; Clone Fare Rules Set</h6>
        <p>Creating a copy of Fare Rules Set</p>
      </div>
      <button onclick="closeModal('cloneModal')">&times;</button>
    </div>
    <div class="frs-modal-body">
      <div class="frs-modal-fld">
        <label>New Fare Rules Set Name <span>*</span></label>
        <input type="text" id="cloneNewName" placeholder="e.g., Fare Rules Set Name - Copy" autocomplete="off">
      </div>
    </div>
    <div class="frs-modal-footer">
      <button class="btn-submit-yellow" id="btnDoClone">&#128203; Submit</button>
    </div>
  </div>
</div>

{{-- ══ DELETE MODAL ══ --}}
<div class="frs-overlay" id="deleteModal">
  <div class="frs-modal" style="width:400px;">
    <div class="frs-modal-hdr" style="border-bottom:none;padding-bottom:0;">
      <div></div>
      <button onclick="closeModal('deleteModal')">&times;</button>
    </div>
    <div class="frs-modal-body" style="text-align:center;padding-top:4px;">
      <span class="del-icon-warn">&#9888;</span>
      <h6 style="font-size:15px;font-weight:700;color:#1a3a5c;margin-bottom:8px;">Delete Fare Rules Set</h6>
      <p id="delModalText" style="font-size:13px;color:#555;margin-bottom:0;">Are you sure you want to delete this? This action cannot be undone.</p>
    </div>
    <div class="frs-modal-footer" style="justify-content:center;gap:12px;">
      <button class="btn-reset" onclick="closeModal('deleteModal')">Cancel</button>
      <button class="btn-yes-del" id="btnDoDelete">Yes, Delete</button>
    </div>
  </div>
</div>

@endsection
@section('footer_js')
<script>
const CSRF = '{{ csrf_token() }}';
const BASE = '{{ url("configuration/dynamic-fare-rules") }}';
let activeCloneId = null;
let activeDeleteId = null;

function openModal(id)  { document.getElementById(id).classList.add('show'); }
function closeModal(id) { document.getElementById(id).classList.remove('show'); }

// Close on backdrop click
document.querySelectorAll('.frs-overlay').forEach(el => {
  el.addEventListener('click', e => { if (e.target === el) el.classList.remove('show'); });
});

// ── Row button delegation ─────────────────────────────────────────────────────
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.frs-row-action');
  if (!btn) return;
  const { action, id, name } = btn.dataset;

  if (action === 'edit') {
    startInlineEdit(id);
  } else if (action === 'save') {
    saveInlineEdit(id);
  } else if (action === 'cancel') {
    cancelInlineEdit(id);
  } else if (action === 'clone') {
    activeCloneId = id;
    document.getElementById('cloneNewName').value = name + ' - Copy';
    openModal('cloneModal');
  } else if (action === 'delete') {
    activeDeleteId = id;
    document.getElementById('delModalText').textContent =
      'Are you sure you want to delete "' + name + '"? This action cannot be undone.';
    openModal('deleteModal');
  }
});

// ── Inline Edit ───────────────────────────────────────────────────────────────
function startInlineEdit(id) {
  const row = document.getElementById('row-' + id);
  row.querySelectorAll('.d-cell').forEach(c => c.style.display = 'none');
  row.querySelectorAll('.e-cell').forEach(c => c.style.display = '');
}
function cancelInlineEdit(id) {
  const row = document.getElementById('row-' + id);
  row.querySelectorAll('.d-cell').forEach(c => c.style.display = '');
  row.querySelectorAll('.e-cell').forEach(c => c.style.display = 'none');
}
function saveInlineEdit(id) {
  const inp  = document.querySelector('[data-editfor="' + id + '"]');
  const name = inp ? inp.value.trim() : '';
  if (!name) { alert('Name is required.'); return; }
  fetch(BASE + '/' + id, {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-HTTP-Method-Override':'PUT'},
    body: JSON.stringify({ name })
  })
  .then(r => r.json())
  .then(res => { if (res.success) location.reload(); else alert('Update failed.'); })
  .catch(() => alert('Network error.'));
}

// ── Clone ─────────────────────────────────────────────────────────────────────
document.getElementById('btnDoClone').addEventListener('click', function() {
  const name = document.getElementById('cloneNewName').value.trim();
  if (!name) { alert('Enter a name for the clone.'); return; }
  fetch(BASE + '/' + activeCloneId + '/clone', {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
    body: JSON.stringify({ name })
  })
  .then(r => r.json())
  .then(res => {
    if (res.success) { closeModal('cloneModal'); location.reload(); }
    else { alert(res.message || 'Clone failed.'); }
  })
  .catch(() => alert('Network error.'));
});

// ── Delete ────────────────────────────────────────────────────────────────────
document.getElementById('btnDoDelete').addEventListener('click', function() {
  fetch(BASE + '/' + activeDeleteId, {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-HTTP-Method-Override':'DELETE'},
    body: '{}'
  })
  .then(r => r.json())
  .then(res => {
    if (res.success) { closeModal('deleteModal'); location.reload(); }
  })
  .catch(() => alert('Delete failed.'));
});
</script>
@endsection
