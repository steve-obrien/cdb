<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('chats', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->bigInteger('user_id')->nullable();
			$table->string('name')->nullable(); //^[a-zA-Z0-9_-]{1,64}$
			$table->string('role'); // can be one of 'user' | 'system' | 'assistant' | 'tool'
			$table->longText('content');
			$table->json('tool_calls')->nullable();
			$table->json('chunks')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::drop('chats');
	}
};
