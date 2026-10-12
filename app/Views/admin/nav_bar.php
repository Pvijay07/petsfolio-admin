<div style="margin-bottom: 150px;">
    <nav class="nav navbar navbar-expand-lg navbar-light iq-navbar"
        style="padding:0px; transition: margin-left 0.3s ease; position: fixed; width: calc(100% - 16rem); top: 0; z-index: 1030;">
        <div class="container-fluid navbar-inner">
            <div class="d-flex align-items-center gap-3">
                <div>
                    <h3 class="fw-bold mb-0 text-dark">Overview</h3>
                </div>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">
                    <span class="mt-2 navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto align-items-center navbar-list mb-lg-0">
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link position-relative p-2" href="#" id="notificationDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="position-relative">
                                <i class="bi bi-bell fs-5 text-dark"></i>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-2 border-white p-0"
                                    style="width: 8px; height: 8px; min-width: 8px;" id="notificationBadge">
                                    <span class="visually-hidden">unread notifications</span>
                                </span>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 notification-dropdown"
                            aria-labelledby="notificationDropdown"
                            style="min-width: 380px; max-height: 500px; overflow-y: auto; border-radius: 12px; margin-top: 10px;">
                            <div class="p-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold text-dark">Notifications</h6>
                                    <span class="badge bg-primary rounded-pill px-2 py-1"
                                        id="notificationCount">0</span>
                                </div>
                                <small class="text-muted" id="notificationSubtitle">Loading notifications...</small>
                            </div>
                            <div class="p-2" id="notificationList">
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 text-muted small">Loading notifications...</p>
                                </div>
                            </div>
                            <div class="p-3 border-top">
                                <a href="https://admin.petsfolio.in/support" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-eye me-1"></i> View All Notifications
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center gap-2 p-2" href="#" id="userProfileDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="position-relative">
                                <img src="<?= base_url() ?>public/assets/images/avatars/01.png" alt="User Profile"
                                    class="img-fluid rounded-circle border border-3 border-white shadow-sm"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                                <span
                                    class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle"
                                    style="width: 10px; height: 10px;"></span>
                            </div>
                            <div class="d-none d-xl-block text-start">
                                <h6 class="mb-0 fw-semibold text-dark" style="font-size: 0.9rem;">
                                    <?php echo session()->get('user')->name ?>
                                </h6>
                                <!-- <small class="text-muted" style="font-size: 0.8rem;"></small> -->
                            </div>
                            <i class="bi bi-chevron-down text-muted"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow-lg border-0"
                            aria-labelledby="userProfileDropdown"
                            style="min-width: 220px; border-radius: 12px; margin-top: 10px;">
                            <div class="p-3 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?= base_url() ?>public/assets/images/avatars/01.png" alt="Profile"
                                        class="rounded-circle border"
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">
                                            <?php echo session()->get('user')->name ?>
                                        </h6>
                                        <small
                                            class="text-muted"><?= session()->get('user')->email ?? 'admin@petsfolio.com' ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <a href="<?= base_url('/profile') ?>" class="dropdown-item p-3">
                                    <i class="bi bi-person me-2 text-primary"></i>
                                    <span>My Profile</span>
                                </a>

                                <div class="dropdown-divider my-2"></div>
                                <a href="<?= base_url('logout') ?>" class="dropdown-item p-3 text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<style>
    /* Modern Navbar Styling */
    .iq-navbar {
        background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        padding: 0.75rem 0;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .navbar-inner {
        padding: 0 1.5rem;
    }

    /* Breadcrumb styling */
    .breadcrumb {
        padding: 0;
        margin: 0;
        background: transparent;
        font-size: 0.875rem;
    }

    .breadcrumb-item a {
        color: #6b7280;
        text-decoration: none;
        transition: color 0.2s;
    }

    .breadcrumb-item a:hover {
        color: #4f46e5;
    }

    .breadcrumb-item.active {
        color: #374151;
        font-weight: 500;
    }

    /* Search Bar */
    .search-input input {
        background: #f9fafb !important;
        border: 1px solid #e5e7eb !important;
        padding: 0.5rem 1rem 0.5rem 2.5rem;
        border-radius: 50px;
        font-size: 0.875rem;
        transition: all 0.3s;
    }

    .search-input input:focus {
        background: #ffffff !important;
        border-color: #c7d2fe !important;
        box-shadow: 0 0 0 3px rgba(199, 210, 254, 0.5) !important;
    }

    /* Notification Bell */
    .nav-link {
        position: relative;
        transition: all 0.2s;
    }

    .nav-link:hover {
        transform: translateY(-1px);
    }

    .notification-dropdown {
        border: 1px solid #e5e7eb;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .notification-item {
        border-radius: 8px;
        transition: all 0.2s;
    }

    .notification-item:hover {
        background: #f8fafc;
        transform: translateX(2px);
    }

    /* Profile Dropdown */
    .dropdown-menu {
        border: 1px solid #e5e7eb;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .dropdown-item {
        border-radius: 8px;
        transition: all 0.2s;
    }

    .dropdown-item:hover {
        background: #f8fafc;
    }

    /* Quick Actions */
    .bi-plus-circle {
        transition: transform 0.3s;
    }

    .nav-link:hover .bi-plus-circle {
        transform: rotate(90deg);
    }

    /* Mobile Toggle */
    .navbar-toggler {
        border: none;
        padding: 0.5rem;
    }

    .navbar-toggler:focus {
        box-shadow: none;
    }

    /* Status Indicator */
    .bg-success {
        background-color: #10b981 !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .iq-navbar {
            width: 100% !important;
            padding: 0.5rem 1rem;
        }

        .navbar-inner {
            padding: 0 1rem;
        }

        .d-none.d-md-block {
            display: none !important;
        }

        .d-none.d-xl-block {
            display: none !important;
        }

        .search-input {
            display: none !important;
        }

        .d-none.d-lg-block {
            display: none !important;
        }

        .notification-dropdown {
            position: fixed !important;
            top: 70px !important;
            right: 20px !important;
            left: 20px !important;
            width: calc(100% - 40px) !important;
        }

        .dropdown-menu {
            position: fixed !important;
            top: 70px !important;
            right: 20px !important;
            left: auto !important;
        }
    }

    @media (max-width: 576px) {
        .navbar-inner {
            padding: 0 0.75rem;
        }

        .notification-dropdown {
            right: 10px !important;
            left: 10px !important;
            width: calc(100% - 20px) !important;
        }
    }

    /* Animation for notifications */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .notification-item {
        animation: slideIn 0.3s ease;
    }

    /* Hover effects for icons */
    .nav-link i {
        transition: color 0.2s, transform 0.2s;
    }

    .nav-link:hover i {
        color: #4f46e5;
    }

    /* Custom scrollbar for dropdowns */
    .notification-dropdown::-webkit-scrollbar {
        width: 6px;
    }

    .notification-dropdown::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .notification-dropdown::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .notification-dropdown::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>

