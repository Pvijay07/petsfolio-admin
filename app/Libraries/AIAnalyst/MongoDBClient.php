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
            }
        }
    }

    /**
     * Run an aggregation pipeline on a MongoDB collection.
     *
     * @param string $collection Collection name (e.g. leads, conversations, messages)
     * @param array $pipeline Aggregation pipeline array
     * @return array Array of matching documents/results
     */
    public function aggregate(string $collection, array $pipeline): array
    {
        if ($this->database === null) {
            log_message('error', "MongoDB aggregate({$collection}): No database connection.");
            return [];
        }

        try {
            $col = $this->database->selectCollection($collection);
            $cursor = $col->aggregate($pipeline);
            return json_decode(json_encode($cursor->toArray()), true) ?: [];
        } catch (\Throwable $e) {
            log_message('error', "MongoDB Aggregate Error [{$collection}]: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Find documents matching a filter query.
     */
    public function find(string $collection, array $filter = [], array $projection = [], int $limit = 100): array
    {
        if ($this->database === null) {
            log_message('error', "MongoDB find({$collection}): No database connection.");
            return [];
        }

        try {
            $options = ['limit' => $limit];
            if (!empty($projection)) {
                $options['projection'] = $projection;
            }
            $col = $this->database->selectCollection($collection);
            $cursor = $col->find($filter, $options);
            return json_decode(json_encode($cursor->toArray()), true) ?: [];
        } catch (\Throwable $e) {
            log_message('error', "MongoDB Find Error [{$collection}]: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Find a single document matching a filter query.
     */
    public function findOne(string $collection, array $filter = [], array $projection = []): ?array
    {
        if ($this->database === null) {
            return null;
        }

        try {
            $options = [];
            if (!empty($projection)) {
                $options['projection'] = $projection;
            }
            $col = $this->database->selectCollection($collection);
            $doc = $col->findOne($filter, $options);
            return $doc ? json_decode(json_encode($doc), true) : null;
        } catch (\Throwable $e) {
            log_message('error', "MongoDB FindOne Error [{$collection}]: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Count documents in a collection matching a filter.
     */
    public function countDocuments(string $collection, array $filter = []): int
    {
        if ($this->database === null) {
            return 0;
        }

        try {
            $col = $this->database->selectCollection($collection);
            return $col->countDocuments($filter);
        } catch (\Throwable $e) {
            log_message('error', "MongoDB CountDocuments Error [{$collection}]: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Diagnostic helper to list all collections and their document counts.
     */
    public function listCollectionsAndStats(): array
    {
        if ($this->database === null) {
            return ['status' => 'error', 'message' => 'MongoDB Client not initialized.'];
        }

        try {
            $stats = [];
            foreach ($this->database->listCollections() as $collInfo) {
                $name = $collInfo->getName();
                $count = $this->database->selectCollection($name)->countDocuments();
                $stats[$name] = $count;
            }

            return [
                'status'      => 'ok',
                'database'    => $this->dbName,
                'collections' => $stats
            ];
        } catch (\Throwable $e) {
            return [
                'status'  => 'error',
                'message' => 'MongoDB Diagnostic Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check if the database connection is active.
     */
    public function isConnected(): bool
    {
        return $this->database !== null;
    }
}
