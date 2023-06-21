## Project Overview

This project is a simple web application developed using PHP and MySQL. It provides various features to users based on their authentication status and user roles. The application utilizes a database to store user information and deliver dynamic content.

## Features

- **User Authentication**: The project includes a login system that allows users to authenticate themselves using their email and password. Sessions are used to maintain user login state.

- **Dynamic Navigation**: The header section (`header.php`) provides a responsive navigation menu. The active page is highlighted based on the current URL.

- **User Profile**: Once logged in, users can access their profile page (`profile.php`), where they can view and edit their profile information. The profile page displays the user's username and profile picture.

- **Access Control**: The project implements access control based on user roles. Certain pages and features are restricted to authenticated users or users with specific roles, such as administrators.

- **Create Account (Admin)**: Administrators have the ability to create new user accounts by accessing the "Create Account" page (`createAccount.php`). This feature is only accessible to users with administrative privileges.

- **Logout**: Logged-in users can log out of their accounts by clicking the "Logout" link, which terminates their session and redirects them to the login page (`login.php`).

## File Descriptions

- `header.php`: This file includes the necessary dependencies and sets up the header section of the project's web pages. It contains HTML, CSS, and JavaScript code for the responsive navigation menu and user profile display.

- `conn.php`: This file establishes a connection to the MySQL database for the project. It contains PHP code to connect to the database using the provided configuration details.

## Usage

To use this project:

1. Set up a PHP development environment with a MySQL database.
2. Import the project files into your project directory.
3. Update the database connection details in the `conn.php` file to match your database configuration.
4. Run the project on a PHP-enabled server.
5. Use `project.sql` file to create the database.

## Disclaimer

This project is intended for educational purposes only and may require further development and security enhancements before being deployed in a production environment.
