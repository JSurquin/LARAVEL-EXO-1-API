<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Newsletter</title></head>
<body style="font-family: sans-serif; padding: 24px; color: #333;">
    <h1 style="color: #1a56db;">{{ $newsletter->subject }}</h1>
    <div style="line-height: 1.6;">
        {!! nl2br(e($newsletter->body)) !!}
    </div>
    <hr style="margin: 32px 0; border: none; border-top: 1px solid #eee;">
    <p style="font-size: 12px; color: #999;">
        Vous recevez cet e-mail car vous êtes abonné à notre newsletter.
    </p>
</body>
</html>