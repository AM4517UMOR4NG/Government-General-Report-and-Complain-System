# Government General Report and Complain System (FRC)

A comprehensive Laravel-based system for handling government reports and complaints. This system provides a robust platform for citizens to submit reports and complaints, and for government officials to manage and respond to them efficiently.

## Features

- **Citizen Portal**: Easy-to-use interface for citizens to submit reports and complaints
- **Admin Dashboard**: Comprehensive dashboard for government officials to manage submissions
- **Report Management**: Track and categorize different types of reports and complaints
- **Status Tracking**: Real-time status updates for submitted reports
- **User Management**: Role-based access control for different government departments
- **Notification System**: Automated notifications for status changes and updates
- **File Attachments**: Support for document and image attachments
- **Search and Filter**: Advanced search and filtering capabilities
- **Analytics**: Reporting and analytics for system usage and trends

## System Requirements

- PHP >= 8.1
- MySQL >= 5.7 or MariaDB >= 10.2
- Composer
- Node.js & NPM (for frontend assets)

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/AM4517UMOR4NG/Government-General-Report-and-Complain-System.git
   cd government-frc-system
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Setup**
   - Create a MySQL database named `government_frc_system`
   - Update the database credentials in `.env` file
   - Run migrations:
   ```bash
   php artisan migrate
   ```

6. **Seed the database** 
   ```bash
   php artisan db:seed
   ```

7. **Build frontend assets**
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

## Configuration

### Database Configuration

The system uses MySQL as the default database. Ensure your database configuration in `.env` matches your MySQL setup:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=government_frc_system
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## System Architecture

### Core Modules

1. **Authentication & Authorization**
   - User registration and login
   - Role-based access control
   - Password reset functionality

2. **Report Management**
   - Report submission
   - Report categorization
   - Status tracking
   - File attachments

3. **Complaint Management**
   - Complaint submission
   - Priority assignment
   - Assignment to departments
   - Resolution tracking

4. **User Management**
   - Citizen accounts
   - Government official accounts
   - Department management
   - Role assignments

5. **Notification System**
   - Email notifications
   - In-app notifications
   - Status change alerts

## API Endpoints

The system provides RESTful API endpoints for:

- User authentication
- Report submission and management
- Complaint handling
- File uploads
- Status updates
- Analytics and reporting

## Security Features

- CSRF protection
- SQL injection prevention
- XSS protection
- File upload validation
- Role-based access control
- Secure password hashing

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes

