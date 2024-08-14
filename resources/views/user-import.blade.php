<p>Dear {{ $user->name }},</p>
<p>You have been registered in our application. Please use the following credentials to log in:</p>
<p>Email: {{ $user->email }}</p>
<p>Password: {{ $user->decrypted_pass }}</p>
<p><a href="{{ $login_url }}">Click here to login</a></p>
<p>Best regards,<br>Admin</p>
