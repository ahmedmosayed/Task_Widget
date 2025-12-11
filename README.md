# Dynamic Task Widget# Dynamic Task Widget

A full-stack Laravel + Blade + AJAX task management application featuring real-time task creation, completion toggling, and deletion without page reloads.A full-stack Laravel + Blade + AJAX task management application featuring real-time task creation, completion toggling, and deletion without page reloads.

## Features## Features

-   **User Authentication**: Register and login with Laravel Breeze- **User Authentication**: Register and login with Laravel Breeze

-   **Task Management API**: RESTful API endpoints protected with Sanctum authentication- **Task Management API**: RESTful API endpoints protected with Sanctum authentication

-   **Real-time AJAX Widget**: Add, toggle, and delete tasks instantly without page refresh- **Real-time AJAX Widget**: Add, toggle, and delete tasks instantly without page refresh

-   **Tailwind CSS Styling**: Modern, responsive UI with Tailwind CSS- **Tailwind CSS Styling**: Modern, responsive UI with Tailwind CSS

-   **Session & Token Auth**: Support for both session-based (SPA) and Bearer token authentication- **Session & Token Auth**: Support for both session-based (SPA) and Bearer token authentication

## Tech Stack## Tech Stack

-   **Backend**: Laravel 11 with Sanctum for API authentication- **Backend**: Laravel 12 with Sanctum for API authentication

-   **Database**: MySQL with migrations for users and tasks- **Database**: MySQL with migrations for users and tasks

-   **Frontend**: Blade templates with vanilla JavaScript and Tailwind CSS- **Frontend**: Blade templates with vanilla JavaScript and Tailwind CSS

-   **Build**: Vite for asset bundling- **Build**: Vite for asset bundling

## Prerequisites## Prerequisites

-   PHP 8.1+- PHP 8.1+

-   Composer- Composer

-   Node.js 18+- Node.js 18+

-   npm or yarn- npm or yarn

-   MySQL 8.0+- MySQL 8.0+

## Installation & Setup## Installation & Setup

### 1. Clone the repository### 1. Clone the repository

```bash${'`'}bash

git clone <repository-url>git clone <repository-url>

cd Task_Widgetcd Task_Widget

```${'`'}

### 2. Install PHP dependencies### 2. Install PHP dependencies

```bash${'`'}bash

composer installcomposer install

```${'`'}

### 3. Install Node.js dependencies### 3. Install Node.js dependencies

```bash${'`'}bash

npm installnpm install

```${'`'}

### 4. Environment configuration### 4. Environment configuration

```bash${'`'}bash

cp .env.example .envcp .env.example .env

php artisan key:generatephp artisan key:generate

```${'`'}

Edit `.env` to configure your MySQL database:Edit ${'.env'} to configure your MySQL database:

```${'`'}

DB_CONNECTION=mysqlDB_CONNECTION=mysql

DB_HOST=127.0.0.1DB_HOST=127.0.0.1

DB_PORT=3306DB_PORT=3306

DB_DATABASE=task_widgetDB_DATABASE=task_widget

DB_USERNAME=rootDB_USERNAME=root

DB_PASSWORD=DB_PASSWORD=

```${'`'}

### 5. Run database migrations### 5. Run database migrations

```bash${'`'}bash

php artisan migratephp artisan migrate

```${'`'}

### 6. Build frontend assets### 6. Build frontend assets

```bash${'`'}bash

npm run buildnpm run build

```${'`'}

For development with hot reload:For development with hot reload:

```bash${'`'}bash

npm run devnpm run dev

```${'`'}

## Running the Application## Running the Application

### Start the Laravel development server### Start the Laravel development server

```bash${'`'}bash

php artisan serve --port=8000php artisan serve --port=8000

```${'`'}

The application will be available at: **http://localhost:8000**The application will be available at: **http://localhost:8000**

### Login / Register### Login / Register

1. Visit http://localhost:8000/register1. Visit http://localhost:8000/register

2. Create a new account with email and password2. Create a new account with email and password

3. After registration, you will be redirected to the dashboard3. After registration, you will be redirected to the dashboard

### Using the Task Widget### Using the Task Widget

On the dashboard (`/dashboard`), you can:On the dashboard (${'/dashboard'}), you can:

-   **Add a Task**: Type a task title in the input field and click "Add" (or press Enter)- **Add a Task**: Type a task title in the input field and click "Add" (or press Enter)

    -   The task appears instantly in the list (AJAX POST `/api/tasks`) - The task appears instantly in the list (AJAX POST ${'/api/tasks'})

    -   No page reload occurs - No page reload occurs

