<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $share->document->title }} — Shared document</title>
</head>

<body style="font-family: system-ui, -apple-system, sans-serif; background:#f8fafc; margin:0;">
    <main style="max-width: 440px; margin: 4rem auto; background:#fff; padding:2rem; border-radius:0.75rem; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
        <h1 style="font-size:1.25rem; margin-bottom:0.25rem;">{{ $share->document->title }}</h1>
        <p style="color:#64748b; font-size:0.9rem;">
            You've been sent a secure link to this document.
            @if ($share->expires_at)
            This link expires {{ $share->expires_at->diffForHumans() }}.
            @endif
        </p>

        <form method="POST" action="{{ route('dms.share.download', $share->token) }}" style="margin-top:1.5rem;">
            @csrf

            @if ($requiresPassword)
            <label for="password" style="display:block; font-size:0.85rem; margin-bottom:0.25rem;">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                style="width:100%; padding:0.5rem; border:1px solid #cbd5e1; border-radius:0.375rem; margin-bottom:1rem;">
            @endif

            <button
                type="submit"
                style="width:100%; padding:0.6rem; background:#1d4ed8; color:#fff; border:0; border-radius:0.375rem; font-weight:600; cursor:pointer;">
                Download document
            </button>
        </form>
    </main>
</body>

</html>