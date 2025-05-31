# URL Shortener Application

A robust URL shortening service built with Laravel 10, offering user authentication, analytics, and a full-featured API.

## Features

- **Create Short URLs**: Generate short URLs from long ones
- **URL Analytics**: Track clicks, visitor information
- **User Authentication**: Register, login, and manage your own URLs
- **Expiration Dates**: Set expiration dates for your URLs
- **URL Status Management**: Enable/disable URLs as needed
- **URL Notifications**: Get notified when your URLs expire
- **API Access**: Programmatically access all features via REST API

## Tech Stack

- **Backend Framework**: Laravel 12
- **Database**: MySQL
- **Authentication**: Laravel Sanctum (API)
- **Frontend**: Bootstrap 5, Blade templates

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/neetabopche1603/url_shortner.git
   cd url-shortener
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Copy the environment file:
   ```bash
   cp .env.example .env
   ```

4. Generate an application key:
   ```bash
   php artisan key:generate
   ```

5. Configure your database in the `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=url_shortener
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Run migrations:
   ```bash
   php artisan migrate
   ```

7. Serve the application:
   ```bash
   php artisan serve
   ```

8. Visit `http://localhost:8000` in your browser.

## Project Structure

- **Models**: `Url`, `UrlVisit`, `User`
- **Controllers**: `UrlController`, `DashboardController`, `Api\UrlController`, `Api\AuthController`
- **Services**: `UrlService`, `NotificationService`
- **Commands**: `ProcessExpiredUrls`
- **Notifications**: `UrlExpiredNotification`
- **Views**: Various Blade templates for the frontend

## Usage

### Web Interface

1. Visit the homepage to create a short URL after login
2. Register or login to manage your URLs
3. View your dashboard to see all your URLs
4. Create, edit, delete, and view analytics for your URLs



Quick example:
```bash
# Create a short URL
curl -X POST https://yourdomain.com/api/urls \
  -H "Content-Type: application/json" \
  -d '{"original_url": "https://example.com/very/long/url"}'
```

## Scheduled Tasks

The application includes a scheduled command to handle expired URLs:

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('urls:process-expired')->daily();
}
```
