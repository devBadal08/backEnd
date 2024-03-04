@component('mail::message')
# Introduction

Hello!

Forgot Password?

@component('mail::button', ['url' => 'http://localhost:5173/auth/Resetpassword?token='.$token])
Click to Reset
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent