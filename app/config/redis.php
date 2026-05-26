<?php
// Cấu hình Redis
// Redis dùng để cache danh sách sản phẩm, giảm tải database

class RedisCache
{
    private static $instance = null;
    private $redis = null;
    private $enabled = false;

    // Thời gian cache mặc định (giây)
    const CACHE_TTL = 300; // 5 phút

    private function __construct()
    {
        // Kiểm tra xem extension redis có được cài không
        if (class_exists('Redis')) {
            try {
                $this->redis = new Redis();
                $this->redis->connect('127.0.0.1', 6379);
                $this->redis->ping();
                $this->enabled = true;
            } catch (Exception $e) {
                // Redis không khả dụng, fallback không cache
                $this->enabled = false;
                $this->redis = null;
            }
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Lấy giá trị từ cache
     */
    public function get($key)
    {
        if (!$this->enabled) return null;

        try {
            $data = $this->redis->get($key);
            if ($data === false) return null;
            return json_decode($data);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Lưu giá trị vào cache
     */
    public function set($key, $value, $ttl = self::CACHE_TTL)
    {
        if (!$this->enabled) return false;

        try {
            return $this->redis->setex($key, $ttl, json_encode($value));
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Xóa một key khỏi cache
     */
    public function delete($key)
    {
        if (!$this->enabled) return false;

        try {
            return $this->redis->del($key);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Xóa tất cả cache có prefix
     */
    public function deleteByPrefix($prefix)
    {
        if (!$this->enabled) return false;

        try {
            $keys = $this->redis->keys($prefix . '*');
            if (!empty($keys)) {
                $this->redis->del($keys);
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Xóa toàn bộ cache
     */
    public function flush()
    {
        if (!$this->enabled) return false;

        try {
            return $this->redis->flushDB();
        } catch (Exception $e) {
            return false;
        }
    }
}
