<?php
include './config/db.con.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ./?page=login');
    exit;
}
$user_id = $_SESSION['user_id'];

// Fetch tasks
$tasks = $db->query("SELECT * FROM tasks WHERE user_id=$user_id ORDER BY start_time ASC");

$category = $_GET['category'] ?? '';
$status = $_GET['status'] ?? '';

$sql = "SELECT * FROM tasks WHERE user_id=$user_id";

if (!empty($category)) {
    $sql .= " AND category='" . $db->real_escape_string($category) . "'";
}

if (!empty($status)) {
    $sql .= " AND status='" . $db->real_escape_string($status) . "'";
}

$sql .= " ORDER BY start_time ASC";

$tasks = $db->query($sql);



$pending_count = $db->query("SELECT COUNT(*) AS c FROM tasks WHERE user_id=$user_id AND status='Pending'")->fetch_assoc()['c'];
$completed_count = $db->query("SELECT COUNT(*) AS c FROM tasks WHERE user_id=$user_id AND status='Completed'")->fetch_assoc()['c'];

?>




<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-calendar"></i> My Calendar Dashboard</h2>
    </div>


    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-center shadow p-3">
                <h5>Pending Tasks</h5>
                <h2><?php echo $pending_count; ?></h2>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center shadow p-3">
                <h5>Completed Tasks</h5>
                <h2><?php echo $completed_count; ?></h2>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal"><i class="fa-solid fa-file-circle-plus"></i> Add New Task</button>

        <form method="get" class="d-flex">
            <input type="hidden" name="page" value="dashboard">
            <select name="category" class="form-select me-2">
                <option value=""> All Categories</option>
                <option value="Study" <?php if ($category == 'Study') echo 'selected'; ?>>Study</option>
                <option value="Freelance" <?php if ($category == 'Freelance') echo 'selected'; ?>>Freelance</option>
                <option value="Personal" <?php if ($category == 'Personal') echo 'selected'; ?>>Personal</option>
            </select>
            <select name="status" class="form-select me-2">
                <option value="">All Status</option>
                <option value="Pending" <?php if ($status == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Completed" <?php if ($status == 'Completed') echo 'selected'; ?>>Completed</option>
            </select>
            <button type="submit" class="btn btn-dark">Filter </button>
        </form>
    </div>


    <div class="row">
        <?php while ($row = $tasks->fetch_assoc()) {
            $color = ($row['category'] == 'Study') ? 'primary' : (($row['category'] == 'Freelance') ? 'success' : 'warning');
        ?>
            <div class="col-md-4 mb-3">
                <div class="card shadow border-<?php echo $color; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['title']; ?></h5>
                        <p class="card-text"><?php echo $row['description']; ?></p>
                        <span class="badge bg-<?php echo $color; ?>"><?php echo $row['category']; ?></span>
                        <span class="badge bg-secondary"><?php echo $row['priority']; ?></span>
                        <span class="badge bg-<?php echo $row['status'] == 'Pending' ? 'danger' : 'success'; ?>"><?php echo $row['status']; ?></span>
                        <p class="mt-2"><small><?php echo $row['start_time']; ?> → <?php echo $row['end_time']; ?></small></p>
                        <!-- Edit/Delete Buttons trigger modals -->
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editTaskModal<?php echo $row['id']; ?>">Edit <i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteTaskModal<?php echo $row['id']; ?>">Delete <i class="fa-solid fa-trash-can"></i></button>
                    </div>
                </div>
            </div>

            <!-- Edit Task Modal -->
            <div class="modal fade" id="editTaskModal<?php echo $row['id']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content text-dark">
                        <form method="post" action="./?page=edit_task">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Task <i class="fa-solid fa-pen-to-square"></i></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <div class="mb-3">
                                    <label>Title</label>
                                    <input type="text" name="title" class="form-control" value="<?php echo $row['title']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control"><?php echo $row['description']; ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Category</label>
                                    <select name="category" class="form-select" required>
                                        <option value="Study" <?php if ($row['category'] == 'Study') echo 'selected'; ?>>Study</option>
                                        <option value="Freelance" <?php if ($row['category'] == 'Freelance') echo 'selected'; ?>>Freelance</option>
                                        <option value="Personal" <?php if ($row['category'] == 'Personal') echo 'selected'; ?>>Personal</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Priority</label>
                                    <select name="priority" class="form-select" required>
                                        <option value="High" <?php if ($row['priority'] == 'High') echo 'selected'; ?>>High</option>
                                        <option value="Medium" <?php if ($row['priority'] == 'Medium') echo 'selected'; ?>>Medium</option>
                                        <option value="Low" <?php if ($row['priority'] == 'Low') echo 'selected'; ?>>Low</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Start Time</label>
                                    <input type="datetime-local" name="start_time" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($row['start_time'])); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label>End Time</label>
                                    <input type="datetime-local" name="end_time" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($row['end_time'])); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label>Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="Completed" <?php if ($row['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="edit_task" class="btn btn-primary w-100">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Task Modal -->
            <div class="modal fade" id="deleteTaskModal<?php echo $row['id']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="./?page=delete_task">
                            <div class="modal-header">
                                <h5 class="modal-title text-dark"><i class="fa-solid fa-trash-can"></i> Delete Task </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-dark">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <p>Are you sure you want to delete "<strong><?php echo $row['title']; ?></strong>"?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="delete_task" class="btn btn-danger w-100">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php } ?>
    </div>

</div>

<!-- Add Task Modal (same as before) -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="./?page=add_task">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">Add New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 text-dark">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3 text-dark">
                        <label>Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Category</label>
                        <select name="category" class="form-select" required>
                            <option value="Study">Study</option>
                            <option value="Freelance">Freelance</option>
                            <option value="Personal">Personal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Priority</label>
                        <select name="priority" class="form-select" required>
                            <option value="High">High</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Start Time</label>
                        <input type="datetime-local" name="start_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>End Time</label>
                        <input type="datetime-local" name="end_time" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_task" class="btn btn-primary w-100">Add Task</button>
                </div>
            </form>
        </div>
    </div>
</div>