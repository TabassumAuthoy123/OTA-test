@extends('b2c.layouts.master')

@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description ?? '')

@section('content')

    {{-- Hero Banner --}}
    <section class="b2c-policy-hero">
        <div class="container">
            <div class="b2c-policy-hero-content">
                <h1>{{ $page->title }}</h1>
                <p class="b2c-policy-meta">
                    <i class="fas fa-calendar-alt"></i> Last Updated: {{ $page->updated_at->format('F d, Y') }}
                    <span class="b2c-policy-divider">|</span>
                    <i class="fas fa-clock"></i> {{ ceil(str_word_count(strip_tags($page->content)) / 200) }} min read
                </p>
            </div>
        </div>
    </section>

    {{-- Content Section --}}
    <section class="b2c-section" style="background: var(--b2c-bg); padding-top: 0;">
        <div class="container">
            <div class="b2c-policy-layout">
                {{-- Sidebar TOC --}}
                <aside class="b2c-policy-sidebar" id="policySidebar">
                    <div class="b2c-policy-toc">
                        <h4><i class="fas fa-list-ul"></i> Table of Contents</h4>
                        <nav id="tocNav"></nav>
                    </div>
                </aside>

                {{-- Main Content --}}
                <article class="b2c-policy-content" id="policyContent">
                    {!! $page->content !!}
                </article>
            </div>
        </div>
    </section>

    {{-- Quick Help Banner --}}
    <section class="b2c-policy-help">
        <div class="container">
            <div class="b2c-policy-help-card">
                <div class="b2c-policy-help-text">
                    <h3><i class="fas fa-headset"></i> Have Questions?</h3>
                    <p>Our support team is available 24/7 to help you with any queries about our policies.</p>
                </div>
                <div class="b2c-policy-help-actions">
                    <a href="tel:+8801XXX-XXXXXX" class="b2c-help-btn-cta"><i class="fas fa-phone-alt"></i> Call Support</a>
                    <a href="https://wa.me/8801XXXXXXXXX" class="b2c-help-btn-outline"><i class="fab fa-whatsapp"></i>
                        WhatsApp</a>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* ━━━ POLICY PAGE STYLES ━━━ */
        .b2c-policy-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #1a1f3d 100%);
            padding: 120px 0 50px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .b2c-policy-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 80%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(244, 114, 182, 0.1) 0%, transparent 50%);
        }

        .b2c-policy-hero-content {
            position: relative;
            z-index: 1;
        }

        .b2c-policy-hero h1 {
            color: #fff;
            font-size: 2.4rem;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .b2c-policy-meta {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        .b2c-policy-meta i {
            margin-right: 4px;
        }

        .b2c-policy-divider {
            margin: 0 12px;
            opacity: 0.4;
        }

        /* Layout */
        .b2c-policy-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 40px;
            padding-top: 40px;
            align-items: start;
        }

        /* Sidebar TOC */
        .b2c-policy-sidebar {
            position: sticky;
            top: 100px;
        }

        .b2c-policy-toc {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .b2c-policy-toc h4 {
            color: #1e293b;
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .b2c-policy-toc h4 i {
            margin-right: 8px;
            color: #6366f1;
        }

        .b2c-policy-toc a {
            display: block;
            color: #64748b;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            line-height: 1.4;
            transition: all 0.2s;
            border-left: 2px solid transparent;
        }

        .b2c-policy-toc a:hover,
        .b2c-policy-toc a.active {
            background: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            border-left-color: #6366f1;
        }

        /* Content */
        .b2c-policy-content {
            background: #ffffff;
            border-radius: 16px;
            padding: 48px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            line-height: 1.9;
            color: #475569;
        }

        .b2c-policy-content h2 {
            color: #1e293b;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 40px 0 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            scroll-margin-top: 100px;
        }

        .b2c-policy-content h2:first-child {
            margin-top: 0;
        }

        .b2c-policy-content h3 {
            color: #1e293b;
            font-size: 1.15rem;
            font-weight: 600;
            margin: 28px 0 10px;
        }

        .b2c-policy-content p {
            margin-bottom: 14px;
        }

        .b2c-policy-content ul,
        .b2c-policy-content ol {
            margin: 12px 0 20px 20px;
        }

        .b2c-policy-content li {
            margin-bottom: 8px;
            padding-left: 4px;
        }

        .b2c-policy-content strong {
            color: #1e293b;
        }

        .b2c-policy-content .policy-highlight {
            background: rgba(99, 102, 241, 0.08);
            border-left: 4px solid #6366f1;
            padding: 16px 20px;
            border-radius: 0 12px 12px 0;
            margin: 20px 0;
        }

        .b2c-policy-content .policy-warning {
            background: rgba(239, 68, 68, 0.08);
            border-left: 4px solid #EF4444;
            padding: 16px 20px;
            border-radius: 0 12px 12px 0;
            margin: 20px 0;
        }

        .b2c-policy-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border-radius: 12px;
            overflow: hidden;
        }

        .b2c-policy-content table th {
            background: #6366f1;
            color: #fff;
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .b2c-policy-content table td {
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.9rem;
        }

        .b2c-policy-content table tr:nth-child(even) td {
            background: rgba(99, 102, 241, 0.04);
        }

        /* Help Banner */
        .b2c-policy-help {
            padding: 40px 0 60px;
            background: #f1f5f9;
        }

        .b2c-policy-help-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #1a1f3d 100%);
            border-radius: 16px;
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .b2c-policy-help-text h3 {
            color: #ffffff;
            font-size: 1.3rem;
            margin-bottom: 8px;
        }

        .b2c-policy-help-text h3 i {
            margin-right: 8px;
            color: #6366f1;
        }

        .b2c-policy-help-text p {
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
        }

        .b2c-policy-help-actions {
            display: flex;
            gap: 12px;
            flex-shrink: 0;
        }

        .b2c-help-btn-cta {
            background: #6366f1;
            color: #fff !important;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .b2c-help-btn-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
        }

        .b2c-help-btn-outline {
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff !important;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .b2c-help-btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .b2c-policy-layout {
                grid-template-columns: 1fr;
            }

            .b2c-policy-sidebar {
                position: relative;
                top: 0;
            }
        }

        @media (max-width: 768px) {
            .b2c-policy-hero h1 {
                font-size: 1.6rem;
            }

            .b2c-policy-content {
                padding: 24px;
            }

            .b2c-policy-help-card {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>

    <script>
        // Auto-generate Table of Contents from h2 headings
        document.addEventListener('DOMContentLoaded', function () {
            var content = document.getElementById('policyContent');
            var tocNav = document.getElementById('tocNav');
            if (!content || !tocNav) return;

            var headings = content.querySelectorAll('h2');

            for (var i = 0; i < headings.length; i++) {
                var heading = headings[i];
                var id = 'section-' + i;
                heading.id = id;

                var link = document.createElement('a');
                link.href = '#' + id;
                link.textContent = heading.textContent;
                tocNav.appendChild(link);
            }

            // Highlight active TOC item on scroll
            var tocLinks = tocNav.querySelectorAll('a');
            window.addEventListener('scroll', function () {
                var current = '';
                for (var j = 0; j < headings.length; j++) {
                    if (window.scrollY >= headings[j].offsetTop - 120) {
                        current = headings[j].id;
                    }
                }
                for (var k = 0; k < tocLinks.length; k++) {
                    if (tocLinks[k].getAttribute('href') === '#' + current) {
                        tocLinks[k].classList.add('active');
                    } else {
                        tocLinks[k].classList.remove('active');
                    }
                }
            });
        });
    </script>
@endsection