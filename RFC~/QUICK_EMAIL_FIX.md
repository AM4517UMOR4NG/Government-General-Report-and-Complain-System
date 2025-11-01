# Quick Email Fix

## Problem
Error: `Connection could not be established with host "mailpit:1025"`

## Immediate Solution

### Step 1: Update .env file
Add these lines to your `.env` file:

```env
# Email Configuration - Development
MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@government-frc.local"
MAIL_FROM_NAME="Government FRC System"
```

### Step 2: Clear cache
```bash
php artisan config:clear
php artisan cache:clear
```

### Step 3: Test
Try submitting a report again. The email will be logged to `storage/logs/laravel.log` instead of being sent.

## Alternative: Disable Email Completely

If you want to completely disable email notifications:

### Option 1: Update .env
```env
MAIL_MAILER=array
```

### Option 2: Use this command
```bash
# Run this command to automatically fix email configuration
php artisan email:setup-dev
```

## What This Does

1. **Log Driver**: Emails are saved to log files instead of being sent
2. **No Network Calls**: No connection to external mail servers
3. **Development Safe**: Works without mail server setup
4. **Notifications Still Work**: In-app notifications still function

## After Fix

- ✅ Reports can be submitted without errors
- ✅ Email content is logged to `storage/logs/laravel.log`
- ✅ In-app notifications still work
- ✅ No external mail server required

## Check Logs

To see the email content:
```bash
tail -f storage/logs/laravel.log
```

## Production Setup

For production, use a real email service:
- SendGrid
- Mailgun
- Amazon SES
- Gmail SMTP

Example for Gmail:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```
