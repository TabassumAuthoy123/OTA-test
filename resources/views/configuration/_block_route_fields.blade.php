<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label fw-600">Rule Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required placeholder="e.g. Block DAC-BKK via TG">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">GDS</label>
    <select name="gds" class="form-select">
      <option value="all">All</option>
      <option value="sabre">Sabre</option>
      <option value="flyhub">Flyhub</option>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Block Type</label>
    <select name="block_type" class="form-select">
      <option value="route">Route</option>
      <option value="airline">Airline</option>
      <option value="class">Cabin Class</option>
      <option value="combo">Combo</option>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Airline Code</label>
    <input type="text" name="airline_code" class="form-control" maxlength="10" placeholder="TG (blank=all)">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Route From (IATA)</label>
    <input type="text" name="route_from" class="form-control" maxlength="5" placeholder="DAC">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Route To (IATA)</label>
    <input type="text" name="route_to" class="form-control" maxlength="5" placeholder="BKK">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Cabin Class</label>
    <input type="text" name="cabin_class" class="form-control" maxlength="5" placeholder="Y/C/F">
  </div>
  <div class="col-md-12">
    <label class="form-label fw-600">Reason</label>
    <textarea name="reason" class="form-control" rows="2" placeholder="Why is this route/airline blocked?"></textarea>
  </div>
  <div class="col-md-12">
    <div class="form-check">
      <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
      <label class="form-check-label">Active</label>
    </div>
  </div>
</div>
