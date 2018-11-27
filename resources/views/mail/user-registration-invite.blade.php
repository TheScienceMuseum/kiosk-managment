@component('mail::message')
# Kiosk Management System

A new account has been created for you.

Please take the time now to set up a password for your account, this link will
expire 30 minutes after this email was sent.

@component('mail::button', ['url' => route('user.onboarding.password', [$token, encrypt($user->email)])])
Setup Your Account
@endcomponent

You will also be asked to register for multi factor authentication, Google Authenticator can scan the
barcode you will be shown.

Thanks,<br>
{{ config('app.name') }} ({{ config('app.env') }})
@endcomponent
