<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Submission</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #003366; color: white; padding: 20px; text-align: center;">
        <h1 style="margin: 0;">New Contact Submission</h1>
    </div>
    
    <div style="background-color: #f8f8f8; padding: 20px; margin-top: 20px;">
        <h2 style="color: #003366; margin-top: 0;">Contact Details</h2>
        
        <p><strong>Name:</strong> {{ $submission->name }}</p>
        <p><strong>Email:</strong> <a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></p>
        <p><strong>Subject:</strong> {{ $submission->subject }}</p>
        <p><strong>Submitted:</strong> {{ $submission->created_at->format('F j, Y g:i A') }}</p>
        
        <h3 style="color: #003366; margin-top: 30px;">Message</h3>
        <div style="background-color: white; padding: 15px; border-left: 4px solid #D4A017;">
            {!! nl2br(e($submission->message)) !!}
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
            <p style="font-size: 12px; color: #666;">
                <strong>IP Address:</strong> {{ $submission->ip_address }}<br>
                <strong>User Agent:</strong> {{ $submission->user_agent }}
            </p>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 20px; padding: 20px; background-color: #f0f0f0;">
        <p style="margin: 0;">
            <a href="{{ route('admin.contacts.show', $submission) }}" 
               style="display: inline-block; background-color: #003366; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;">
                View in Admin Panel
            </a>
        </p>
    </div>
</body>
</html>
