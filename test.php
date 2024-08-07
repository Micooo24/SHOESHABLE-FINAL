<?php

use Meilisearch\Client as MeiliClient;

require 'vendor/autoload.php';

// Initialize with your host and API key
$client = new MeiliClient('http://localhost:7700', 'ef8136dffb2b7b6e823da50805ee09fa42b9e123');

try {
    // Test connection
    $indexes = $client->getIndexes();
    echo "Meilisearch client created successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
