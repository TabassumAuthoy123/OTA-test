<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label fw-600">Rule Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required placeholder="e.g. 30% upfront - flights">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Min Payment % <span class="text-danger">*</span></label>
    <input type="number" name="min_payment_percent" class="form-control" step="0.01" min="1" max="100" required placeholder="30">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Due Days <span class="text-danger">*</span></label>
    <input type="number" name="payment_due_days" class="form-control" min="1" required placeholder="7">
  </div>
  <div class="col-md-4">
    <label class="form-label fw-600">Applicable For</label>
    <select name="applicable_for" class="form-select">
      <option value="all">All</option>
      <option value="flight">Flight</option>
      <option value="tour">Tour</option>
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label fw-600">Airline Code (blank=all)</label>
    <input type="text" name="airline_code" class="form-control" maxlength="10" placeholder="BG">
  </div>
  <div class="col-md-2">
    <label class="form-label fw-600">Route From</label>
    <input type="text" name="route_from" class="form-control" maxlength="10" placeholder="DAC">
  </div>
  <div class="col-md-2">
    <label class="form-label fw-600">Route To</label>
    <input type="text" name="route_to" class="form-control" maxlength="10" placeholder="DXB">
  </div>
  <div class="col-md-12">
    <label class="form-label fw-600">Notes</label>
    <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
  </div>
  <div class="col-md-12">
    <div class="form-check">
      <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
      <label class="form-check-label">Active</label>
    </div>
  </div>
</div>
