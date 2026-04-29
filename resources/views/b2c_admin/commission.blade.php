@extends('master')
@section('header_css')
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;display:flex;justify-content:space-between;align-items:center;}
.b2c-page-header h5{margin:0;font-size:18px;font-weight:700;}
.btn-assign{background:#f0a500;color:#fff;border:none;padding:8px 18px;border-radius:6px;font-size:13px;font-weight:700;text-decoration:none;}
.commission-card{background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:20px 24px;margin-bottom:14px;display:flex;justify-content:space-between;align-items:center;}
.btn-configure{background:#fff;border:1px solid #aaa;padding:6px 14px;border-radius:6px;font-size:13px;cursor:pointer;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="b2c-page-header">
      <div><h5>B2C Commission List</h5><small>Dashboard &rsaquo; Configuration &rsaquo; B2c-commission</small></div>
      <button class="btn-assign" data-bs-toggle="modal" data-bs-target="#assignModal">+ Assign B2C Commission</button>
    </div>
    <div class="card-body">
      <h5 class="fw-bold mb-1">Commission Management</h5>
      <p class="text-muted mb-4">Manage your Flight and Hotel commission sets</p>
      @forelse($rules as $r)
      <div class="commission-card">
        <div>
          <div class="fw-bold">{{ $r->name }}</div>
          <div class="text-primary" style="font-size:13px;">Flight Commission Set</div>
          <div class="fw-bold" style="font-size:13px;">{{ $r->name }}</div>
        </div>
        <button class="btn-configure"><i class="fas fa-cog me-1"></i> Configure</button>
      </div>
      @empty
      <div class="text-center text-muted py-5">No commission sets assigned yet.</div>
      @endforelse
    </div>
  </div>
</div></div>

<div class="modal fade" id="assignModal" tabindex="-1">
  <div class="modal-dialog"><form method="POST" action="{{ url('b2c/config/commission') }}">@csrf
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Assign B2C Commission</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <label class="form-label fw-bold text-danger">* Select Commission Set</label>
        <select name="name" class="form-select" required>
          <option value="">Select commission Set eg: BG</option>
          @foreach($commissionRules as $cr)
          <option value="{{ $cr->name }}">{{ $cr->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="modal-footer"><button type="submit" class="btn btn-warning text-white fw-bold"><i class="fas fa-paper-plane me-1"></i> Submit</button></div>
    </div>
  </form></div>
</div>
@endsection
