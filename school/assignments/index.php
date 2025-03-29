<?php
require_once '../includes/header.php';

// Delete assignment if requested
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $assignment_id = sanitize($_GET['delete']);
    
    // Check if assignment exists
    $check_query = "SELECT assignment_id FROM teacher_subjects WHERE assignment_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $assignment_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Delete the assignment
        $delete_query = "DELETE FROM teacher_subjects WHERE assignment_id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $assignment_id);
        
        if ($delete_stmt->execute()) {
            redirect('/school/assignments/index.php', 'Assignment deleted successfully', 'success');
        } else {
            redirect('/school/assignments/index.php', 'Error deleting assignment: ' . $conn->error, 'danger');
        }
    } else {
        redirect('/school/assignments/index.php', 'Assignment not found', 'warning');
    }
}

// Get all teacher-subject assignments with teacher and subject names
$query = "SELECT ts.assignment_id, 
                ts.teacher_id,
                ts.subject_id,
                CONCAT(t.first_name, ' ', t.last_name) AS teacher_name, 
                s.subject_name, 
                ts.academic_year, 
                ts.semester 
            FROM teacher_subjects ts 
            JOIN teachers t ON ts.teacher_id = t.teacher_id 
            JOIN subjects s ON ts.subject_id = s.subject_id
            ORDER BY ts.academic_year DESC, ts.semester, teacher_name";
$result = $conn->query($query);
?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Teacher-Subject Assignments</h2>
            <a href="/school/assignments/create.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New Assignment</a>
        </div>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Teacher</th>
                            <th>Subject</th>
                            <th>Academic Year</th>
                            <th>Semester</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['assignment_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['academic_year']); ?></td>
                                <td><?php echo htmlspecialchars($row['semester']); ?></td>
                                <td class="action-buttons">
                                    <a href="/school/assignments/edit.php?id=<?php echo $row['assignment_id']; ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Edit</a>
                                    <a href="/school/assignments/index.php?delete=<?php echo $row['assignment_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this assignment?');"><i class="bi bi-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No teacher-subject assignments found. <a href="/school/assignments/create.php">Add a new assignment</a>.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>