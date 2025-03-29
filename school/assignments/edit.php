<?php
require_once '../includes/header.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    redirect('assignments/index.php', 'Invalid assignment ID', 'danger');
}

$assignment_id = sanitize($_GET['id']);

// Get assignment data
$query = "SELECT * FROM teacher_subjects WHERE assignment_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $assignment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirect('assignments/index.php', 'Assignment not found', 'warning');
}

$assignment = $result->fetch_assoc();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $teacher_id = sanitize($_POST['teacher_id']);
    $subject_id = sanitize($_POST['subject_id']);
    $academic_year = sanitize($_POST['academic_year']);
    $semester = sanitize($_POST['semester']);
    
    $errors = [];
    
    // Validate required fields
    if (empty($teacher_id)) $errors[] = "Teacher is required";
    if (empty($subject_id)) $errors[] = "Subject is required";
    if (empty($academic_year)) $errors[] = "Academic year is required";
    if (empty($semester)) $errors[] = "Semester is required";
    
    // Check if assignment already exists (excluding current assignment)
    if (!empty($teacher_id) && !empty($subject_id) && !empty($academic_year) && !empty($semester)) {
        $check_query = "SELECT assignment_id FROM teacher_subjects WHERE teacher_id = ? AND subject_id = ? AND academic_year = ? AND semester = ? AND assignment_id != ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("iissi", $teacher_id, $subject_id, $academic_year, $semester, $assignment_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $errors[] = "This teacher-subject assignment already exists for the selected academic year and semester.";
        }
    }
    
    // If no errors, update the assignment
    if (empty($errors)) {
        $update_query = "UPDATE teacher_subjects SET teacher_id = ?, subject_id = ?, academic_year = ?, semester = ? WHERE assignment_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("iissi", $teacher_id, $subject_id, $academic_year, $semester, $assignment_id);
        
        if ($update_stmt->execute()) {
            redirect('assignments/index.php', 'Assignment updated successfully', 'success');
        } else {
            $errors[] = "Error updating assignment: " . $conn->error;
        }
    }
}

// Get all teachers for dropdown
$teachers_query = "SELECT teacher_id, CONCAT(first_name, ' ', last_name) AS teacher_name FROM teachers ORDER BY last_name, first_name";
$teachers_result = $conn->query($teachers_query);

// Get all subjects for dropdown
$subjects_query = "SELECT subject_id, CONCAT(subject_name, ' (', subject_code, ')') AS subject_info FROM subjects ORDER BY subject_name";
$subjects_result = $conn->query($subjects_query);
?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Edit Teacher-Subject Assignment</h2>
            <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Assignments</a>
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
                        <label for="teacher_id" class="form-label">Teacher <span class="text-danger">*</span></label>
                        <select class="form-select" id="teacher_id" name="teacher_id" required>
                            <option value="">Select Teacher</option>
                            <?php while ($teacher = $teachers_result->fetch_assoc()): ?>
                                <option value="<?php echo $teacher['teacher_id']; ?>" <?php echo (isset($_POST['teacher_id']) ? $_POST['teacher_id'] : $assignment['teacher_id']) == $teacher['teacher_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($teacher['teacher_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
                        <select class="form-select" id="subject_id" name="subject_id" required>
                            <option value="">Select Subject</option>
                            <?php while ($subject = $subjects_result->fetch_assoc()): ?>
                                <option value="<?php echo $subject['subject_id']; ?>" <?php echo (isset($_POST['subject_id']) ? $_POST['subject_id'] : $assignment['subject_id']) == $subject['subject_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($subject['subject_info']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="academic_year" class="form-label">Academic Year <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="academic_year" name="academic_year" value="<?php echo isset($_POST['academic_year']) ? htmlspecialchars($_POST['academic_year']) : htmlspecialchars($assignment['academic_year']); ?>" placeholder="e.g. 2023-2024" required>
                    </div>
                    <div class="col-md-6">
                        <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                        <select class="form-select" id="semester" name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="Fall" <?php echo (isset($_POST['semester']) ? $_POST['semester'] : $assignment['semester']) == 'Fall' ? 'selected' : ''; ?>>Fall</option>
                            <option value="Spring" <?php echo (isset($_POST['semester']) ? $_POST['semester'] : $assignment['semester']) == 'Spring' ? 'selected' : ''; ?>>Spring</option>
                            <option value="Summer" <?php echo (isset($_POST['semester']) ? $_POST['semester'] : $assignment['semester']) == 'Summer' ? 'selected' : ''; ?>>Summer</option>
                            <option value="Winter" <?php echo (isset($_POST['semester']) ? $_POST['semester'] : $assignment['semester']) == 'Winter' ? 'selected' : ''; ?>>Winter</option>
                        </select>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="/school/assignments/index.php" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Assignment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>