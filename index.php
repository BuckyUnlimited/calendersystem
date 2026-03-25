<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
session_start();
include './config/db.con.php';
include './components/header.com.php';
include './components/navbar.com.php';

// Allowed pages
$pages = [
    'login',
    'register',
    'dashboard',
    'task',
    'profile',
    'add_task',
    'edit_task',
    'delete_task',
    'api/calendar_events',
    'api/update_event',
    'calendar',
    'logout' // ✅ add logout
];

// Get page
$page = $_GET['page'] ?? 'login';

// Security check
if (!in_array($page, $pages)) {
    $page = 'login';
}

// Check login status
$isLoggedIn = isset($_SESSION['user_id']);

// Pages that require login
$protected_pages = [
    'dashboard',
    'task',
    'profile',
    'add_task',
    'edit_task',
    'delete_task',
    'calendar'
];

// Pages for guests only
$guest_pages = ['login', 'register'];

// ❌ If NOT logged in → block protected pages
if (!$isLoggedIn && in_array($page, $protected_pages)) {
    header('Location: index.php?page=login');
    exit;
}

// ❌ If logged in → block login & register
if ($isLoggedIn && in_array($page, $guest_pages)) {
    header('Location: index.php?page=dashboard');
    exit;
}

// Load page
include "pages/$page.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<style>
    #loading {
        transition: opacity 0.3s ease;
    }
</style>

<body>
    <!-- GLOBAL LOADING -->
    <div id="loading" style="
    display:flex;
    position:fixed;
    top:0;left:0;
    width:100%;height:100%;
    background:rgba(0,0,0,0.4);
    z-index:9999;
    align-items:center;
    justify-content:center;">

        <div class="spinner-border text-light" style="width:3rem;height:3rem;"></div>

    </div>



    <script>
        function showLoading() {
            document.getElementById("loading").style.display = "flex";
        }

        function hideLoading() {
            document.getElementById("loading").style.display = "none";
        }

        // hide after page load
        function hideLoadingSafe() {
            hideLoading();
        }

        // normal load
        window.addEventListener("load", hideLoadingSafe);

        // back/forward cache fix
        window.addEventListener("pageshow", function(event) {
            hideLoadingSafe();
        });
    </script>

    <script>
        // show loading when clicking links
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll("a").forEach(link => {
                link.addEventListener("click", function(e) {

                    // ignore empty links
                    if (this.getAttribute("href") && !this.getAttribute("href").startsWith("#")) {
                        showLoading();
                    }

                });
            });

        });
    </script>

    <script>
        document.querySelectorAll("form").forEach(form => {
            form.addEventListener("submit", function() {
                showLoading();
            });
        });
    </script>

    <script>
        window.addEventListener("pageshow", function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>

</body>

</html>

<?php include './components/footer.com.php'; ?>