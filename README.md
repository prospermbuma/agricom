# AGRICOM
A web application enabling two-way communication between farmers and Village Extension Officers (VEOs) in Tanzania's agriculture system.

## ⚙️ Tech Stack
+ Laravel
+ Postgres

## 🚀 Project Setup
```
composer global require laravel installer
laravel new agricom
composer install
```

## Start Server
```
php artisan serve
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
php artisan make:controller PostController --resource
php artisan make:controller ProfileController --resource
php artisan make:controller CommentController --resource
php artisan make:controller LikeController --resource
```

## Public Storage Link Creation
```
php artisan storage:link
```