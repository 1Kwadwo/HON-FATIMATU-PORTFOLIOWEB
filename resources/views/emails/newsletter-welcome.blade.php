<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Newsletter</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f8f8f8;">
    <div style="background-color: #003366; color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="margin: 0; font-size: 28px;">Welcome!</h1>
        <p style="margin: 10px 0 0 0; font-size: 16px;">Thank you for subscribing to our newsletter</p>
    </div>
    
    <div style="background-color: white; padding: 30px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <p style="font-size: 16px; margin-top: 0;">Dear Subscriber,</p>
        
        <p style="font-size: 16px;">
            Thank you for joining our community! We're excited to have you on board and look forward to keeping you informed about the latest news, updates, and initiatives from Hon. Fatimatu Abubakar.
        </p>
        
        <div style="background-color: #f0f7ff; padding: 20px; border-left: 4px solid #D4A017; margin: 25px 0;">
            <h2 style="color: #003366; margin-top: 0; font-size: 20px;">What to Expect</h2>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li style="margin-bottom: 8px;">Latest news and announcements</li>
                <li style="margin-bottom: 8px;">Updates on community initiatives and projects</li>
                <li style="margin-bottom: 8px;">Upcoming events and speaking engagements</li>
                <li style="margin-bottom: 8px;">Exclusive insights and stories</li>
            </ul>
        </div>
        
        <p style="font-size: 16px;">
            You'll receive our newsletter periodically with carefully curated content. We respect your inbox and promise to only send you valuable information.
        </p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ config('app.url') }}" 
               style="display: inline-block; background-color: #003366; color: white; padding: 14px 28px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 16px;">
                Visit Our Website
            </a>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <p style="font-size: 14px; color: #666; margin: 0;">
                <strong>Stay Connected:</strong>
            </p>
            <p style="font-size: 14px; color: #666; margin: 10px 0 0 0;">
                Follow us on social media for daily updates and behind-the-scenes content.
            </p>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 20px; padding: 20px;">
        <p style="font-size: 12px; color: #666; margin: 0;">
            You're receiving this email because you subscribed to our newsletter at {{ config('app.url') }}
        </p>
        <p style="font-size: 12px; color: #666; margin: 10px 0 0 0;">
            If you wish to unsubscribe, please contact us at {{ config('mail.from.address') }}
        </p>
    </div>
</body>
</html>
