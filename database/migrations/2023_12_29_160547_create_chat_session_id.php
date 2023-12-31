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
		Schema::table('chats', function (Blueprint $table) {
			// Assuming `chat_sessions` table has an `id` column of type `bigInteger`
			$table->uuid('chat_session_id')->nullable()->after('id');
			$table->foreign('chat_session_id')->references('id')->on('chat_sessions');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('chats', function (Blueprint $table) {
			$table->dropForeign(['chat_session_id']);
			$table->dropColumn('chat_session_id');
		});
	}
};
