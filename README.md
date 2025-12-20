# 📚 E-Learning Platform

A modern Learning Management System (LMS) built with Laravel 10, designed to provide a comprehensive online learning experience for students and educators.

## 🚀 Features (Planned)

### For Students
- 📖 Browse and enroll in courses
- 📹 Watch video lectures
- 📝 Take quizzes and assignments
- 📊 Track learning progress
- 💬 Discussion forums
- 📜 Download certificates

### For Instructors
- 🎓 Create and manage courses
- 📹 Upload course materials (videos, documents, presentations)
- ✍️ Create quizzes and assignments
- 📈 Monitor student progress
- 💰 Earnings dashboard
- 📊 Analytics and reports

### For Administrators
- 👥 User management (students, instructors, admins)
- 📚 Course management and approval
- 💳 Payment and transaction management
- 📊 System analytics and reports
- ⚙️ System configuration
- 🔒 Security and backup management

## 🛠️ Tech Stack

- **Framework**: Laravel 10.50.0
- **PHP**: 8.1.25
- **Database**: MySQL
- **Frontend**: Blade Templates, Vite
- **Authentication**: Laravel Sanctum
- **Package Manager**: Composer 2.9.2

## 📋 Prerequisites

- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Node.js & NPM (for frontend assets)
- Laragon/XAMPP/WAMP (for local development)

## 🔧 Installation

1. **Clone or navigate to the project directory**
   ```bash
   cd c:\laragon\www\e-learning
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Configure environment**
   - Copy `.env.example` to `.env` (already done during installation)
   - Update database credentials in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=e_learning
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Generate application key** (already done)
   ```bash
   php artisan key:generate
   ```

6. **Create database**
   - Create a new database named `e_learning` in your MySQL server

7. **Run migrations**
   ```bash
   php artisan migrate
   ```

8. **Seed database** (optional)
   ```bash
   php artisan db:seed
   ```

9. **Build frontend assets**
   ```bash
   npm run dev
   ```

10. **Start development server**
    ```bash
    php artisan serve
    ```
    
    Or if using Laragon, access via: `http://e-learning.test`

## 📁 Project Structure

```
e-learning/
├── app/                    # Application core
│   ├── Http/
│   │   ├── Controllers/   # Controllers
│   │   └── Middleware/    # Middleware
│   ├── Models/            # Eloquent models
│   └── Providers/         # Service providers
├── config/                # Configuration files
├── database/
│   ├── migrations/        # Database migrations
│   ├── seeders/          # Database seeders
│   └── factories/        # Model factories
├── public/               # Public assets
├── resources/
│   ├── views/           # Blade templates
│   ├── css/             # CSS files
│   └── js/              # JavaScript files
├── routes/
│   ├── web.php          # Web routes
│   └── api.php          # API routes
├── storage/             # Logs, cache, uploads
└── tests/               # Test files
```

## 🎯 Development Roadmap

### Phase 1: Foundation (Current)
- [x] Laravel installation
- [ ] Database schema design
- [ ] Authentication system
- [ ] User roles and permissions

### Phase 2: Core Features
- [ ] Course management
- [ ] Content management (videos, documents)
- [ ] Quiz and assignment system
- [ ] Progress tracking

### Phase 3: Advanced Features
- [ ] Payment integration
- [ ] Certificate generation
- [ ] Discussion forums
- [ ] Live classes (optional)

### Phase 4: Optimization
- [ ] Performance optimization
- [ ] Security hardening
- [ ] Testing and QA
- [ ] Deployment

## 🧪 Testing

Run tests using PHPUnit:
```bash
php artisan test
```

## 📚 Documentation

For detailed installation and development guide, see [INSTALLATION.md](INSTALLATION.md)

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Development Team

- **Developer**: [Your Name]
- **Project Start**: December 20, 2025

## 📞 Support

For support and questions, please contact [your-email@example.com]

---

**Built with ❤️ using Laravel**
