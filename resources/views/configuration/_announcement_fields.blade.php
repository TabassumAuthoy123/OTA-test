<div class="row g-3">
  <div class="col-md-12">
    <label class="form-label fw-600">Title <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control" required placeholder="Announcement title...">
  </div>
  <div class="col-md-12">
    <label class="form-label fw-600">Message <span class="text-danger">*</span></label>
    <textarea name="message" class="form-control" rows="4" required placeholder="Announcement body text..."></textarea>
  </div>
  <div class="col-md-4">
    <label class="form-label fw-600">Type <span class="text-danger">*</span></label>
    <select name="type" class="form-select" required>
      <option value="info">Info (Blue)</option>
      <option value="warning">Warning (Yellow)</option>
      <option value="success">Success (Green)</option>
      <option value="danger">Danger (Red)</option>
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label fw-600">Target Audience</label>
    <select name="target" class="form-select">
      <option value="all">All Users</option>
      <option value="b2c">B2C Users</option>
      <option value="b2b">B2B Users</option>
    </select>
  </div>
  <div class="col-md-4">&nbsp;</div>
  <div class="col-md-6">
    <label class="form-label fw-600">Show From</label>
    <input type="datetime-local" name="show_from" class="form-control">
  </div>
  <div class="col-md-6">
    <label class="form-label fw-600">Show Until</label>
    <input type="datetime-local" name="show_until" class="form-control">
  </div>
  <div class="col-md-12">
    <div class="form-check">
      <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
      <label class="form-check-label">Active (show to users)</label>
    </div>
  </div>
</div>