-   **Toggle Task Completion**: Click the checkbox next to a task- **Toggle Task Completion**: Click the checkbox next to a task

    -   The task title turns grey with strikethrough styling when completed (AJAX PATCH `/api/tasks/{id}/toggle`) - The task title turns grey with strikethrough styling when completed (AJAX PATCH ${'/api/tasks/{id}/toggle'})

    -   Uncheck to mark as incomplete - Uncheck to mark as incomplete

-   **Delete a Task**: Hover over a task and click the "Delete" button- **Delete a Task**: Hover over a task and click the "Delete" button

    -   Confirmation dialog appears before deletion (AJAX DELETE `/api/tasks/{id}`) - Confirmation dialog appears before deletion (AJAX DELETE ${'/api/tasks/{id}'})

    -   Task is removed from the list instantly - Task is removed from the list instantly

## API Endpoints## Demo Video

All endpoints require authentication (`auth:sanctum`). Support both Bearer token and session-based auth.A demo video (1–3 minutes) is included in the ${'demo.mp4'} file, showing:

1. Running the project locally (${'php artisan serve'})

### Tasks2. Visiting the dashboard and registering/logging in

3. Adding a new task using the form

-   **GET `/api/tasks`** - List all tasks for the logged-in user4. Seeing the new task appear instantly (no page reload)

    -   Response: `{ "data": [{ "id": 1, "title": "...", "completed": false, "user": {...} }, ...] }`5. Toggling task completion with the checkbox

6. Seeing the visual update (grey text + line-through when completed)

-   **POST `/api/tasks`** - Create a new task7. Deleting a task from the list

    -   Request body: `{ "title": "Task title" }`

    -   Response: `{ "data": { "id": 1, "title": "Task title", "completed": false, "user": {...} } }`## Troubleshooting

    -   Status: 201 Created

### "Error creating task: Unauthenticated"

-   **GET `/api/tasks/{id}`** - Get a single task by ID- Ensure you are logged in (session exists or Bearer token is valid)

    -   Response: `{ "data": { "id": 1, "title": "...", "completed": false, "user": {...} } }`- Check the browser DevTools → Network tab for the API response

-   Verify the CSRF token is being sent (X-CSRF-TOKEN header or XSRF-TOKEN cookie)

-   **PUT `/api/tasks/{id}`** - Update task title

    -   Request body: `{ "title": "Updated title" }`### Tasks not appearing after adding

    -   Response: `{ "data": { "id": 1, "title": "Updated title", "completed": false, "user": {...} } }`- Check the Network tab for the POST request status (should be 201)

    -   Status: 200 OK- Verify the response contains data.id and data.title

-   Check browser console for any JavaScript errors

-   **PATCH `/api/tasks/{id}/toggle`** - Toggle task completion status

    -   Response: `{ "data": { "id": 1, "title": "...", "completed": true, "user": {...} } }`For full documentation, see the inline comments in the code and the project structure section in the original Laravel documentation.

    -   Status: 200 OK

-   **DELETE `/api/tasks/{id}`** - Delete a task
    -   Response: `{ "message": "Task deleted successfully" }`
    -   Status: 200 OK

### Authentication Endpoints

-   **POST `/api/register`** - Register a new user

    -   Request body: `{ "name": "John Doe", "email": "john@example.com", "password": "secret", "password_confirmation": "secret" }`
    -   Response: `{ "message": "User registered", "access_token": "...", "token_type": "Bearer" }`
    -   Status: 201 Created

-   **POST `/api/login`** - Login an existing user

    -   Request body: `{ "email": "john@example.com", "password": "secret" }`
    -   Response: `{ "message": "User logged in", "access_token": "...", "token_type": "Bearer" }`
    -   Status: 200 OK

-   **POST `/api/logout`** - Logout the current user (requires authentication)

    -   Response: `{ "message": "Logged out" }`
    -   Status: 200 OK

-   **GET `/api/user`** - Get current authenticated user (requires authentication)
    -   Response: `{ "id": 1, "name": "John Doe", "email": "john@example.com", ... }`

## Testing the API

### Using cURL (Bearer Token)

1. Register a user and get the token:

```bash
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"name":"John","email":"john@example.com","password":"secret","password_confirmation":"secret"}'
```

Response:

```json
{
    "message": "User registered",
    "access_token": "1|abc123...",
    "token_type": "Bearer"
}
```

2. Create a task with the token:

```bash
curl -X POST http://127.0.0.1:8000/api/tasks \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 1|abc123..." \
  -d '{"title":"Buy groceries"}'
```

Response:

```json
{
    "data": {
        "id": 1,
        "title": "Buy groceries",
        "completed": false,
        "user": { "id": 1, "name": "John", "email": "john@example.com" }
    }
}
```

3. List all tasks:

```bash
curl -X GET http://127.0.0.1:8000/api/tasks \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|abc123..."
```

4. Toggle task completion:

```bash
curl -X PATCH http://127.0.0.1:8000/api/tasks/1/toggle \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|abc123..."
```

