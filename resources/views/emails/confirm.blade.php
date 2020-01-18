@component('mail::message')
# Hello {{ $user->name }},

Your changed your email, so we need to verify this new email address, please use the button below to verify the email address:

@component('mail::button', ['url' => route('verify', $user->verification_token) ])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
