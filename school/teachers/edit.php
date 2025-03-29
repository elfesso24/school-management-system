<?php
require_once '../includes/header.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    redirect('teachers/index.php', 'Invalid teacher ID', 'danger');
}

$teacher_id = sanitize($_GET['id']);

// Get teacher data
$query = "SELECT * FROM teachers WHERE teacher_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirect('teachers/index.php', 'Teacher not found', 'warning');
}

$teacher = $result->fetch_assoc();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $hire_date = sanitize($_POST['hire_date']);
    $qualification = sanitize($_POST['qualification']);
    
    $errors = [];
    
    // Validate required fields
    if (empty($first_name)) $errors[] = "First name is required";
    if (empty($last_name)) $errors[] = "Last name is required";
    if (empty($email)) $errors[] = "Email is required";
    
    // Validate email format
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // Check if email already exists (excluding current teacher)
    if (!empty($email)) {
        $check_query = "SELECT teacher_id FROM teachers WHERE email = ? AND teacher_id != ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("si", $email, $teacher_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $errors[] = "Email already exists. Please use a different email.";
        }
    }
    
    // If no errors, update the teacher
    if (empty($errors)) {
        $update_query = "UPDATE teachers SET first_name = ?, last_name = ?, email = ?, phone = ?, hire_date = ?, qualification = ? WHERE teacher_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssssssi", $first_name, $last_name, $email, $phone, $hire_date, $qualification, $teacher_id);
        
        if ($update_stmt->execute()) {
            redirect('teachers/index.php', 'Teacher updated successfully', 'success');
        } else {
            $errors[] = "Error updating teacher: " . $conn->error;
        }
    }
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Edit Teacher</h2>
            <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Teachers</a>
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
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : htmlspecialchars($teacher['first_name']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : htmlspecialchars($teacher['last_name']); ?>" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : htmlspecialchars($teacher['email']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : htmlspecialchars($teacher['phone']); ?>">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="hire_date" class="form-label">Hire Date</label>
                        <input type="date" class="form-control" id="hire_date" name="hire_date" value="<?php echo isset($_POST['hire_date']) ? $_POST['hire_date'] : $teacher['hire_date']; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="qualification" class="form-label">Qualification</label>
                        <input type="text" class="form-control" id="qualification" name="qualification" value="<?php echo isset($_POST['qualification']) ? htmlspecialchars($_POST['qualification']) : htmlspecialchars($teacher['qualification']); ?>">
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="/school/teachers/index.php" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Teacher</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>