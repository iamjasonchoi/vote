<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateVotesTable.
 */
class CreateVotesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vote', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 8)->comment('候选人姓名');
            $table->integer('vote_num')->default(0)->comment('得票数');
            $table->tinyInteger('is_waiting')
            		->default(0)->comment('是否推荐人选: 1=>是, 2=>否');
            $table->integer('vote_model_id')->comment('关联投票模型');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vote');
	}
}
