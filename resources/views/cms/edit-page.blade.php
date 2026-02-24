@extends('master')
@section('header_css')
    <style>
        .cms-modal .form-label {
            position: static !important;
            transform: none !important;
            pointer-events: auto !important;
            padding: 0 0 4px 0 !important;
            height: auto !important;
            font-size: 13px !important;
        }

        .cms-modal .form-control,
        .cms-modal textarea {
            padding: 8px 12px !important;
            height: auto !important;
        }

        .edit-page-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }
    </style>
@endsection

@section('content')
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <div>
            <h4 class="mb-0 fw-bold">✏️ Edit Page: {{ $page->title }}</h4>
        </div>
        <a href="{{ url('cms/pages') }}" class="btn btn-light btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to
            Pages</a>
    </div>

    <div class="edit-page-card cms-modal">
        <form action="{{ url('cms/pages/' . $page->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label fw-bold">Title *</label><input type="text" name="title"
                        class="form-control" value="{{ $page->title }}" required></div>
                <div class="col-md-6 mb-3"><label class="form-label fw-bold">Slug *</label><input type="text" name="slug"
                        class="form-control" value="{{ $page->slug }}" required></div>
            </div>
            <div class="mb-3"><label class="form-label fw-bold">Content *</label><textarea name="content"
                    class="form-control" rows="15" required>{{ $page->content }}</textarea></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label fw-bold">Meta Title (SEO)</label><input type="text"
                        name="meta_title" class="form-control" value="{{ $page->meta_title }}"></div>
                <div class="col-md-6 mb-3"><label class="form-label fw-bold">Meta Description (SEO)</label><input
                        type="text" name="meta_description" class="form-control" value="{{ $page->meta_description }}">
                </div>
            </div>
            <div class="form-check mb-3"><input type="checkbox" name="is_active" class="form-check-input" {{ $page->is_active ? 'checked' : '' }}><label class="form-check-label">Active</label></div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Save Changes</button>
            <a href="{{ url('page/' . $page->slug) }}" target="_blank" class="btn btn-outline-secondary ms-2"><i
                    class="fas fa-external-link-alt me-1"></i> Preview</a>
        </form>
    </div>
@endsection