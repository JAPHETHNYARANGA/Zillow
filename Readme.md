# Zillow Full Stack Application

This repository contains the full-stack application for the Zillow-like platform. It consists of two main parts:
1. **Backend**: A Laravel 12.2.0 API handling server-side logic and database management.
2. **Frontend**: An Angular application that communicates with the Laravel backend.
3. **Docker**: Docker configuration files to run both frontend and backend services within containers.

---

## Table of Contents

- [Requirements](#requirements)
- [Setup](#setup)
  - [Backend](#backend)
  - [Frontend](#frontend)
  - [Docker](#docker)
- [Usage](#usage)
- [Directory Structure](#directory-structure)
- [License](#license)


## Requirements

- PHP 8.3.7 (for Laravel backend)
- Composer (for managing PHP dependencies)
- Node.js and npm (for Angular frontend)
- Docker (for containerized environment)


## Setup

### Backend

1. Navigate to the `backend/zillow-backend` directory:
   ```bash
   cd backend/zillow-backend
   `composer install`
`cp .env.example .env`
`php artisan key:generate`
`php artisan migrate`
`php artisan serve`


### Frontend
cd frontend
npm install
ng serve


### Docker

Docker is used to containerize the application, both the backend (Laravel) and frontend (Angular).

1- Ensure Docker and Docker Compose are installed:
* Install Docker: Install Docker
* Install Docker Compose: Install Docker Compose

2- Build and run the application with Docker Compose:

 * cd /path/to/your/project/docker/
 * `docker-compose up --build`

3- Access the Application:

* Frontend (Angular): Once Docker is running, open your browser and go to: `http://localhost:4200`

* Backend (Laravel): You can access the Laravel backend API at: `http://localhost:8000`

4 - Stop Docker Containers:
* When you're done, you can stop the containers with the following command:
`docker-compose down`







