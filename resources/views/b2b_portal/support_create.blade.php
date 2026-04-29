@extends('master')
@section('content')
<div class="row"><div class="col-lg-8 mx-auto">
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div style="background:linear-gradient(135deg,#0f1f3d,#1a3a6b);color:#fff;padding:16px 24px;">
      <h5 style="margin:0;font-weight:700;"><i class="fas fa-headset me-2"></i>Submit Support Ticket</h5>
      <small style="opacity:.7;">Dashboard &rsaquo; Booking Support &rsaquo; New Ticket</small>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ url('my/booking-support') }}">
        @csrf
        @if($errors->any())
          <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-bold text-danger">* Issue Type</label>
            <select name="issue_type" class="form-select" required>
              <option value="">Select Issue Type</option>
              <option value="ticket_issue" {{ old('issue_type')=='ticket_issue'?'selected':'' }}>Ticket Issue</option>
              <option value="refund" {{ old('issue_type')=='refund'?'selected':'' }}>Refund</option>
              <option value="reissue" {{ old('issue_type')=='reissue'?'selected':'' }}>Reissue</option>
              <option value="void" {{ old('issue_type')=='void'?'selected':'' }}>Void</option>
              <option value="others" {{ old('issue_type')=='others'?'selected':'' }}>Others</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Booking Reference (if any)</label>
            <input type="text" name="booking_ref" class="form-control" value="{{ old('booking_ref') }}" placeholder="e.g. FM-20260422-00012">
          </div>
          <div class="col-12">
            <label class="form-label fw-bold text-danger">* Subject</label>
            <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required placeholder="Brief description of the issue">
          </div>
          <div class="col-12">
            <label class="form-label fw-bold text-danger">* Description</label>
            <textarea name="description" class="form-control" rows="5" required placeholder="Please provide full details of your issue...">{{ old('description') }}</textarea>
          </div>
          <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-warning text-white fw-bold px-4"><i class="fas fa-paper-plane me-1"></i>Submit Ticket</button>
            <a href="{{ url('my/booking-support') }}" class="btn btn-outline-secondary px-4">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div></div>
@endsection
