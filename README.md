# Laravel 8 Authorization with Policy and Sanctum

This guide explains how to implement authorization for managing book data in Laravel 8 using Policies. There will be two types of users:

1. **Viewer (User 1):** Can only view the data.
2. **Admin (User 2):** Can add, edit, delete, and view the data.

Additionally, authentication is implemented using Laravel Sanctum to ensure only logged-in users can access the API.

---

## Installation and Setup

### 1. Clone the Repository
```bash
git clone https://github.com/adjisdhani/lara8-autho-policy.git
cd lara8-autho-policy
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Configure `.env` File
Set your database configuration in the `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Seed the Database
Seed the database to create two users (Viewer and Admin):
```bash
php artisan db:seed --class=UserSeeder
```

### 6. Install Laravel Sanctum
Install Sanctum for API authentication:
```bash
composer require laravel/sanctum
```

### 7. Publish Sanctum Configuration
Publish the Sanctum configuration and run the migrations:
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

---

## Usage

### 1. Login to Obtain a Token
Use the login endpoint to obtain a token for the Viewer or Admin account.

**Endpoint:**
```http
POST /api/login
```
**Request Body:**
```json
{
    "email": "viewer@example.com",
    "password": "password"
}
```
**Response Example:**
```json
{
    "user": {
        "id": 1,
        "name": "Viewer User",
        "email": "viewer@example.com",
        "role": "viewer"
    },
    "token": "1|oO8Z8kxpqpPzLH2H79KYZSwHq2Xm..."
}
```

### 2. Use the Token to Access API Endpoints
Include the token in the `Authorization` header for all subsequent requests:
```
Authorization: Bearer {TOKEN}
```

### 3. Access Book Data Endpoints

#### **Viewer (User 1)**:
- **View all books:**
  ```http
  GET /api/books
  ```
- **View a specific book:**
  ```http
  GET /api/books/{id}
  ```
- **Other actions (add, edit, delete)**:
  Will return a **403 Forbidden** response.

#### **Admin (User 2)**:
- **Add a book:**
  ```http
  POST /api/books
  {
      "title": "Book Title",
      "author": "Book Author"
  }
  ```
- **Edit a book:**
  ```http
  PUT /api/books/{id}
  {
      "title": "Updated Title",
      "author": "Updated Author"
  }
  ```
- **Delete a book:**
  ```http
  DELETE /api/books/{id}
  ```

---

## Notes

1. Ensure that the `auth:sanctum` middleware is applied to protect the endpoints in `routes/api.php`:
   ```php
   Route::middleware('auth:sanctum')->group(function () {
       Route::apiResource('books', BookController::class);
   });
   ```

2. Customize error responses for unauthorized actions by overriding the `render` method in `App\Exceptions\Handler.php`. Add the following:
   ```php
   use Illuminate\Auth\Access\AuthorizationException;

   public function render($request, Throwable $exception)
   {
       if ($exception instanceof AuthorizationException) {
           return response()->json([
               'message' => 'You are not authorized to perform this action.',
           ], 403);
       }

       return parent::render($request, $exception);
   }
   ```

3. After login, all API requests must include the token in the header as shown above.

---

Now you have a fully functional system with role-based authorization and authentication using Laravel Policies and Sanctum. If you encounter any issues, feel free to raise them in the repository!