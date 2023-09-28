<?php
  declare(strict_types = 1);
  namespace App\Services;  use Illuminate\Support\Facades\Redis;  class RedisService {
         public $oRedis;      public function __construct()     {
                 $this->oRedis = app('redis');     }      // 使用redis实现分布式锁 setNX，是set if not exists 的缩写，也就是只有不存在的时候才设置, 设置成功时返回 1 ， 设置失败时返回 0     public function lock($key, $lockTime = 5)     {         // return $this->oRedis->set($key, '1', ['EX' => $lockTime, 'NX']);         $result = $this->oRedis->setnx($key, 1);         // 上锁成功才需设置过期时间         if ($result) {             $this->oRedis->expire($key, $lockTime);         }          return $result;     }      public function release($key)     {         return $this->oRedis->del($key);     }      public function set($key, $value, $timeOut = 1800)     {         return $this->oRedis->setex($key, (int) $timeOut, $value);     }      public function hGet($key, $hashKey)     {         return $this->oRedis->hGet($key, $hashKey);     }      public function hSet($key, $hashKey, $value)     {         return $this->oRedis->hSet($key, $hashKey, $value);     }      public function get($key)     {         return $this->oRedis->get($key);     }      public function listLength($key)     {         return $this->oRedis->lLen($key);     }      public function rpush($key, $value)     {         return $this->oRedis->rpush($key, $value);     }      public function lpush($key, $value)     {         return $this->oRedis->lpush($key, $value);     }      public function lpop($key)     {         return $this->oRedis->lPop($key);     }      public function rpop($key)     {         return $this->oRedis->rpop($key);     }     public function zAdd($key, $score, $value)     {         return $this->oRedis->zAdd($key, $score, $value);     }     public function zRem($key, $member, $other_members = null)     {         return $this->oRedis->zRem($key, $member);     }     public function zCard($key)     {         return $this->oRedis->zCard($key);     }      public function zAll($key)     {         return $this->oRedis->zRange($key, 0, -1);     }      /**      * Increase a key      *      * @param $key      * @return mixed      */     public function increase($key)     {        return $this->oRedis->incr($key);     } } 