# ZipArchive Extension Fix

## Problem
Error: `Class 'ZipArchive' not found`

## Solutions

### **Solution 1: Install PHP Zip Extension**

#### **Windows (XAMPP/WAMP)**
1. Open `php.ini` file
2. Find and uncomment this line:
   ```ini
   extension=zip
   ```
3. Restart Apache/Web server

#### **Ubuntu/Debian**
```bash
sudo apt-get update
sudo apt-get install php-zip
sudo systemctl restart apache2
```

#### **CentOS/RHEL**
```bash
sudo yum install php-zip
sudo systemctl restart httpd
```

#### **Docker**
Add to your Dockerfile:
```dockerfile
RUN apt-get update && apt-get install -y php-zip
```

### **Solution 2: Check Extensions**
Run this command to check PHP extensions:
```bash
php artisan php:check-extensions
```

### **Solution 3: Alternative Download Methods**

The system now provides alternative download methods when ZipArchive is not available:

#### **1. JSON Download**
- Downloads report data as JSON file
- Includes all metadata and file information
- Works without ZipArchive

#### **2. CSV Download**
- Downloads report data as CSV file
- Easy to open in Excel
- Structured data format

#### **3. HTML Download**
- Downloads report as formatted HTML
- Includes all information
- Can be printed or saved as PDF

#### **4. Individual File Download**
- Downloads attachments one by one
- HTML page with download links
- Works for all file types

### **Solution 4: Update Download Buttons**

Add these alternative download buttons to your admin interface:

```html
<!-- In your admin reports view -->
<div class="btn-group">
    <a href="{{ route('admin.reports.download', $report->id) }}" class="btn btn-primary">
        <i class="fas fa-download"></i> Download ZIP
    </a>
    <a href="{{ route('admin.reports.download_csv', $report->id) }}" class="btn btn-success">
        <i class="fas fa-file-csv"></i> Download CSV
    </a>
    <a href="{{ route('admin.reports.download_pdf', $report->id) }}" class="btn btn-info">
        <i class="fas fa-file-pdf"></i> Download HTML
    </a>
    <a href="{{ route('admin.reports.download_attachments', $report->id) }}" class="btn btn-warning">
        <i class="fas fa-paperclip"></i> Download Files
    </a>
</div>
```

## How It Works Now

### **Automatic Fallback**
1. System checks if ZipArchive is available
2. If available: Creates ZIP file with all data and attachments
3. If not available: Falls back to JSON download with file URLs

### **JSON Download Content**
```json
{
    "id": 1,
    "ticket_no": "RPT-ABC12345",
    "title": "Report Title",
    "description": "Report description",
    "attachments": [
        {
            "name": "file1.jpg",
            "path": "attachments/reports/file1.jpg",
            "size": 1024000,
            "url": "http://localhost/files/report/1/download/file1.jpg"
        }
    ]
}
```

### **File Download Instructions**
The JSON includes download URLs for each attachment, so users can:
1. Download the JSON file
2. Use the URLs to download individual files
3. Or use the HTML page with clickable links

## Testing

### **Test ZipArchive**
```bash
php -r "if (class_exists('ZipArchive')) { echo 'ZipArchive is available'; } else { echo 'ZipArchive is NOT available'; }"
```

### **Test Download**
1. Go to admin reports
2. Click download button
3. Should work with any of the methods

## Production Setup

For production, ensure all required extensions are installed:

```bash
# Check all extensions
php artisan php:check-extensions

# Install missing ones
sudo apt-get install php-zip php-mbstring php-curl php-json php-fileinfo
```

## Troubleshooting

### **Still Getting Errors?**
1. Check if extension is loaded: `php -m | grep zip`
2. Restart web server after installing
3. Check PHP version compatibility
4. Use alternative download methods

### **Alternative: Use External ZIP Service**
If you can't install ZipArchive, you can:
1. Use cloud storage services
2. Use external ZIP APIs
3. Use JavaScript ZIP libraries
4. Use the JSON/HTML download methods

## Quick Fix Commands

```bash
# Check extensions
php artisan php:check-extensions

# Clear cache
php artisan config:clear
php artisan cache:clear

# Test download
# Go to admin panel and try downloading a report
```

The system now gracefully handles the missing ZipArchive extension and provides multiple download alternatives! 🎉
