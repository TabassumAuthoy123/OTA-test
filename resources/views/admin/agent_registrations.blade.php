@extends('master')
@section('header_css')
<style>
.page-header{background:linear-gradient(135deg,#0f1f3d,#1a3a6e);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;display:flex;justify-content:space-between;align-items:center;}
.page-header h5{margin:0;font-size:18px;font-weight:700;}
.agt-table th{background:#0f1f3d;color:#fff;font-size:13px;padding:10px 12px;white-space:nowrap;}
.agt-table td{font-size:13px;padding:9px 12px;vertical-align:middle;}
.agt-table tr:hover td{background:#f0f4ff;}
.s-pending{background:#fff3cd;color:#856404;padding:3px 9px;border-radius:10px;font-size:11px;font-weight:600;}
.s-approved{background:#d4edda;color:#155724;padding:3px 9px;border-radius:10px;font-size:11px;font-weight:600;}
.s-rejected{background:#f8d7da;color:#721c24;padding:3px 9px;border-radius:10px;font-size:11px;font-weight:600;}
.doc-link{font-size:12px;color:#1565a0;text-decoration:none;}
.doc-link:hover{text-decoration:underline;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="page-header">
      <div>
        <h5><i class="fas fa-clipboard-list me-2"></i> Agent Registration Requests</h5>
        <small>Dashboard &rsaquo; User Management &rsaquo; Agent Requests</small>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered mb-0 agt-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Company</th>
              <th>Contact</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Documents</th>
              <th>Status</th>
              <th>Submitted</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($registrations as $i => $r)
            <tr>
              <td>{{ $registrations->firstItem() + $i }}</td>
              <td style="font-weight:700;">{{ $r->company_name }}</td>
              <td>{{ $r->contact_name }}</td>
              <td>{{ $r->email }}</td>
              <td>{{ $r->phone }}</td>
              <td>
                @if($r->trade_license) <a href="{{ url($r->trade_license) }}" target="_blank" class="doc-link"><i class="fas fa-file me-1"></i>Trade Lic</a><br> @endif
                @if($r->nid_document) <a href="{{ url($r->nid_document) }}" target="_blank" class="doc-link"><i class="fas fa-id-card me-1"></i>NID</a><br> @endif
                @if($r->civil_aviation) <a href="{{ url($r->civil_aviation) }}" target="_blank" class="doc-link"><i class="fas fa-plane me-1"></i>Aviation</a> @endif
                @if(!$r->trade_license && !$r->nid_document && !$r->civil_aviation) <span class="text-muted">None</span> @endif
              </td>
              <td>
                @if($r->status == 0)<span class="s-pending">Pending</span>
                @elseif($r->status == 1)<span class="s-approved">Approved</span>
                @else<span class="s-rejected">Rejected</span>@endif
              </td>
              <td>{{ date('d M Y', strtotime($r->created_at)) }}</td>
              <td>
                @if($r->status == 0)
                  <a href="{{ url('admin/agent-registrations/'.$r->id.'/approve') }}"
                     class="btn btn-success btn-sm"
                     onclick="return confirm('Approve this agent?')">
                    <i class="fas fa-check"></i> Approve
                  </a>
                  <button class="btn btn-danger btn-sm ms-1"
                          onclick="rejectPrompt({{ $r->id }})">
                    <i class="fas fa-times"></i> Reject
                  </button>
                @else
                  <span class="text-muted" style="font-size:12px;">No action</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="9" class="text-center py-5 text-muted">
                <i class="fas fa-clipboard fa-2x mb-2 d-block" style="opacity:.3;"></i>
                No agent registration requests yet.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <small class="text-muted">Showing {{ $registrations->firstItem() ?? 0 }}&ndash;{{ $registrations->lastItem() ?? 0 }} of {{ $registrations->total() }} entries</small>
        {{ $registrations->links() }}
      </div>
    </div>
  </div>
</div></div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Reject Registration</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form id="rejectForm" method="POST">
      @csrf
      <div class="modal-body">
        <label class="form-label fw-semibold">Reason for Rejection</label>
        <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Explain why..." required></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
      </div>
    </form>
  </div></div>
</div>

<script>
function rejectPrompt(id){
  document.getElementById('rejectForm').action = '/admin/agent-registrations/' + id + '/reject';
  new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
@endsection
