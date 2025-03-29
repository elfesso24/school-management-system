<?php
require_once '../includes/header.php';

// Delete teacher if requested
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $teacher_id = sanitize($_GET['delete']);
    
    // Check if teacher exists
    $check_query = "SELECT teacher_id FROM teachers WHERE teacher_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $teacher_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Delete the teacher
        $delete_query = "DELETE FROM teachers WHERE teacher_id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $teacher_id);
        
        if ($delete_stmt->execute()) {
            redirect('teachers/index.php', 'Teacher deleted successfully', 'success');
        } else {
            redirect('teachers/index.php', 'Error deleting teacher: ' . $conn->error, 'danger');
        }
    } else {
        redirect('teachers/index.php', 'Teacher not found', 'warning');
    }
}

// Get all teachers
$query = "SELECT * FROM teachers ORDER BY last_name, first_name";
$result = $conn->query($query);
?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Teachers</h2>
            <a href="/school/teachers/create.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New Teacher</a>
        </div>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Hire Date</th>
                            <th>Qualification</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['teacher_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo $row['hire_date']; ?></td>
                                <td><?php echo htmlspecialchars($row['qualification']); ?></td>
                                <td class="action-buttons">
                                    <a href="/school/teachers/edit.php?id=<?php echo $row['teacher_id']; ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Edit</a>
                                    <a href="/school/teachers/index.php?delete=<?php echo $row['teacher_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this teacher?');"><i class="bi bi-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No teachers found. <a href="/school/teachers/create.php">Add a new teacher</a>.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>