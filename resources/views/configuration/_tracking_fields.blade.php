<div class="row g-3">
  <div class="col-md-7">
    <label class="form-label fw-600">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required placeholder="e.g. Main Google Analytics">
  </div>
  <div class="col-md-5">
    <label class="form-label fw-600">Type <span class="text-danger">*</span></label>
    <select name="type" class="form-select" required>
      <option value="google_analytics">Google Analytics</option>
      <option value="facebook_pixel">Facebook Pixel</option>
      <option value="google_tag_manager">Google Tag Manager</option>
      <option value="custom">Custom Script</option>
    </select>
  </div>
  <div class="col-md-12">
    <label class="form-label fw-600">Tracking Code / Script <span class="text-danger">*</span></label>
    <textarea name="tracking_code" class="form-control" rows="6" required
      placeholder="Paste the full script snippet or tracking ID here...&#10;&#10;e.g. &lt;script async src=&quot;...&quot;&gt;&lt;/script&gt;&#10;or just: G-XXXXXXXXXX"></textarea>
    <small class="text-muted">Paste the full script tag or just the ID — your theme will handle placement.</small>
  </div>
  <div class="col-md-12">
    <label class="form-label fw-600">Notes</label>
    <input type="text" name="notes" class="form-control" placeholder="Optional notes...">
  </div>
  <div class="col-md-12">
    <div class="form-check">
      <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
      <label class="form-check-label">Active (inject into B2C pages)</label>
    </div>
  </div>
</div>
