<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>School Management System</h1>
                    <p class="lead">Track subjects taught by teachers to students</p>
                </div>
                <div>
                    <div class="btn-group">
                        <a href="/school/teachers/index.php" class="btn btn-outline-primary">Teachers</a>
                        <a href="/school/students/index.php" class="btn btn-outline-primary">Students</a>
                        <a href="/school/subjects/index.php" class="btn btn-outline-primary">Subjects</a>
                        <a href="/school/assignments/index.php" class="btn btn-outline-primary">Assignments</a>
                        <a href="/school/enrollments/index.php" class="btn btn-outline-primary">Enrollments</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h2>Database Connection Status</h2>
                <?php
                // Database connection
                $host = 'localhost';
                $user = 'root'; // Default XAMPP username
                $password = ''; // Default XAMPP password
                $database = 'school_management';

                $conn = new mysqli($host, $user, $password);

                // Check connection
                if ($conn->connect_error) {
                    echo '<div class="alert alert-danger">Connection failed: ' . $conn->connect_error . '</div>';
                } else {
                    echo '<div class="alert alert-success">Connected to MySQL server successfully!</div>';
                    
                    // Check if database exists
                    $result = $conn->query("SHOW DATABASES LIKE '$database'");
                    if ($result->num_rows == 0) {
                        echo '<div class="alert alert-warning">Database does not exist. Please run the database.sql script first.</div>';
                        echo '<div class="alert alert-info">You can run the script using phpMyAdmin or with the following command:</div>';
                        echo '<pre>mysql -u root < database.sql</pre>';
                    } else {
                        // Connect to the database
                        $conn->select_db($database);
                        echo '<div class="alert alert-success">Connected to database successfully!</div>';
                        
                        // Display tables
                        displayTables($conn);
                    }
                }

                function displayTables($conn) {
                    // Display Teachers
                    echo '<div class="table-container">';
                    echo '<h3>Teachers</h3>';
                    $result = $conn->query("SELECT * FROM teachers");
                    if ($result->num_rows > 0) {
                        echo '<table class="table table-striped">';
                        echo '<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Qualification</th></tr></thead>';
                        echo '<tbody>';
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row["teacher_id"] . '</td>';
                            echo '<td>' . $row["first_name"] . ' ' . $row["last_name"] . '</td>';
                            echo '<td>' . $row["email"] . '</td>';
                            echo '<td>' . $row["phone"] . '</td>';
                            echo '<td>' . $row["qualification"] . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<p>No teachers found</p>';
                    }
                    echo '</div>';

                    // Display Students
                    echo '<div class="table-container">';
                    echo '<h3>Students</h3>';
                    $result = $conn->query("SELECT * FROM students");
                    if ($result->num_rows > 0) {
                        echo '<table class="table table-striped">';
                        echo '<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Grade Level</th><th>Enrollment Date</th></tr></thead>';
                        echo '<tbody>';
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row["student_id"] . '</td>';
                            echo '<td>' . $row["first_name"] . ' ' . $row["last_name"] . '</td>';
                            echo '<td>' . $row["email"] . '</td>';
                            echo '<td>' . $row["grade_level"] . '</td>';
                            echo '<td>' . $row["enrollment_date"] . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<p>No students found</p>';
                    }
                    echo '</div>';

                    // Display Subjects
                    echo '<div class="table-container">';
                    echo '<h3>Subjects</h3>';
                    $result = $conn->query("SELECT * FROM subjects");
                    if ($result->num_rows > 0) {
                        echo '<table class="table table-striped">';
                        echo '<thead><tr><th>ID</th><th>Subject Name</th><th>Code</th><th>Description</th><th>Credit Hours</th></tr></thead>';
                        echo '<tbody>';
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row["subject_id"] . '</td>';
                            echo '<td>' . $row["subject_name"] . '</td>';
                            echo '<td>' . $row["subject_code"] . '</td>';
                            echo '<td>' . $row["description"] . '</td>';
                            echo '<td>' . $row["credit_hours"] . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<p>No subjects found</p>';
                    }
                    echo '</div>';

                    // Display Teacher-Subject Assignments
                    echo '<div class="table-container">';
                    echo '<h3>Teacher-Subject Assignments</h3>';
                    $query = "SELECT ts.assignment_id, 
                                    CONCAT(t.first_name, ' ', t.last_name) AS teacher_name, 
                                    s.subject_name, 
                                    ts.academic_year, 
                                    ts.semester 
                                FROM teacher_subjects ts 
                                JOIN teachers t ON ts.teacher_id = t.teacher_id 
                                JOIN subjects s ON ts.subject_id = s.subject_id";
                    $result = $conn->query($query);
                    if ($result->num_rows > 0) {
                        echo '<table class="table table-striped">';
                        echo '<thead><tr><th>ID</th><th>Teacher</th><th>Subject</th><th>Academic Year</th><th>Semester</th></tr></thead>';
                        echo '<tbody>';
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row["assignment_id"] . '</td>';
                            echo '<td>' . $row["teacher_name"] . '</td>';
                            echo '<td>' . $row["subject_name"] . '</td>';
                            echo '<td>' . $row["academic_year"] . '</td>';
                            echo '<td>' . $row["semester"] . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<p>No teacher-subject assignments found</p>';
                    }
                    echo '</div>';

                    // Display Student Enrollments
                    echo '<div class="table-container">';
                    echo '<h3>Student Enrollments</h3>';
                    $query = "SELECT se.enrollment_id, 
                                    CONCAT(s.first_name, ' ', s.last_name) AS student_name, 
                                    sub.subject_name, 
                                    CONCAT(t.first_name, ' ', t.last_name) AS teacher_name, 
                                    se.academic_year, 
                                    se.semester, 
                                    se.grade 
                                FROM student_enrollments se 
                                JOIN students s ON se.student_id = s.student_id 
                                JOIN subjects sub ON se.subject_id = sub.subject_id 
                                JOIN teachers t ON se.teacher_id = t.teacher_id";
                    $result = $conn->query($query);
                    if ($result->num_rows > 0) {
                        echo '<table class="table table-striped">';
                        echo '<thead><tr><th>ID</th><th>Student</th><th>Subject</th><th>Teacher</th><th>Academic Year</th><th>Semester</th><th>Grade</th></tr></thead>';
                        echo '<tbody>';
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row["enrollment_id"] . '</td>';
                            echo '<td>' . $row["student_name"] . '</td>';
                            echo '<td>' . $row["subject_name"] . '</td>';
                            echo '<td>' . $row["teacher_name"] . '</td>';
                            echo '<td>' . $row["academic_year"] . '</td>';
                            echo '<td>' . $row["semester"] . '</td>';
                            echo '<td>' . ($row["grade"] ? $row["grade"] : 'Not graded') . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<p>No student enrollments found</p>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <footer class="footer">
            <p>&copy; <?php echo date('Y'); ?> School Management System</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>