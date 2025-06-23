# AGRICOM - Agricultural Communication Platform

A comprehensive web application enabling two-way communication between farmers and Village Extension Officers (VEOs) in Tanzania's agriculture system. This platform facilitates knowledge sharing, real-time communication, and agricultural information dissemination.

## 🌟 Features

### Core Functionality
- **User Management**: Multi-role system (Farmers, VEOs, Administrators)
- **Article System**: Publish and manage agricultural content with categorization
- **Real-time Chat**: Direct messaging between users with file sharing
- **Notification System**: Real-time alerts for new content and interactions
- **Activity Logging**: Comprehensive audit trail of user actions
- **Profile Management**: Detailed user profiles with farming information
- **Regional Organization**: Tanzania-based regional and village structure
- **Crop Management**: Track farmer crops and agricultural activities

### User Roles
- **Farmers**: Access relevant articles, participate in discussions, manage profiles
- **Village Extension Officers (VEOs)**: Create articles, manage content, communicate with farmers
- **Administrators**: Full system management, user administration, analytics

## ⚙️ Tech Stack
- **Backend**: Laravel 12.x (PHP 8.2+)
- **Database**: PostgreSQL / MySQL
- **Frontend**: Blade Templates, Bootstrap 5, JavaScript
- **Real-time**: Pusher WebSockets
- **Authentication**: Laravel Sanctum
- **File Handling**: Intervention Image
- **Activity Logging**: Spatie Laravel Activity Log
- **Development**: Vite, NPM

## 🚀 Project Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- PostgreSQL or MySQL
- Git

### Environment Setup
1. Clone the repository:
```bash
git clone <repository-url>
cd agricom_02
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install frontend dependencies:
```bash
npm install
npm install pusher-js laravel-echo
```

4. Copy environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=agricom
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Configure Pusher for real-time features:
```env
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1
```

### Database Setup

#### Run Migrations
```bash
php artisan migrate
```

#### Seed Database
```bash
php artisan db:seed
```

#### Create Storage Link
```bash
php artisan storage:link
```

### Development Server
```bash
# Start Laravel development server
php artisan serve

# Start Vite development server (in another terminal)
npm run dev

# Or run both simultaneously
composer run dev
```

## 📁 Project Structure

### Models
```
app/Models/
├── User.php                 # User authentication and profiles
├── Article.php              # Agricultural articles and content
├── Comment.php              # Article comments and discussions
├── ChatConversation.php     # Chat conversations
├── ChatMessage.php          # Individual chat messages
├── ChatParticipant.php      # Chat participants management
├── Notification.php         # User notifications
├── ActivityLog.php          # System activity logging
├── FarmerProfile.php        # Detailed farmer information
├── FarmerCrop.php           # Farmer crop tracking
├── Crop.php                 # Crop catalog
├── Region.php               # Tanzania regions
└── Village.php              # Villages within regions
```

### Controllers
```
app/Http/Controllers/
├── AuthController.php           # Authentication (login, register, logout)
├── DashboardController.php      # Dashboard views and statistics
├── ProfileController.php        # User profile management
├── ArticleController.php        # Article CRUD operations
├── ChatController.php           # Real-time chat functionality
├── NotificationController.php   # Notification management
├── ActivityLogController.php    # Activity log viewing
└── Admin/
    └── UserManagementController.php  # Admin user management
