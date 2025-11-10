# Email Configuration Guide

This document explains how email notifications are configured and used in the portfolio website.

## Overview

The application sends two types of emails:
1. **Contact Form Notifications** - Sent to admin when someone submits the contact form
2. **Newsletter Welcome Emails** - Sent to subscribers when they join the newsletter

## Configuration

### Environment Variables

Configure the following variables in your `.env` file:

```env
# Mail Driver (log, smtp, sendmail, mailgun, ses, postmark)
MAIL_MAILER=log

# SMTP Configuration (if using smtp)
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null

# From Address (appears as sender)
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Admin Email (receives contact form notifications)
MAIL_ADMIN_EMAIL="admin@example.com"
```

### Development Setup

For local development, the mailer is set to `log`, which writes emails to `storage/logs/laravel.log` instead of sending them. This allows you to test email functionality without configuring an SMTP server.

### Production Setup

For production, you have several options:

#### Option 1: SMTP Server
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

#### Option 2: Mailgun
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-mailgun-secret
```

#### Option 3: Amazon SES
```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
```

#### Option 4: Postmark
```env
MAIL_MAILER=postmark
POSTMARK_TOKEN=your-postmark-token
```

## Email Classes

### ContactSubmissionReceived

**Location:** `app/Mail/ContactSubmissionReceived.php`

**Purpose:** Notifies the admin when a visitor submits the contact form.

**Template:** `resources/views/emails/contact-submission.blade.php`

**Features:**
- Displays contact details (name, email, subject)
- Shows the full message
- Includes IP address and user agent for security
- Provides a link to view the submission in the admin panel
- Sets reply-to address to the submitter's email

**Usage:**
```php
use App\Mail\ContactSubmissionReceived;
use Illuminate\Support\Facades\Mail;

$adminEmail = config('mail.admin_email');
Mail::to($adminEmail)->send(new ContactSubmissionReceived($submission));
```

### NewsletterWelcome

**Location:** `app/Mail/NewsletterWelcome.php`

**Purpose:** Sends a welcome email to new newsletter subscribers.

**Template:** `resources/views/emails/newsletter-welcome.blade.php`

**Features:**
- Welcomes the subscriber
- Explains what to expect from the newsletter
- Provides a link to the website
- Includes unsubscribe information
- Queued for background processing (implements `ShouldQueue`)

**Usage:**
```php
use App\Mail\NewsletterWelcome;
use Illuminate\Support\Facades\Mail;

Mail::to($subscription->email)->send(new NewsletterWelcome($subscription));
```

## Email Templates

### Contact Submission Template

**File:** `resources/views/emails/contact-submission.blade.php`

**Design:**
- Professional layout with brand colors (#003366 primary blue, #D4A017 accent gold)
- Clear sections for contact details and message
- Responsive design for mobile devices
- Call-to-action button to view in admin panel

### Newsletter Welcome Template

**File:** `resources/views/emails/newsletter-welcome.blade.php`

**Design:**
- Welcoming header with brand colors
- Clear explanation of newsletter benefits
- Call-to-action button to visit website
- Footer with unsubscribe information
- Responsive design for mobile devices

## Error Handling

Both email implementations include error handling to prevent failures from breaking the user experience:

```php
try {
    Mail::to($email)->send(new SomeEmail($data));
} catch (\Exception $e) {
    \Log::error('Failed to send email: ' . $e->getMessage());
}
```

This ensures that:
- Contact form submissions are saved even if email fails
- Newsletter subscriptions are recorded even if welcome email fails
- Errors are logged for debugging
- Users receive appropriate success messages

## Queue Configuration

The `NewsletterWelcome` email implements `ShouldQueue`, which means it will be processed in the background if you have queue workers running.

### Setting Up Queues

1. Configure queue driver in `.env`:
```env
QUEUE_CONNECTION=database
```

2. Run migrations to create jobs table:
```bash
php artisan queue:table
php artisan migrate
```

3. Start queue worker:
```bash
php artisan queue:work
```

For production, use a process manager like Supervisor to keep the queue worker running.

## Testing Emails

### Local Testing (Log Driver)

1. Set `MAIL_MAILER=log` in `.env`
2. Trigger an email action (submit contact form or subscribe to newsletter)
3. Check `storage/logs/laravel.log` for the email content

### Testing with Mailtrap

Mailtrap is a fake SMTP server for development:

1. Sign up at [mailtrap.io](https://mailtrap.io)
2. Get your SMTP credentials
3. Configure in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
```

### Testing in Production

Before going live, test with real email addresses:

1. Configure production mail settings
2. Submit a test contact form
3. Subscribe to newsletter with a test email
4. Verify emails are received correctly
5. Check spam folders
6. Verify links work correctly

## Troubleshooting

### Emails Not Sending

1. Check `.env` configuration
2. Verify SMTP credentials
3. Check `storage/logs/laravel.log` for errors
4. Ensure firewall allows outbound SMTP connections
5. Verify email addresses are valid

### Emails Going to Spam

1. Configure SPF records for your domain
2. Set up DKIM signing
3. Configure DMARC policy
4. Use a reputable email service (Mailgun, SES, Postmark)
5. Avoid spam trigger words in subject/content

### Queue Not Processing

1. Verify queue worker is running: `php artisan queue:work`
2. Check failed jobs: `php artisan queue:failed`
3. Retry failed jobs: `php artisan queue:retry all`
4. Check queue configuration in `.env`

## Security Considerations

1. **Never commit credentials** - Keep `.env` out of version control
2. **Use environment variables** - Don't hardcode email addresses or passwords
3. **Rate limiting** - Contact form and newsletter have rate limiting to prevent abuse
4. **Validate input** - All email addresses are validated before sending
5. **Log errors** - Failed emails are logged for monitoring
6. **Queue sensitive emails** - Use queues to prevent blocking requests

## Monitoring

Monitor email delivery in production:

1. Check Laravel logs for email errors
2. Monitor queue failures
3. Track bounce rates in your email service
4. Set up alerts for critical email failures
5. Regularly review spam complaints

## Additional Resources

- [Laravel Mail Documentation](https://laravel.com/docs/mail)
- [Laravel Queue Documentation](https://laravel.com/docs/queues)
- [Mailgun Documentation](https://documentation.mailgun.com/)
- [Amazon SES Documentation](https://docs.aws.amazon.com/ses/)
- [Postmark Documentation](https://postmarkapp.com/developer)
