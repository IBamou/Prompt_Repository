# Prompts Manager

A PHP-based web application for managing AI prompts with user authentication, categories, and activity logging.

## Features

- **User Authentication**
  - User registration and login
  - Password hashing with `password_hash()`
  - Session management with regeneration
  - Role-based access control (user, admin, superAdmin)
  - User status management (active, blocked)

- **Prompts Management**
  - Create, read, update, and delete prompts
  - Categorize prompts
  - Search and filter by title, category, user, and date range
  - Sorting capabilities

- **Categories Management**
  - Create and manage prompt categories
  - Link prompts to categories

- **Dashboard**
  - Statistics overview (total prompts, categories, users)
  - Role-based views

- **Audit Logging**
  - Track all prompt changes (create, update, delete)
  - Record old/new values and field changes
  - User activity logging

- **Session Tracking**
  - Track login/logout times
  - Store IP address and user agent

## Technology Stack

- **Backend**: PHP
- **Database**: MySQL (PDO)
- **Architecture**: MVC-like pattern
  - Controllers: `app/controller/`
  - Models: `app/model/`
  - Views: `app/view/`
- **Configuration**: `app/config/db.php`

## Database Schema

The application uses the following tables:

- `users` - User accounts with roles and status
- `categories` - Prompt categories
- `prompts` - AI prompts with category association
- `prompt_logs` - Audit trail for prompt changes
- `user_sessions` - Session tracking

See `query.sql` for the complete schema.

## Installation

1. Clone or copy the project to your web server directory (e.g., `htdocs/PromptsManager`)

2. Create the database:
   ```sql
   CREATE DATABASE prompts_manager;
   USE prompts_manager;
   ```

3. Run the SQL queries from `query.sql` to create all tables

4. Configure database connection in `app/config/db.php`:
   ```php
   $host = 'localhost';
   $dbname = 'prompts_manager';
   $dbuser = 'root';
   $password = '';
   ```

5. Access the application at `http://localhost/PromptsManager/`

## Default Admin Account

The application creates a default admin user on first load:
- **Name**: admin name
- **Email**: exemple@gmail.com
- **Password**: passwor

## Routes

| URL | Controller | Description |
|-----|------------|-------------|
| `/auth` | authController | Login/Register (default) |
| `/dashboard` | dashboardController | Dashboard view |
| `/prompts` | promptsController | Prompts listing |
| `/categories` | categoriesController | Categories management |
| `/promptCategory` | promptCategoryController | Prompt-category linking |
| `/profile` | profileController | User profile |
| `/promptsLogs` | promptsLogsController | Activity logs |
| `/users` | usersController | User management (admin) |

## Security Features

- Password hashing with `PASSWORD_DEFAULT`
- Input sanitization with `htmlspecialchars()`
- Session regeneration on login
- Role-based access control
- SQL injection prevention via PDO prepared statements
- CSRF token protection on forms
- Input validation (empty fields, duplicates, length limits)
- User self-protection (cannot modify own account in admin panel)

## Project Structure

```
PromptsManager/
├── index.php              # Entry point with routing
├── query.sql              # Database schema
├── README.md              # This file
├── .htaccess              # Apache configuration
└── app/
    ├── config/
    │   └── db.php         # Database configuration
    ├── controller/
    │   ├── authController.php
    │   ├── categoriesController.php
    │   ├── dashboardController.php
    │   ├── profileController.php
    │   ├── promptCategoryController.php
    │   ├── promptsController.php
    │   ├── promptsLogsController.php
    │   └── usersController.php
    ├── model/
    │   ├── authenModel.php
    │   ├── CategoryModel.php
    │   ├── promptLogsModel.php
    │   ├── promptModel.php
    │   ├── staticsModel.php
    │   └── userModel.php
    └── view/
        ├── authen.php
        ├── categoriesView.php
        ├── categoryFormView.php
        ├── css/
        ├── dashboardView.php
        ├── home.php
        ├── js/
        ├── navbar.php
        ├── profileView.php
        ├── promptCategoryView.php
        ├── promptFormView.php
        ├── promptsLogs.php
        ├── promptsView.php
        └── users.php
```

## Requirements

- PHP 7.4+ with PDO extension
- MySQL 5.7+
- Apache with mod_rewrite (for clean URLs via .htaccess)
- XAMPP, WAMP, or similar local development environment

## License

This project is for educational purposes.
