@extends('master')
@section('header_css')
    <style>
        .cms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 16px;
        }

        .cms-item {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }

        .cms-item img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .cms-item h6 {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .cms-item .meta {
            font-size: 12px;
            color: #6c757d;
        }

        .cms-item .actions {
            display: flex;
            gap: 6px;
            margin-top: 10px;
        }

        .cms-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 4px;
        }

        .status-dot.active {
            background: #198754;
        }

        .status-dot.inactive {
            background: #dc3545;
        }

        .cms-modal .form-label {
            position: static !important;
            transform: none !important;
            pointer-events: auto !important;
            padding: 0 0 4px 0 !important;
            height: auto !important;
            font-size: 13px !important;
            color: #212529 !important;
            white-space: normal !important;
            z-index: auto !important;
            border: none !important;
            opacity: 1 !important;
        }

        .cms-modal .form-control,
        .cms-modal .form-select {
            padding: 8px 12px !important;
            height: auto !important;
        }

        .badge-preview {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            color: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="cms-header">
        <div>
            <h4 class="mb-0 fw-bold">🏷️ Promotions & Deals</h4><small class="text-muted">Manage promotional cards for B2C
                landing page</small>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i
                class="fas fa-plus me-1"></i> Add Promotion</button>
    </div>

    <div class="cms-grid">
        @forelse($items as $item)
            <div class="cms-item">
                @if($item->image)
                    <img src="{{ url($item->image) }}" alt="{{ $item->title }}">
                @endif
                <h6>{{ $item->title }}</h6>
                @if($item->badge_text)<span class="badge-preview"
                style="background:{{ $item->badge_color }}">{{ $item->badge_text }}</span>@endif
                @if($item->discount_text)<span class="badge bg-success">{{ $item->discount_text }}</span>@endif
                <p class="meta mt-1">{{ Str::limit($item->description, 80) }}</p>
                <div class="meta"><span
                        class="status-dot {{ $item->is_active ? 'active' : 'inactive' }}"></span>{{ $item->is_active ? 'Active' : 'Inactive' }}
                </div>
                <div class="actions">
                    <button class="btn btn-sm btn-outline-primary" onclick="editItem({{ json_encode($item) }})"><i
                            class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteItem({{ $item->id }})"><i
                            class="fas fa-trash"></i></button>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5" style="grid-column:1/-1;"><i class="fas fa-tags fa-3x mb-3 d-block"></i>No
                promotions yet.</div>
        @endforelse
    </div>

    <div class="modal fade cms-modal" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ url('cms/promotions') }}" method="POST" enctype="multipart/form-data">@csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Promotion</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label fw-bold">Title *</label><input type="text" name="title"
                                class="form-control" required></div>
                        <div class="mb-3"><label class="form-label fw-bold">Description</label><textarea name="description"
                                class="form-control" rows="2"></textarea></div>
                        <div class="row">
                            <div class="col-4 mb-3"><label class="form-label fw-bold">Discount Text</label><input
                                    type="text" name="discount_text" class="form-control" placeholder="30% OFF"></div>
                            <div class="col-4 mb-3"><label class="form-label fw-bold">Badge Text</label><input type="text"
                                    name="badge_text" class="form-control" placeholder="HOT DEAL"></div>
                            <div class="col-4 mb-3"><label class="form-label fw-bold">Badge Color</label><input type="color"
                                    name="badge_color" class="form-control form-control-color" value="#FF6B35"></div>
                        </div>
                        <div class="mb-3"><label class="form-label fw-bold">Image</label><input type="file" name="image"
                                class="form-control" accept="image/*"></div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Link URL</label><input type="text"
                                    name="url" class="form-control"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Position</label><input type="number"
                                    name="position" class="form-control" value="0"></div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Starts At</label><input type="date"
                                    name="starts_at" class="form-control"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Expires At</label><input type="date"
                                    name="expires_at" class="form-control"></div>
                        </div>
                        <div class="form-check"><input type="checkbox" name="is_active" class="form-check-input"
                                checked><label class="form-check-label">Active</label></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">Cancel</button><button type="submit"
                            class="btn btn-primary">Create</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade cms-modal" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Promotion</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label fw-bold">Title *</label><input type="text" name="title"
                                id="eTitle" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label fw-bold">Description</label><textarea name="description"
                                id="eDesc" class="form-control" rows="2"></textarea></div>
                        <div class="row">
                            <div class="col-4 mb-3"><label class="form-label fw-bold">Discount Text</label><input
                                    type="text" name="discount_text" id="eDiscount" class="form-control"></div>
                            <div class="col-4 mb-3"><label class="form-label fw-bold">Badge Text</label><input type="text"
                                    name="badge_text" id="eBadge" class="form-control"></div>
                            <div class="col-4 mb-3"><label class="form-label fw-bold">Badge Color</label><input type="color"
                                    name="badge_color" id="eBadgeColor" class="form-control form-control-color"></div>
                        </div>
                        <div class="mb-3"><label class="form-label fw-bold">Image</label><input type="file" name="image"
                                class="form-control" accept="image/*"></div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Link URL</label><input type="text"
                                    name="url" id="eUrl" class="form-control"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Position</label><input type="number"
                                    name="position" id="ePos" class="form-control"></div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Starts At</label><input type="date"
                                    name="starts_at" id="eStart" class="form-control"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Expires At</label><input type="date"
                                    name="expires_at" id="eExpire" class="form-control"></div>
                        </div>
                        <div class="form-check"><input type="checkbox" name="is_active" class="form-check-input"
                                id="eActive"><label class="form-check-label" for="eActive">Active</label></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">Cancel</button><button type="submit"
                            class="btn btn-primary">Update</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer_js')
    <script>
        function editItem(item) {
            $('#editForm').attr('action', '{{ url("cms/promotions") }}/' + item.id);
            $('#eTitle').val(item.title); $('#eDesc').val(item.description); $('#eDiscount').val(item.discount_text);
            $('#eBadge').val(item.badge_text); $('#eBadgeColor').val(item.badge_color || '#FF6B35');
            $('#eUrl').val(item.url); $('#ePos').val(item.position);
            $('#eStart').val(item.starts_at ? item.starts_at.substring(0, 10) : '');
            $('#eExpire').val(item.expires_at ? item.expires_at.substring(0, 10) : '');
            $('#eActive').prop('checked', item.is_active); $('#editModal').modal('show');
        }
        function deleteItem(id) { if (!confirm('Delete?')) return; $.ajax({ url: '{{ url("cms/promotions") }}/' + id, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: () => location.reload() }); }
    </script>
@endsection