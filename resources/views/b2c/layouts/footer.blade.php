@php
    use App\Models\CmsSiteSetting;
    use App\Models\CmsPaymentMethod;
    use Illuminate\Support\Facades\DB;
    $footerSettings  = CmsSiteSetting::allAsArray();
    $paymentMethods  = CmsPaymentMethod::active()->get();
    $b2cFooterInfo   = DB::table('b2c_footer_infos')->first();
    $b2cSocialLinks  = DB::table('social_media_links')->orderBy('name')->get();
    $b2cCompanyInfo  = $b2cFooterInfo ? $b2cFooterInfo->company_info : null;
    $b2cCompanyLinks = $b2cFooterInfo ? json_decode($b2cFooterInfo->company_links ?? '[]', true) : [];
    $b2cSupportLinks = $b2cFooterInfo ? json_decode($b2cFooterInfo->support_links ?? '[]', true) : [];
    $b2cPayMethods   = $b2cFooterInfo ? json_decode($b2cFooterInfo->payment_methods ?? '[]', true) : [];
    $iataNumber      = $footerSettings['iata_number'] ?? '42344724';
    $officeAddress   = $footerSettings['footer_address'] ?? 'Abedin Tower (Level 5), 35 Kamal Ataturk Avenue, Banani, Dhaka-1213';
    $officeName      = $footerSettings['office_name'] ?? 'FaithTrip Office (Dhaka)';
    $sitePhone       = $footerSettings['footer_phone'] ?? '+880 9678 189188';
    $siteEmail       = $footerSettings['footer_email'] ?? 'info@faithtrip.net';
@endphp

