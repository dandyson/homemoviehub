<div align="center">
    <img src="public/assets/images/homemoviehub-logo.png" alt="HomeMovieHub Logo" width="120">
</div>

<br>

# HomeMovieHub - <a href="https://homemoviehub.com" target="_blank">homemoviehub.com</a>

## Overview

HomeMovieHub is a Vue.js and Laravel Web-App designed to create a streaming experience for home movies. The app combines the power of Vue & Laravel to create a Netflix-like experience to users and a safe place to store their precious footage.

## Live Demo

Visit [HomeMovieHub](https://homemoviehub.com) to see the full application

## Features

### Netflix-like UI

A clean, family-friendly UI that allows users to easily navigate their collection.

### Personal Profiles

Users can have each family member as a profile, which can be tagged in videos!

### See locations

Using the integrated Google Maps, users can pin the locations in their videos, allowing a better perspective of where they have been in the world

<br>

## Technical Stack

- Frontend: Vue 3, Inertia.js, Vite, Tailwind CSS
- Backend: Laravel 10, PHP 8.2
- Database: MySQL
- Caching/Queue: Redis
- Authentication: Auth0 (auth0/login), Laravel Sanctum
- Testing: PHPUnit, Duster (lint)
- CI/CD: GitHub Actions
- Containerization: Docker, Laravel Sail
- Storage: AWS S3 (via Flysystem)
- Other: Google Maps (vue3-google-map, autocomplete), Plyr video player, FontAwesome, SweetAlert2


<br>

# HomeMovieHub Setup Guide

## Prerequisites

Before you can run this project on your local machine, make sure you have the following installed:

1. **Docker and Docker Compose**: Used for containerization, ensuring the application runs consistently across different environments (I highly recommend installing Docker Desktop for ease of use).
2. **Node.js (LTS version) and npm**: Required for managing frontend dependencies and building the frontend.
3. **Composer**: The dependency manager for PHP, used to install Laravel and other PHP packages.
<br>

## Step 1: Clone the Repository

First, clone the HomeMovieHub repository to your local machine using the following command:

```bash
git clone https://github.com/dandyson/homemoviehub.git
``` 

Then, navigate into the project directory:
```bash
cd homemoviehub
``` 
<br>

## Step 2: Configure Environment Variables
Create a new .env file by copying the example file:
```bash
cp .env.example .env
``` 
Then, open the .env file and ensure the database connection settings match the Docker setup:
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=homemoviehub_clone
DB_USERNAME=root
DB_PASSWORD=
```

***IMPORTANT*** Do not skip this step - ensure your env file has these, especially the db_connection and db_host before you start the container later on.

You also need the Auth0 settings, otherwise you won't be able to log in locally. Get these values from the Auth0 dashboard (or copy them from the production environment variables):
```
AUTH0_CLIENT_ID=
AUTH0_CLIENT_SECRET=
AUTH0_DOMAIN=
AUTH0_AUDIENCE=
```
`AUTH0_AUDIENCE` must be the **Identifier** of the API in Auth0 (Applications → APIs). Fill these in **before** your first login. See the Troubleshooting section if you logged in with them missing.
<br>

## Step 3: Set Up Laravel Sail
Laravel Sail provides a Docker environment for running Laravel projects. 

To set it up, run the following command - this command uses a small Docker container containing PHP and Composer to install the application's dependencies:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```
When using the laravelsail/phpXX-composer image, you should use the same version of PHP that is listed in the `composer.json` file (this project requires PHP 8.2, so use `php82`).

Next, make sure your docker engine is running (if using Docker Desktop, ensure you start the program), and then run the following:
```bash
./vendor/bin/sail up -d
``` 
This will start the Docker containers in the background (I recommend using the '-d' flag as above to start a detached container, so you will know when the container is up).

Check that **all** the containers are running, especially `mysql`:
```bash
docker compose ps
```
If `mysql` is missing or shows `Exited`, see the Troubleshooting section below before carrying on.

<br>

## Step 4: Generate Application key
This key is essential for maintaining the security of your application. Run the following the generate a new key (now Laravel Sail is installed, you can use it to run commands inside the docker container):
```bash
./vendor/bin/sail artisan key:generate
```
<br>

## Step 5: Install Frontend Dependencies
Next, install the Node.js dependencies using npm:
```bash
./vendor/bin/sail npm install
```
The first install inside Sail can take several minutes with no visible progress. Add `--verbose` if you want to see what it's doing. You only need this to run the frontend; the backend tests don't need it.
<br>

## Step 6: Run Database Migrations

Next, we will need to run the database migrations to set up the Database and the necessary tables:

```bash
./vendor/bin/sail artisan migrate
```

At this point, you can access the DB using a program like Sequel Pro or Sequel Ace etc. and use the following details to connect:

CONNECTION TYPE: TCP/IP
- **Host:** 0.0.0.0
- **Username:** root
- **Password:** (leave blank)
- **Port:** 3306 (or whatever `FORWARD_DB_PORT` is set to in your .env)

<br>

## Step 7: Create Symbolic Link for Storage:

This command creates a symbolic link from public/storage to storage/app/public so that files stored in storage/app/public can be publicly accessed, allowing upload images to store properly:

```bash
./vendor/bin/sail artisan storage:link
```

<br>

## Step 8: Build and Serve the Frontend
To build and serve the frontend assets using Vite, run:
```bash
./vendor/bin/sail npm run dev
``` 
This command starts a development server that serves your frontend assets and automatically refreshes the browser on changes.
<br>

## Step 9: Access the Application
Once everything is set up, you can access the application by visiting http://localhost in your web browser.

<br>

## Step 10: Run the Tests
```bash
./vendor/bin/sail artisan test
```
The tests use a separate `testing` database (set in `phpunit.xml`). Sail creates it automatically when the MySQL volume is first initialised, so you don't need to create it yourself.


## Understanding the Auth Layer

The application uses Auth0 in production to protect costly API routes. For local development, focus on exploring the architecture and UI components


## Troubleshooting

If you encounter any issues during the setup, consider the following:

- **Docker Issues:** Ensure Docker Desktop is running, and your system meets Docker's minimum requirements.

- **Port Conflicts:** If http://localhost doesn’t work, ensure that no other services are running on the default ports.

- **Environment Variables:** Ensure the .env file exists in your project root. If it doesn’t, you can create one by copying .env.example.

- **'Connection Refused' when running migrations** - This is likely due to your DB_HOST being incorrect. In Laravel sail, DB_CONNECTION and DB_HOST need to BOTH be 'mysql' as this is what it uses. Run the migration command again after making this change and it should work.

- **'getaddrinfo for mysql failed: Name or service not known'** - The `mysql` container isn't running. Check with `docker compose ps -a` and look at its logs with `docker compose logs mysql`.

- **MySQL container keeps exiting with 'Unable to setup unix socket lock file'** - A stale lock file was left behind after Docker shut down uncleanly. Remove it (this only deletes the lock file, not your data) and start MySQL again:
  ```bash
  docker run --rm -v homemoviehub_sail-mysql:/var/lib/mysql alpine rm -f /var/lib/mysql/mysql.sock /var/lib/mysql/mysql.sock.lock
  ./vendor/bin/sail up -d
  ```

- **'The JWT string must contain two dots' when logging in** - You logged in while `AUTH0_AUDIENCE` was empty, so Auth0 returned an opaque (non-JWT) access token, and it was saved in your Laravel session. Sessions are stored on the server in `storage/framework/sessions` (the browser only holds a cookie with the session ID), so fixing the .env or rebuilding the container won't replace the bad token. Set `AUTH0_AUDIENCE`, then clear the sessions and log in again in a fresh or incognito window:
  ```bash
  ./vendor/bin/sail artisan config:clear
  find storage/framework/sessions -type f ! -name .gitignore -delete
  ```

- **DB Access Denied Issue** - Can be caused by not updating the .env before you first sail up. Make sure you set up the DB details in your .env file BEFORE running the `./vendor/bin/sail up` command. If this does not work, then you need to run `./vendor/bin/sail down`, remove the docker volumes & images for the project and run `./vendor/bin/sail build`.

##

Thank you for using HomeMovieHub! If you have any questions or suggestions, please don't hesitate to reach out.
