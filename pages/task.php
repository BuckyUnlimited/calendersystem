<?php
include './config/db.con.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ./?page=login');
    exit;
}

$user_id = $_SESSION['user_id'];

// SEARCH + FILTER
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$status = $_GET['status'] ?? '';

// PAGINATION
$limit = 5;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$start = ($page - 1) * $limit;

$sql = "SELECT * FROM tasks WHERE user_id=$user_id";

// search
if ($search) {
    $sql .= " AND title LIKE '%" . $db->real_escape_string($search) . "%'";
}

// filter
if ($category) {
    $sql .= " AND category='" . $db->real_escape_string($category) . "'";
}
if ($status) {
    $sql .= " AND status='" . $db->real_escape_string($status) . "'";
}

// total rows
$total = $db->query($sql)->num_rows;
$pages = ceil($total / $limit);

// final query
$sql .= " ORDER BY id DESC LIMIT $start, $limit";
$tasks = $db->query($sql);
?>



<div class="container mt-4">

    <h3 class="mb-3"><i class="fa-solid fa-list-check"></i> Task Management</h3>

    <!-- SEARCH + FILTER -->
    <form method="get" action="index.php" class="row mb-3">

        <input type="hidden" name="page" value="task">

        <div class="col-md-3">
            <input type="text" name="search" class="form-control"
                placeholder="Search..."
                value="<?php echo $search; ?>">
        </div>

        <div class="col-md-2">
            <select name="category" class="form-select">
                <option value="">Category</option>
                <option value="Study" <?php if ($category == 'Study') echo 'selected'; ?>>Study</option>
                <option value="Freelance" <?php if ($category == 'Freelance') echo 'selected'; ?>>Freelance</option>
                <option value="Personal" <?php if ($category == 'Personal') echo 'selected'; ?>>Personal</option>
            </select>
        </div>

        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Status</option>
                <option value="Pending" <?php if ($status == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Completed" <?php if ($status == 'Completed') echo 'selected'; ?>>Completed</option>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter <i class="fa-solid fa-magnifying-glass"></i></button>
        </div>

        <div class="col-md-2">
            <a href="./?page=task" class="btn btn-dark w-100">Reset <i class="fa-solid fa-arrows-rotate"></i></a>
        </div>

    </form>

    <!-- TABLE -->
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $i = 1;
            while ($row = $tasks->fetch_assoc()) {
            ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><span class="badge bg-primary"><?php echo $row['category']; ?></span></td>
                    <td>
                        <span class="badge bg-<?php echo $row['status'] == 'Pending' ? 'danger' : 'success'; ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td><?php echo $row['priority']; ?></td>

                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?php echo $row['id']; ?>">
                            Edit <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete<?php echo $row['id']; ?>">
                            Delete <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </td>
                </tr>
                <div class="modal fade" id="edit<?php echo $row['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content text-dark">

                            <form method="post" action="./?page=edit_task">

                                <div class="modal-header">
                                    <h5 class="modal-title"> Edit Task <i class="fa-solid fa-pen-to-square"></i></h5>
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
                                        <select name="category" class="form-select">
                                            <option value="Study" <?php if ($row['category'] == 'Study') echo 'selected'; ?>>Study</option>
                                            <option value="Freelance" <?php if ($row['category'] == 'Freelance') echo 'selected'; ?>>Freelance</option>
                                            <option value="Personal" <?php if ($row['category'] == 'Personal') echo 'selected'; ?>>Personal</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Priority</label>
                                        <select name="priority" class="form-select">
                                            <option value="High" <?php if ($row['priority'] == 'High') echo 'selected'; ?>>High</option>
                                            <option value="Medium" <?php if ($row['priority'] == 'Medium') echo 'selected'; ?>>Medium</option>
                                            <option value="Low" <?php if ($row['priority'] == 'Low') echo 'selected'; ?>>Low</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Status</label>
                                        <select name="status" class="form-select">
                                            <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                            <option value="Completed" <?php if ($row['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Start Time</label>
                                        <input type="datetime-local" name="start_time"
                                            value="<?php echo date('Y-m-d\TH:i', strtotime($row['start_time'])); ?>"
                                            class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label>End Time</label>
                                        <input type="datetime-local" name="end_time"
                                            value="<?php echo date('Y-m-d\TH:i', strtotime($row['end_time'])); ?>"
                                            class="form-control">
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="submit" name="edit_task" class="btn btn-primary w-100">
                                        Save Changes
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
                <div class="modal fade" id="delete<?php echo $row['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content text-dark">

                            <form method="post" action="./?page=delete_task">

                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="fa-solid fa-trash-can"></i> Delete Task </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body text-center">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <p>Are you sure you want to delete <strong><?php echo $row['title']; ?></strong>?</p>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" name="delete_task" class="btn btn-danger w-100">Delete</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

            <?php } ?>
        </tbody>
    </table>

    <!-- PAGINATION -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $pages; $i++) { ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="index.php?page=task&p=<?php echo $i; ?>&search=<?php echo $search; ?>&category=<?php echo $category; ?>&status=<?php echo $status; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </nav>

</div>