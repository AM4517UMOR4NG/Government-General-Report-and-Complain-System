# Email Configuration Fix

## Problem
Error: `Connection could not be established with host "mailpit:1025"`

## Solutions

### **Solution 1: Quick Fix (Recommended for Development)**

Update your `.env` file with these settings:

```env
# Email Configuration for Development
MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@government-frc.local"
MAIL_FROM_NAME="Government FRC System"
```

### **Solution 2: Use Gmail SMTP**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="Government FRC System"
```

### **Solution 3: Use Mailtrap (Recommended for Testing)**

1. Sign up at [mailtrap.io](https://mailtrap.io)
2. Get your SMTP credentials
3. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@government-frc.local"
MAIL_FROM_NAME="Government FRC System"
```

## Commands to Run

### **1. Update Environment**
```bash
# Copy the example env file if you don't have one
cp .env.example .env

# Generate application key
php artisan key:generate

# Clear config cache
php artisan config:clear
```

### **2. Test Email Configuration**
```bash
# Test email sending
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

### **3. Check Email Logs**
```bash
# If using MAIL_MAILER=log, check the log file
tail -f storage/logs/laravel.log
```

## Alternative: Disable Email for Development

If you want to completely disable email sending during development, you can:

### **Option 1: Use Log Driver**
```env
MAIL_MAILER=log
```

### **Option 2: Use Array Driver**
```env
MAIL_MAILER=array
```

### **Option 3: Mock Email in Tests**
```php
// In your test setup
Mail::fake();
```

## Troubleshooting

### **Common Issues:**

1. **"Connection refused"**
   - Check if mail server is running
   - Verify host and port settings

2. **"Authentication failed"**
   - Check username/password
   - For Gmail, use App Password, not regular password

3. **"SSL/TLS error"**
   - Try different encryption settings
   - Check if port 587 or 465 works better

### **Gmail Setup:**
1. Enable 2-Factor Authentication
2. Generate App Password
3. Use App Password in MAIL_PASSWORD

### **Mailtrap Setup:**
1. Sign up for free account
2. Go to Inboxes → Demo inbox
3. Copy SMTP credentials
4. Use in .env file

## Production Setup

For production, use a reliable email service:

- **SendGrid**
- **Mailgun**
- **Amazon SES**
- **Postmark**

Example for SendGrid:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

## Quick Fix Commands

```bash
# Run this command to setup development email
php artisan email:setup-dev

# Or manually update .env
echo "MAIL_MAILER=log" >> .env
echo "MAIL_FROM_ADDRESS=noreply@government-frc.local" >> .env
echo "MAIL_FROM_NAME=Government FRC System" >> .env

# Clear cache
php artisan config:clear
php artisan cache:clear
```

After making these changes, try submitting a report again. The email will be logged to `storage/logs/laravel.log` instead of being sent.
