# <p align="center">![tanzim-v2 logo](logo.png)</p>

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/11.x)

Clone the repository

    git clone git@github.com:mazenbassiouni/tanzim-v2.git

Switch to the repository folder

    cd tanzim-v2

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

create a symbolic link from public/storage to storage/app/public (**Make sure file upload size meet you requirements in php.ini**)

    php artisan storage:link

Start the local development server

    php artisan serve

**Add dependencies**

Install dependencies

    npm install

Serve with hot reload (development)

    npm run dev

Build for production with minification

    npm run build

**TL;DR command list**

    git clone git@github.com:mazenbassiouni/tanzim-v2.git
    cd tanzim-v2
    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan storage:link
    npm install
    npm run build
    
**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    php artisan migrate --seed
    php artisan serve

## Environment variables

- `.env` - Environment variables can be set in this file

## Filament admin panel

Create a new user account

    php artisan make:filament-user