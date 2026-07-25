<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f3f4f6;
    }
  </style>
</head>

<body class="p-6">
  <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
      <?= esc($title) ?>
    </h1>

    <?php if ($error): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
        <strong class="font-bold">Error:</strong>
        <span class="block sm:inline"><?= esc($error) ?></span>
      </div>
    <?php elseif ($stats): ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Installs Card -->
        <div class="bg-blue-500 text-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-105">
          <div class="p-6">
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m0 0l-6-6m6 6l6-6" />
              </svg>
              <div>
                <div class="uppercase tracking-wide text-sm font-semibold">Total Installs</div>
                <div class="mt-1 text-4xl font-extrabold leading-none">
                  <?= number_format(esc($stats['total_installs'])) ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Uninstalls Card -->
        <div class="bg-red-500 text-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-105">
          <div class="p-6">
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19.5v-15m0 0l-6 6m6-6l6 6" />
              </svg>
              <div>
                <div class="uppercase tracking-wide text-sm font-semibold">Total Uninstalls</div>
                <div class="mt-1 text-4xl font-extrabold leading-none">
                  <?= number_format(esc($stats['total_uninstalls'])) ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-8 text-sm text-gray-500">
        <p>Last updated: <?= esc($stats['last_updated']) ?></p>
      </div>
    <?php else: ?>
      <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg relative" role="alert">
        <strong class="font-bold">Info:</strong>
        <span class="block sm:inline">No data available. Please check the setup and try again.</span>
      </div>
    <?php endif; ?>
  </div>
</body>

</html>