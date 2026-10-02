# Log Viewer

A Laravel-based log viewer application with Vue.js frontend for viewing and analyzing application logs.

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- MySQL/PostgreSQL/SQLite database
- Web server (Apache/Nginx) or use Laravel's built-in server

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/opcodesio/log-viewer.git
cd logviewer
```

### 2. Quick Setup (Recommended)

Run the automated setup script:

```bash
composer setup
```

This will:
- Install PHP dependencies
- Copy `.env.example` to `.env`
- Generate application key
- Run database migrations
- Install npm dependencies
- Build frontend assets

### 3. Manual Setup (Alternative)

If you prefer to set up manually:

```bash
# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env file
# Then run migrations
php artisan migrate

# Install JavaScript dependencies
npm install

# Build frontend assets
npm run build
```

## Configuration

### Database Setup

Edit `.env` file and configure your database connection:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=logviewer
DB_USERNAME=root
DB_PASSWORD=
```

For SQLite (simpler option):

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

### Application Settings

Update other environment variables as needed:

```env
APP_NAME="Log Viewer"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

## Running the Application

### Development Mode

Run the development server with hot reload:

```bash
composer dev
```

This starts:
- Laravel development server (http://localhost:8000)
- Queue worker
- Log viewer (Pail)
- Vite dev server with HMR

Alternatively, run services individually:

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Frontend dev server with hot reload
npm run dev

# Terminal 3 (optional): Queue worker
php artisan queue:listen

# Terminal 4 (optional): View logs
php artisan pail
```

### Production Mode

Build frontend assets for production:

```bash
npm run build
```

Configure your web server to point to the `public` directory.

## Testing

Run the test suite:

```bash
composer test
```

Or directly:

```bash
php artisan test
```

## Development

### Code Style

Format code using Laravel Pint:

```bash
./vendor/bin/pint
```

### Frontend Development

The application uses:
- Vue 3 with Composition API
- Pinia for state management
- Tailwind CSS 4.0 for styling
- Vite for bundling

Frontend files are located in:
- `resources/js/` - Vue components and stores
- `resources/views/` - Blade templates

### Backend Development

Key directories:
- `app/Http/Controllers/` - Controllers
- `app/Readers/` - Log reader implementations
- `routes/` - Application routes
- `database/migrations/` - Database migrations

## Troubleshooting

### Permission Issues

Ensure storage and cache directories are writable:

```bash
chmod -R 775 storage bootstrap/cache
```

### Frontend Build Issues

Clear npm cache and reinstall:

```bash
rm -rf node_modules package-lock.json
npm install
npm run build
```

### Database Issues

Reset database and run fresh migrations:

```bash
php artisan migrate:fresh
```

### Cache Issues

Clear application cache:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Credits

Based on [opcodesio/log-viewer](https://github.com/opcodesio/log-viewer)
