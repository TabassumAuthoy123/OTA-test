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

        .cms-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .route-code {
            font-size: 20px;
            font-weight: 700;
            color: #0d6efd;
            letter-spacing: 1px;
        }

        .route-arrow {
            color: #6c757d;
            margin: 0 4px;
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
        }

        .cms-modal .form-control {
            padding: 8px 12px !important;
            height: auto !important;
        }
    </style>
@endsection

@section('content')
    <div class="cms-header">
        <div>
            <h4 class="mb-0 fw-bold">✈️ Popular Routes</h4><small class="text-muted">Manage featured destination
                cards</small>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i
                class="fas fa-plus me-1"></i> Add Route</button>
    </div>

    <div class="cms-grid">
        @forelse($items as $item)
            <div class="cms-item">
                @if($item->image)<img src="{{ url($item->image) }}" alt="">@endif
                <div class="route-code">{{ $item->origin_code }} <span class="route-arrow">→</span>
                    {{ $item->destination_code }}</div>
                <div style="font-size:13px;color:#495057;">{{ $item->origin_city }} → {{ $item->destination_city }}</div>
                @if($item->starting_price)
                    <div style="font-size:14px;font-weight:600;color:#198754;margin-top:4px;">From
                ৳{{ number_format($item->starting_price) }}</div>@endif
                <div style="font-size:12px;color:#6c757d;margin-top:4px;"><span
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
            <div class="text-center text-muted py-5" style="grid-column:1/-1;"><i class="fas fa-route fa-3x mb-3 d-block"></i>No
                popular routes yet.</div>
        @endforelse
    </div>

    <div class="modal fade cms-modal" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ url('cms/routes') }}" method="POST" enctype="multipart/form-data">@csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Popular Route</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Origin City *</label><input
                                    type="text" name="origin_city" class="form-control" required placeholder="Dhaka"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Origin Code</label><input type="text"
                                    name="origin_code" class="form-control" placeholder="DAC" maxlength="4"></div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Destination City *</label><input
                                    type="text" name="destination_city" class="form-control" required
                                    placeholder="Cox's Bazar"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Destination Code</label><input
                                    type="text" name="destination_code" class="form-control" placeholder="CXB"
                                    maxlength="4"></div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Starting Price (৳)</label><input
                                    type="number" name="starting_price" class="form-control" step="0.01"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Position</label><input type="number"
                                    name="position" class="form-control" value="0"></div>
                        </div>
                        <div class="mb-3"><label class="form-label fw-bold">Destination Image</label><input type="file"
                                name="image" class="form-control" accept="image/*"></div>
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
                        <h5 class="modal-title">Edit Route</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Origin City *</label><input
                                    type="text" name="origin_city" id="eOCity" class="form-control" required></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Origin Code</label><input type="text"
                                    name="origin_code" id="eOCode" class="form-control"></div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Destination City *</label><input
                                    type="text" name="destination_city" id="eDCity" class="form-control" required></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Destination Code</label><input
                                    type="text" name="destination_code" id="eDCode" class="form-control"></div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Starting Price</label><input
                                    type="number" name="starting_price" id="ePrice" class="form-control" step="0.01"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Position</label><input type="number"
                                    name="position" id="ePos" class="form-control"></div>
                        </div>
                        <div class="mb-3"><label class="form-label fw-bold">Image</label><input type="file" name="image"
                                class="form-control" accept="image/*"></div>
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
            $('#editForm').attr('action', '{{ url("cms/routes") }}/' + item.id);
            $('#eOCity').val(item.origin_city); $('#eOCode').val(item.origin_code);
            $('#eDCity').val(item.destination_city); $('#eDCode').val(item.destination_code);
            $('#ePrice').val(item.starting_price); $('#ePos').val(item.position);
            $('#eActive').prop('checked', item.is_active); $('#editModal').modal('show');
        }
        function deleteItem(id) { if (!confirm('Delete?')) return; $.ajax({ url: '{{ url("cms/routes") }}/' + id, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: () => location.reload() }); }
    </script>
@endsection