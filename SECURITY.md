# Security Implementation

This document outlines the security measures implemented in the portfolio website application.

## Security Features

### 1. Rate Limiting

**Contact Form Rate Limiting**
- **Implementation**: Applied to the contact form submission endpoint
- **Limit**: 5 submissions per hour per IP address
- **Location**: `routes/web.php` - `throttle:5,60` middleware on contact submission route
- **Purpose**: Prevents spam and abuse of the contact form

```php
Route::post('/contact', [PublicController::class, 'submitContact'])
    ->middleware('throttle:5,60') // 5 submissions per hour per IP
    ->name('contact.submit');
```

### 2. File Upload Validation

**Image Upload Security**
- **Max File Size**: 5MB (5,120 KB)
- **Allowed MIME Types**: 
  - `image/jpeg`
  - `image/png`
  - `image/jpg`
  - `image/webp`
- **Validation Layers**:
  1. File size check
  2. MIME type whitelist validation
  3. File extension validation
  4. Image integrity verification using `getimagesize()`
- **Location**: `app/Services/ImageService.php` - `validateImage()` method

**Controller Validation**
- Gallery uploads: `'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120'`
- News featured images: `'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120'`

### 3. CSRF Protection

**Implementation**
- Laravel's built-in CSRF protection is enabled by default
- All forms include `@csrf` directive
- Livewire components automatically handle CSRF tokens
- **Coverage**:
  - All admin forms (gallery, news, pages, contacts)
  - Contact form (via Livewire)
  - All POST, PUT, PATCH, DELETE requests

**Verification**
- CSRF tokens are automatically validated by Laravel's middleware
- Invalid tokens result in 419 HTTP status code

### 4. Security Headers

**Implemented Headers**
- **X-Frame-Options**: `SAMEORIGIN` - Prevents clickjacking attacks
- **X-Content-Type-Options**: `nosniff` - Prevents MIME type sniffing
- **X-XSS-Protection**: `1; mode=block` - Enables browser XSS protection
- **Referrer-Policy**: `strict-origin-when-cross-origin` - Controls referrer information
- **Strict-Transport-Security**: `max-age=31536000; includeSubDomains` (production only)

**Implementation**
- **Middleware**: `app/Http/Middleware/SecurityHeadersMiddleware.php`
- **Registration**: Automatically applied to all responses via `bootstrap/app.php`

### 5. HTTPS Enforcement

**Production HTTPS**
- **Automatic Redirect**: All HTTP requests are redirected to HTTPS in production
- **Implementation**: `app/Http/Middleware/ForceHttpsMiddleware.php`
- **Environment Detection**: Only enforced when `APP_ENV=production`
- **Status Code**: 301 (Permanent Redirect)

**HSTS Header**
- Strict-Transport-Security header is added in production
- Duration: 1 year (31,536,000 seconds)
- Includes subdomains

## Additional Security Measures

### Authentication & Authorization

- **Admin Access**: Protected by `AdminMiddleware`
- **Session Management**: 120-minute session lifetime
- **Password Hashing**: Bcrypt with 12 rounds
- **Two-Factor Authentication**: Available via Laravel Fortify

### Input Validation

- All user inputs are validated using Laravel's validation rules
- Maximum string lengths enforced
- Email format validation
- Required field validation
- XSS protection via Blade's automatic escaping

### Database Security

- **SQL Injection Prevention**: Eloquent ORM with parameter binding
- **Mass Assignment Protection**: `$fillable` properties defined on all models
- **Prepared Statements**: All queries use prepared statements

### Error Handling

- **Production Mode**: Generic error messages (no stack traces)
- **Development Mode**: Detailed error information for debugging
- **Logging**: All errors logged to `storage/logs/laravel.log`
- **Critical Errors**: Email notifications sent to admin in production

## Configuration

### Environment Variables

```env
# Security: Set these values in production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Admin email for critical error notifications
MAIL_ADMIN_EMAIL=admin@yourdomain.com
```

### Production Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure `APP_URL` with HTTPS
- [ ] Set up SSL certificate
- [ ] Configure `MAIL_ADMIN_EMAIL` for error notifications
- [ ] Verify rate limiting is working
- [ ] Test file upload restrictions
- [ ] Verify security headers are present
- [ ] Confirm HTTPS redirect is working
- [ ] Review and rotate `APP_KEY`

## Testing Security

### Rate Limiting Test
```bash
# Test contact form rate limiting
for i in {1..6}; do
  curl -X POST http://localhost:8000/contact \
    -d "name=Test&email=test@example.com&subject=Test&message=Test"
done
# 6th request should return 429 Too Many Requests
```

### Security Headers Test
```bash
# Check security headers
curl -I https://yourdomain.com
# Should include X-Frame-Options, X-Content-Type-Options, etc.
```

### File Upload Test
```bash
# Test file size limit (should fail for files > 5MB)
# Test invalid MIME types (should fail for non-image files)
```

## Reporting Security Issues

If you discover a security vulnerability, please email security@yourdomain.com. Do not create a public GitHub issue.

## Updates and Maintenance

- Regularly update Laravel and dependencies
- Monitor security advisories
- Review logs for suspicious activity
- Rotate secrets and keys periodically
- Keep SSL certificates up to date

## References

- [Laravel Security Documentation](https://laravel.com/docs/security)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Laravel Rate Limiting](https://laravel.com/docs/routing#rate-limiting)
- [Content Security Policy](https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP)
