@extends('master')

@section('header_css')
<style>
.b2b-page-header { background:linear-gradient(135deg,#1e3a5f,#2d5f8a); color:#fff; padding:16px 24px; border-radius:8px 8px 0 0; display:flex; justify-content:space-between; align-items:center; }
.b2b-page-header h5 { margin:0; font-size:18px; font-weight:700; }
.b2b-filters { background:#f8f9fa; padding:14px 16px; border-bottom:1px solid #dee2e6; display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end; }
.b2b-filters .filter-group { display:flex; flex-direction:column; gap:4px; }
.b2b-filters label { font-size:11px; font-weight:600; color:#555; margin:0; }
.b2b-filters input, .b2b-filters select { font-size:13px; padding:5px 10px; border:1px solid #ced4da; border-radius:5px; height:34px; }
.b2b-table th { background:#1e3a5f; color:#fff; font-size:13px; padding:10px 12px; white-space:nowrap; }
.b2b-table td { font-size:13px; padding:9px 12px; vertical-align:middle; }
.b2b-table tr:hover td { background:#f0f4ff; }
.s-pending { background:#fff3cd; color:#856404; padding:4px 10px; border-radius:12px; font-size:11px; font-weight:600; }
.s-approved { background:#d4edda; color:#155724; padding:4px 10px; border-radius:12px; font-size:11px; font-weight:600; }
.s-rejected { background:#f8d7da; color:#721c24; padding:4px 10px; border-radius:12px; font-size:11px; font-weight:600; }
.btn-update { background:#f0a500; color:#fff; border:none; padding:5px 12px; border-radius:5px; font-size:12px; font-weight:600; }
.btn-eye { background:#17a2b8; color:#fff; border:none; padding:5px 10px; border-radius:5px; font-size:12px; }
.doc-thumb { max-height:50px; border-radius:4px; cursor:pointer; }
</style>
@endsection

@section('content')
<div class="row">
  <div class="col-lg-12">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card" style="border-radius:8px; overflow:hidden;">
      <div class="b2b-page-header">
        <h5><i class="typcn typcn-document-text me-2"></i> Registration Requests</h5>
      </div>

      <form method="GET" action="{{ url('b2b/registration-requests') }}">
        <div class="b2b-filters">
          <div class="filter-group">
            <label>Search by keyword</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." style="width:220px;">
          </div>
          <div class="filter-group">
            <label>Filter by status</label>
            <select name="status">
              <option value="all" {{ request('status','all')=='all'?'selected':'' }}>All Status</option>
              <option value="0" {{ request('status')=='0'?'selected':'' }}>Pending</option>
              <option value="1" {{ request('status')=='1'?'selected':'' }}>Approved</option>
              <option value="2" {{ request('status')=='2'?'selected':'' }}>Rejected</option>
            </select>
          </div>
          <div class="filter-group">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary btn-sm" style="height:34px;">Search</button>
          </div>
        </div>
      </form>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered mb-0 b2b-table">
            <thead>
              <tr>
                <th>SL</th>
                <th>Agency Name</th>
                <th>Contact Person</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Civil Aviation</th>
                <th>Trade Licence</th>
                <th>NID</th>
                <th>Status</th>
                <th>Action</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($requests as $i => $r)
              <tr>
                <td>{{ $requests->firstItem() + $i }}</td>
                <td style="font-weight:600;">{{ $r->agency_name }}</td>
                <td>{{ $r->contact_person }}</td>
                <td>{{ $r->email }}</td>
                <td>{{ $r->phone }}</td>
                <td>
                  @if($r->civil_aviation_doc)
                    <img src="{{ url($r->civil_aviation_doc) }}" class="doc-thumb" alt="doc">
                  @else
                    <span class="text-muted" style="font-size:12px;">No File</span>
                  @endif
                </td>
                <td>
                  @if($r->trade_license_doc)
                    <img src="{{ url($r->trade_license_doc) }}" class="doc-thumb" alt="doc">
                  @else
                    <span class="text-muted" style="font-size:12px;">No File</span>
                  @endif
                </td>
                <td>
                  @if($r->nid_doc)
                    <img src="{{ url($r->nid_doc) }}" class="doc-thumb" alt="doc">
                  @else
                    <span class="text-muted" style="font-size:12px;">No File</span>
                  @endif
                </td>
                <td>
                  @if($r->status==0)<span class="s-pending">Pending</span>
                  @elseif($r->status==1)<span class="s-approved">Approved</span>
                  @else<span class="s-rejected">Rejected</span>
                  @endif
                </td>
                <td>
                  <button class="btn-update" onclick="openUpdateModal({{ $r->id }}, {{ $r->status }})">Update Request</button>
                </td>
                <td>
                  <button class="btn-eye" onclick="openViewModal({{ json_encode($r) }})"><i class="fas fa-eye"></i></button>
                </td>
              </tr>
              @empty
              <tr><td colspan="11" class="text-center py-5 text-muted">No registration requests found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-between align-items-center px-3 py-2">
          <small class="text-muted">Showing {{ $requests->firstItem() ?? 0 }}–{{ $requests->lastItem() ?? 0 }} of {{ $requests->total() }} entries</small>
          {{ $requests->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Update Modal --}}
<div class="modal fade" id="updateModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="updateForm" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header" style="background:#1e3a5f; color:#fff;">
          <h5 class="modal-title">Update Registration Request</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-bold">Status</label>
            <select name="status" class="form-select" id="updateStatus">
              <option value="0">Pending</option>
              <option value="1">Approved</option>
              <option value="2">Rejected</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Notes</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Optional notes..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- View Modal --}}
<div class="modal fade" id="viewModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:#1e3a5f; color:#fff;">
        <h5 class="modal-title">Registration Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="viewModalBody"></div>
    </div>
  </div>
</div>
@endsection

@section('footer_js')
<script>
function openUpdateModal(id, status) {
    document.getElementById('updateForm').action = '/b2b/registration-requests/' + id + '/update';
    document.getElementById('updateStatus').value = status;
    new bootstrap.Modal(document.getElementById('updateModal')).show();
}
function openViewModal(data) {
    var html = '<table class="table table-bordered"><tbody>';
    html += '<tr><th>Agency Name</th><td>' + (data.agency_name||'') + '</td></tr>';
    html += '<tr><th>Contact Person</th><td>' + (data.contact_person||'') + '</td></tr>';
    html += '<tr><th>Email</th><td>' + (data.email||'') + '</td></tr>';
    html += '<tr><th>Phone</th><td>' + (data.phone||'') + '</td></tr>';
    html += '<tr><th>Notes</th><td>' + (data.notes||'N/A') + '</td></tr>';
    html += '<tr><th>Created At</th><td>' + (data.created_at||'') + '</td></tr>';
    html += '</tbody></table>';
    document.getElementById('viewModalBody').innerHTML = html;
    new bootstrap.Modal(document.getElementById('viewModal')).show();
}
</script>
@endsection
