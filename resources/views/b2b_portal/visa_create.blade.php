@extends('master')
@section('content')
<div class="row"><div class="col-lg-8 mx-auto">
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div style="background:linear-gradient(135deg,#0f1f3d,#1a3a6b);color:#fff;padding:16px 24px;">
      <h5 style="margin:0;font-weight:700;"><i class="fas fa-passport me-2"></i>New Visa Application</h5>
      <small style="opacity:.7;">Dashboard &rsaquo; Visa Application List &rsaquo; New</small>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ url('my/visa-applications') }}">
        @csrf
        @if($errors->any())
          <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-bold text-danger">* Applicant Name</label>
            <input type="text" name="applicant_name" class="form-control" value="{{ old('applicant_name') }}" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Passport No</label>
            <input type="text" name="passport_no" class="form-control" value="{{ old('passport_no') }}">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Nationality</label>
            <input type="text" name="nationality" class="form-control" value="{{ old('nationality') }}" placeholder="e.g. Bangladeshi">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold text-danger">* Destination Country</label>
            <input type="text" name="destination_country" class="form-control" value="{{ old('destination_country') }}" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold text-danger">* Visa Type</label>
            <select name="visa_type" class="form-select" required>
              <option value="">Select Type</option>
              @foreach(['tourist','business','student','work','medical','other'] as $t)
              <option value="{{ $t }}" {{ old('visa_type')==$t?'selected':'' }}>{{ ucfirst($t) }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Travel Date</label>
            <input type="date" name="travel_date" class="form-control" value="{{ old('travel_date') }}">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Passport Expiry Date</label>
            <input type="date" name="passport_expiry" class="form-control" value="{{ old('passport_expiry') }}">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Contact No</label>
            <input type="text" name="contact_no" class="form-control" value="{{ old('contact_no') }}">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
          </div>
          <div class="col-12">
            <label class="form-label fw-bold">Notes / Remarks</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
          </div>
          <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-warning text-white fw-bold px-4"><i class="fas fa-paper-plane me-1"></i>Submit Application</button>
            <a href="{{ url('my/visa-applications') }}" class="btn btn-outline-secondary px-4">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div></div>
@endsection
