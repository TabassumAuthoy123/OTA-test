@extends('master')
@section('header_css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;}
.b2c-page-header h5{margin:0;font-size:18px;font-weight:700;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="b2c-page-header"><h5>Terms &amp; Conditions</h5><small>Dashboard &rsaquo; Configuration &rsaquo; Terms-conditions</small></div>
    <div class="card-body">
      <form method="POST" action="{{ url('b2c/config/terms-conditions') }}">@csrf
        <div class="mb-3">
          <label class="form-label fw-bold">* Panel</label>
          <select class="form-select" style="width:120px;"><option>B2C</option></select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold text-danger">* Terms &amp; Conditions</label>
          <textarea id="termsEditor" name="content" rows="15" class="form-control">{{ $page ? $page->content : '' }}</textarea>
        </div>
        <button type="submit" class="btn btn-warning text-white fw-bold px-4">Save</button>
      </form>
    </div>
  </div>
</div></div>
@endsection
@section('footer_js')
<script src="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.js"></script>
<script>Jodit.make('#termsEditor',{height:400});</script>
@endsection
