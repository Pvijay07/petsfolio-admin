<!DOCTYPE html>
<html>

<head>
<<<<<<< HEAD
    <!-- Theme Early Detection (Zero-Flicker) -->
    <script>
        (function() {
            try {
                var theme = localStorage.getItem('petsfolio_theme');
                if (!theme) {
                    theme = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
                }
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                    document.documentElement.setAttribute('data-bs-theme', 'dark');
                    document.documentElement.setAttribute('data-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    document.documentElement.setAttribute('data-bs-theme', 'light');
                    document.documentElement.setAttribute('data-theme', 'light');
                }
            } catch (e) {}
        })();
    </script>
=======
>>>>>>> origin/main
    <title>403 Unauthorize</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@100;200;300;400;500;600;700;800;900&family=Barlow:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url() ?>public/assets/_ui/css/styles.css">
    <link rel="stylesheet" href="<?= base_url() ?>public/assets/_ui/css/responsive.css">
<<<<<<< HEAD
    <link rel="stylesheet" href="<?= base_url('public/assets/css/theme-dark-overrides.css?v=' . time()) ?>">
=======
>>>>>>> origin/main
    <style type="text/css">
        .er_pg {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            min-height: 100vh;
        }
        .er_pg_lst {
            padding: 20px 20px 40px;
            box-shadow: 0 0 11px 1px #ff0000;
            border-radius: 40px;
        }
        .er_img {
            width: 170px;
            margin: 0 auto 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 15px;
        }
        h3 {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="er_pg">
        <div class="er_pg_lst">
            <div class="er_img">
                <img src="<?= base_url() ?>public/assets/_ui/images/error.png" alt="">
            </div>
            <h1>403 Unauthorize</h1>
            <h3>Access denied. You do not have permission to access this page.</h3>
        </div>
    </div>
</body>

</html>