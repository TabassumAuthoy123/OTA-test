@extends('master')
@section('header_css')
<style>
.b2b-page-header{background:linear-gradient(135deg,#0f1f3d,#1a3a6e);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;}
.b2b-page-header h5{margin:0;font-size:18px;font-weight:700;}
.agency-info-card{background:#f8f9fa;border:1px solid #dee2e6;border-radius:8px;padding:20px;margin-bottom:20px;}
.agency-info-card .label{font-size:11px;font-weight:700;color:#999;text-transform:uppercase;letter-spacing:.5px;}
.agency-info-card .value{font-size:14px;font-weight:600;color:#333;margin-top:2px;}
.coming-soon-box{text-align:center;padding:60px 20px;color:#aaa;}
.coming-soon-box i{font-size:48px;margin-bottom:16px;display:block;opacity:.4;}
.coming-soon-box h5{font-size:18px;font-weight:700;color:#555;margin-bottom:8px;}
.coming-soon-box p{font-size:13px;max-width:360px;margin:0 auto;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="b2b-page-header">
      <div>
        <h5><i class="fas fa-users me-2"></i> Agency Users</h5>
        <small>Dashboard &rsaquo; Administrator &rsaquo; User</small>
      </div>
    </div>

    <div class="card-body">
      @if($companyProfile)
      <div class="agency-info-card">
        <div class="row">
          @if($companyProfile->logo && file_exists(public_path($companyProfile->logo)))
          <div class="col-auto">
            <img src="{{ url($companyProfile->logo) }}" style="height:64px;object-fit:contain;border-radius:6px;border:1px solid #dee2e6;padding:4px;">
          </div>
          @endif
          <div class="col">
            <div class="row">
              <div class="col-md-3 mb-2">
                <div class="label">Agency Name</div>
                <div class="value">{{ $companyProfile->name }}</div>
              </div>
              <div class="col-md-3 mb-2">
                <div class="label">Email</div>
                <div class="value">{{ $companyProfile->email }}</div>
              </div>
              <div class="col-md-3 mb-2">
                <div class="label">Phone</div>
                <div class="value">{{ $companyProfile->phone }}</div>
              </div>
              <div class="col-md-3 mb-2">
                <div class="label">Address</div>
                <div class="value">{{ $companyProfile->address ?? '—' }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif

      <div class="coming-soon-box">
        <i class="fas fa-user-plus"></i>
        <h5>Sub-User Management</h5>
        <p>Add and manage sub-agents under your agency account. This feature allows you to create multiple login accounts for your team members with customized access levels.</p>
        <div style="margin-top:20px;">
          <span style="background:#fff3cd;color:#856404;padding:6px 16px;border-radius:20px;font-size:12px;font-weight:700;">
            <i class="fas fa-clock me-1"></i> Coming Soon
          </span>
        </div>
      </div>
    </div>
  </div>
</div></div>
@endsection
