# Project Setup Instructions

## Requirements

-   PHP >= 8.1
-   Composer
-   Node.js and npm
-   MySQL or PostgreSQL
-   Laravel 12

## Installation

1. **Clone the repository**

    ```bash
    git clone https://github.com/rxmy14/your-repo-name.git
    cd your-repo-name
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Install JavaScript dependencies**

    ```bash
    npm install
    ```

4. **Copy and configure the environment file**

    ```bash
    cp .env.example .env
    ```

    Then update the `.env` file with your local database credentials and other necessary config values.

5. **Generate application key**

    ```bash
    php artisan key:generate
    ```

6. **Run database migrations**

    ```bash
    php artisan migrate
    ```

7. **Build frontend assets**

    ```bash
    npm run dev
    ```

8. **Start the development server**
    ```bash
    php artisan serve
    ```
