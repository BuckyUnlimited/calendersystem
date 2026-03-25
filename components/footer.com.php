    <div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999">
        <div id="liveToast" class="toast text-white border-0">
            <div class="d-flex">
                <div class="toast-body" id="toastMsg"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <script>
        function showToast(message, type = "success") {
            let toastEl = document.getElementById('liveToast');
            let toastMsg = document.getElementById('toastMsg');

            if (!toastEl) return; // safety

            toastMsg.innerText = message;

            toastEl.classList.remove('bg-success', 'bg-danger', 'bg-warning');

            if (type === "success") {
                toastEl.classList.add('bg-success');
            } else if (type === "error") {
                toastEl.classList.add('bg-danger');
            } else {
                toastEl.classList.add('bg-warning');
            }

            let toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script>
        function toggleTheme() {
            let mode = localStorage.getItem('theme');

            if (mode === 'dark') {
                document.body.classList.remove('bg-dark', 'text-white');
                localStorage.setItem('theme', 'light');
            } else {
                document.body.classList.add('bg-dark', 'text-white');
                localStorage.setItem('theme', 'dark');
            }
        }

        // load theme
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('bg-dark', 'text-white');
        }
    </script>

    <?php if (isset($_SESSION['error'])) { ?>
        <script>
            showToast("<?php echo $_SESSION['error']; ?>", "error");
        </script>
    <?php unset($_SESSION['error']);
    } ?>

    <?php if (isset($_SESSION['success'])) { ?>
        <script>
            showToast("<?php echo $_SESSION['success']; ?>", "success");
        </script>
    <?php unset($_SESSION['success']);
    } ?>
    </body>

    </html>