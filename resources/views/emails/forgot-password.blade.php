@component('mail::message')
# Hola de nuevo

Por favor ve al siguiente enlace para actualizar tu contraseña.

@component('mail::button', ['url' => 'http://localhost:3001/recovery/'. $token ])
Enlace
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
