<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateVoteModelsTable.
 */
class CreateVoteModelsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vote_model', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->comment('该项目管理员id');
            $table->string('name')->comment('项目名称');
            $table->integer('start')->comment('项目开始时间');
            $table->integer('end')->comment('项目截止时间');
            $table->string('banner')->nullable()
            			->comment('项目海报');
            $table->string('introduce')->nullable()
            			->comment('项目简介');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vote_model');
	}
}
