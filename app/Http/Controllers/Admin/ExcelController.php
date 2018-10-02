<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;


class ExcelController extends Controller
{
    public function import()
    {
    	//$excel = Excel::load();
    }

    public function exportModel(
    	$filename, $sheetname, $title, $format
    ) {
    	Excel::create($filename, function($excel) {
    		$excel->sheet($sheetname, function($sheet) {
    			$sheet->with($title);
    		});
		})->export($format);
    }
}
