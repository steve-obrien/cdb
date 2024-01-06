<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up()
	{
		Schema::create('chat_sessions', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->string('name')->nullable();
			$table->enum('visibility', ['public', 'private'])->default('public');
			$table->bigInteger('created_by')->unsigned();
			$table->foreign('created_by')->references('id')->on('users');
			$table->json('folder')->nullable(); // To store folder information
			$table->longText('prompt')->nullable(); // The original prompt creating this session
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::dropIfExists('chat_sessions');
	}
};
