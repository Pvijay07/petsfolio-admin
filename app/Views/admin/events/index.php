<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="apple-touch-icon" sizes="76x76"
    href="https://cmschamps.com/psf-admin/public/assets/images/icons/Petsfolio.png">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<!-- Library / Plugin Css Build -->
<link rel="stylesheet" href="<?= base_url() ?>public/assets/css/core/libs.min.css" />

<!-- Aos Animation Css -->
<link rel="stylesheet" href="<?= base_url() ?>public/assets/vendor/aos/dist/aos.css" />

<!-- Hope Ui Design System Css -->
<link rel="stylesheet" href="<?= base_url() ?>public/assets/css/hope-ui.min.css?v=2.0.0" />

<!-- Custom Css -->
<link rel="stylesheet" href="<?= base_url() ?>public/assets/css/custom.min.css?v=2.0.0" />

<!-- Dark Css -->
<link rel="stylesheet" href="<?= base_url() ?>public/assets/css/dark.min.css" />

<!-- Customizer Css -->
<link rel="stylesheet" href="<?= base_url() ?>public/assets/css/customizer.min.css" />

<!-- RTL Css -->
<link rel="stylesheet" href="<?= base_url() ?>public/assets/css/rtl.min.css" />

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

<!-- Lucide Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@latest/font/lucide.css" />

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.5);
        --text-main: #1e293b;
        --text-muted: #64748b;
    }

    body {
        background: #f8fafc;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: var(--text-main);
    }

    .main-content {
        padding-top: 2rem;
    }

    .premium-header {
        background: var(--primary-gradient);
        border-radius: 24px;
        padding: 3rem;
        margin-bottom: 3rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
    }

    .premium-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .event-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        height: 100%;
    }

    .event-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        border-color: rgba(99, 102, 241, 0.3);
    }

    .card-icon {
        width: 50px;
        height: 50px;
        background: var(--primary-gradient);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: var(--text-main);
    }

    .card-text {
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .btn-premium {
        background: var(--primary-gradient);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-premium:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
        color: white;
    }

    .empty-state {
        padding: 5rem 2rem;
        text-align: center;
        background: var(--glass-bg);
        border-radius: 24px;
        border: 2px dashed #e2e8f0;
    }
</style>
</head>
<main class="main-content">
    <div class="container-fluid px-4">
        <div class="premium-header">
            <div class="header-content d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-5 fw-bold text-white mb-2">Events Management</h1>
                    <p class="fs-5 text-white-75 mb-0">Experience a modern way to manage your event entries and scanning analytics.</p>
                </div>
                <div class="d-none d-md-block">
                    <div class="badge bg-white text-primary px-3 py-2 rounded-pill fw-bold">
                        <i class="bi bi-calendar-event me-1"></i> Live Events
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <div class="col" data-aos="fade-up">
                        <div class="card event-card p-4">
                            <div class="card-body p-0">
                                <div class="card-icon">
                                    <i class="bi bi-ticket-perforated fs-4"></i>
                                </div>
                                <h5 class="card-title"><?= esc($event['name'] ?? 'Event #' . $event['id']) ?></h5>
                                <p class="card-text">Access real-time booking data, manage ticket entries, and monitor scanning reports for this event.</p>
                                <div class="d-grid mt-auto">
                                    <a href="<?= base_url('pet-events/bookings/' . $event['id']) ?>" class="btn-premium justify-content-center">
                                        View Bookings
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="empty-state">
                        <i class="bi bi-calendar-x display-1 text-muted mb-4"></i>
                        <h3 class="fw-bold">No Events Found</h3>
                        <p class="text-muted">There are no active events available in the system at the moment.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://petsfolio.in/admin/public/assets/js/core/libs.min.js"></script>
<script src="https://petsfolio.in/admin/public/assets/js/core/external.min.js"></script>
<script src="https://petsfolio.in/admin/public/assets/js/charts/widgetcharts.js"></script>
<script src="https://petsfolio.in/admin/public/assets/js/charts/vectore-chart.js"></script>
<script src="https://petsfolio.in/admin/public/assets/js/charts/dashboard.js"></script>
<script src="https://petsfolio.in/admin/public/assets/js/plugins/fslightbox.js"></script>
<script src="https://petsfolio.in/admin/public/assets/js/plugins/setting.js"></script>
<script src="https://petsfolio.in/admin/public/assets/js/plugins/slider-tabs.js"></script>
<script src="https://petsfolio.in/admin/public/assets/js/plugins/form-wizard.js"></script>
<script src="https://petsfolio.in/admin/public/assets/vendor/aos/dist/aos.js"></script>
<script src="https://petsfolio.in/admin/public/assets/js/hope-ui.js" defer></script>
<!-- Fix jQuery UI conflict -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('public/assets/js/backend.js?v=' . time()) ?>"></script>
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
    }
</style>