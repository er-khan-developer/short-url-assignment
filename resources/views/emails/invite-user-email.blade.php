<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Company Invitation</title>
</head>
<body>
    <h2>Hello {{ $name }},</h2>

    <p>You have been invited to join <strong>{{ $company_name }}</strong>. by {{ $inviter_name }}</p>

    <p>
        Click the link below to accept your invitation if you wish to join the company otherwise you can ignore this email.
    </p>

    <p>
        <a href="{{ $url }}">
            Accept Invitation
        </a>
    </p>

    <p>Thank you.</p>
</body>
</html>