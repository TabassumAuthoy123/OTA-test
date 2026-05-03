@extends('master')
@section('header_css')
<style>
.anc-header{background:linear-gradient(135deg,#1e3a5f,#2d5f8a);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;display:flex;justify-content:space-between;align-items:center;}
.anc-header h5{margin:0;font-size:18px;font-weight:700;}
.anc-table th{background:#1e3a5f;color:#fff;font-size:13px;padding:10px 12px;white-space:nowrap;}
.anc-table td{font-size:13px;padding:9px 12px;vertical-align:middle;}
.anc-table tr:hover td{background:#f0f4ff;}
.badge-type{padding:3px 10px;border-radius:10px;font-size:11px;font-weight:700;}
.t-baggage{background:#cce5ff;color:#004085;}
.t-meal{background:#d4edda;color:#155724;}
.t-seat{background:#fff3cd;color:#856404;}
.t-other{background:#e2e3e5;color:#383d41;}
</style>
@endsection
@section('content')
<div class="row">
  <div class="col-12">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card" style="border-radius:8px;overflow:hidden;">
      <div class="anc-header">
        <div>
          <h5><i class="fas fa-suitcase me-2"></i> Ancillary Options</h5>
          <small>Manage baggage, meal, and seat add-ons</small>
        </div>
        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#ancModal">
          <i class="fas fa-plus"></i> Add Option
        </button>
      </div>

      {{-- Global commission quick-set panel --}}
      <div style="background:#fff8e1;border-bottom:1px solid #ffe082;padding:12px 20px;display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
        <strong style="font-size:14px;"><i class="fas fa-percent me-1 text-warning"></i> Global B2B Commission (all agents at once)</strong>
        <form method="POST" action="{{ route('SetGlobalCommission') }}" style="display:flex;gap:8px;align-items:center;">
          @csrf
          <input type="number" name="global_commission" min="0" max="100" step="0.01" placeholder="e.g. 6.75" class="form-control form-control-sm" style="width:130px;" required>
          <button class="btn btn-sm btn-warning" type="submit">Set for ALL Agents</button>
        </form>
        <small class="text-muted">Supports fractions: 5.5%, 6.75%, 6.20% etc.</small>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered mb-0 anc-table">
          <thead>
            <tr>
              <th>SL</th><th>Type</th><th>Name</th><th>Description</th>
              <th>Weight (kg)</th><th>Price</th><th>Currency</th>
              <th>Airline</th><th>Route</th><th>Status</th><th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($options as $i => $o)
            <tr>
              <td>{{ $options->firstItem() + $i }}</td>
              <td><span class="badge-type t-{{ $o->type }}">{{ strtoupper($o->type) }}</span></td>
              <td style="font-weight:600;">{{ $o->name }}</td>
              <td style="color:#777;font-size:12px;">{{ $o->description ?? '—' }}</td>
              <td>{{ $o->weight_kg ? $o->weight_kg . ' kg' : '—' }}</td>
              <td style="font-weight:700;">৳ {{ number_format($o->price, 2) }}</td>
              <td>{{ $o->currency }}</td>
              <td>{{ $o->airline_code ?? 'All' }}</td>
              <td>{{ $o->route_from ? $o->route_from . '-' . $o->route_to : 'All' }}</td>
              <td>
                @if($o->is_active)
                  <span class="badge bg-success">Active</span>
                @else
                  <span class="badge bg-secondary">Inactive</span>
                @endif
              </td>
              <td style="white-space:nowrap;">
                <button class="btn btn-sm btn-warning edit-btn"
                  data-id="{{ $o->id }}" data-type="{{ $o->type }}" data-name="{{ $o->name }}"
                  data-description="{{ $o->description }}" data-weight="{{ $o->weight_kg }}"
                  data-price="{{ $o->price }}" data-currency="{{ $o->currency }}"
                  data-airline="{{ $o->airline_code }}" data-from="{{ $o->route_from }}"
                  data-to="{{ $o->route_to }}" data-active="{{ $o->is_active ? 1 : 0 }}">
                  <i class="fas fa-edit"></i>
                </button>
                <form method="POST" action="{{ route('AdminAncillaryDelete', $o->id) }}" class="d-inline"
                      onsubmit="return confirm('Delete this option?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="11" class="text-center py-5 text-muted">No ancillary options defined yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="px-3 py-2">{{ $options->links() }}</div>
    </div>
  </div>
</div>

{{-- Add/Edit Modal --}}
<div class="modal fade" id="ancModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form id="ancForm" method="POST" action="{{ route('AdminAncillaryStore') }}">
      @csrf
      <input type="hidden" name="option_id" id="optionId">
      <div class="modal-content">
        <div class="modal-header" style="background:#1e3a5f;color:#fff;">
          <h5 class="modal-title" id="modalTitle">Add Ancillary Option</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-3">
          <div class="col-md-4">
            <label class="form-label">Type <span class="text-danger">*</span></label>
            <select name="type" id="fType" class="form-select" required>
              <option value="baggage">Baggage</option>
              <option value="meal">Meal</option>
              <option value="seat">Seat</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="col-md-8">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="fName" class="form-control" placeholder="e.g. Extra 10kg Baggage" required>
          </div>
          <div class="col-12">
            <label class="form-label">Description</label>
            <input type="text" name="description" id="fDesc" class="form-control" placeholder="Optional description">
          </div>
          <div class="col-md-3">
            <label class="form-label">Weight (kg)</label>
            <input type="number" name="weight_kg" id="fWeight" class="form-control" step="0.5" placeholder="10">
          </div>
          <div class="col-md-3">
            <label class="form-label">Price <span class="text-danger">*</span></label>
            <input type="number" name="price" id="fPrice" class="form-control" step="0.01" min="0" placeholder="1500.00" required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Currency</label>
            <input type="text" name="currency" id="fCurrency" class="form-control" value="BDT" maxlength="5">
          </div>
          <div class="col-md-4">
            <label class="form-label">Airline Code (blank=All)</label>
            <input type="text" name="airline_code" id="fAirline" class="form-control" placeholder="BG / BS / EK">
          </div>
          <div class="col-md-3">
            <label class="form-label">Route From (blank=All)</label>
            <input type="text" name="route_from" id="fFrom" class="form-control" placeholder="DAC">
          </div>
          <div class="col-md-3">
            <label class="form-label">Route To (blank=All)</label>
            <input type="text" name="route_to" id="fTo" class="form-control" placeholder="DXB">
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <div class="form-check">
              <input type="checkbox" name="is_active" id="fActive" class="form-check-input" checked value="1">
              <label class="form-check-label" for="fActive">Active</label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Option</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@section('footer_js')
<script>
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('modalTitle').textContent = 'Edit Ancillary Option';
        document.getElementById('optionId').value  = this.dataset.id;
        document.getElementById('fType').value     = this.dataset.type;
        document.getElementById('fName').value     = this.dataset.name;
        document.getElementById('fDesc').value     = this.dataset.description || '';
        document.getElementById('fWeight').value   = this.dataset.weight || '';
        document.getElementById('fPrice').value    = this.dataset.price;
        document.getElementById('fCurrency').value = this.dataset.currency;
        document.getElementById('fAirline').value  = this.dataset.airline || '';
        document.getElementById('fFrom').value     = this.dataset.from || '';
        document.getElementById('fTo').value       = this.dataset.to || '';
        document.getElementById('fActive').checked = this.dataset.active == '1';
        new bootstrap.Modal(document.getElementById('ancModal')).show();
    });
});
</script>
@endsection
