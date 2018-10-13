<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;

Trait DoQueueTrait
{

	/**
     * doQueue 执行队列
     * @author leekachung <leekachung17@gmail.com>
     * @param  $list      链表名
     * @param  $max_num   队列元素个数最大值
     * @param  $laytime   等待再次入队时间 微秒计算
     * @return [type] [description]
     */
    public function doQueue($list, $max_num, $laytime)
    {
        while(Redis::llen($list) > $max_num) {
            usleep($laytime); //队列占满时, 睡0.5s
        }

        //进入队列
        Redis::lpush($list, 1); 
        //保证队列只有max_num
        Redis::ltrim($list, 0, $max_num); 
        //退出队列执行后续操作
        $count = Redis::lpop($list);

        if (!$count) {
            return false;
        }

        return true;
    }

}