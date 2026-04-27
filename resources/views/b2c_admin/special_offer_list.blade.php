@extends('master')
@section('header_css')
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:18px 24px;border-radius:8px 8px 0 0;}
.b2c-page-header h4{margin:0;font-weight:700;font-size:1.2rem;}
.table thead th{background:#2471a3;color:#fff;border:none;font-size:.82rem;padding:10px 12px;}
.table tbody tr:hover{background:#eaf4fb;}
.badge-active{background:#27ae60;color:#fff;padding:4px 10px;border-radius:12px;font-size:.78rem;}
.badge-inactive{background:#e74c3c;color:#fff;padding:4px 10px;border-radius:12px;font-size:.78rem;}
.offer-thumb{width:60px;height:40px;object-fit:cover;border-radius:4px;}
</style>
@endsection
@section('content')
<div class="container-fluid py-3">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="b2c-page-header d-flex align-items-center justify-content-between">
        <h4><i class="fas fa-fire me-2"></i>{{ $typeLabel }} List</h4>
        <a href="{{ route('B2cCreateOffer', $type) }}" class="btn btn-warning fw-bold">
          <i class="fas fa-plus me-1"></i>Create {{ $typeLabel }}
        </a>
      </div>
      @if(session('success'))<div class="alert alert-success mt-2">{{ session('success') }}</div>@endif
      <div class="card shadow-sm border-0">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Photo</th>
                  <th>Title</th>
                  <th>Link</th>
                  <th>Status</th>
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
                  <td>{{ $offer->title ?? '—' }}</td>
                  <td>{{ $offer->link ? Str::limit($offer->link, 40) : '—' }}</td>
                  <td>
                    <span class="{{ $offer->is_active ? 'badge-active' : 'badge-inactive' }}">
                      {{ $offer->is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td>{{ \Carbon\Carbon::parse($offer->created_at)->format('d M Y') }}</td>
                  <td>
                    <a href="{{ route('B2cDetailsOffer', $offer->id) }}" class="btn btn-sm btn-info text-white">
                      <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('B2cEditOffer', [$type, $offer->id]) }}" class="btn btn-sm btn-warning">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('B2cToggleOffer', $offer->id) }}" style="display:inline">
                      @csrf
                      <button class="btn btn-sm {{ $offer->is_active ? 'btn-secondary' : 'btn-success' }}" title="{{ $offer->is_active ? 'Deactivate' : 'Activate' }}">
                        <i class="fas fa-{{ $offer->is_active ? 'pause' : 'play' }}"></i>
                      </button>
                    </form>
                    <form method="POST" action="{{ route('B2cDeleteOffer', $offer->id) }}" style="display:inline" onsubmit="return confirm('Delete?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No {{ strtolower($typeLabel) }} found.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
