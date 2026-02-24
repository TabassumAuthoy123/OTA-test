@extends('master')
@section('header_css')
    <style>
        .cms-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .pages-table {
            width: 100%;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }

        .pages-table th {
            background: #f8f9fa;
            font-size: 13px;
            font-weight: 600;
            padding: 12px 16px;
        }

        .pages-table td {
            padding: 12px 16px;
            font-size: 13px;
            border-top: 1px solid #e9ecef;
            vertical-align: middle;
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
        .cms-modal .form-select,
        .cms-modal textarea {
            padding: 8px 12px !important;
            height: auto !important;
        }
    </style>
@endsection

@section('content')
    <div class="cms-header">
        <div>
            <h4 class="mb-0 fw-bold">📄 Static Pages</h4><small class="text-muted">Manage About Us, Terms, Privacy Policy,
                etc.</small>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i
                class="fas fa-plus me-1"></i> Add Page</button>
    </div>

    <table class="pages-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Slug</th>
                <th>Status</th>
                <th>URL</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $item->title }}</strong></td>
                    <td><code>{{ $item->slug }}</code></td>
                    <td><span
                            class="status-dot {{ $item->is_active ? 'active' : 'inactive' }}"></span>{{ $item->is_active ? 'Active' : 'Inactive' }}
                    </td>
                    <td><a href="{{ url('page/' . $item->slug) }}" target="_blank" class="text-primary"><i
                                class="fas fa-external-link-alt"></i> View</a></td>
                    <td>
                        <a href="{{ url('cms/pages/' . $item->id . '/edit') }}" class="btn btn-sm btn-outline-primary"><i
                                class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteItem({{ $item->id }})"><i
                                class="fas fa-trash"></i></button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No pages yet. Click "Add Page" to create one.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="modal fade cms-modal" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ url('cms/pages') }}" method="POST">@csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Static Page</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Title *</label><input type="text"
                                    name="title" class="form-control" required placeholder="About Us"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Slug *</label><input type="text"
                                    name="slug" class="form-control" required placeholder="about-us"></div>
                        </div>
                        <div class="mb-3"><label class="form-label fw-bold">Content *</label><textarea name="content"
                                class="form-control" rows="10" required
                                placeholder="Write page content (HTML supported)..."></textarea></div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Meta Title (SEO)</label><input
                                    type="text" name="meta_title" class="form-control"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Meta Description (SEO)</label><input
                                    type="text" name="meta_description" class="form-control"></div>
                        </div>
                        <div class="form-check"><input type="checkbox" name="is_active" class="form-check-input"
                                checked><label class="form-check-label">Active</label></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Create
                            Page</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer_js')
    <script>
        function deleteItem(id) { if (!confirm('Delete this page?')) return; $.ajax({ url: '{{ url("cms/pages") }}/' + id, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: () => location.reload() }); }
    </script>
@endsection