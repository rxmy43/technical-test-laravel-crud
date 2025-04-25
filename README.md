# User Management API

A Laravel-based User Management System with CRUD operations, search functionality, and a modern UI built with Tailwind CSS.

## Features

-   **User Management**

    -   Create new users
    -   View user list with pagination
    -   Update existing users
    -   Delete users with confirmation
    -   Search users by name or email
    -   Sort users by name, email, or age

-   **UI/UX**
    -   Responsive design
    -   Modal-based forms
    -   Real-time form validation
    -   Success/Error notifications
    -   Confirmation dialogs for destructive actions
    -   Clean and modern interface using Tailwind CSS

## Technology Stack

-   **Backend**

    -   PHP 8.2
    -   Laravel 10.x
    -   MySQL/MariaDB

-   **Frontend**
    -   Tailwind CSS
    -   Vite
    -   Blade Templates

## Database Schema

### Users Table

```sql
CREATE TABLE users (
    id bigint unsigned AUTO_INCREMENT PRIMARY KEY,
    name varchar(255) NOT NULL,
    email varchar(255) UNIQUE NOT NULL,
    age integer NOT NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL
);
```

## API Endpoints

| Method | Endpoint      | Description       | Parameters                                                                                                     |
| ------ | ------------- | ----------------- | -------------------------------------------------------------------------------------------------------------- |
| GET    | `/users`      | List all users    | `q`: Search term for name/email<br>`sort_by`: Column to sort by (name/email/age)<br>`sort_direction`: asc/desc |
| POST   | `/users`      | Create a new user | `name`: string<br>`email`: unique email<br>`age`: integer                                                      |
| PUT    | `/users/{id}` | Update a user     | `name`: string<br>`email`: unique email<br>`age`: integer                                                      |
| DELETE | `/users/{id}` | Delete a user     | -                                                                                                              |

## Installation & Setup

1. **Clone the repository**

```bash
git clone https://github.com/rxmy43/technical-test-laravel-crud.git
cd user-crud-api
```

2. **Install Dependencies**

```bash
composer install
npm install
```

3. **Environment Setup**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure Database**

-   Open `.env` file and update database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=user_crud_api
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Run Migrations**

```bash
php artisan migrate
```

6. **Build Assets**

```bash
npm run build
```

7. **Start the Server**

```bash
php artisan serve
```

## Usage Guide

### User Listing

-   Navigate to `/users` to view the user list
-   Use the search box to filter users by name or email
-   Click on column headers to sort by that column
-   Pagination controls are at the bottom of the table

### Creating Users

1. Click "Create User" button
2. Fill in the required fields:
    - Name
    - Email (must be unique)
    - Age (must be positive number)
3. Submit the form

### Updating Users

1. Click "Edit" button on the user row
2. Modify the desired fields
3. Submit the form to save changes

### Deleting Users

1. Click "Delete" button on the user row
2. Type the user's name in the confirmation dialog
3. Confirm deletion

## Error Handling

The API returns appropriate HTTP status codes:

-   200: Success
-   400: Bad Request
-   422: Validation Error
-   404: Not Found
-   500: Server Error

## Security

-   Input validation for all user data
-   CSRF protection enabled
-   XSS protection through proper escaping
-   SQL injection protection through Laravel's query builder
