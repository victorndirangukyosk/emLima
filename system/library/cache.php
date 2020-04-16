<?php


class Cache {

    protected $cache;

    public function __construct($storage, $lifetime = 86400) {
        $class = 'Joomla\Cache\\' . ucfirst($storage);

        $options = array(
            'file.path' => DIR_CACHE,
            'ttl' => $lifetime,
        );

        if (class_exists($class)) {
            $this->cache = new $class($options);
        }
        else {
            exit('Error: Could not load cache storage ' . $storage . ' cache!');
        }
    }

    public function get($key) {
        $item = $this->cache->get($key);

        return $item->getValue();
    }

    public function getMultiple($keys) {
        return $this->cache->getMultiple($keys);
    }

    public function getOption($key) {
        return $this->cache->getOption($key);
    }

    public function set($key, $value, $ttl = null) {
        return $this->cache->set($key, $value, $ttl = null);
    }

    public function setMultiple($keys, $ttl = null) {
        return $this->cache->setMultiple($keys, $ttl = null);
    }

    public function setOption($key) {
        return $this->cache->setOption($key);
    }

    public function remove($key) {
        return $this->cache->remove($key);
    }

    public function removeMultiple($keys) {
        return $this->cache->removeMultiple($keys);
    }

    // Clear the whole cache
    public function clear() {
        return $this->cache->clear();
    }

    // Check if key exists
    public function exists($key) {
        return $this->cache->exists($key);
    }

    // Depreciated, use remove instead (B/C)
    public function delete($key) {
        return $this->remove($key);
    }
}