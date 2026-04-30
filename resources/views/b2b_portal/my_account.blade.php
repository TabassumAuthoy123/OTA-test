@extends('master')

@section('header_css')
<style>
.acct-wrap { max-width: 780px; margin: 40px auto; padding: 0 20px 60px; }

/* ─── Card ─── */
.acct-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(0,0,0,.06);
}
.acct-card-body {
    padding: 32px 36px;
    display: flex;
    gap: 32px;
    align-items: flex-start;
}

/* ─── Info Side ─── */
.acct-info { flex: 1; }
.acct-company-name {
    font-size: 20px;
    font-weight: 800;
    color: #0f1f3d;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f3f4f6;
}
.acct-info-label {
    font-size: 11px;
    font-weight: 700;
    color: #9ca3af;
    letter-spacing: .8px;
    text-transform: uppercase;
    margin-bottom: 10px;
}
.acct-info-row {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    color: #374151;
    margin-bottom: 10px;
}
.acct-info-row i { color: #6b7280; width: 16px; text-align: center; flex-shrink: 0; }
.acct-info-row .val { font-weight: 500; }
.acct-info-row.balance .val { color: #2f9e44; font-weight: 700; font-size: 15px; }

.acct-since {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid #f3f4f6;
}

/* ─── Logo/Actions Side ─── */
.acct-right { flex-shrink: 0; display: flex; flex-direction: column; align-items: center; gap: 12px; }
.acct-logo {
    width: 120px; height: 120px;
    border-radius: 12px;
    border: 2px solid #e9ecef;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    background: #f8f9fa;
}
.acct-logo img { width: 100%; height: 100%; object-fit: contain; }
.acct-logo-placeholder {
    font-size: 36px; font-weight: 900;
    color: #1a3a6b;
    letter-spacing: -1px;
}
.acct-btn {
    width: 160px;
    display: flex; align-items: center; justify-content: center; gap: 7px;
    padding: 9px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all .15s;
    text-decoration: none;
}
.acct-btn-primary {
    background: #1a3a6b;
    color: #fff;
    border: none;
}
.acct-btn-primary:hover { background: #243f73; color: #fff; text-decoration: none; }
.acct-btn-outline {
    background: #fff;
    color: #374151;
    border: 1.5px solid #e5e7eb;
}
.acct-btn-outline:hover { border-color: #1a3a6b; color: #1a3a6b; }

/* ─── Modals ─── */
.acct-modal-backdrop {
    display: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 9990;
    align-items: center;
    justify-content: center;
}
.acct-modal-backdrop.show { display: flex; }
.acct-modal {
    background: #fff;
    border-radius: 14px;
    width: 100%;
    max-width: 480px;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
    overflow: hidden;
}
.acct-modal-header {
    padding: 18px 22px 16px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.acct-modal-header h5 {
    font-size: 16px;
    font-weight: 700;
    color: #0f1f3d;
    margin: 0;
}
.acct-modal-close {
    background: none; border: none; cursor: pointer;
    color: #9ca3af; font-size: 18px;
    transition: color .15s; padding: 2px;
}
.acct-modal-close:hover { color: #374151; }
.acct-modal-body { padding: 22px; }
.acct-modal-footer {
    padding: 14px 22px;
    border-top: 1px solid #f3f4f6;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

/* ─── Modal Brand ─── */
.modal-brand {
    text-align: center;
    font-size: 20px;
    font-weight: 900;
    color: #0f1f3d;
    margin-bottom: 18px;
}
.modal-brand span { color: #f0a500; }

/* ─── Tab Pill ─── */
.modal-tab {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #e8eeff;
    color: #3b5bdb;
    border-radius: 6px;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 18px;
}
.modal-tab i { font-size: 14px; }

/* ─── Form Controls ─── */
.acct-form-group { margin-bottom: 16px; }
.acct-form-label {
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
    display: block;
}
.acct-form-control {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    color: #111827;
    background: #fff;
    outline: none;
    transition: border-color .15s;
}
.acct-form-control:focus { border-color: #1a3a6b; }

/* ─── Toggle Switch ─── */
.toggle-wrap {
    display: flex;
    align-items: center;
    gap: 14px;
    background: #f8f9fa;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    padding: 12px 14px;
}
.toggle-label { font-size: 13px; font-weight: 500; color: #374151; flex: 1; }
.toggle-switch { position: relative; width: 42px; height: 24px; flex-shrink: 0; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
    position: absolute; inset: 0;
    background: #d1d5db;
    border-radius: 24px;
    cursor: pointer;
    transition: .3s;
}
.toggle-slider::before {
    content: '';
    position: absolute;
    width: 18px; height: 18px;
    left: 3px; bottom: 3px;
    background: #fff;
    border-radius: 50%;
    transition: .3s;
    box-shadow: 0 1px 4px rgba(0,0,0,.2);
}
.toggle-switch input:checked + .toggle-slider { background: #1a3a6b; }
.toggle-switch input:checked + .toggle-slider::before { transform: translateX(18px); }

/* ─── Photo Upload ─── */
.photo-upload-area {
    border: 2px dashed #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    text-align: center;
    cursor: pointer;
    transition: border-color .15s;
}
.photo-upload-area:hover { border-color: #1a3a6b; }
.photo-upload-area i { font-size: 22px; color: #9ca3af; margin-bottom: 6px; display: block; }
.photo-upload-area span { font-size: 12px; color: #6b7280; }

/* ─── Password Input Wrapper ─── */
.pw-wrap { position: relative; }
.pw-wrap .acct-form-control { padding-right: 42px; }
.pw-eye {
    position: absolute; right: 12px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none;
    color: #9ca3af; cursor: pointer; font-size: 15px;
}

/* ─── Submit Button ─── */
.acct-submit-btn {
    width: 100%;
    padding: 12px;
    background: #1a3a6b;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: background .15s;
    margin-top: 4px;
}
.acct-submit-btn:hover { background: #243f73; }

@media(max-width: 600px) {
    .acct-card-body { flex-direction: column; padding: 20px; }
    .acct-right { width: 100%; flex-direction: row; align-items: center; flex-wrap: wrap; }
    .acct-btn { width: auto; }
}
</style>
@endsection

@section('content')
<div class="acct-wrap">
    <div class="acct-card">
        <div class="acct-card-body">

            {{-- Left: Info --}}
            <div class="acct-info">
                <div class="acct-company-name">
                    {{ $companyProfile->name ?? (Auth::user()->name . '\'s Account') }}
                </div>

                <div class="acct-info-label">Information</div>

                <div class="acct-info-row">
                    <i class="fas fa-user"></i>
                    <span class="val" id="acct-name-display">{{ $user->name }}</span>
                </div>
                <div class="acct-info-row">
                    <i class="fas fa-envelope"></i>
                    <span class="val">{{ $user->email }}</span>
                </div>
                <div class="acct-info-row">
                    <i class="fas fa-phone"></i>
                    <span class="val" id="acct-phone-display">{{ $user->phone ?? '—' }}</span>
                </div>
                <div class="acct-info-row balance">
                    <i class="fas fa-dollar-sign"></i>
                    <span class="val">Balance: {{ number_format($user->balance ?? 0, 2) }} BDT</span>
                </div>

                <div class="acct-info-label" style="margin-top: 20px;">Account Details</div>

                <div class="acct-since">
                    Member since {{ $user->created_at ? $user->created_at->format('n/j/Y') : '—' }}
                </div>
            </div>

            {{-- Right: Logo + Actions --}}
            <div class="acct-right">
                <div class="acct-logo">
                    @if($companyProfile && $companyProfile->logo && file_exists(public_path($companyProfile->logo)))
                        <img src="{{ asset($companyProfile->logo) }}" alt="Logo">
                    @elseif($user->image && file_exists(public_path($user->image)))
                        <img src="{{ asset($user->image) }}" id="acct-photo-preview" alt="Photo">
                    @else
                        <div class="acct-logo-placeholder" id="acct-avatar-initials">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                </div>

                <button class="acct-btn acct-btn-primary" onclick="document.getElementById('editProfileModal').classList.add('show')">
                    <i class="fas fa-edit"></i> Edit Profile
                </button>
                <button class="acct-btn acct-btn-outline" id="changePwdBtn" onclick="document.getElementById('changePwdModal').classList.add('show')">
                    <i class="fas fa-lock"></i> Change Password
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ═══ Edit Profile Modal ═══ --}}
<div class="acct-modal-backdrop" id="editProfileModal">
    <div class="acct-modal">
        <div class="acct-modal-header">
            <h5>Update Profile</h5>
            <button class="acct-modal-close" onclick="document.getElementById('editProfileModal').classList.remove('show')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="acct-modal-body">
            <div class="modal-tab"><i class="fas fa-user"></i> User Info</div>
            <form id="editProfileForm" enctype="multipart/form-data">
                @csrf
                <div class="acct-form-group">
                    <label class="acct-form-label">Name</label>
                    <input type="text" name="name" class="acct-form-control" value="{{ $user->name }}" required>
                </div>
                <div class="acct-form-group">
                    <label class="acct-form-label">Phone</label>
                    <input type="text" name="phone" class="acct-form-control" value="{{ $user->phone }}">
                </div>
                <div class="acct-form-group">
                    <div class="toggle-wrap">
                        <span class="toggle-label">Enable Two-Factor Authentication (2FA)</span>
                        <label class="toggle-switch">
                            <input type="checkbox" name="two_factor_enabled" id="twoFactorToggle"
                                {{ $user->two_factor_enabled ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
                <div class="acct-form-group">
                    <label class="acct-form-label">User Photo</label>
                    <div class="photo-upload-area" onclick="document.getElementById('photoInput').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span id="photoLabel">Update User Photo</span>
                    </div>
                    <input type="file" name="photo" id="photoInput" style="display:none" accept="image/*"
                        onchange="document.getElementById('photoLabel').textContent = this.files[0]?.name || 'Update User Photo'">
                </div>
                <div class="modal-brand">Fanam<span>Trip</span></div>
                <button type="submit" class="acct-submit-btn">Submit</button>
            </form>
        </div>
    </div>
</div>

{{-- ═══ Change Password Modal ═══ --}}
<div class="acct-modal-backdrop" id="changePwdModal">
    <div class="acct-modal">
        <div class="acct-modal-header">
            <h5>Change Password</h5>
            <button class="acct-modal-close" onclick="document.getElementById('changePwdModal').classList.remove('show')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="acct-modal-body">
            <form id="changePwdForm">
                @csrf
                <div class="acct-form-group">
                    <label class="acct-form-label">* Old Password</label>
                    <div class="pw-wrap">
                        <input type="password" name="old_password" id="oldPwd" class="acct-form-control" required>
                        <button type="button" class="pw-eye" onclick="togglePwd('oldPwd',this)"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                <div class="acct-form-group">
                    <label class="acct-form-label">* New Password</label>
                    <div class="pw-wrap">
                        <input type="password" name="new_password" id="newPwd" class="acct-form-control" required>
                        <button type="button" class="pw-eye" onclick="togglePwd('newPwd',this)"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                <button type="submit" class="acct-submit-btn">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer_js')
<script>
// Edit Profile
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Saving...';

    const fd = new FormData(this);
    // Checkbox value
    fd.set('two_factor_enabled', document.getElementById('twoFactorToggle').checked ? '1' : '0');

    fetch('{{ url("my/account/update-profile") }}', {
        method: 'POST',
        body: fd,
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('acct-name-display').textContent  = data.name;
            document.getElementById('acct-phone-display').textContent = data.phone || '—';
            document.getElementById('editProfileModal').classList.remove('show');
            toastr.success(data.message);
        } else {
            toastr.error(data.message || 'Update failed.');
        }
    })
    .catch(() => toastr.error('Something went wrong.'))
    .finally(() => { btn.disabled = false; btn.textContent = 'Submit'; });
});

// Change Password
document.getElementById('changePwdForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Saving...';

    const fd = new FormData(this);
    fetch('{{ url("my/account/change-password") }}', {
        method: 'POST',
        body: fd,
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('changePwdModal').classList.remove('show');
            this.reset();
            toastr.success(data.message);
        } else {
            toastr.error(data.message || 'Failed to change password.');
        }
    })
    .catch(() => toastr.error('Something went wrong.'))
    .finally(() => { btn.disabled = false; btn.textContent = 'Submit'; });
});

// Toggle password visibility
function togglePwd(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Close modal on backdrop click
document.querySelectorAll('.acct-modal-backdrop').forEach(bd => {
    bd.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('show');
    });
});
</script>
@endsection
