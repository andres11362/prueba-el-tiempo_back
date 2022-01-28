@component('mail::message')
# Bienvenido a la plataforma

{{ $name }} por favor ingresa al siguiente enlace para entrar a la plataforma con tus credenciales,
recuerda cambiar la contraseña enseguida tengas acceso a todos los servicios de la aplicación.

Recuerda que estas son tus credenciales:

# Usuario: {{ $user }}
# Contraseña: {{ $password }}

@component('mail::button', ['url' => ''])
Enlace
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
