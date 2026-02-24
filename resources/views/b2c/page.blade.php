@extends('b2c.layouts.master')

@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description ?? '')

@section('content')
    {{-- Spacer for fixed navbar --}}
    <div style="height: 80px;"></div>

    <section class="b2c-section">
        <div class="container">
            <div style="max-width: 800px; margin: 0 auto;">
                <h1 class="b2c-section-title" style="text-align: left; margin-bottom: 24px;">{{ $page->title }}</h1>
                <div class="b2c-page-content" style="line-height: 1.8; color: var(--b2c-text);">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </section>
@endsection