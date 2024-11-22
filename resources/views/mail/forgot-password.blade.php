<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Permintaan Reset Password Akun</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; text-align: center;">
    <div style="max-width: 600px; margin: auto; padding: 20px; background-color: #ffffff; border-radius: 10px;">
        <h2 style="color: #333333;">Permintaan Reset Password</h2>
        <p style="color: #666666;">
            Kami mendapat permintaan reset password akun anda! Klik tombol di bawah ini untuk me-reset password akun Anda.
        </p>

        <a href="{{ $verificationLink }}" style="display: inline-block; margin: 20px 0; padding: 10px 20px; background-color: #4CAF50; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;">
            Reset Password
        </a>

        <p style="color: #999999; font-size: 12px;">
            Jika Anda tidak melakukan permintaan ini, abaikan email ini.
        </p>
    </div>
</body>
</html>
