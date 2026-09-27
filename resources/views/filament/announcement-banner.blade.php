{{-- resources/views/filament/announcement-banner.blade.php --}}
@php
$announcement = \App\Models\Announcement::getFeatured();
@endphp

@if ($announcement)
<div
    style="background-color: {{ $announcement->background_color ?? '#f59e0b' }}"
    class="text-white">
    <div class="mx-auto flex max-w-screen-2xl items-center justify-center gap-x-3 px-4 py-2.5 text-sm">
        <x-heroicon-o-megaphone class="h-5 w-5 shrink-0" />

        <div class="text-center font-medium [&_p]:m-0">
            {!! str($announcement->content)->sanitizeHtml() !!}
        </div>
    </div>
</div>
@endif