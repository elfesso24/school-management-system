<?php
require_once '../includes/header.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $email = sanitize($_POST['email']);
    $date_of_birth = sanitize($_POST['date_of_birth']);
    $grade_level = sanitize($_POST['grade_level']);
    $enrollment_date = sanitize($_POST['enrollment_date']);
    
    $errors = [];
    
    // Validate required fields
    if (empty($first_name)) $errors[] = "First name is required";
    if (empty($last_name)) $errors[] = "Last name is required";
    
    // Validate email format if provided
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // Check if email already exists
    if (!empty($email)) {
        $check_query = "SELECT student_id FROM students WHERE email = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $errors[] = "Email already exists. Please use a different email.";
        }
    }
    
    // If no errors, insert the student
    if (empty($errors)) {
        $insert_query = "INSERT INTO students (first_name, last_name, email, date_of_birth, grade_level, enrollment_date) VALUES (?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("ssssss", $first_name, $last_name, $email, $date_of_birth, $grade_level, $enrollment_date);
        
        if ($insert_stmt->execute()) {
            redirect('students/index.php', 'Student added successfully', 'success');
        } else {
            $errors[] = "Error adding student: " . $conn->error;
        }
    }
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Add New Student</h2>
            <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Students</a>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="POST" action="">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : ''; ?>">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="grade_level" class="form-label">Grade Level</label>
                        <input type="text" class="form-control" id="grade_level" name="grade_level" value="<?php echo isset($_POST['grade_level']) ? htmlspecialchars($_POST['grade_level']) : ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="enrollment_date" class="form-label">Enrollment Date</label>
                        <input type="date" class="form-control" id="enrollment_date" name="enrollment_date" value="<?php echo isset($_POST['enrollment_date']) ? $_POST['enrollment_date'] : date('Y-m-d'); ?>">
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="index.php" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>