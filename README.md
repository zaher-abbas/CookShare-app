## &#127869; CookShare App

CookShare is a modern web application for managing recipes, built with native PHP using an MVC structure and both
MongoDB and MySQL
for data persistence.
It allows users to create, manage, and share their culinary recipes, as well as share their opinions and notes on recipes from other users.

## ✨Features

- User registration and authentication 👤
- Create, edit, and delete recipes 🥣
- Add images and rich descriptions to recipes
- Comment on recipes and view discussions 🗨
- Personal dashboard and profile management
- Upload and update custom user profile pictures 🖼️
- Search and browse recipes 🔍
- Sort recipes by different criteria ↕️
- Add recipes to your favorites ⭐
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
    - git clone https://repo-url/recipe_app.git
    - cd recipe_app

2. Install PHP dependencies:
    - composer install

3. Configure databases:
    - MySQL:
        - Create a database (e.g., recipe_app)
        - Create a MySQL user with permissions for this database
        - If a schema file is provided (e.g., database/schema.sql), import it
    - MongoDB:
        - Ensure a MongoDB database and user are available

4. Configure connection settings:
    - Update your MySQL connection details (host, dbname, user, password) in the MySQL configuration file
    - Update your MongoDB URI and database name in the MongoDB configuration file

5. Configure your web server:
    - Document root should point to the application’s web entry (the Views directory with index.php)
    - For quick local testing, you can use PHP’s built-in server:
        - php -S localhost:8000 -t src/View
        - Then open http://localhost:8000/index.php in your browser

## 🚀 Usage

- Register a new account or log in
- Create new recipes from your dashboard
- View, edit, and delete your recipes
- Browse recipes and leave comments
- Manage your profile information

## ⚙️ Environment & Configuration

- Ensure your PHP environment has both MySQL (PDO) and MongoDB extensions enabled
- Set proper file permissions for any folders that handle uploads (e.g., images)
- Adjust base URLs or paths in configuration or view templates if you deploy under a subdirectory

## 💡 Development Tips

- Follow PSR standards where applicable
- Keep controller logic thin and move data logic to models
- Reuse shared UI components for headers/navbars to ensure consistency
- If you add tests with PHPUnit, place them under a tests directory and configure phpunit.xml accordingly

## 🧯 Troubleshooting

- 500 or blank page:
    - Enable error reporting in your PHP environment during development
    - Verify database credentials and extensions
- Database not reachable:
    - Check that MySQL/MongoDB services are running and accessible
    - Confirm hostnames, ports, and credentials
- CSS/JS not loading:
    - Ensure your document root points to the correct directory
    - Verify relative paths to assets in your layout/templates

## Scripts

- Install dependencies:
    - composer install
- Start local server (example):
    - php -S localhost:8000 -t src/View

## License

Specify your license (e.g., MIT) here.

## Author

- Zaher ABBAS — z.abbas83@yahoo.com
