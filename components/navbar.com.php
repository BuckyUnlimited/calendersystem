<?php
$current = $_GET['page'] ?? '';
?>




<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="./?page=dashboard"><i class="fa-solid fa-calendar"></i> MyCalendar</a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link <?php if ($current == 'dashboard') echo 'active'; ?>" href="./?page=dashboard">Dashboard <i class="fa-solid fa-chart-line"></i></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if ($current == 'tasks') echo 'active'; ?>" href="./?page=task">Task List <i class="fa-solid fa-list-check"></i></a>
                </li>

            </ul>

            <div class="dropdown ms-3">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user"></i>  Account
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="./?page=profile"><i class="fa-solid fa-circle-user"></i> Profile </a></li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>