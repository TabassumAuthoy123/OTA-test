@extends('master')
@section('header_css')
<style>
.b2b-page-header{background:linear-gradient(135deg,#0f1f3d,#1a3a6e);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;}
.b2b-page-header h5{margin:0;font-size:18px;font-weight:700;}
.coming-soon-box{text-align:center;padding:80px 20px;color:#aaa;}
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
        <h5><i class="fas fa-shield-alt me-2"></i> Agency Roles</h5>
        <small>Dashboard &rsaquo; Administrator &rsaquo; Role</small>
      </div>
    </div>
    <div class="card-body">
      <div class="coming-soon-box">
        <i class="fas fa-shield-alt"></i>
        <h5>Role Management</h5>
        <p>Define custom roles for your agency team — control what each sub-agent can view, book, or manage within the B2B portal.</p>
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
