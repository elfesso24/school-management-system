# School Management System

A PHP-based web application for managing school data including teachers, students, subjects, assignments, and enrollments.

## Prerequisites

Before you begin, ensure you have the following installed on your system:

- [XAMPP](https://www.apachefriends.org/index.html) (version 7.4 or higher) which includes:
  - Apache Web Server
  - MySQL Database
  - PHP (version 7.4 or higher)
- A modern web browser (Chrome, Firefox, Edge, etc.)

## Installation

1. **Clone or download the project**
   - Download the project files or clone the repository into your XAMPP's `htdocs` directory
   - The default path is typically: `C:\xampp\htdocs\` (Windows) or `/Applications/XAMPP/htdocs/` (Mac)

2. **Start XAMPP services**
   - Open the XAMPP Control Panel
   - Start the Apache and MySQL services

3. **Set up the database**
   - Open your web browser and navigate to `http://localhost/phpmyadmin`
   - Create a new database named `school_management` (or you can use the provided SQL script which will create it for you)
   - Import the database schema by selecting the `school_management` database and clicking on the "Import" tab
   - Choose the `database.sql` file from the project directory and click "Go"

   Alternatively, you can run the SQL script directly:
   - Open a terminal or command prompt
   - Navigate to the MySQL bin directory (e.g., `C:\xampp\mysql\bin` on Windows)
   - Run the following command:
     ```
     mysql -u root -p < [path_to_project]/database.sql
     ```
   - If prompted for a password, press Enter (default XAMPP MySQL installation has no password)

4. **Configure the database connection (if needed)**
   - Open the `config.php` file in the project root directory
   - Update the database connection details if they differ from the default:
     ```php
     $host = 'localhost';
     $user = 'root'; // Default XAMPP username
     $password = ''; // Default XAMPP password
     $database = 'school_management';
     ```

## Running the Application

1. **Access the application**
   - Open your web browser and navigate to: `http://localhost/school/`
   - You should see the School Management System homepage

2. **Navigate the system**
   - Use the navigation menu to access different sections:
     - **Teachers**: View, add, edit, and delete teacher records
     - **Students**: Manage student information
     - **Subjects**: Manage subject details
     - **Assignments**: Assign teachers to subjects
     - **Enrollments**: Manage student enrollments in subjects

## Features

### Teachers Management
- View all teachers with their details
- Add new teachers with qualifications and contact information
- Edit existing teacher information
- Delete teachers from the system

### Students Management
- View all students with their details
- Add new students with enrollment information
- Edit existing student information
- Delete students from the system

### Subjects Management
- View all subjects with their details
- Add new subjects with descriptions and credit hours
- Edit existing subject information
- Delete subjects from the system

### Teacher-Subject Assignments
- Assign teachers to specific subjects
- Specify academic year and semester for each assignment
- View all current teacher-subject assignments
- Edit or delete assignments

### Student Enrollments
- Enroll students in subjects with specific teachers
- Record academic year, semester, and grades
- View all current enrollments
- Edit or delete enrollment records

## Database Structure

The system uses the following database tables:

- `teachers`: Stores teacher information
- `students`: Stores student information
- `subjects`: Stores subject details
- `teacher_subjects`: Links teachers to the subjects they teach
- `student_enrollments`: Records student enrollments in subjects

## Troubleshooting

- **Database Connection Issues**: Ensure MySQL service is running in XAMPP Control Panel
- **Page Not Found Errors**: Verify that Apache service is running and that you're using the correct URL path
- **Import Errors**: Make sure you have proper permissions to create and modify databases
- **PHP Errors**: Check XAMPP's PHP error logs at `C:\xampp\php\logs\` (Windows) or `/Applications/XAMPP/logs/` (Mac)

