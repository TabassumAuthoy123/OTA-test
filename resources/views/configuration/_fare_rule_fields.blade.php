<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label fw-600">Rule Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required placeholder="e.g. Dhaka-Dubai markup">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Origin (IATA)</label>
    <input type="text" name="origin" class="form-control" maxlength="10" placeholder="DAC">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Destination (IATA)</label>
    <input type="text" name="destination" class="form-control" maxlength="10" placeholder="DXB">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Airline Code</label>
    <input type="text" name="airline_code" class="form-control" maxlength="10" placeholder="BG (leave blank=all)">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Trip Type</label>
    <select name="trip_type" class="form-select">
      <option value="all">All</option>
      <option value="one_way">One Way</option>
      <option value="round_trip">Round Trip</option>
      <option value="multi_city">Multi City</option>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Cabin Class</label>
    <input type="text" name="cabin_class" class="form-control" maxlength="10" placeholder="Y/C/F (blank=all)">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Markup Type <span class="text-danger">*</span></label>
    <select name="markup_type" class="form-select" required>
      <option value="percentage">Percentage (%)</option>
      <option value="fixed">Fixed (BDT)</option>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Markup Value <span class="text-danger">*</span></label>
    <input type="number" name="markup_value" class="form-control" step="0.01" min="0" required placeholder="0.00">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Min Fare (BDT)</label>
    <input type="number" name="min_fare" class="form-control" step="0.01" placeholder="Leave blank = no limit">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Max Fare (BDT)</label>
    <input type="number" name="max_fare" class="form-control" step="0.01" placeholder="Leave blank = no limit">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Valid From</label>
    <input type="date" name="valid_from" class="form-control">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Valid Until</label>
    <input type="date" name="valid_until" class="form-control">
  </div>
  <div class="col-md-12">
    <label class="form-label fw-600">Notes</label>
    <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
  </div>
  <div class="col-md-12">
    <div class="form-check">
      <input type="checkbox" name="is_active" class="form-check-input" id="fareActive" value="1" checked>
      <label class="form-check-label" for="fareActive">Active</label>
    </div>
  </div>
</div>
