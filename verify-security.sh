#!/bin/bash

echo "==================================="
echo "Security Implementation Verification"
echo "==================================="
echo ""

echo "1. Checking Security Headers Middleware..."
if [ -f "app/Http/Middleware/SecurityHeadersMiddleware.php" ]; then
    echo "   ✅ SecurityHeadersMiddleware exists"
else
    echo "   ❌ SecurityHeadersMiddleware not found"
fi

echo ""
echo "2. Checking HTTPS Enforcement Middleware..."
if [ -f "app/Http/Middleware/ForceHttpsMiddleware.php" ]; then
    echo "   ✅ ForceHttpsMiddleware exists"
else
    echo "   ❌ ForceHttpsMiddleware not found"
fi

echo ""
echo "3. Checking Rate Limiting on Contact Form..."
if grep -q "throttle:5,60" routes/web.php; then
    echo "   ✅ Rate limiting configured (5 per hour)"
else
    echo "   ❌ Rate limiting not found"
fi

echo ""
echo "4. Checking File Upload Validation..."
if grep -q "validateImage" app/Services/ImageService.php; then
    echo "   ✅ Image validation implemented"
    if grep -q "5 \* 1024 \* 1024" app/Services/ImageService.php; then
        echo "   ✅ 5MB file size limit enforced"
    fi
    if grep -q "allowedMimes" app/Services/ImageService.php; then
        echo "   ✅ MIME type whitelist implemented"
    fi
else
    echo "   ❌ Image validation not found"
fi

echo ""
echo "5. Checking CSRF Protection..."
csrf_count=$(grep -r "@csrf" resources/views/admin --include="*.blade.php" | wc -l)
if [ "$csrf_count" -gt 0 ]; then
    echo "   ✅ CSRF tokens found in $csrf_count admin forms"
else
    echo "   ❌ CSRF tokens not found"
fi

echo ""
echo "6. Checking Middleware Registration..."
if grep -q "SecurityHeadersMiddleware" bootstrap/app.php; then
    echo "   ✅ Security headers middleware registered"
else
    echo "   ❌ Security headers middleware not registered"
fi

if grep -q "ForceHttpsMiddleware" bootstrap/app.php; then
    echo "   ✅ HTTPS enforcement middleware registered"
else
    echo "   ❌ HTTPS enforcement middleware not registered"
fi

echo ""
echo "7. Checking Documentation..."
if [ -f "SECURITY.md" ]; then
    echo "   ✅ SECURITY.md documentation exists"
else
    echo "   ❌ SECURITY.md not found"
fi

echo ""
echo "8. Running Security Tests..."
php artisan test --filter=SecurityTest --quiet
if [ $? -eq 0 ]; then
    echo "   ✅ All security tests passed"
else
    echo "   ❌ Some security tests failed"
fi

echo ""
echo "==================================="
echo "Verification Complete!"
echo "==================================="
