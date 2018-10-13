<?php

namespace App\Traits;

trait ReturnFormatTrait
{

	/**
	 * ReturnFormat
	 * @author leekachung <leekachung17@gmail.com>
	 * @param  [type] $status  [description]
	 * @param  [type] $content [description]
	 * @param  [type] $ps      [description]
	 */
	public function ReturnFormat($status, $content=null, $ps=null)
	{
		return [
			'Status' => $status,
			'Content' => $content,
			'ps' => $ps
		];
	}

	/**
	 * ReturnJsonResponse
	 * @author leekachung <leekachung17@gmail.com>
	 * @param  [Int] $status_code [返回状态码]
	 * @param  [String] $messgae     [返回信息]
	 * @param  [String] $token       [返回token]
	 * @param  [String] $ps          [返回补充信息]
	 */
	public function ReturnJsonResponse(
		$status_code, $messgae, $token=null, $ps=null)
	{
		$param = [
			'status_code' => $status_code,
			'message' => $messgae,
			'_token' => $token,
			'ps' => $ps
		];
		return response()->json($param);
	}


}