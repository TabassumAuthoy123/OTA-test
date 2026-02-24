@extends('master')
@section('header_css')
    <style>
        .cms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 16px;
        }

        .cms-item {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }

        .cms-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .star-rating {
            color: #ffc107;
            font-size: 13px;
        }

        .testimonial-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
        }

        .testimonial-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
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
        }

        .cms-modal .form-control,
        .cms-modal .form-select {
            padding: 8px 12px !important;
            height: auto !important;
        }
    </style>
@endsection

@section('content')
    <div class="cms-header">
        <div>
            <h4 class="mb-0 fw-bold">⭐ Testimonials</h4><small class="text-muted">Manage customer reviews for social
                proof</small>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i
                class="fas fa-plus me-1"></i> Add Testimonial</button>
    </div>

    <div class="cms-grid">
        @forelse($items as $item)
            <div class="cms-item">
                <div class="testimonial-header">
                    @if($item->avatar)<img src="{{ url($item->avatar) }}" class="testimonial-avatar">@else<div
                        class="testimonial-avatar"
                        style="background:#e9ecef;display:flex;align-items:center;justify-content:center;"><i
                    class="fas fa-user"></i></div>@endif
                    <div><strong>{{ $item->customer_name }}</strong>
                        <div class="star-rating">@for($i = 0; $i < $item->rating; $i++)★@endfor</div>
                    </div>
                </div>
                <p style="font-size:13px;color:#6c757d;font-style:italic;">"{{ Str::limit($item->review, 120) }}"</p>
                <div style="font-size:12px;color:#6c757d;"><span
                        class="status-dot {{ $item->is_active ? 'active' : 'inactive' }}"></span>{{ $item->is_active ? 'Active' : 'Inactive' }}
                </div>
                <div style="display:flex;gap:6px;margin-top:10px;">
                    <button class="btn btn-sm btn-outline-primary" onclick="editItem({{ json_encode($item) }})"><i
                            class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteItem({{ $item->id }})"><i
                            class="fas fa-trash"></i></button>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5" style="grid-column:1/-1;"><i class="fas fa-star fa-3x mb-3 d-block"></i>No
                testimonials yet.</div>
        @endforelse
    </div>

    <div class="modal fade cms-modal" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ url('cms/testimonials') }}" method="POST" enctype="multipart/form-data">@csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Testimonial</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label fw-bold">Customer Name *</label><input type="text"
                                name="customer_name" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label fw-bold">Avatar Photo</label><input type="file"
                                name="avatar" class="form-control" accept="image/*"></div>
                        <div class="mb-3"><label class="form-label fw-bold">Rating</label><select name="rating"
                                class="form-select">
                                <option value="5">★★★★★ (5)</option>
                                <option value="4">★★★★ (4)</option>
                                <option value="3">★★★ (3)</option>
                            </select></div>
                        <div class="mb-3"><label class="form-label fw-bold">Review Text</label><textarea name="review"
                                class="form-control" rows="3" placeholder="What the customer said..."></textarea></div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Position</label><input type="number"
                                    name="position" class="form-control" value="0"></div>
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
                        <h5 class="modal-title">Edit Testimonial</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label fw-bold">Customer Name *</label><input type="text"
                                name="customer_name" id="eName" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label fw-bold">Avatar Photo</label><input type="file"
                                name="avatar" class="form-control" accept="image/*"></div>
                        <div class="mb-3"><label class="form-label fw-bold">Rating</label><select name="rating" id="eRating"
                                class="form-select">
                                <option value="5">★★★★★ (5)</option>
                                <option value="4">★★★★ (4)</option>
                                <option value="3">★★★ (3)</option>
                            </select></div>
                        <div class="mb-3"><label class="form-label fw-bold">Review Text</label><textarea name="review"
                                id="eReview" class="form-control" rows="3"></textarea></div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Position</label><input type="number"
                                    name="position" id="ePos" class="form-control"></div>
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
            $('#editForm').attr('action', '{{ url("cms/testimonials") }}/' + item.id);
            $('#eName').val(item.customer_name); $('#eRating').val(item.rating); $('#eReview').val(item.review);
            $('#ePos').val(item.position); $('#eActive').prop('checked', item.is_active); $('#editModal').modal('show');
        }
        function deleteItem(id) { if (!confirm('Delete?')) return; $.ajax({ url: '{{ url("cms/testimonials") }}/' + id, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: () => location.reload() }); }
    </script>
@endsection