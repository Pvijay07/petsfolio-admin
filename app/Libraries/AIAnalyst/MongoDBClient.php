<?php

namespace App\Libraries\AIAnalyst;

use MongoDB\Client;

/**
 * MongoDBClient
 * 
 * Provides live read-only access to MongoDB Atlas CRM collections (`petsfolio-crm-live`)
 * using the official MongoDB PHP library (mongodb/mongodb).
 */
class MongoDBClient
{
    private ?Client $client = null;
    private $database = null;
<<<<<<< HEAD
    private string $dbName = 'petsfolio-crm-live';

    public function __construct()
    {
        $uri = env('mongo.uri') ?: env('MONGODB_URI') ?: '';
        $this->dbName = env('mongo.database') ?: env('MONGODB_DATABASE') ?: 'petsfolio-crm-live';

        if (!empty($uri) && class_exists('MongoDB\\Client')) {
            try {
                $this->client = new Client($uri);
                $this->database = $this->client->selectDatabase($this->dbName);
            } catch (\Throwable $e) {
                log_message('error', 'MongoDB Client Connection Error: ' . $e->getMessage());
                $this->client = null;
                $this->database = null;
=======
    private string $dbName = 'crm';

    private ?Client $spClient = null;
    private $spDatabase = null;
    private string $spDbName = 'service-provider-crm';

    public function __construct()
    {
        $clientUri = env('mongo.uri') ?: env('MONGODB_URI') ?: 'mongodb://crmuser:PetsfolioCRM%402026@127.0.0.1:27017/crm?authSource=admin';
        $this->dbName = env('mongo.database') ?: env('MONGODB_DATABASE') ?: 'crm';

        $spUri = env('mongo.sp_uri') ?: env('MONGODB_SP_URI') ?: 'mongodb://crmuser:PetsfolioCRM%402026@127.0.0.1:27017/service-provider-crm?authSource=admin';
        $this->spDbName = env('mongo.sp_database') ?: env('MONGODB_SP_DATABASE') ?: 'service-provider-crm';

        if (class_exists('MongoDB\\Client')) {
            // Initialize Client CRM connection
            if (!empty($clientUri)) {
                try {
                    $this->client = new Client($clientUri);
                    $this->database = $this->client->selectDatabase($this->dbName);
                } catch (\Throwable $e) {
                    log_message('error', 'Client MongoDB Connection Error: ' . $e->getMessage());
                    $this->client = null;
                    $this->database = null;
                }
            }

            // Initialize Service Provider CRM connection
            if (!empty($spUri)) {
                try {
                    $this->spClient = new Client($spUri);
                    $this->spDatabase = $this->spClient->selectDatabase($this->spDbName);
                } catch (\Throwable $e) {
                    log_message('error', 'Service Provider MongoDB Connection Error: ' . $e->getMessage());
                    $this->spClient = null;
                    $this->spDatabase = null;
                }
>>>>>>> origin/main
            }
        }
    }

    /**
<<<<<<< HEAD
=======
     * Resolve target database instance ('client' or 'provider'/'sp').
     */
    public function getDatabase(string $target = 'client')
    {
        $cleanTarget = strtolower(trim($target));
        if (in_array($cleanTarget, ['provider', 'sp', 'service-provider', 'service_provider'], true)) {
            return $this->spDatabase ?? $this->database;
        }
        return $this->database ?? $this->spDatabase;
    }

    /**
>>>>>>> origin/main
     * Run an aggregation pipeline on a MongoDB collection.
     *
     * @param string $collection Collection name (e.g. leads, conversations, messages)
     * @param array $pipeline Aggregation pipeline array
<<<<<<< HEAD
     * @return array Array of matching documents/results
     */
    public function aggregate(string $collection, array $pipeline): array
    {
        if ($this->database === null) {
            log_message('error', "MongoDB aggregate({$collection}): No database connection.");
=======
     * @param string $target Database target ('client' or 'provider')
     * @return array Array of matching documents/results
     */
    public function aggregate(string $collection, array $pipeline, string $target = 'client'): array
    {
        $db = $this->getDatabase($target);
        if ($db === null) {
            log_message('error', "MongoDB aggregate({$collection}, {$target}): No database connection.");
>>>>>>> origin/main
            return [];
        }

        try {
<<<<<<< HEAD
            $col = $this->database->selectCollection($collection);
            $cursor = $col->aggregate($pipeline);
            return json_decode(json_encode($cursor->toArray()), true) ?: [];
        } catch (\Throwable $e) {
            log_message('error', "MongoDB Aggregate Error [{$collection}]: " . $e->getMessage());
=======
            $col = $db->selectCollection($collection);
            $cursor = $col->aggregate($pipeline);
            return json_decode(json_encode($cursor->toArray()), true) ?: [];
        } catch (\Throwable $e) {
            log_message('error', "MongoDB Aggregate Error [{$target}.{$collection}]: " . $e->getMessage());
>>>>>>> origin/main
            return [];
        }
    }

    /**
     * Find documents matching a filter query.
     */
<<<<<<< HEAD
    public function find(string $collection, array $filter = [], array $projection = [], int $limit = 100): array
    {
        if ($this->database === null) {
            log_message('error', "MongoDB find({$collection}): No database connection.");
=======
    public function find(string $collection, array $filter = [], array $projection = [], int $limit = 100, string $target = 'client'): array
    {
        $db = $this->getDatabase($target);
        if ($db === null) {
            log_message('error', "MongoDB find({$collection}, {$target}): No database connection.");
>>>>>>> origin/main
            return [];
        }

        try {
            $options = ['limit' => $limit];
            if (!empty($projection)) {
                $options['projection'] = $projection;
            }
<<<<<<< HEAD
            $col = $this->database->selectCollection($collection);
            $cursor = $col->find($filter, $options);
            return json_decode(json_encode($cursor->toArray()), true) ?: [];
        } catch (\Throwable $e) {
            log_message('error', "MongoDB Find Error [{$collection}]: " . $e->getMessage());
=======
            $col = $db->selectCollection($collection);
            $cursor = $col->find($filter, $options);
            return json_decode(json_encode($cursor->toArray()), true) ?: [];
        } catch (\Throwable $e) {
            log_message('error', "MongoDB Find Error [{$target}.{$collection}]: " . $e->getMessage());
>>>>>>> origin/main
            return [];
        }
    }

    /**
     * Find a single document matching a filter query.
     */
<<<<<<< HEAD
    public function findOne(string $collection, array $filter = [], array $projection = []): ?array
    {
        if ($this->database === null) {
=======
    public function findOne(string $collection, array $filter = [], array $projection = [], string $target = 'client'): ?array
    {
        $db = $this->getDatabase($target);
        if ($db === null) {
>>>>>>> origin/main
            return null;
        }

        try {
            $options = [];
            if (!empty($projection)) {
                $options['projection'] = $projection;
            }
<<<<<<< HEAD
            $col = $this->database->selectCollection($collection);
            $doc = $col->findOne($filter, $options);
            return $doc ? json_decode(json_encode($doc), true) : null;
        } catch (\Throwable $e) {
            log_message('error', "MongoDB FindOne Error [{$collection}]: " . $e->getMessage());
=======
            $col = $db->selectCollection($collection);
            $doc = $col->findOne($filter, $options);
            return $doc ? json_decode(json_encode($doc), true) : null;
        } catch (\Throwable $e) {
            log_message('error', "MongoDB FindOne Error [{$target}.{$collection}]: " . $e->getMessage());
>>>>>>> origin/main
            return null;
        }
    }

    /**
     * Count documents in a collection matching a filter.
     */
<<<<<<< HEAD
    public function countDocuments(string $collection, array $filter = []): int
    {
        if ($this->database === null) {
=======
    public function countDocuments(string $collection, array $filter = [], string $target = 'client'): int
    {
        $db = $this->getDatabase($target);
        if ($db === null) {
>>>>>>> origin/main
            return 0;
        }

        try {
<<<<<<< HEAD
            $col = $this->database->selectCollection($collection);
            return $col->countDocuments($filter);
        } catch (\Throwable $e) {
            log_message('error', "MongoDB CountDocuments Error [{$collection}]: " . $e->getMessage());
=======
            $col = $db->selectCollection($collection);
            return $col->countDocuments($filter);
        } catch (\Throwable $e) {
            log_message('error', "MongoDB CountDocuments Error [{$target}.{$collection}]: " . $e->getMessage());
>>>>>>> origin/main
            return 0;
        }
    }

    /**
     * Diagnostic helper to list all collections and their document counts.
     */
<<<<<<< HEAD
    public function listCollectionsAndStats(): array
    {
        if ($this->database === null) {
            return ['status' => 'error', 'message' => 'MongoDB Client not initialized.'];
=======
    public function listCollectionsAndStats(string $target = 'client'): array
    {
        $db = $this->getDatabase($target);
        $name = in_array(strtolower(trim($target)), ['provider', 'sp', 'service-provider'], true) ? $this->spDbName : $this->dbName;

        if ($db === null) {
            return ['status' => 'error', 'database' => $name, 'message' => "MongoDB Client not initialized for {$name}."];
>>>>>>> origin/main
        }

        try {
            $stats = [];
<<<<<<< HEAD
            foreach ($this->database->listCollections() as $collInfo) {
                $name = $collInfo->getName();
                $count = $this->database->selectCollection($name)->countDocuments();
                $stats[$name] = $count;
=======
            foreach ($db->listCollections() as $collInfo) {
                $cName = $collInfo->getName();
                $count = $db->selectCollection($cName)->countDocuments();
                $stats[$cName] = $count;
>>>>>>> origin/main
            }

            return [
                'status'      => 'ok',
<<<<<<< HEAD
                'database'    => $this->dbName,
=======
                'database'    => $name,
                'target'      => $target,
>>>>>>> origin/main
                'collections' => $stats
            ];
        } catch (\Throwable $e) {
            return [
<<<<<<< HEAD
                'status'  => 'error',
                'message' => 'MongoDB Diagnostic Error: ' . $e->getMessage()
=======
                'status'   => 'error',
                'database' => $name,
                'message'  => 'MongoDB Diagnostic Error: ' . $e->getMessage()
>>>>>>> origin/main
            ];
        }
    }

    /**
     * Check if the database connection is active.
     */
<<<<<<< HEAD
    public function isConnected(): bool
    {
        return $this->database !== null;
=======
    public function isConnected(string $target = 'client'): bool
    {
        return $this->getDatabase($target) !== null;
    }

    /**
     * Diagnostic helper to check driver installation and database connection status.
     */
    public function isClientAvailable(): array
    {
        $hasDriver = class_exists('MongoDB\Client');
        return [
            'driver_installed'     => $hasDriver,
            'client_crm_connected' => $this->database !== null,
            'client_crm_database'  => $this->dbName,
            'sp_crm_connected'     => $this->spDatabase !== null,
            'sp_crm_database'      => $this->spDbName,
        ];
>>>>>>> origin/main
    }
}
