<?php
/**
 * Created by PhpStorm.
 * User: jamieaitken
 * Date: 07/09/2018
 * Time: 23:16
 */

namespace App\Controllers;


use Doctrine\Common\Cache\PredisCache;
use Predis\Client;

class CacheHandler
{
    protected $cache;

    public function __construct()
    {
        $this->cache = new PredisCache(new Client(getenv('CACHE_KEY')));
    }

    public function save(string $path, array $data)
    {
        return $this->cache->save($path, $data, 3600);
    }

    public function fetch(string $path)
    {
        return $this->cache->fetch($path);
    }
}