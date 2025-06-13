<x-mail::message>
<style>
    .email-container {
        text-align: center;
        padding: 30px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }
    .email-title {
        color: #333;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .welcome-text {
        font-size: 18px;
        color: #555;
        margin-bottom: 20px;
    }
    .cta-button {
        display: inline-block;
        background: linear-gradient(135deg, #4CAF50, #2E8B57);
        color: #fff;
        font-size: 18px;
        font-weight: bold;
        text-decoration: none;
        padding: 12px 25px;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(46, 139, 87, 0.3);
        transition: all 0.3s ease-in-out;
    }
    .cta-button:hover {
        background: linear-gradient(135deg, #45a049, #207a48);
    }
    .footer-text {
        margin-top: 20px;
        font-size: 14px;
        color: #777;
    }
</style>

<div class="email-container">
    <h2 class="email-title">Hello, {{ $user->name }} 👋</h2>
    <p style="font-size: 18px; color: #555;">Your email verfication code is:</p>
    <h1 class="code-box">{{ $user->email_verfiy_code }}</h1>
    <p class="expiry-text">This code will expire in <strong>10 minutes</strong>. Please use it before it becomes invalid.</p>
</div>
<p class="footer-text">If you didn’t request a verify code, you can't verfiy this email.</p>

Thanks, {{ $user->name }}<br>
{{ config('app.name') }}
</x-mail::message>