<footer class="ft-footer">
    <div class="container">
        <div class="row g-5">

            {{-- Column 1: Brand --}}
            <div class="col-lg-4 col-md-6">
                <a href="{{ url('/') }}" class="ft-footer-logo">
                    <svg width="36" height="36" viewBox="0 0 42 42" fill="none">
                        <circle cx="21" cy="21" r="21" fill="#0D1B5E"/>
                        <path d="M7 30 Q21 17 35 30" stroke="#C62828" stroke-width="2.8" fill="none" stroke-linecap="round"/>
                        <polygon points="31,8 37,13 30,15" fill="#F5A623"/>
                        <text x="9" y="20.5" font-family="Arial,sans-serif" font-weight="700" font-size="7.5" fill="white">Faith</text>
                        <text x="9" y="29.5" font-family="Arial,sans-serif" font-weight="700" font-size="7.5" fill="#F5A623">Trip</text>
                    </svg>
                    <span style="font-family:'Poppins',sans-serif;font-weight:800;font-size:1.3rem;line-height:1;">
                        <span style="color:#0D1B5E;">Faith</span><span style="color:#F5A623;">Trip</span>
                    </span>
                </a>
                <p class="ft-footer-desc">
                    {{ $b2cCompanyInfo ?? $footerSettings['footer_description'] ?? 'FaithTrip is committed to delivering exceptional travel experiences. As an IATA-affiliated travel agency, we offer flights, tours, visa, hotels and more — ensuring you get the best deals with the highest quality service.' }}
                </p>

                {{-- Emails --}}
                <div style="margin-bottom:16px;">
                    <div style="font-weight:700;font-size:.82rem;color:#0D1B5E;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;">Email Us</div>
                    <div style="display:flex;flex-direction:column;gap:4px;">
                        @foreach(['info@faithtrip.net','marketing1@faithtrip.net','director@faithtrip.net','it@faithtrip.net'] as $mail)
                        <a href="mailto:{{ $mail }}" style="font-size:.82rem;color:#555;text-decoration:none;display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-envelope" style="color:#F5A623;font-size:.7rem;flex-shrink:0;"></i>{{ $mail }}
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Social --}}
                <div>
                    <div style="font-weight:700;font-size:.82rem;color:#0D1B5E;margin-bottom:10px;text-transform:uppercase;letter-spacing:.5px;">Connect With Us</div>
                    <div class="ft-footer-social">
                        @if($b2cSocialLinks->count())
                            @foreach($b2cSocialLinks as $sm)
                            <a href="{{ $sm->link ?? '#' }}" target="_blank" title="{{ $sm->name }}">
                                        @if($sm->logo)
                                    <img src="{{ asset('uploads/social/'.$sm->logo) }}" style="width:18px;height:18px;border-radius:50%;object-fit:cover;" alt="{{ $sm->name }}">
                                @else
                                    @php
                                        $smN = strtolower($sm->name ?? '');
                                        if      (str_contains($smN,'facebook'))  $smIcon = 'fa-facebook-f';
                                        elseif  (str_contains($smN,'instagram')) $smIcon = 'fa-instagram';
                                        elseif  (str_contains($smN,'youtube'))   $smIcon = 'fa-youtube';
                                        elseif  (str_contains($smN,'tiktok'))    $smIcon = 'fa-tiktok';
                                        elseif  (str_contains($smN,'pinterest')) $smIcon = 'fa-pinterest-p';
                                        elseif  (str_contains($smN,'twitter') || str_contains($smN,'x.com')) $smIcon = 'fa-twitter';
                                        elseif  (str_contains($smN,'linkedin'))  $smIcon = 'fa-linkedin-in';
                                        else                                     $smIcon = 'fa-globe';
                                    @endphp
                                    <i class="fab {{ $smIcon }}"></i>
                                @endif
                            </a>
                            @endforeach
                        @else
                            <a href="{{ $footerSettings['social_facebook']  ?? '#' }}" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="{{ $footerSettings['social_twitter']   ?? '#' }}" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="{{ $footerSettings['social_instagram'] ?? '#' }}" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="{{ $footerSettings['social_youtube']   ?? '#' }}" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
                            <a href="{{ $footerSettings['social_pinterest'] ?? '#' }}" target="_blank" title="Pinterest"><i class="fab fa-pinterest-p"></i></a>
                            <a href="{{ $footerSettings['social_tiktok']    ?? '#' }}" target="_blank" title="TikTok"><i class="fab fa-tiktok"></i></a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Column 2: Company --}}
            <div class="col-lg-2 col-md-3 col-6">
                <h5 class="ft-footer-heading">Company</h5>
                <ul class="ft-footer-links">
                    @if(!empty($b2cCompanyLinks))
                        @foreach($b2cCompanyLinks as $cl)
                        <li><a href="{{ $cl['url'] ?? '#' }}">{{ $cl['label'] ?? '' }}</a></li>
                        @endforeach
                    @else
                        <li><a href="{{ url('/page/about') }}">About Us</a></li>
                        <li><a href="{{ url('/page/contact') }}">Contact Us</a></li>
                        <li><a href="{{ url('/page/terms') }}">Terms &amp; Conditions</a></li>
                        <li><a href="{{ url('/page/privacy') }}">Privacy Policy</a></li>
                    @endif
                </ul>
            </div>

            {{-- Column 3: Support --}}
            <div class="col-lg-2 col-md-3 col-6">
                <h5 class="ft-footer-heading">Support</h5>
                <ul class="ft-footer-links">
                    @if(!empty($b2cSupportLinks))
                        @foreach($b2cSupportLinks as $sl)
                        <li><a href="{{ $sl['url'] ?? '#' }}">{{ $sl['label'] ?? '' }}</a></li>
                        @endforeach
                    @else
                        <li>
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                                <div style="width:30px;height:30px;background:#f5f5f5;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-phone-alt" style="font-size:11px;color:#0D1B5E;"></i>
                                </div>
                                <a href="tel:{{ preg_replace('/\s+/','',$sitePhone) }}" style="font-size:.87rem;color:#555;text-decoration:none;">{{ $sitePhone }}</a>
                            </div>
                        </li>
                        <li>
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                                <div style="width:30px;height:30px;background:#f5f5f5;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-envelope" style="font-size:11px;color:#0D1B5E;"></i>
                                </div>
                                <a href="mailto:{{ $siteEmail }}" style="font-size:.82rem;color:#555;text-decoration:none;word-break:break-all;">{{ $siteEmail }}</a>
                            </div>
                        </li>
                        <li><a href="{{ url('/page/faq') }}" style="margin-top:6px;display:inline-block;">FAQ</a></li>
                        <li><a href="{{ url('/page/contact') }}">Contact Us</a></li>
                    @endif
                </ul>
            </div>

            {{-- Column 4: Address + Payment --}}
            <div class="col-lg-4 col-md-6">
                <h5 class="ft-footer-heading">Address</h5>
                <div style="display:flex;gap:12px;align-items:flex-start;margin-bottom:18px;">
                    <div style="width:34px;height:34px;background:#f5f5f5;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
                        <i class="fas fa-map-marker-alt" style="font-size:12px;color:#0D1B5E;"></i>
                    </div>
                    <div style="font-size:.88rem;color:#555;line-height:1.7;">
                        <strong style="color:#0D1B5E;font-size:.9rem;">{{ $officeName }}</strong><br>
                        {!! nl2br(e($officeAddress)) !!}
                    </div>
                </div>

                {{-- Business hours --}}
                <div style="display:flex;gap:12px;align-items:flex-start;margin-bottom:20px;">
                    <div style="width:34px;height:34px;background:#f5f5f5;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
                        <i class="fas fa-clock" style="font-size:12px;color:#0D1B5E;"></i>
                    </div>
                    <div style="font-size:.85rem;color:#555;line-height:1.7;">
                        <strong style="color:#0D1B5E;font-size:.87rem;">Business Hours</strong><br>
                        Sun – Thu: 9:00 AM – 6:00 PM<br>
                        Fri – Sat: Closed
                    </div>
                </div>

                <div class="ft-we-accept-label">We Accept</div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @if(!empty($b2cPayMethods))
                        @foreach($b2cPayMethods as $pm)
                            @if(!empty($pm['logo']))
                                <img src="{{ Str::startsWith($pm['logo'],'http') ? $pm['logo'] : asset($pm['logo']) }}"
                                     alt="{{ $pm['name'] ?? '' }}" style="height:28px;object-fit:contain;border:1px solid #eee;border-radius:4px;padding:2px 6px;background:#fff;">
                            @endif
                        @endforeach
                    @elseif($paymentMethods->count())
                        @foreach($paymentMethods as $pm)
                            <img src="{{ asset($pm->image) }}" alt="{{ $pm->name }}" style="height:28px;object-fit:contain;">
                        @endforeach
                    @else
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/200px-Visa_Inc._logo.svg.png"
                             alt="Visa" style="height:26px;background:#fff;border-radius:4px;padding:2px 8px;border:1px solid #eee;">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/MasterCard_Logo.svg/200px-MasterCard_Logo.svg.png"
                             alt="Mastercard" style="height:26px;background:#fff;border-radius:4px;padding:2px 8px;border:1px solid #eee;">
                    @endif
                </div>
            </div>

        </div>{{-- /row --}}
    </div>{{-- /container --}}

    {{-- Certified By / IATA --}}
    <div class="ft-footer-certified">
        <span>Certified By</span>
        <div style="width:40px;height:40px;border:1.5px solid #ddd;border-radius:50%;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-star" style="color:#F5A623;font-size:16px;"></i>
        </div>
        <div class="ft-iata-badge">IATA: {{ $iataNumber }}</div>
    </div>

    {{-- Copyright --}}
    <div class="ft-footer-copyright">
        <p>
            &copy; {{ date('Y') }} <a href="{{ url('/') }}">FaithTrip</a>. All rights reserved.<br>
            <span style="font-size:.78rem;opacity:.7;">
                Abedin Tower (Level 5), 35 Kamal Ataturk Avenue, Banani, Dhaka-1213 &nbsp;|&nbsp;
                <a href="mailto:info@faithtrip.net" style="color:#F5A623;">info@faithtrip.net</a>
            </span>
        </p>
    </div>
</footer>
