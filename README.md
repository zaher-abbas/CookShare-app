## &#127869; CookShare App

CookShare is a modern web application for managing recipes, built with native PHP using an MVC structure and both
MongoDB and MySQL
for data persistence.
It allows users to create, manage, and share their culinary recipes, as well as share their opinions and notes on recipes from other users.

## ✨Features

- User registration and authentication 👤
- Role-based access with standard user and admin accounts 🔐
- Personal dashboard and profile management
- Upload and update custom user profile pictures 🖼️
- Create, edit, and delete recipes 🥣
- Add images and rich descriptions to recipes
- Leave comments and ratings on recipes, and view discussions 🗨️
- Search and browse recipes 🔍
- Sort recipes by different criteria ↕️
- Add recipes to your favorites ⭐
- Admin panel for managing users and recipes 🛠️
- Administrative user management, including viewing and deleting users
- Administrative recipe management with global recipe oversight
- Toast notifications for user actions and feedback 🔔
- Uses MySQL (PDO) and MongoDB drivers
- Simple, modular MVC-like structure (Controllers, Models, Views)


## 🛠️ Tech Stack

- PHP 8.1+ (recommended 8.3)
- Composer (dependency management)
- MySQL (via PDO)
- MongoDB (via mongodb/mongodb PHP library)
- Bootstrap (front-end styling and components)

## 📋 Prerequisites

- PHP 8.1+ with the following extensions:
    - ext-pdo (for MySQL)
    - ext-mongodb (for MongoDB)
- Composer
- A running MySQL instance and credentials
- A running MongoDB instance and credentials
- A web server (Apache/Nginx) or PHP’s built-in server

## 📦 Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/zaher-abbas/CookShare-app.git
    cd CookShare-app
    ```

2. Install PHP dependencies:
    ```bash
    composer install
    ```

3. Set up the environment file:
  - Copy `.env.example` to `.env`
  - Update the environment variables with your own MySQL and MongoDB credentials

   Example:
    ```bash
    cp .env.example .env
    ```

4. Configure the databases:
  - MySQL:
    - Create a MySQL database
    - Create a MySQL user with permission to access this database
    - Import `database_schema.sql` to create the required tables
  - MongoDB:
    - Make sure your MongoDB server is running
    - Create the database referenced in your `.env` file if needed

5. Update your environment variables in `.env`:
  - `MYSQL_HOST`
  - `MYSQL_DB`
  - `MYSQL_USER`
  - `MYSQL_PASSWORD`
  - `MONGODB_URI`
  - `MONGODB_DB`

6. Configure your web server:
  - Point the document root to the application entry point located in `src/View`
  - For quick local testing, you can use PHP’s built-in server:
    ```bash
    php -S localhost:8000 -t src/View
    ```

7. Open the application in your browser:
    ```text
    http://localhost:8000/index.php
    ```

## 🚀 Usage

- Register a new account or log in
- Create new recipes from your dashboard
- View, edit, and delete your recipes
- Add recipes to your favorites
- Browse recipes and leave comments
- Manage your profile information

## 💡 Development Tips

- Follow PSR standards where applicable
- Keep controller logic thin and move data logic to models
- Reuse shared UI components for headers/navbars to ensure consistency
- PHPUnit is installed via Composer and tests can be placed in the `tests` directory
- You may add a `phpunit.xml` file at the project root to simplify test configuration and execution

## License

This project is licensed under the MIT License.

## Author

- Zaher ABBAS — z.abbas83@yahoo.com
