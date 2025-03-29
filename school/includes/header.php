<?php
require_once __DIR__ . '/../config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            padding-top: 20px;
            padding-bottom: 20px;
        }
        .header {
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e5e5;
            margin-bottom: 30px;
        }
        .table-container {
            margin-bottom: 30px;
        }
        .action-buttons .btn {
            margin-right: 5px;
        }
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="d-flex justify-content-between align-items-center">
                <h1>School Management System</h1>
                <nav>
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a href="/school/index.php" class="nav-link">Home</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Teachers</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/school/teachers/index.php">View All</a></li>
                                <li><a class="dropdown-item" href="/school/teachers/create.php">Add New</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Students</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/school/students/index.php">View All</a></li>
                                <li><a class="dropdown-item" href="/school/students/create.php">Add New</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Subjects</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/school/subjects/index.php">View All</a></li>
                                <li><a class="dropdown-item" href="/school/subjects/create.php">Add New</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Assignments</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/school/assignments/index.php">Teacher-Subject</a></li>
                                <li><a class="dropdown-item" href="/school/enrollments/index.php">Student Enrollments</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        
        <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?php 
            echo $_SESSION['message']; 
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>