<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ticket', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();;
			$table->string('full_name');
			$table->string('email');
			$table->string('phone_num', 50);
			$table->string('hash');
			$table->string('file_path')->nullable();
			$table->text('description');
			$table->integer('ticket_category')->nullable();
			$table->integer('ticket_status')->nullable();
			$table->integer('admin_id')->unsigned()->nullable();
			$table->dateTime('answered_at')->nullable();
			$table->timestamps();
		});

		Schema::table('ticket', function (Blueprint $table) {
			$table->foreign('admin_id')->references('id')->on('users');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ticket');
	}

}
