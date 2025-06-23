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
        margin-bottom: 15px;
    }
    .code-box {
        display: inline-block;
        font-size: 28px;
        font-weight: bold;
        color:red;
        /* background: linear-gradient(135deg, #ff416c, #ff4b2b); */
        padding: 15px 30px;
        border-radius: 8px;
        letter-spacing: 3px;
        box-shadow: 0px 4px 8px rgba(255, 75, 43, 0.3);
    }
    .expiry-text {
        margin-top: 15px;
        color: #777;
        font-size: 16px;
    }
    .footer-text {
        margin-top: 20px;
        font-size: 14px;
        color: #555;
    }
</style>

<div class="email-container">
    <h2 class="email-title">Hello, {{ $user }} 👋</h2>
    <p style="font-size: 18px; color: #555;">Your password reset code is:</p>
    <div class="code-box">{{ $code }}</div>
    <p class="expiry-text">This code will expire in <strong>5 minutes</strong>. Please use it before it becomes invalid.</p>
</div>

<p class="footer-text">If you didn’t request a password reset, you can ignore this email.</p>

Thanks, {{ $user }}<br>
{{ config('app.name') }}
</x-mail::message>
