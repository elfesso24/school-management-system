<?php
require_once '../includes/header.php';

// Delete enrollment if requested
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $enrollment_id = sanitize($_GET['delete']);
    
    // Check if enrollment exists
    $check_query = "SELECT enrollment_id FROM student_enrollments WHERE enrollment_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $enrollment_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Delete the enrollment
        $delete_query = "DELETE FROM student_enrollments WHERE enrollment_id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $enrollment_id);
        
        if ($delete_stmt->execute()) {
            redirect('/school/enrollments/index.php', 'Enrollment deleted successfully', 'success');
        } else {
            redirect('/school/enrollments/index.php', 'Error deleting enrollment: ' . $conn->error, 'danger');
        }
    } else {
        redirect('/school/enrollments/index.php', 'Enrollment not found', 'warning');
    }
}

// Get all student enrollments with student, subject, and teacher names
$query = "SELECT se.enrollment_id, 
                se.student_id,
                se.subject_id,
                se.teacher_id,
                CONCAT(s.first_name, ' ', s.last_name) AS student_name, 
                sub.subject_name, 
                CONCAT(t.first_name, ' ', t.last_name) AS teacher_name,
                se.academic_year, 
                se.semester,
                se.grade
            FROM student_enrollments se 
            JOIN students s ON se.student_id = s.student_id 
            JOIN subjects sub ON se.subject_id = sub.subject_id
            JOIN teachers t ON se.teacher_id = t.teacher_id
            ORDER BY se.academic_year DESC, se.semester, student_name";
$result = $conn->query($query);
?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Student Enrollments</h2>
            <a href="/school/enrollments/create.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New Enrollment</a>
        </div>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Academic Year</th>
                            <th>Semester</th>
                            <th>Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['enrollment_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['academic_year']); ?></td>
                                <td><?php echo htmlspecialchars($row['semester']); ?></td>
                                <td><?php echo $row['grade'] ? htmlspecialchars($row['grade']) : 'Not graded'; ?></td>
                                <td class="action-buttons">
                                    <a href="/school/enrollments/edit.php?id=<?php echo $row['enrollment_id']; ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Edit</a>
                                    <a href="/school/enrollments/index.php?delete=<?php echo $row['enrollment_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this enrollment?');"><i class="bi bi-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No student enrollments found. <a href="/school/enrollments/create.php">Add a new enrollment</a>.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>