-- Database schema for tracking subjects taught by teachers to students

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS school_management;

-- Use the database
USE school_management;

-- Teachers table
CREATE TABLE IF NOT EXISTS teachers (
    teacher_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    hire_date DATE,
    qualification VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Students table
CREATE TABLE IF NOT EXISTS students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE,
    date_of_birth DATE,
    grade_level VARCHAR(20),
    enrollment_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Subjects table
CREATE TABLE IF NOT EXISTS subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL,
    subject_code VARCHAR(20) UNIQUE NOT NULL,
    description TEXT,
    credit_hours INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Teacher-Subject assignments (which teacher teaches which subjects)
CREATE TABLE IF NOT EXISTS teacher_subjects (
    assignment_id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT NOT NULL,
    subject_id INT NOT NULL,
    academic_year VARCHAR(20),
    semester VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES teachers(teacher_id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE,
    UNIQUE KEY unique_teacher_subject (teacher_id, subject_id, academic_year, semester)
);

-- Student enrollments in subjects
CREATE TABLE IF NOT EXISTS student_enrollments (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    teacher_id INT NOT NULL,
    academic_year VARCHAR(20),
    semester VARCHAR(20),
    grade VARCHAR(5),
    enrollment_date DATE DEFAULT CURRENT_DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(teacher_id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (student_id, subject_id, teacher_id, academic_year, semester)
);

-- Sample data insertion for testing

-- Insert sample teachers
INSERT INTO teachers (first_name, last_name, email, phone, hire_date, qualification) VALUES
('John', 'Smith', 'john.smith@school.edu', '555-1234', '2018-08-15', 'PhD in Mathematics'),
('Mary', 'Johnson', 'mary.johnson@school.edu', '555-2345', '2019-07-20', 'Masters in Literature'),
('Robert', 'Williams', 'robert.williams@school.edu', '555-3456', '2017-06-10', 'PhD in Physics');

-- Insert sample students
INSERT INTO students (first_name, last_name, email, date_of_birth, grade_level, enrollment_date) VALUES
('Emma', 'Davis', 'emma.davis@student.edu', '2005-03-12', '10th Grade', '2020-09-01'),
('James', 'Miller', 'james.miller@student.edu', '2004-07-22', '11th Grade', '2019-09-01'),
('Sophia', 'Wilson', 'sophia.wilson@student.edu', '2006-11-05', '9th Grade', '2021-09-01');

-- Insert sample subjects
INSERT INTO subjects (subject_name, subject_code, description, credit_hours) VALUES
('Mathematics', 'MATH101', 'Introduction to Algebra and Calculus', 4),
('English Literature', 'ENG201', 'Study of classic literary works', 3),
('Physics', 'PHY101', 'Introduction to mechanics and thermodynamics', 4);

-- Assign teachers to subjects
INSERT INTO teacher_subjects (teacher_id, subject_id, academic_year, semester) VALUES
(1, 1, '2023-2024', 'Fall'),
(2, 2, '2023-2024', 'Fall'),
(3, 3, '2023-2024', 'Fall');

-- Enroll students in subjects
INSERT INTO student_enrollments (student_id, subject_id, teacher_id, academic_year, semester) VALUES
(1, 1, 1, '2023-2024', 'Fall'),
(1, 2, 2, '2023-2024', 'Fall'),
(2, 1, 1, '2023-2024', 'Fall'),
(2, 3, 3, '2023-2024', 'Fall'),
(3, 2, 2, '2023-2024', 'Fall'),
(3, 3, 3, '2023-2024', 'Fall');