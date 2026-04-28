<div class="row g-3">
  <div class="col-md-8">
    <label class="form-label fw-600">Airline Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required placeholder="Biman Bangladesh Airlines">
  </div>
  <div class="col-md-4">
    <label class="form-label fw-600">IATA Code <span class="text-danger">*</span></label>
    <input type="text" name="iata" class="form-control" maxlength="10" required placeholder="BG" style="text-transform:uppercase;">
  </div>
  <div class="col-md-4">
    <label class="form-label fw-600">ICAO Code</label>
    <input type="text" name="icao" class="form-control" maxlength="10" placeholder="BBC" style="text-transform:uppercase;">
  </div>
  <div class="col-md-5">
    <label class="form-label fw-600">Country</label>
    <input type="text" name="country" class="form-control" placeholder="Bangladesh">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-600">Commission %</label>
    <input type="number" name="comission" class="form-control" step="0.01" min="0" value="0" placeholder="0.00">
  </div>
  <div class="col-md-12">
    <div class="form-check">
      <input type="checkbox" name="active" class="form-check-input" value="Y" checked>
      <label class="form-check-label">Active</label>
    </div>
  </div>
</div>
