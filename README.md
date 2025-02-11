# Authentication Module

A robust authentication module for Laravel applications that provides flexible, secure authentication functionality with support for multiple authentication drivers.

## Features

- Multiple authentication drivers (Sanctum/Passport)
- User registration with email verification
- Login/logout functionality
- Password reset flow
- Token-based authentication
- JSON API responses
- Rate limiting
- Event-driven architecture

## Installation

1. Add the module to your Laravel project:
```bash
composer require your-agency/authentication-module
```

2. Publish the configuration:
```bash
php artisan module:publish-config Authentication
```

3. Run migrations:
```bash
php artisan module:migrate Authentication
```

## Configuration

```php
// config/authentication.php
return [
    'driver' => env('AUTH_DRIVER', 'sanctum'),
    'register_provides_token' => true,
    'register_must_confirm_email' => true,
];
```

## Events

The Authentication module provides several events that you can listen to in your application. These events follow Laravel's event system and can be used to extend functionality without modifying the module's code.

### Available Events

#### 1. UserRegistered

Fired when a new user successfully registers.

```php
use Modules\Authentication\Events\UserRegistered;

// In your EventServiceProvider
protected $listen = [
    UserRegistered::class => [
        SendWelcomeEmail::class,
        SetupUserProfile::class,
    ],
];
```

Event Properties:
- `$user`: Instance of `Modules\Authentication\Models\User`

Example Listener:
```php
namespace App\Listeners;

use Modules\Authentication\Events\UserRegistered;

class SendWelcomeEmail
{
    public function handle(UserRegistered $event)
    {
        $user = $event->user;
        // Your welcome email logic
    }
}
```

### Event Best Practices

1. **Queued Listeners**
   ```php
   namespace App\Listeners;
   
   use Illuminate\Contracts\Queue\ShouldQueue;
   use Modules\Authentication\Events\UserRegistered;
   
   class SendWelcomeEmail implements ShouldQueue
   {
       public function handle(UserRegistered $event)
       {
           // This will be processed in the background
       }
   }
   ```

2. **Error Handling**
   ```php
   public function handle(UserRegistered $event)
   {
       try {
           // Your listener logic
       } catch (\Exception $e) {
           // Log the error
           logger()->error('Failed to process UserRegistered event', [
               'user_id' => $event->user->id,
               'error' => $e->getMessage()
           ]);
       }
   }
   ```

3. **Event Subscribers**
   ```php
   namespace App\Subscribers;
   
   use Modules\Authentication\Events\UserRegistered;
   
   class UserEventSubscriber
   {
       public function handleUserRegistration($event)
       {
           // Handle the registration
       }
   
       public function subscribe($events)
       {
           $events->listen(
               UserRegistered::class,
               [UserEventSubscriber::class, 'handleUserRegistration']
           );
       }
   }
   ```

### Testing Events

The module provides helper methods for testing event listeners:

```php
use Modules\Authentication\Events\UserRegistered;
use Illuminate\Support\Facades\Event;

class UserRegistrationTest extends TestCase
{
    public function test_user_registered_event_is_dispatched()
    {
        Event::fake();

        // Perform registration...

        Event::assertDispatched(UserRegistered::class, function ($event) {
            return $event->user->email === 'test@example.com';
        });
    }
}
```

## API Documentation

### Registration Endpoint

```http
POST /api/v1/auth/register
```

Request body:
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

Response:
```json
{
    "message": "Registration successful. Please check your email to verify your account.",
    "access_token": "1|example_token",
    "token_type": "Bearer"
}
```

### Login Endpoint

```http
POST /api/v1/auth/login
```

Request body:
```json
{
    "email": "john@example.com",
    "password": "password"
}
```

Response:
```json
{
    "access_token": "1|example_token",
    "token_type": "Bearer"
}
```

## Contributing

When contributing to this module:

1. Follow PSR-12 coding standards
2. Add tests for new functionality
3. Update documentation
4. Create detailed pull requests

## Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the internal documentation
