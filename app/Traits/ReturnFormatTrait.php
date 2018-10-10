<?php

namespace App\Traits;

trait ReturnFormatTrait
{

	/**
	 * ReturnFormat
	 * @author leekachung <leekachung17@gmail.com>
	 * @param  [Int] $status  [返回状态码]
	 * @param  [String] $content [返回内容]
	 * @param  [String] $ps      [返回补充内容]
	 */
	public function ReturnFormat($status, $content=null, $ps=null)
	{
		return [
			'Status' => $status,
			'Content' => $content,
			'ps' => $ps
		];
	}
}