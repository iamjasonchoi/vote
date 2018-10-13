<?php

namespace App\Traits;

trait TrimTrait
{

	/**
	 * trimAll 去除字符串中所有空格
	 * @author leekachung <leekachung17@gmail.com>
	 * @param  [type] $str [description]
	 * @return [type]      [description]
	 */
	public function trimAll($str)
	{
		return preg_replace('# #','',$str);
	}

}