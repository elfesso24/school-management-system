<?php
require_once '../includes/header.php';

// Delete subject if requested
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $subject_id = sanitize($_GET['delete']);
    
    // Check if subject exists
    $check_query = "SELECT subject_id FROM subjects WHERE subject_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $subject_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Delete the subject
        $delete_query = "DELETE FROM subjects WHERE subject_id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $subject_id);
        
        if ($delete_stmt->execute()) {
            redirect('subjects/index.php', 'Subject deleted successfully', 'success');
        } else {
            redirect('subjects/index.php', 'Error deleting subject: ' . $conn->error, 'danger');
        }
    } else {
        redirect('subjects/index.php', 'Subject not found', 'warning');
    }
}

// Get all subjects
$query = "SELECT * FROM subjects ORDER BY subject_name";
$result = $conn->query($query);
?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Subjects</h2>
            <a href="/school/subjects/create.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New Subject</a>
        </div>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject Name</th>
                            <th>Subject Code</th>
                            <th>Description</th>
                            <th>Credit Hours</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['subject_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['subject_code']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo $row['credit_hours']; ?></td>
                                <td class="action-buttons">
                                    <a href="/school/subjects/edit.php?id=<?php echo $row['subject_id']; ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Edit</a>
                                    <a href="/school/subjects/index.php?delete=<?php echo $row['subject_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this subject?');"><i class="bi bi-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No subjects found. <a href="/school/subjects/create.php">Add a new subject</a>.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>