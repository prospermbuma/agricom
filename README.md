# AGRICOM
A web application enabling two-way communication between farmers and Village Extension Officers (VEOs) in Tanzania's agriculture system.

## ⚙️ Tech Stack
+ Laravel
+ Postgres

## 🚀 Project Setup

### FRONT-END SETUP
Install frontend dependencies
```
npm install
npm install pusher-js laravel-echo
```

### BACK-END SETUP
Initialize project and Install backend dependencies
```
composer global require laravel installer
laravel new agricom
composer install
```

## Models Creation
```
php artisan make:model User -m
php artisan make:model Region -m
php artisan make:model Village -m
php artisan make:model Crop -m
php artisan make:model FarmerProfile -m
php artisan make:model Article -m
php artisan make:model Comment -m
php artisan make:model FarmerCrop -m
php artisan make:model ChatParticipant -m
php artisan make:model ChatMessage -m
```

## Laravel UI Installation
```
composer require laravel/ui
```

## Authentication UI Scaffolding
```
php artisan ui bootstrap --auth
```

## Building Controllers
```
php artisan make:controller UserController --resource
php artisan make:controller RegionController --resource
php artisan make:controller VillageController --resource
php artisan make:controller CropController --resource
php artisan make:controller FarmerProfileController --resource
php artisan make:controller ArticleController --resource
php artisan make:controller CommentController --resource
php artisan make:controller FarmerCropController --resource
php artisan make:controller ChatParticipantController --resource
php artisan make:controller ChatMessageController --resource
```

## Public Storage Link Creation
```
php artisan storage:link
```

## Laravel Sanctum Setup
Laravel Sanctum - A simple authentication system for SPAs (Single Page Applications), mobile apps, and token-based APIs.

Use cases:

+ API token authentication (like for mobile or frontend apps)

+ Session-based authentication for SPAs using Laravel's built-in session/cookie handling

Key features:

+ Issue and manage API tokens easily

+ Protect routes using middleware (auth:sanctum)

```
composer require laravel/sanctum
```

## Pusher (Real-time WebSocket communication) Setup
Pusher PHP SDK allows Laravel to send real-time events to the Pusher service.

Use cases:

+ Real-time notifications

+ Live chat apps

+ Broadcast events to frontend using Pusher (WebSockets)

How it works:
<br>
Laravel emits events via broadcasting, and Pusher delivers them instantly to connected clients via WebSocket.

```
composer require pusher/pusher-php-server
```

## Intervention Image Setup
Intervention Image - A powerful image handling and manipulation library for PHP.

Use Cases:
+ Resize, crop, watermark, or optimize images

+ Generate thumbnails

+ Save image uploads with modifications

Laravel integration:
<br>
Works well with file uploads in controllers and can be configured as a Laravel service provider.

```
composer require intervention/image
```

## Spatie's Activity Setup
Spatie's Activity Log package is for logging changes and actions in your Laravel app

Use cases:

+ Track user actions (e.g., login, updates, deletions)

+ Audit trails and system logs

+ View who did what and when

Features:

+ Automatically logs model events

+ Customizable log names and descriptions

+ Stores data in a dedicated activity_log table

```
composer require spatie/laravel-activitylog
```

## Start Server
Run:
```
php artisan serve
```