```

### Services
```
app/Services/
├── ActivityLogService.php       # Activity logging service
└── NotificationService.php      # Notification management service
```

### Middleware
```
app/Http/Middleware/
├── AdminMiddleware.php          # Admin access control
├── RoleMiddleware.php           # Role-based access control
└── ActivityLogMiddleware.php    # Automatic activity logging
```

## 🔧 Advanced Setup

### Laravel Sanctum Setup
Laravel Sanctum provides a simple authentication system for SPAs, mobile apps, and token-based APIs.

**Use cases:**
- API token authentication for mobile/frontend apps
- Session-based authentication for SPAs

**Key features:**
- Issue and manage API tokens easily
- Protect routes using middleware (`auth:sanctum`)

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### Pusher Real-time Communication Setup
Pusher PHP SDK enables real-time WebSocket communication.

**Use cases:**
- Real-time notifications
- Live chat applications
- Broadcast events to frontend

**How it works:**
Laravel emits events via broadcasting, and Pusher delivers them instantly to connected clients via WebSocket.

```bash
composer require pusher/pusher-php-server
```

### Intervention Image Setup
Intervention Image provides powerful image handling and manipulation.

**Use cases:**
- Resize, crop, watermark, or optimize images
- Generate thumbnails
- Process image uploads

```bash
composer require intervention/image
```

### Spatie Activity Log Setup
Spatie's Activity Log package tracks changes and actions in your Laravel app.

**Use cases:**
- Track user actions (login, updates, deletions)
- Audit trails and system logs
- View who did what and when

**Features:**
- Automatically logs model events
- Customizable log names and descriptions
- Stores data in dedicated activity_log table

```bash
composer require spatie/laravel-activitylog
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan migrate
```

## 🗄️ Database Schema

### Core Tables
- **users**: User accounts and authentication
- **articles**: Agricultural content and articles
- **comments**: Article comments and discussions
- **chat_conversations**: Chat conversation metadata
- **chat_messages**: Individual chat messages
- **chat_participants**: Chat participant management
- **notifications**: User notification system
- **activity_logs**: System activity tracking

### Agricultural Tables
- **farmer_profiles**: Detailed farmer information
- **farmer_crops**: Farmer crop tracking and management
- **crops**: Crop catalog and information
- **regions**: Tanzania regions
- **villages**: Villages within regions

## 🔐 Authentication & Authorization

### User Roles
1. **Farmer**: Access articles, participate in discussions, manage profile
2. **VEO**: Create articles, manage content, communicate with farmers
3. **Admin**: Full system administration and user management

### Access Control
- Role-based middleware for route protection
- Policy-based authorization for model operations
- Activity logging for security auditing

## 📱 API Endpoints

### Authentication
- `POST /login` - User login
- `POST /register` - User registration
- `POST /logout` - User logout

### Articles
- `GET /articles` - List articles
- `POST /articles` - Create article (VEO/Admin only)
- `GET /articles/{id}` - View article
- `PUT /articles/{id}` - Update article
- `DELETE /articles/{id}` - Delete article

### Chat
- `GET /chat` - List conversations
- `POST /chat/conversations` - Create conversation
- `GET /chat/{id}` - View conversation
- `POST /chat/{id}/messages` - Send message

### Notifications
- `GET /notifications` - List notifications
- `PATCH /notifications/{id}/read` - Mark as read
- `DELETE /notifications/{id}` - Delete notification

## 🚀 Deployment

### Production Setup
1. Set environment to production:
```env
APP_ENV=production
APP_DEBUG=false
```

2. Optimize for production:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

3. Set up queue workers for background jobs:
```bash
php artisan queue:work
```

### Server Requirements
- PHP 8.2+
- PostgreSQL 12+ or MySQL 8.0+
- Redis (for caching and queues)
- Web server (Apache/Nginx)

## 🧪 Testing

### Run Tests
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter=ArticleTest

# Run with coverage
php artisan test --coverage
```

### Test Structure
```
tests/
├── Feature/           # Feature tests
├── Unit/             # Unit tests
└── TestCase.php      # Base test case
```

## 📊 Monitoring & Logging

### Activity Logging
- Automatic logging of user actions
- Configurable log levels and retention
- Export capabilities for audit purposes

### Error Tracking
- Laravel's built-in error logging
- Custom error handling for specific scenarios
- Performance monitoring integration

## 🔧 Maintenance

### Database Maintenance
```bash
# Clear all data
php artisan db:wipe

# Refresh migrations and seed
php artisan migrate:fresh --seed

# Backup database
php artisan db:backup
```

### Cache Management
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### File Storage
```bash
# Create storage link
php artisan storage:link

# Clean up old files
php artisan storage:clean
```

## 🚨 Troubleshooting

### Common Issues

#### Database Connection Issues
```bash
# Check database connection
php artisan tinker
DB::connection()->getPdo();

# Clear config cache
php artisan config:clear
```

#### Permission Issues
```bash
# Set proper permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

#### Composer Issues
```bash
# Clear composer cache
composer clear-cache

# Reinstall dependencies
rm -rf vendor/
composer install
```

#### NPM Issues
```bash
# Clear npm cache
npm cache clean --force

# Reinstall node modules
rm -rf node_modules/
npm install
```

## 🔄 Development Workflow

### Git Workflow
```bash
# Create feature branch
git checkout -b feature/new-feature

# Make changes and commit
git add .
git commit -m "Add new feature"

# Push to remote
git push origin feature/new-feature

# Create pull request
```

### Code Quality
```bash
# Run Laravel Pint (code style)
./vendor/bin/pint

# Run PHPStan (static analysis)
./vendor/bin/phpstan analyse

# Run tests
php artisan test
```

## 📚 Additional Resources

### Documentation
- [Laravel Documentation](https://laravel.com/docs)
- [Bootstrap Documentation](https://getbootstrap.com/docs)
- [Pusher Documentation](https://pusher.com/docs)
- [Spatie Activity Log](https://spatie.be/docs/laravel-activitylog)

### Useful Commands
```bash
# Generate model with migration
php artisan make:model ModelName -m

# Generate controller with resource methods
php artisan make:controller ControllerName --resource

# Generate policy
php artisan make:policy ModelNamePolicy

# Generate seeder
php artisan make:seeder SeederName

# List all routes
php artisan route:list

# Clear all caches
php artisan optimize:clear
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Coding Standards
- Follow PSR-12 coding standards
- Use Laravel conventions
- Write tests for new features
- Update documentation

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the documentation

## 🔄 Version History

### v1.0.0 (Current)
- Initial release with core functionality
- User management and authentication
- Article system with categorization
- Real-time chat functionality
- Notification system
- Activity logging
- Regional organization
- Crop management

---

**Note**: This application is designed specifically for Tanzania's agricultural system and includes regional data and crop information relevant to the local context.