# Task Management Web Application

This is a simple Laravel web application for task management designed to prioritize a particulat project priority.


## Installation

1. **Clone the repository**:
    ```bash
    git clone https://github.com/Nderi12/task-manager.git
    ```
2. **Navigate to project directory**:
    ```bash
    cd task-manager
    ```
3. **Install dependencies**:

    ```bash
    composer install
    ```

4. **Create a copy of the `.env` file**:

    ```bash
    cp .env.example .env
    ```

5. **Generate application key**:

    ```bash
    php artisan key:generate
    ```

6. **Create database**:

    Create a MySQL database and update the `.env` file with database credentials.

7. **Run migrations**:

    ```bash
    php artisan migrate
    ```

8. **Seed the database** (optional):

    If you want to seed the database with sample data, run:

    ```bash
    php artisan db:seed
    ```

## Running the Application

To run the application, use the following command:

```
php artisan serve
```
