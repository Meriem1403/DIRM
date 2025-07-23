<?php

namespace App\Service;

use MongoDB\Client;
use MongoDB\Collection;

class MongoService
{
    private Client $client;

    public function __construct(string $mongoUrl)
    {
        $this->client = new Client($mongoUrl);
    }

    public function getCollection(string $db, string $collection): Collection
    {
        return $this->client->$db->$collection;
    }
}
