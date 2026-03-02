## **MacFit – Simple Gym Management System**

---

## Introduction

MacFit is a Laravel-based backend system to manage gym branches, workout categories, and dynamic membership bundles. The system centralizes gym operations by handling branch management, structured workout categories, and flexible membership packages with scheduling capabilities.

Built with Laravel 12 and powered by MySQL, MacFit provides a scalable and maintainable backend architecture suitable for gym chains and fitness centers.

---

## Features

### Gym Management
- Manage multiple gym locations and branches.
- Store branch-specific data including formatted names (e.g., `GymName-WorkoutArea`).

### Category System
- Organize workouts by type:
  - Strength
  - Yoga
  - HIIT
  - Cardio
  - And more...

### Dynamic Membership Bundles
Create flexible membership packages with:
- Configurable session durations (in hours)
- Linked gym locations
- Daily start times
- Schedule-based logic
- Extendable structure for subscriptions

---

## Tech Stack

| Component | Version |
|------------|----------|
| Framework | Laravel 12.51.0 |
| Language | PHP 8.5.3 |
| Database | MySQL |
| Time Handling | Carbon |

---

## Installation

### 1️⃣ Prerequisites

Ensure the following are installed:

- PHP
- Composer
- MySQL

---

### 2️⃣ Clone the Repository

```bash
git clone https://github.com/alexamita/MacFit-Gym-Management.git
cd macfit
```

---

### 3️⃣ Install Dependencies

```bash
composer install
```

---

### 4️⃣ Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

---

## Database Setup

1. Create a new MySQL database:

```sql
CREATE DATABASE macfit_db;
```

2. Update `.env` file:

```
DB_DATABASE=macfit_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

3. Run migrations and seeders:

```bash
php artisan migrate:fresh --seed
```

This will:
- Create all necessary tables
- Populate gyms, roles, categories, equipment, and bundles.

---

## Usage

Start the development server:

```bash
php artisan serve
```

Default URL:

```
http://127.0.0.1:8000
```

API base path:

```
/api/
```

---

## API Endpoint Structure

### Bundles

| Method | Endpoint | Description |
|--------|----------|------------|
| POST | `/api/saveBundle` | Create a new workout bundle |
| GET | `/api/getBundles` | List all available bundles |
| GET | `/api/getBundle/{id}` | Get details of a specific bundle |
| POST | `/api/updateBundles/{id}` | Update an existing bundle |
| DELETE | `/api/deleteBundle/{id}` | Delete a bundle |

---

## Dependencies

- Laravel Framework
- Carbon (Date & Time Handling)
- MySQL Driver
- Composer Packages (managed via `composer.json`)

---

## License

This project is open-source and available under the MIT License.
