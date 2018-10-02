<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateBehalvesTable.
 */
class CreateBehalvesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('behalf', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 8)->comment('姓名');
            $table->string('student_id', 16)->comment('学号');
            $table->tinyInteger('is_sign')->default(0)
            				->comment('是否签到');
            $table->tinyInteger('is_vote')->default(0)
            				->comment('是否投票');
            $table->integer('vote_model_id')
            				->comment('关联投票模型');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('behalf');
	}
}
