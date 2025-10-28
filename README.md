# School Information System

This is a web-based school information system designed to manage various aspects of a school's operations. It provides different functionalities for different roles like Admin, Principal, Registrar, Security, and Teacher.

## Description

The School Information System is a comprehensive platform that helps in managing school-related data and activities. It allows for the management of events, user accounts, and files. The system is designed to be used by different stakeholders in a school, each with their own set of permissions and functionalities.

## Installation

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/your-repository.git
    ```
2.  **Move the project to your web server's root directory.**
    For XAMPP, it's `htdocs`.
3.  **Import the database:**
    - Open your database management tool (e.g., phpMyAdmin).
    - Create a new database.
    - Import the `bscs(1).sql` file into the newly created database.
4.  **Configure the database connection:**
    - Open `model/database.php`.
    - Update the database credentials (hostname, username, password, and database name) to match your environment.

## Usage

1.  Open your web browser and navigate to the project's URL (e.g., `http://localhost/INFORMATIONSYSTEM`).
2.  Log in with your credentials. The system will redirect you to the appropriate dashboard based on your role.

## Project Structure

- `assets/`: Contains all the static files like CSS, JavaScript, and images.
- `controller/`: Contains the business logic for different user roles.
- `model/`: Contains the database connection, data models, and other helper functions.
- `view/`: Contains the presentation layer (HTML and PHP files for the user interface).
- `bscs(1).sql`: The database dump file.

## Technologies Used

- **Backend:** PHP
- **Frontend:** HTML, CSS, JavaScript
- **Database:** MySQL
- **Web Server:** Apache (XAMPP)

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License

[MIT](https://choosealicense.com/licenses/mit/)
