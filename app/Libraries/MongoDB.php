<?php

namespace App\Libraries;

use MongoDB\Client;

/**
 * MongoDB
 *
 * General-purpose MongoDB library for CodeIgniter 4.
 * Connects to MongoDB Atlas using the official mongodb/mongodb package.
 */
class MongoDB
{
    protected Client $client;
    protected $database;

    public function __construct()
    {
        $uri = env('mongo.uri');
        $dbName = env('mongo.database');

        $this->client = new Client($uri);
        $this->database = $this->client->selectDatabase($dbName);
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function collection($name)
    {
        return $this->database->selectCollection($name);
    }
}
