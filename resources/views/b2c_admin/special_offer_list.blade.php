@extends('master')
@section('header_css')
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:18px 24px;border-radius:8px 8px 0 0;}
.b2c-page-header h4{margin:0;font-weight:700;font-size:1.1rem;}
.filter-bar{background:#f8f9fa;padding:12px 16px;border-bottom:1px solid #dee2e6;}
.table thead th{background:#2471a3;color:#fff;border:none;font-size:.82rem;padding:10px 12px;white-space:nowrap;}
.table tbody tr:hover{background:#eaf4fb;}
.badge-active{background:#27ae60;color:#fff;padding:3px 10px;border-radius:12px;font-size:.78rem;font-weight:600;}
.badge-inactive{background:#e74c3c;color:#fff;padding:3px 10px;border-radius:12px;font-size:.78rem;font-weight:600;}
.offer-thumb{width:70px;height:45px;object-fit:cover;border-radius:4px;border:1px solid #dee2e6;}
.form-switch .form-check-input{cursor:pointer;width:2.5em;height:1.25em;}
</style>
@endsection
@section('content')
<div class="container-fluid py-3">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="b2c-page-header d-flex align-items-center justify-content-between">
        <div>
          <h4><i class="fas fa-fire me-2"></i>{{ $typeLabel }} List</h4>
          <small style="opacity:.8;">Dashboard &rsaquo; B2C Configuration &rsaquo; {{ $typeLabel }}</small>
        </div>
        <a href="{{ route('B2cCreateOffer', $type) }}" class="btn btn-warning fw-bold">
          <i class="fas fa-plus me-1"></i>Create {{ $typeLabel }}
        </a>
      </div>

      @if(session('success'))<div class="alert alert-success mt-2">{{ session('success') }}</div>@endif

      <!-- Filters -->
      <div class="filter-bar">
        <form method="GET" action="{{ route('B2cSpecialOfferList', $type) }}" class="row g-2 align-items-end">
          @if($type === 'offer')
          <div class="col-auto">
            <label class="form-label mb-1 small fw-semibold">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search title..." style="min-width:180px;">
          </div>
          @endif
          <div class="col-auto">
            <label class="form-label mb-1 small fw-semibold">Status</label>
            <select name="filter_status" class="form-select form-select-sm" style="min-width:130px;">
              <option value="all" {{ request('filter_status','all')==='all'?'selected':'' }}>All Status</option>
              <option value="1" {{ request('filter_status')==='1'?'selected':'' }}>Active</option>
              <option value="0" {{ request('filter_status')==='0'?'selected':'' }}>Inactive</option>
            </select>
          </div>
          <div class="col-auto">
            <label class="form-label mb-1 small fw-semibold">From</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control form-control-sm">
          </div>
          <div class="col-auto">
            <label class="form-label mb-1 small fw-semibold">To</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control form-control-sm">
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>
            <a href="{{ route('B2cSpecialOfferList', $type) }}" class="btn btn-secondary btn-sm ms-1">Clear</a>
          </div>
        </form>
      </div>

      <div class="card shadow-sm border-0">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Photo</th>
                  @if($type === 'offer')
                  <th>Title</th>
                  @endif
                  <th>Link</th>
                  <th>Active</th>
                  <th>Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($offers as $i => $offer)
                <tr>
                  <td>{{ $i+1 }}</td>
                  <td>
                    @if($offer->photo)
                      <img src="{{ asset($offer->photo) }}" class="offer-thumb">
                    @else
                      <span class="text-muted">—</span>
                    @endif
                  </td>
                  @if($type === 'offer')
                  <td>{{ $offer->title ?? '—' }}</td>
                  @endif
                  <td>
                    @if($offer->link)
                      <a href="{{ $offer->link }}" target="_blank" class="text-primary small">{{ Str::limit($offer->link, 45) }}</a>
                    @else
                      <span class="text-muted">—</span>
                    @endif
                  </td>
                  <td>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" {{ $offer->is_active ? 'checked' : '' }}
                        onchange="toggleActive({{ $offer->id }}, this)">
                    </div>
                  </td>
                  <td>{{ \Carbon\Carbon::parse($offer->created_at)->format('d M Y') }}</td>
                  <td>
                    <a href="{{ route('B2cDetailsOffer', $offer->id) }}" class="btn btn-sm btn-info text-white" title="View">
                      <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('B2cEditOffer', [$type, $offer->id]) }}" class="btn btn-sm btn-warning" title="Edit">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('B2cDeleteOffer', $offer->id) }}" style="display:inline" onsubmit="return confirm('Delete this {{ strtolower($typeLabel) }}?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr><td colspan="{{ $type === 'offer' ? 7 : 6 }}" class="text-center text-muted py-4">No {{ strtolower($typeLabel) }} found.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="px-3 py-2">
            <small class="text-muted">Total: {{ $total }} {{ strtolower($typeLabel) }}</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('footer_js')
<script>
function toggleActive(id, el) {
  fetch('{{ url("b2c/config/offer") }}/' + id + '/toggle', {
    method: 'POST',
    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'},
    body: JSON.stringify({})
  }).then(r => r.json()).then(data => {
    if (!data.success) el.checked = !el.checked;
  }).catch(() => { el.checked = !el.checked; });
}
</script>
@endsection