<script>
    // Mobile sidebar toggle
    function toggleMobileSidebar() {
        const sidebar = document.querySelector('.sidebar-container');
        sidebar.classList.toggle('open');
    }

    // Update navbar margin based on sidebar state
    function updateNavbarMargin() {
        const navbar = document.querySelector('.iq-navbar');
        const sidebar = document.querySelector('.sidebar-container');

        if (window.innerWidth > 768) {
            if (sidebar.classList.contains('collapsed')) {
                navbar.style.width = 'calc(100% - 4rem)';
            } else {
                navbar.style.width = 'calc(100% - 16rem)';
            }
        } else {
            navbar.style.width = '100%';
        }
    }

    // Initialize navbar
    document.addEventListener('DOMContentLoaded', function() {
        // Initial margin update
        updateNavbarMargin();

        // Update on sidebar changes
        const sidebar = document.querySelector('.sidebar-container');
        const observer = new MutationObserver(updateNavbarMargin);
        observer.observe(sidebar, {
            attributes: true,
            attributeFilter: ['class']
        });

        // Update on window resize
        window.addEventListener('resize', updateNavbarMargin);

        // Load notifications from your API
        loadNotifications();

        // Search functionality
        const searchInput = document.querySelector('.search-input input');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                // Implement search functionality here
                console.log('Searching for:', searchTerm);
            });
        }
    });

    // Your original notification loading function - UPDATED for new UI
    function loadNotifications() {
        const notificationBadge = document.getElementById('notificationBadge');
        const notificationCount = document.getElementById('notificationCount');
        const notificationSubtitle = document.getElementById('notificationSubtitle');

        fetch('<?= base_url() ?>supports')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                updateNotificationList(data);
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                showNotificationError();
            });
    }

    function updateNotificationList(notifications) {
        const notificationList = document.getElementById('notificationList');
        const notificationBadge = document.getElementById('notificationBadge');
        const notificationCount = document.getElementById('notificationCount');
        const notificationSubtitle = document.getElementById('notificationSubtitle');

        // Clear existing notifications
        notificationList.innerHTML = '';

        if (!notifications || notifications.length === 0) {
            notificationList.innerHTML = `
                <div class="text-center py-4">
                    <i class="bi bi-bell-slash display-6 text-muted"></i>
                    <p class="mt-2 text-muted small">No notifications found</p>
                </div>
            `;
            notificationBadge.style.display = 'none';
            notificationCount.textContent = '0';
            notificationCount.classList.remove('bg-primary');
            notificationCount.classList.add('bg-secondary');
            notificationSubtitle.textContent = 'No new notifications';
            return;
        }

        // Add new notifications using your original createNotificationItem function
        notifications.forEach(notification => {
            const notificationItem = createNotificationItem(notification);
            notificationList.appendChild(notificationItem);
        });

        // Update badge and count
        const unreadCount = notifications.filter(n => !n.read).length;

        if (unreadCount > 0) {
            notificationBadge.style.display = 'block';
            notificationCount.textContent = unreadCount;
            notificationCount.classList.remove('bg-secondary');
            notificationCount.classList.add('bg-primary');
            notificationSubtitle.textContent = `${unreadCount} new notifications`;
        } else {
            notificationBadge.style.display = 'none';
            notificationCount.textContent = '0';
            notificationCount.classList.remove('bg-primary');
            notificationCount.classList.add('bg-secondary');
            notificationSubtitle.textContent = 'No new notifications';
        }
    }

    function createNotificationItem(notification) {
        var path = '';
        notification.user_type == 'client' ? path = 'users' : path = 'providers';

        const li = document.createElement('div');
        li.className = 'notification-item border-bottom';
        li.innerHTML = `
            <a class="dropdown-item " href="https://admin.petsfolio.in//support">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle overflow-hidden" style="width: 40px; height: 40px;">
                            <img class="img-fluid h-100 w-100" style="object-fit: cover;"
                                src="${notification.profile ? `https://admin.petsfolio.in/public/uploads/${path}/${notification.profile}` : 'https://petsfolio.in/admin/public/assets/images/avatars/user.png'}"
                                alt="${notification.name}">
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="mb-0 fw-semibold text-dark">${notification.name}</h6>
                            <small class="text-muted">${formatDate(notification.created_at)}</small>
                        </div>
                        <p class="mb-1 text-muted small">${notification.category || 'Support Request'}</p>
                        <span class="badge" style="background-color: rgba(59, 130, 246, 0.1) !important;
        color: #2563eb !important;
        border: 1px solid rgba(59, 130, 246, 0.2) !important; border-radius:50px !important">
                            ${notification.user_type}
                        </span>
                    </div>
                </div>
            </a>
        `;
        return li;
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);

        if (diffMins < 60) {
            return `${diffMins} minutes ago`;
        } else if (diffHours < 24) {
            return `${diffHours} hours ago`;
        } else if (diffDays < 7) {
            return `${diffDays} days ago`;
        } else {
            return date.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        }
    }

    function showNotificationError() {
        const notificationList = document.getElementById('notificationList');
        notificationList.innerHTML = `
            <div class="text-center py-4">
                <i class="bi bi-exclamation-triangle display-6 text-danger"></i>
                <p class="mt-2 text-muted small">Failed to load notifications</p>
                <button onclick="loadNotifications()" class="btn btn-sm btn-outline-primary mt-2">
                    <i class="bi bi-arrow-clockwise"></i> Retry
                </button>
            </div>
        `;
    }

    // Mark notifications as read when clicked
    document.addEventListener('click', function(e) {
        if (e.target.closest('.notification-item')) {
            const notificationBadge = document.getElementById('notificationBadge');
            const notificationCount = document.getElementById('notificationCount');

            // You might want to add an API call here to mark as read
            // For now, just update the UI
            setTimeout(() => {
                const currentCount = parseInt(notificationCount.textContent);
                if (currentCount > 0) {
                    notificationCount.textContent = currentCount - 1;

                    if (currentCount - 1 === 0) {
                        notificationBadge.style.display = 'none';
                        notificationCount.classList.remove('bg-primary');
                        notificationCount.classList.add('bg-secondary');
                        document.getElementById('notificationSubtitle').textContent = 'No new notifications';
                    }
                }
            }, 100);
        }
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(e.target) && !dropdown.previousElementSibling.contains(e.target)) {
                const bsDropdown = bootstrap.Dropdown.getInstance(dropdown.previousElementSibling);
                if (bsDropdown) {
                    bsDropdown.hide();
                }
            }
        });
    });

    // Set up periodic refresh of notifications (every 2 minutes)
    setInterval(loadNotifications, 120000);
</script>