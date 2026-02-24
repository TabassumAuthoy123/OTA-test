@extends('master')

@section('header_css')
    <style>
        .cms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 16px;
        }

        .cms-item {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }

        .cms-item .faq-q {
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 8px;
            color: #1a1a2e;
        }

        .cms-item .faq-a {
            font-size: 13px;
            color: #555;
            line-height: 1.6;
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

        /* Fix modal form labels */
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
            <h4 class="mb-0 fw-bold">❓ FAQs</h4>
            <small class="text-muted">Manage frequently asked questions for the B2C site</small>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus me-1"></i> Add FAQ
        </button>
    </div>

    <div class="cms-grid">
        @forelse($items as $item)
            <div class="cms-item">
                <div class="faq-q">Q: {{ $item->question }}</div>
                <div class="faq-a">{{ Str::limit($item->answer, 200) }}</div>
                <div class="meta mt-2">
                    <span class="status-dot {{ $item->is_active ? 'active' : 'inactive' }}"></span>
                    {{ $item->is_active ? 'Active' : 'Inactive' }} · Position: {{ $item->position }}
                </div>
                <div class="actions">
                    <button class="btn btn-sm btn-outline-primary" onclick="editItem({{ json_encode($item) }})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteItem({{ $item->id }})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5" style="grid-column:1/-1;">
                <i class="fas fa-question-circle fa-3x mb-3 d-block"></i>
                No FAQs yet. Click "Add FAQ" to create one.
            </div>
        @endforelse
    </div>

    {{-- Add Modal --}}
    <div class="modal fade cms-modal" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ url('cms/faqs') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add FAQ</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label fw-bold">Question *</label><input type="text"
                                name="question" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label fw-bold">Answer *</label><textarea name="answer"
                                class="form-control" rows="4" required></textarea></div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Position</label><input type="number"
                                    name="position" class="form-control" value="0"></div>
                        </div>
                        <div class="form-check"><input type="checkbox" name="is_active" class="form-check-input"
                                id="addActive" checked><label class="form-check-label" for="addActive">Active</label></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">Cancel</button><button type="submit"
                            class="btn btn-primary">Create</button></div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade cms-modal" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit FAQ</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label fw-bold">Question *</label><input type="text"
                                name="question" id="eQuestion" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label fw-bold">Answer *</label><textarea name="answer"
                                id="eAnswer" class="form-control" rows="4" required></textarea></div>
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
            $('#editForm').attr('action', '{{ url('cms/faqs') }}/' + item.id);
            $('#eQuestion').val(item.question);
            $('#eAnswer').val(item.answer);
            $('#ePos').val(item.position);
            $('#eActive').prop('checked', item.is_active);
            $('#editModal').modal('show');
        }

        function deleteItem(id) {
            if (!confirm('Delete this FAQ?')) return;
            $.ajax({
                url: '{{ url('cms/faqs') }}/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: () => location.reload()
            });
        }
    </script>
@endsection