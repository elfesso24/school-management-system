<?php
require_once '../includes/header.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    redirect('subjects/index.php', 'Invalid subject ID', 'danger');
}

$subject_id = sanitize($_GET['id']);

// Get subject data
$query = "SELECT * FROM subjects WHERE subject_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirect('subjects/index.php', 'Subject not found', 'warning');
}

$subject = $result->fetch_assoc();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $subject_name = sanitize($_POST['subject_name']);
    $subject_code = sanitize($_POST['subject_code']);
    $description = sanitize($_POST['description']);
    $credit_hours = sanitize($_POST['credit_hours']);
    
    $errors = [];
    
    // Validate required fields
    if (empty($subject_name)) $errors[] = "Subject name is required";
    if (empty($subject_code)) $errors[] = "Subject code is required";
    
    // Check if subject code already exists (excluding current subject)
    if (!empty($subject_code)) {
        $check_query = "SELECT subject_id FROM subjects WHERE subject_code = ? AND subject_id != ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("si", $subject_code, $subject_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $errors[] = "Subject code already exists. Please use a different code.";
        }
    }
    
    // If no errors, update the subject
    if (empty($errors)) {
        $update_query = "UPDATE subjects SET subject_name = ?, subject_code = ?, description = ?, credit_hours = ? WHERE subject_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sssii", $subject_name, $subject_code, $description, $credit_hours, $subject_id);
        
        if ($update_stmt->execute()) {
            redirect('subjects/index.php', 'Subject updated successfully', 'success');
        } else {
            $errors[] = "Error updating subject: " . $conn->error;
        }
    }
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Edit Subject</h2>
            <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Subjects</a>
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
                        <label for="subject_name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="subject_name" name="subject_name" value="<?php echo isset($_POST['subject_name']) ? htmlspecialchars($_POST['subject_name']) : htmlspecialchars($subject['subject_name']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="subject_code" class="form-label">Subject Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="subject_code" name="subject_code" value="<?php echo isset($_POST['subject_code']) ? htmlspecialchars($_POST['subject_code']) : htmlspecialchars($subject['subject_code']); ?>" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="credit_hours" class="form-label">Credit Hours</label>
                        <input type="number" class="form-control" id="credit_hours" name="credit_hours" value="<?php echo isset($_POST['credit_hours']) ? htmlspecialchars($_POST['credit_hours']) : htmlspecialchars($subject['credit_hours']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : htmlspecialchars($subject['description']); ?></textarea>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="/school/subjects/index.php" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>