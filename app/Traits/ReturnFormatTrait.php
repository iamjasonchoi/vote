<?php

namespace App\Traits;

trait ReturnFormatTrait
{

	/**
	 * ReturnFormat
	 * @author leekachung <[leekachung17@gmail.com]>
	 * @DateTime        2018-10-02T21:24:22+0800
	 * @param    [type] $status                  [description]
	 * @param    [type] $content                 [description]
	 * @param    [type] $ps                      [description]
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