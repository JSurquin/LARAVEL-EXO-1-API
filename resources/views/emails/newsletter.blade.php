{{-- Template HTML de l'e-mail envoyé à chaque abonné --}}
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Newsletter</title></head>
<body style="font-family: sans-serif; padding: 24px; color: #333;">
    {{-- Titre de la newsletter = sujet saisi par l'admin --}}
    <h1 style="color: #1a56db;">{{ $newsletter->subject }}</h1>

    <div style="line-height: 1.6;">
        {{-- Corps de la newsletter : e() échappe les balises HTML (sécurité XSS),
             nl2br convertit les retours à la ligne en <br> pour respecter la mise en forme --}}
        {!! nl2br(e($newsletter->body)) !!}
    </div>

    {{-- Séparateur visuel avant le footer --}}
    <hr style="margin: 32px 0; border: none; border-top: 1px solid #eee;">

    {{-- Mention légale de désabonnement (footer) --}}
    <p style="font-size: 12px; color: #999;">
        Vous recevez cet e-mail car vous êtes abonné à notre newsletter.
    </p>
</body>
</html>
