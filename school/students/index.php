<?php
require_once '../includes/header.php';

// Delete student if requested
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $student_id = sanitize($_GET['delete']);
    
    // Check if student exists
    $check_query = "SELECT student_id FROM students WHERE student_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $student_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Delete the student
        $delete_query = "DELETE FROM students WHERE student_id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $student_id);
        
        if ($delete_stmt->execute()) {
            redirect('students/index.php', 'Student deleted successfully', 'success');
        } else {
            redirect('students/index.php', 'Error deleting student: ' . $conn->error, 'danger');
        }
    } else {
        redirect('students/index.php', 'Student not found', 'warning');
    }
}

// Get all students
$query = "SELECT * FROM students ORDER BY last_name, first_name";
$result = $conn->query($query);
?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Students</h2>
            <a href="/school/students/create.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New Student</a>
        </div>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Grade Level</th>
                            <th>Enrollment Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['student_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['grade_level']); ?></td>
                                <td><?php echo $row['enrollment_date']; ?></td>
                                <td class="action-buttons">
                                    <a href="/school/students/edit.php?id=<?php echo $row['student_id']; ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Edit</a>
                                    <a href="/school/students/index.php?delete=<?php echo $row['student_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this student?');"><i class="bi bi-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No students found. <a href="/school/students/create.php">Add a new student</a>.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>