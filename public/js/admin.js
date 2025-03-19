document.addEventListener("DOMContentLoaded", function () {
    // Initialize Bootstrap components
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Sidebar functionality
    const toggleSidebar = document.getElementById("toggle-sidebar");
    const closeSidebarBtn = document.getElementById("close-sidebar");
    const sidebar = document.getElementById("admin-sidebar");
    const overlay = document.getElementById("sidebar-overlay");

    function toggleSidebarVisibility() {
        sidebar.classList.toggle("active");
        if (overlay) overlay.classList.toggle("active");
    }

    // Toggle sidebar visibility
    if (toggleSidebar) {
        toggleSidebar.addEventListener("click", function (e) {
            e.preventDefault();
            toggleSidebarVisibility();
        });
    }

    // Close sidebar when close button is clicked
    if (closeSidebarBtn) {
        closeSidebarBtn.addEventListener("click", function (e) {
            e.preventDefault();
            toggleSidebarVisibility();
        });
    }

    // Close sidebar when overlay is clicked
    if (overlay) {
        overlay.addEventListener("click", toggleSidebarVisibility);
    }

    // Close sidebar with Escape key
    document.addEventListener("keydown", function (e) {
        if (
            e.key === "Escape" &&
            sidebar &&
            sidebar.classList.contains("active")
        ) {
            toggleSidebarVisibility();
        }
    });

    // Close sidebar on window resize (for small devices)
    window.addEventListener("resize", function () {
        if (window.innerWidth >= 1200 && sidebar.classList.contains("active")) {
            toggleSidebarVisibility();
        }
    });

    // Set active state for current page
    if (sidebar) {
        const currentPath = window.location.pathname;
        const sidebarLinks = sidebar.querySelectorAll(".nav-link");
        sidebarLinks.forEach((link) => {
            if (link.getAttribute("href") === currentPath) {
                link.classList.add("active");
            }
        });
    }
});
