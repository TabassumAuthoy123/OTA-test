@extends('master')

@section('header_css')
    <style>
        .settings-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }

        .settings-card h5 {
            font-weight: 700;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .settings-card h5 i {
            font-size: 18px;
            color: #084277;
        }

        /* Fix form labels */
        .settings-card .form-label {
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
            font-weight: 600;
        }

        .settings-card .form-control,
        .settings-card .form-select {
            padding: 8px 12px !important;
            height: auto !important;
        }

        .cms-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="cms-header">
        <div>
            <h4 class="mb-0 fw-bold">⚙️ Site Settings</h4>
            <small class="text-muted">Manage hero section, footer content, and social links for the B2C site</small>
        </div>
    </div>

    <form action="{{ url('cms/site-settings') }}" method="POST">
        @csrf

        {{-- Hero Section --}}
        <div class="settings-card">
            <h5><i class="fas fa-star"></i> Hero Section</h5>
            <div class="mb-3">
                <label class="form-label">Badge Text</label>
                <input type="text" name="hero_badge" class="form-control"
                    value="{{ $settings['hero_badge'] ?? 'Trusted by 10,000+ travelers' }}"
                    placeholder="e.g. Trusted by 10,000+ travelers">
                <small class="text-muted">The small badge shown above the hero title</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Hero Title (supports HTML)</label>
                <textarea name="hero_title" class="form-control" rows="3"
                    placeholder="e.g. Find & Book <span>Best Flights</span><br>At Unbeatable Prices">{{ $settings['hero_title'] ?? 'Find & Book <span>Best Flights</span><br>At Unbeatable Prices' }}</textarea>
                <small class="text-muted">Use <code>&lt;span&gt;text&lt;/span&gt;</code> for gradient accent
                    and <code>&lt;br&gt;</code> for line breaks</small>
            </div>
        </div>

        {{-- Footer Contact --}}
        <div class="settings-card">
            <h5><i class="fas fa-address-card"></i> Footer Contact Info</h5>
            <div class="mb-3">
                <label class="form-label">Site Description</label>
                <textarea name="footer_description" class="form-control"
                    rows="2">{{ $settings['footer_description'] ?? '' }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="footer_phone" class="form-control"
                        value="{{ $settings['footer_phone'] ?? '' }}" placeholder="+880-XXXX-XXXXXX">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="footer_email" class="form-control"
                        value="{{ $settings['footer_email'] ?? '' }}" placeholder="support@example.com">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="footer_address" class="form-control"
                        value="{{ $settings['footer_address'] ?? '' }}" placeholder="Dhaka, Bangladesh">
                </div>
            </div>
        </div>

        {{-- Footer Social Links --}}
        <div class="settings-card">
            <h5><i class="fas fa-share-alt"></i> Social Media Links</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fab fa-facebook-f me-1"></i> Facebook URL</label>
                    <input type="url" name="social_facebook" class="form-control"
                        value="{{ $settings['social_facebook'] ?? '' }}" placeholder="https://facebook.com/yourpage">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fab fa-instagram me-1"></i> Instagram URL</label>
                    <input type="url" name="social_instagram" class="form-control"
                        value="{{ $settings['social_instagram'] ?? '' }}" placeholder="https://instagram.com/yourpage">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fab fa-twitter me-1"></i> Twitter URL</label>
                    <input type="url" name="social_twitter" class="form-control"
                        value="{{ $settings['social_twitter'] ?? '' }}" placeholder="https://twitter.com/yourpage">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fab fa-linkedin-in me-1"></i> LinkedIn URL</label>
                    <input type="url" name="social_linkedin" class="form-control"
                        value="{{ $settings['social_linkedin'] ?? '' }}"
                        placeholder="https://linkedin.com/company/yourpage">
                </div>
            </div>
        </div>

        <div class="text-end mb-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-1"></i> Save Settings
            </button>
        </div>
    </form>

    {{-- Payment Methods (separate section — each is a file upload) --}}
    <div class="settings-card">
        <h5><i class="fas fa-credit-card"></i> Payment Gateway Logos</h5>
        <small class="text-muted d-block mb-3">Upload payment method logos (e.g. Visa, bKash, Nagad) that will show in the
            B2C footer</small>

        {{-- Add New --}}
        <form action="{{ url('cms/payment-methods') }}" method="POST" enctype="multipart/form-data"
            class="border rounded p-3 mb-3" style="background: #f8f9fa;">
            @csrf
            <div class="row align-items-end">
                <div class="col-md-3 mb-2">
                    <label class="form-label fw-bold">Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Visa" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-bold">Logo Image *</label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label fw-bold">Position</label>
                    <input type="number" name="position" class="form-control" value="0">
                </div>
                <div class="col-md-1 mb-2 pt-2">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="pmActive" checked>
                        <label class="form-check-label" for="pmActive">Active</label>
                    </div>
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-plus me-1"></i> Add
                    </button>
                </div>
            </div>
        </form>

        {{-- Existing Payment Methods --}}
        @if(isset($paymentMethods) && $paymentMethods->count())
            <div class="row g-3">
                @foreach($paymentMethods as $pm)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="text-center border rounded p-2 position-relative" style="background: #fff;">
                            @if($pm->image)
                                <img src="{{ asset($pm->image) }}" alt="{{ $pm->name }}"
                                    style="max-height: 48px; max-width: 100%; object-fit: contain; margin-bottom: 8px;">
                            @else
                                <div style="height: 48px; display: flex; align-items: center; justify-content: center;
                                                    color: #ccc; margin-bottom: 8px;">
                                    <i class="fas fa-image fa-2x"></i>
                                </div>
                            @endif
                            <div style="font-size: 12px; font-weight: 600;">{{ $pm->name }}</div>
                            <span style="font-size: 10px;" class="badge {{ $pm->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $pm->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <button onclick="deletePayment({{ $pm->id }})" class="btn btn-sm btn-outline-danger mt-1"
                                style="font-size: 11px; padding: 2px 8px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted py-4">
                <i class="fas fa-credit-card fa-2x mb-2 d-block"></i>
                No payment methods yet. Add one above.
            </div>
        @endif
    </div>
@endsection

@section('footer_js')
    <script>
        function deletePayment(id) {
            if (!confirm('Delete this payment method?')) return;
            $.ajax({
                url: '{{ url("cms/payment-methods") }}/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: () => location.reload()
            });
        }
    </script>
@endsection