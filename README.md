<div align="center">
    <img src="public/assets/images/homemoviehub-logo.png" alt="HomeMovieHub Logo" width="120">
</div>

<br>

# HomeMovieHub - <a href="https://homemoviehub.com" target="_blank">homemoviehub.com</a>

## Overview

Welcome to HomeMovieHub! It is a Vue.js and Laravel Web-App designed to create a streaming experience for home movies. The app combines the power of Vue & Laravel to create a Netflix-like experience to users and a safe place to store their precious footage.

## Features

### Netflix-like UI


A nice and family UI allows users to easily navigate their collection.

### Personal Profiles

Users can have each family member as a profile, which can be tagged in videos!

### See locations

Using the integrated Google Maps, users can pin the locations in their videos, allowing a better perspective of where they have been in the world


## Future Planned Updates

The next release of HomeMovieHub is planned to include:

- **Email Verification** Added security for something as private as a place for home movies

- **Enhanced User Profiles:** Like Netflix, users will be able to have a family account, and then profiles for each member that they can secondarily login to.

- **Enhanced Maps:** Enhance the locations section by allowing users to add notes and pictures for each map location pin, essentially helping to detail the story behind the scenes.

- **Code Improvements:** Refine the user interface and codebase for an improved user experience.

- **CI/CD Implementation:** Integrate Continuous Integration and Continuous Deployment processes with frontend testing to ensure a robust and reliable application.

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
<br>

## Step 3: Set Up Laravel Sail
Laravel Sail provides a Docker environment for running Laravel projects. 

To set it up, run the following command - this command uses a small Docker container containing PHP and Composer to install the application's dependencies:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```
When using the laravelsail/phpXX-composer image, you should use the same version of PHP that is listed in the `composer.json` file (80, 81, 82, or 83 - so if the version is '8.1', use '81' etc).

Next, make sure your docker engine is running (if using Docker Desktop, ensure you start the program), and then run the following:
```bash
./vendor/bin/sail up -d
``` 
This will start the Docker containers in the background (I recommend using the '-d' flag as above to start a detached container, so you will know when the container is up).

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
- **Port:** 3305

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

Visit http://localhost/register to create a new local user and access the app - enjoy!

## Troubleshooting

If you encounter any issues during the setup, consider the following:

- **Docker Issues:** Ensure Docker Desktop is running, and your system meets Docker's minimum requirements.

- **Port Conflicts:** If http://localhost doesn’t work, ensure that no other services are running on the default ports.

- **Environment Variables:** Ensure the .env file exists in your project root. If it doesn’t, you can create one by copying .env.example.

- **'Connection Refused' when running migrations** - This is likely due to your DB_HOST being incorrect. In Laravel sail, DB_CONNECTION and DB_HOST need to BOTH be 'mysql' as this is what it uses. Run the migration command again after making this change and it should work.

- **DB Access Denied Issue** - Can be caused by not updating the .env before you first sail up. Make sure you set up the DB details in your .env file BEFORE running the `./vendor/bin/sail up` command. If this does not work, then you need to run `./vendor/bin/sail down`, remove the docker volumes & images for the project and run `./vendor/bin/sail build`.

##

Thank you for using HomeMovieHub! If you have any questions or suggestions, please don't hesitate to reach out.
