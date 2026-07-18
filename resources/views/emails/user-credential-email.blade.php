<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Welcome to the our Company</title>
</head>

<body>

    <h2>Hello, {{ $name }}!</h2>

    <p>
        Thank you for Accepting the invitation for company
        <strong>{{ $companyName }}</strong>. You can login from the below details 
    </p>

    <p><strong>Email:</strong> {{ $email }}</p>

    <p><strong> Password:</strong> {{ $password }}</p>

    <p>
        <a href="{{ $loginUrl }}">
            Login to your account
        </a>
    </p>

    <p>
        For security, please change your password after your first login.
    </p>

    <p>Thank you.</p>

</body>

</html>