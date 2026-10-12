<?php

namespace App\Libraries;

use Google\Client;
use Google\Service\AndroidPublisher;

require_once APPPATH . 'ThirdParty/google-api/vendor/autoload.php';

/**
 * A library to interact with the Google Play Developer API.
 * This class handles authentication and fetching of app statistics.
 *
 * NOTE: The Google Play Developer Reporting API provides daily reports
 * in a Google Cloud Storage bucket. This library uses a simplified
 * approach to demonstrate the setup. For a real-world application,
 * you would need to set up the reporting API and read data from the
 * specified Cloud Storage bucket.
 *
 * Prerequisites:
 * - Install the Google API Client library:
 * composer require google/apiclient
 * - Place your downloaded service account JSON key in a secure location,
 * e.g., `writable/credentials/playstore-api-key.json`
 */
class PlayStoreAPI
{
    /**
     * @var Client The Google API client instance.
     */
    protected $client;

    /**
     * @var string The package name of the app (e.g., com.example.myapp).
     */
    protected $packageName;

    public function __construct()
    {
        // Get the package name from the .env file.
        $this->packageName = getenv('APP_PACKAGE_NAME');

        // Check for the existence of the package name.
        if (empty($this->packageName)) {
            throw new \Exception('APP_PACKAGE_NAME is not defined in the .env file.');
        }

        // Path to your service account JSON key file.
        // It's a good practice to store this outside the public webroot.
        // $credentialsPath = WRITEPATH . 'credentials/playstore-api-key.json';
<<<<<<< HEAD
        $credentialsPath = ROOTPATH . 'public/service_account/petsfolio-492009-878571efbf22.json';
=======
        $credentialsPath = ROOTPATH . 'public/service_account/petsfolio-client-app-cb99ea8f9ae6.json';
>>>>>>> origin/main

        // Check if the credentials file exists.
        if (!file_exists($credentialsPath)) {
            throw new \Exception("Google API credentials file not found at: " . $credentialsPath);
        }

        // Initialize the Google API Client.
        $this->client = new Client();
        $this->client->setAuthConfig($credentialsPath);
        $this->client->setApplicationName('CI4 App Statistics Dashboard');
        $this->client->addScope('https://www.googleapis.com/auth/androidpublisher');
    }

    /**
     * Fetches a simplified view of app installs and uninstalls.
     * In a real-world scenario, this would read from the reporting API.
     * We'll simulate fetching the data for this example.
     *
     * @return array|bool An array of statistics or false on failure.
     */
    public function getAppStatistics()
    {
<<<<<<< HEAD
        try {
=======
        // try {
>>>>>>> origin/main
            // NOTE: This is a placeholder. The real reporting API is more complex.
            // A simplified API call for getting a basic app edit would look like this,
            // but it does not return install/uninstall counts directly.
            // We use this as a valid API call to test the connection.
<<<<<<< HEAD
=======
            // Library
>>>>>>> origin/main
            $service = new AndroidPublisher($this->client);
            $edit = $service->edits->insert(
                $this->packageName,
                new AndroidPublisher\AppEdit()
            );

            // Discard the edit as we are only reading data.
            $service->edits->delete($this->packageName, $edit->getId());
<<<<<<< HEAD

=======
            var_dump($service);
>>>>>>> origin/main
            // Since the reporting API is complex, we will return some mock data.
            // In a real implementation, you would read a report from a Cloud Storage bucket.
            return [
                'total_installs' => rand(10000, 99999),
                'total_uninstalls' => rand(500, 5000),
                'last_updated' => date('Y-m-d H:i:s')
            ];
<<<<<<< HEAD
        } catch (\Exception $e) {
            log_message('error', 'Google Play API Error: ' . $e->getMessage());
            throw $e;
        }
=======
        // } catch (\Exception $e) {
        //     log_message('error', 'Google Play API Error: ' . $e->getMessage());
        //     return false;
        // }
>>>>>>> origin/main
    }
}
