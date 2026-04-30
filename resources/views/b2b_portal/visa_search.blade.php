@extends('master')

@section('header_css')
<style>
.vsearch-wrap { padding: 0 0 40px; }

/* ─── Hero ─── */
.vsearch-hero {
    background: linear-gradient(135deg, #0f4c75 0%, #0d2137 100%);
    padding: 60px 36px;
    text-align: center;
    margin-bottom: 0;
    position: relative;
    overflow: hidden;
}
.vsearch-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.vsearch-hero h2 {
    font-size: 44px;
    font-weight: 900;
    color: #fff;
    margin: 0 0 24px;
    position: relative;
}

/* ─── Search Box ─── */
.vsearch-box {
    background: rgba(255,255,255,.1);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 14px;
    padding: 24px 28px;
    max-width: 860px;
    margin: 0 auto;
    position: relative;
}
.vsearch-grid {
    display: grid;
    grid-template-columns: 2fr 2fr 1fr auto;
    gap: 14px;
    align-items: end;
}
.vf-label {
    font-size: 11px;
    font-weight: 700;
    color: rgba(255,255,255,.7);
    letter-spacing: .5px;
    text-transform: uppercase;
    margin-bottom: 6px;
    display: block;
}
.vf-control {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid rgba(255,255,255,.3);
    border-radius: 8px;
    font-size: 13px;
    color: #fff;
    background: rgba(255,255,255,.12);
    outline: none;
    transition: border-color .15s;
}
.vf-control:focus { border-color: rgba(255,255,255,.7); }
.vf-control option { color: #111; background: #fff; }
.vf-control::placeholder { color: rgba(255,255,255,.5); }
.vf-travelers {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,.12);
    border: 1.5px solid rgba(255,255,255,.3);
    border-radius: 8px;
    padding: 8px 12px;
    color: #fff;
    font-size: 13px;
}
.vf-travelers i { color: rgba(255,255,255,.7); }
.vsearch-btn {
    padding: 10px 24px;
    background: #fff;
    color: #0f1f3d;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    transition: all .15s;
}
.vsearch-btn:hover { background: #f0a500; color: #fff; }

/* ─── Range hint ─── */
.vsearch-range-hint {
    font-size: 12px;
    color: rgba(255,255,255,.5);
    margin-top: 10px;
    text-align: left;
}

/* ─── Results Section ─── */
.vsearch-results-section { padding: 32px 28px; }
.vsearch-result-heading {
    font-size: 22px;
    font-weight: 800;
    color: #0f1f3d;
    margin-bottom: 6px;
}
.vsearch-result-sub {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 24px;
}

/* ─── Visa Type Cards ─── */
.vtype-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}
.vtype-card {
    background: #fff;
    border: 1.5px solid #e9ecef;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    transition: all .2s;
}
.vtype-card:hover { border-color: #1a3a6b; box-shadow: 0 4px 16px rgba(26,58,107,.1); transform: translateY(-2px); }
.vtype-icon { font-size: 32px; margin-bottom: 10px; }
.vtype-name { font-size: 14px; font-weight: 700; color: #0f1f3d; margin-bottom: 6px; }
.vtype-stat { font-size: 12px; color: #6b7280; margin-bottom: 2px; }
.vtype-badge {
    display: inline-block;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    padding: 3px 8px;
    border-radius: 20px;
    margin-top: 8px;
}
.vtype-badge.ok { background: #dcfce7; color: #166534; }
.vtype-badge.pending { background: #fef9c3; color: #854d0e; }

/* ─── Application Guide ─── */
.vguide-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 24px 28px;
}
.vguide-card h4 {
    font-size: 16px;
    font-weight: 700;
    color: #0f1f3d;
    margin-bottom: 4px;
}
.vguide-card p {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 18px;
}
.vguide-checklist { list-style: none; padding: 0; margin: 0; }
.vguide-checklist li {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #374151;
    padding: 8px 0;
    border-bottom: 1px solid #f3f4f6;
}
.vguide-checklist li:last-child { border: none; }
.vguide-checklist li i { color: #2f9e44; font-size: 14px; flex-shrink: 0; }

/* ─── Apply Button ─── */
.vapply-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 20px;
    padding: 11px 24px;
    background: #1a3a6b;
    color: #fff;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    transition: background .15s;
}
.vapply-btn:hover { background: #243f73; color: #fff; text-decoration: none; }

/* ─── Empty State ─── */
.vsearch-empty {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 48px 24px;
    text-align: center;
    color: #9ca3af;
}
.vsearch-empty i { font-size: 48px; margin-bottom: 14px; opacity: .3; display: block; }
.vsearch-empty h4 { font-size: 18px; color: #374151; font-weight: 700; margin-bottom: 6px; }
.vsearch-empty p { font-size: 14px; }
.vsearch-tip {
    font-size: 12px;
    color: rgba(255,255,255,.45);
    margin-top: 10px;
    text-align: left;
}

@media(max-width: 768px) {
    .vsearch-grid { grid-template-columns: 1fr 1fr; }
    .vsearch-hero { padding: 36px 20px; }
    .vsearch-hero h2 { font-size: 28px; }
    .vsearch-results-section { padding: 20px 16px; }
    .vtype-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>
@endsection

@section('content')
<div class="vsearch-wrap">

    {{-- Hero with Search Form --}}
    <div class="vsearch-hero">
        <h2>Welcome to<br>Visa Search</h2>

        <form method="GET" action="{{ url('visa-search') }}">
            <div class="vsearch-box">
                <div class="vsearch-grid">
                    <div>
                        <label class="vf-label">Country</label>
                        <select name="country" class="vf-control">
                            <option value="">Select a country</option>
                            @foreach($countries as $c)
                                <option value="{{ $c }}" {{ request('country') == $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="vf-label">Travel dates</label>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                            <input type="date" name="start_date" class="vf-control"
                                value="{{ request('start_date', date('Y-m-d')) }}">
                            <input type="date" name="end_date" class="vf-control"
                                value="{{ request('end_date', date('Y-m-d', strtotime('+30 days'))) }}">
                        </div>
                    </div>
                    <div>
                        <label class="vf-label">Travelers</label>
                        <div class="vf-travelers">
                            <i class="fas fa-user"></i>
                            <select name="travelers" style="background:transparent;border:none;color:#fff;outline:none;font-size:13px;flex:1;">
                                @for($t = 1; $t <= 9; $t++)
                                    <option value="{{ $t }}" {{ request('travelers', 1) == $t ? 'selected' : '' }} style="color:#111;background:#fff;">
                                        {{ $t }} traveler{{ $t > 1 ? 's' : '' }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="vsearch-btn">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
                @if(request('country') && request('start_date') && request('end_date'))
                    <div class="vsearch-range-hint">
                        {{ request('travelers', 1) }} traveler{{ request('travelers', 1) > 1 ? 's' : '' }}
                        &bull;
                        {{ \Carbon\Carbon::parse(request('start_date'))->format('M d, Y') }}
                        to
                        {{ \Carbon\Carbon::parse(request('end_date'))->format('M d, Y') }}
                    </div>
                @else
                    <div class="vsearch-tip">Tip: Dates default to today → next 30 days.</div>
                @endif
            </div>
        </form>
    </div>

    {{-- Results --}}
    <div class="vsearch-results-section">
        @if(!$searched)
            {{-- Default hint card --}}
            <div class="vsearch-empty">
                <i class="far fa-id-card"></i>
                <h4>Search for visas</h4>
                <p>Choose a country, travel dates, and travelers. Then hit Search.</p>
                <p style="font-size:12px;margin-top:4px;">Tip: Dates default to today → next 30 days.</p>
            </div>
        @elseif($results->count() > 0)
            <div class="vsearch-result-heading">Visa information for {{ request('country') }}</div>
            <div class="vsearch-result-sub">
                Based on {{ $results->sum('total') }} application(s) processed through our system.
            </div>

            <div class="vtype-grid">
                @foreach($results as $r)
                @php
                    $icons = ['tourist'=>'🏖️','business'=>'💼','student'=>'🎓','pilgrimage'=>'🕌','medical'=>'🏥','other'=>'📋'];
                    $icon = $icons[$r->visa_type] ?? '📋';
                @endphp
                <div class="vtype-card">
                    <div class="vtype-icon">{{ $icon }}</div>
                    <div class="vtype-name">{{ ucfirst($r->visa_type) }} Visa</div>
                    <div class="vtype-stat">{{ $r->total }} application(s)</div>
                    <div class="vtype-stat">{{ $r->approved }} approved</div>
                    @if($r->pending > 0)
                        <span class="vtype-badge pending">{{ $r->pending }} Pending</span>
                    @else
                        <span class="vtype-badge ok">✓ Processing</span>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="vguide-card">
                <h4>How to apply for {{ request('country') }} visa</h4>
                <p>Our team handles your visa application end-to-end. Here's what you'll need:</p>
                <ul class="vguide-checklist">
                    <li><i class="fas fa-check-circle"></i> Valid passport (min. 6 months validity)</li>
                    <li><i class="fas fa-check-circle"></i> Passport-size photographs (recent, white background)</li>
                    <li><i class="fas fa-check-circle"></i> Completed visa application form</li>
                    <li><i class="fas fa-check-circle"></i> Confirmed flight tickets and hotel bookings</li>
                    <li><i class="fas fa-check-circle"></i> Bank statements (last 3 months)</li>
                    <li><i class="fas fa-check-circle"></i> Travel insurance (if required)</li>
                </ul>
                <a href="{{ url('my/visa-applications/create') }}" class="vapply-btn">
                    <i class="far fa-id-card"></i> Apply for Visa Now
                </a>
            </div>

        @else
            <div class="vsearch-empty">
                <i class="far fa-id-card"></i>
                <h4>No visa data found for {{ request('country') }}</h4>
                <p>We haven't processed any applications for this destination yet.<br>
                You can still apply — our team will guide you through the process.</p>
                <a href="{{ url('my/visa-applications/create') }}" class="vapply-btn" style="display:inline-flex;margin-top:16px;">
                    <i class="far fa-id-card"></i> Apply Now
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
