# LostCity Market

A Laravel application with Inertia.js and Vue (TypeScript).

## Prerequisites

Before you begin, ensure you have the following installed on your system:

- PHP 8.2 or higher
- Composer
- Node.js 18.x or higher and npm
- MySQL or PostgreSQL database
- Git

## Local Setup

Follow these steps to set up the project locally:

### 1. Clone the Repository

```bash
git clone https://github.com/K-John/LostCity-Market.git
cd LostCity-Market
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the example environment file and configure your environment variables:

```bash
cp .env.example .env
```

Open `.env` and update the following variables:

- **Database Configuration**:
  ```
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=lostcity_market
  DB_USERNAME=your_database_username
  DB_PASSWORD=your_database_password
  ```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Create Database

Create a new database for the application:

```bash
mysql -u root -p
CREATE DATABASE lostcity_markets;
exit;
```

### 7. Run Migrations

```bash
php artisan migrate
```

### 8. Seed Database (Optional)

If you want to populate the database with sample data:

```bash
php artisan db:seed
```

### 9. Build Frontend Assets

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

### 10. Start the Development Server

In a separate terminal, start the Laravel development server:

```bash
php artisan serve
```

The application should now be running at `http://localhost:8000`.

## Development Workflow

### Running the Application

1. Start the Laravel server:
   ```bash
   php artisan serve
   ```

2. In a separate terminal, run the Vite dev server for hot module replacement:
   ```bash
   npm run dev
   ```

### Development Login

For quick access during development, you can use the development login route:

Visit `http://localhost:8000/dev/login` to automatically log in with the development account.

## Troubleshooting

### Clear Cache

If you encounter issues, try clearing the application cache:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```