5. Delete a task:

```bash
curl -X DELETE http://127.0.0.1:8000/api/tasks/1 \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|abc123..."
```

### Using the Browser

1. Visit http://localhost:8000/register
2. Create an account
3. Navigate to /dashboard
4. Open Developer Tools (F12) → Network tab
5. Add a task and observe the AJAX POST request to `/api/tasks`
6. Toggle task and observe PATCH request to `/api/tasks/{id}/toggle`
7. Delete task and observe DELETE request to `/api/tasks/{id}`
8. Click on any request to see request headers, response body, and HTTP status

## Demo Video

A demo video (1–3 minutes) is included in the `demo.mp4` file, showing:

1. Running the project locally (`php artisan serve`)
2. Visiting the dashboard and registering/logging in
3. Adding a new task using the form
4. Seeing the new task appear instantly (no page reload)
5. Toggling task completion with the checkbox
6. Seeing the visual update (grey text + line-through when completed)
7. Deleting a task from the list

## Project Structure

```
.
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── TaskController.php      (Task CRUD API)
│   │   │   ├── AuthController.php      (Register/Login/Logout)
│   │   │   └── ProfileController.php   (User profile)
│   │   └── Requests/
│   │       ├── StoreTaskRequest.php    (Task validation)
│   │       ├── RegisterRequest.php     (Registration validation)
│   │       └── LoginRequest.php        (Login validation)
│   ├── Models/
│   │   ├── User.php
│   │   └── Task.php                    (Task model with user relationship)
│   └── Policies/
│       └── TaskPolicy.php              (Task ownership authorization)
├── database/
│   ├── migrations/
│   │   ├── ...
│   │   └── 2025_12_10_235157_create_tasks_table.php
│   └── factories/
├── resources/
│   ├── views/
│   │   ├── dashboard.blade.php         (Task widget with AJAX JS)
│   │   ├── layouts/
│   │   │   └── app.blade.php           (Layout with CSRF + auth-token meta)
│   │   └── auth/                       (Login/Register views)
│   ├── css/
│   │   └── app.css
│   └── js/
│       ├── app.js
│       └── bootstrap.js
├── routes/
│   ├── api.php                         (Task API routes with auth:sanctum)
│   └── web.php                         (Breeze auth routes)
├── public/
│   ├── build/                          (Vite-generated assets)
│   └── index.php
├── vite.config.js                      (Vite build configuration)
├── tailwind.config.js                  (Tailwind CSS configuration)
└── README.md                           (This file)
```

## How AJAX Authentication Works

The dashboard uses two authentication methods:

1. **Bearer Token (API-first)**:

    - If a Bearer token is available in `meta[name="auth-token"]`, the JavaScript will use `Authorization: Bearer <token>` header
    - Useful for testing and single-page applications

2. **Session-based (Stateful SPA)**:
    - If no Bearer token is present, the app performs a handshake with `/sanctum/csrf-cookie` to obtain CSRF protection
    - Subsequent requests include the CSRF token in the `X-CSRF-TOKEN` header
    - Cookies are automatically sent with `credentials: 'same-origin'`

## Troubleshooting

### "Error creating task: Unauthenticated"

-   Ensure you are logged in (session exists or Bearer token is valid)
-   Check the browser DevTools → Network tab for the API response
-   Verify the CSRF token is being sent (X-CSRF-TOKEN header or XSRF-TOKEN cookie)

### Tasks not appearing after adding

-   Check the Network tab for the POST request status (should be 201)
-   Verify the response contains `data.id` and `data.title`
-   Check browser console for any JavaScript errors

### 401 Unauthorized or 403 Forbidden

-   Ensure you are using a valid Bearer token or have an active session
-   For Bearer token: Include `Authorization: Bearer <token>` header
-   For session auth: Ensure cookies are sent with requests (use `credentials: 'same-origin'` in fetch)

### 419 CSRF Token Mismatch

-   The app automatically requests CSRF cookie on page load
-   Try refreshing the page (Ctrl+F5) and logging in again
-   Ensure X-CSRF-TOKEN header is being sent with POST/PUT/PATCH/DELETE requests

## Development

### Watch mode (auto-rebuild on file changes)

```bash
npm run dev
```

### Format code with Pint

```bash
./vendor/bin/pint
```

### Run tests

```bash
php artisan test
```

### Tinker (interactive shell)

```bash
php artisan tinker
```

## License

This project is open source and available under the MIT License.

## Author

Created as a demonstration of full-stack Laravel development with modern frontend practices (AJAX, no page reloads).

---

**Questions or issues?** Check the browser console and Network tab for detailed error messages.

## 🎥 Demo Video

![Demo](https://img.icons8.com/?size=200&id=59813&format=png)

[Watch Full Demo Video](videos/demo.mp4)
