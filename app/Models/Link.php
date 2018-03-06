<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Link extends Model
{
    protected $fillable = ['title', 'link'];

    public $cache_key = 'larabbs_links';

    protected $cache_expire_in_minutes = 1440;

    public function getAllCached() {
        // 尝试从缓存中取出cache_key对应的数据，如果取到，则直接返回，否则运行匿名函数中的代码来取出数据，返回的同时进行缓存
        return Cache::remember($this->cache_key, $this->cache_expire_in_minutes, function() {
            return $this->all();
        });
    }
}
