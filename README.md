# 🏥 MediCare+ EMR System

A comprehensive Electronic Medical Record (EMR) management system built with Laravel and Bootstrap 5, designed for hospitals and healthcare facilities.

## 🌟 Features

### 📋 Core Features
- **Multi-Role Authentication**: Admin, Doctor, and Patient dashboards
- **Medical Records Management**: Complete patient medical history tracking
- **Prescription Management**: Digital prescription creation and tracking
- **Lab Reports**: Upload and manage laboratory test results
- **Audit Logging**: Comprehensive activity tracking for compliance
- **User Management**: Role-based access control

### 🎨 UI/UX Features
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile
- **Modern Interface**: Clean, professional healthcare-themed design
- **Interactive Modals**: Detailed medical record views with scrollable content
- **Real-time Validation**: Client-side form validation with error alerts
- **Accessibility**: WCAG compliant with proper ARIA labels

### 🔒 Security Features
- **Secure Authentication**: Laravel's built-in authentication system
- **Rate Limiting**: Protection against brute force attacks
- **Session Management**: Secure session handling and regeneration
- **Input Validation**: Comprehensive server-side and client-side validation
- **CSRF Protection**: Cross-site request forgery protection

## 📋 System Requirements

### Minimum Requirements
- **PHP**: 8.0 or higher
- **MySQL**: 5.7 or higher / MariaDB 10.3 or higher
- **Web Server**: Apache 2.4 or Nginx 1.18
- **Memory**: 512MB RAM minimum (1GB recommended)
- **Storage**: 500MB available disk space

### Recommended Requirements
- **PHP**: 8.2 or higher
- **MySQL**: 8.0 or higher
- **Web Server**: Apache 2.4 with mod_rewrite or Nginx
- **Memory**: 2GB RAM
- **Storage**: 2GB available disk space

## 🚀 Quick Start

### Option 1: Automated Setup (Windows)
1. Run `setup-complete.bat` for full installation
2. Follow the on-screen prompts
3. Access your EMR system at `http://localhost:8000`

### Option 2: Manual Setup
1. Clone the repository
2. Install dependencies: `composer install`
3. Set up database: Run `setup-database.bat`
4. Configure environment: Copy `.env.example` to `.env`
5. Generate application key: `php artisan key:generate`
6. Run migrations: `php artisan migrate`
7. Start development server: `php artisan serve`

## 🔧 Configuration

### Environment Variables
Copy `.env.example` to `.env` and configure:

```env
APP_NAME=MediCare+
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medicare_emr
DB_USERNAME=root
DB_PASSWORD=
```

## 👥 Default Accounts

### Admin Account
- **Email**: admin@hospital.com
- **Password**: password
- **Role**: System Administrator

### Doctor Account
- **Email**: rajesh@hospital.com
- **Password**: password
- **Role**: Doctor

### Patient Account
- **Email**: amit@hospital.com
- **Password**: password
- **Role**: Patient

## 🔧 Batch Files

### 1. setup-complete.bat
Complete automated setup including:
- Environment configuration
- Database creation and migration
- Dependency installation
- Application key generation

### 2. setup-database.bat
Database setup utility:
- Interactive database connection setup
- Database creation
- Table migration
- Sample data seeding

### 3. run-application.bat
Application launcher:
- Starts Laravel development server
- Opens browser automatically
- Provides server status information

## 🛠️ Development

### Running Tests
```bash
php artisan test
```

## 🔒 Security Considerations

- Change default passwords in production
- Configure proper database permissions
- Enable HTTPS in production
- Regular security updates

## 📄 License

This project is licensed under the MIT License.

---

**© 2026 MediCare+ EMR System. Built with ❤️ for healthcare professionals.**
