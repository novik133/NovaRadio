@extends('themes.default.layout')

@section('content')
<div class="page-header" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 120px 0 60px; text-align: center;">
    <div class="container">
        <h1 style="font-size: 48px; font-weight: 800;">{{ $page->title }}</h1>
    </div>
</div>

<div style="padding: 60px 0;">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto; font-size: 17px; line-height: 1.8; color: var(--color-text);">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-content h2 { font-size: 32px; margin: 40px 0 20px; }
.page-content h3 { font-size: 24px; margin: 30px 0 15px; }
.page-content img { max-width: 100%; border-radius: 16px; margin: 30px 0; }
.page-content ul, .page-content ol { margin: 20px 0; padding-left: 30px; }
.page-content li { margin-bottom: 10px; }
</style>
@endpush
