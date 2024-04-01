<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			// Add columns for OAuth provider and provider unique ID
			$table->string('provider')->nullable();
			$table->string('provider_id')->nullable();
			// Optional: Store the OAuth token if needed
			$table->text('provider_token')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			// Remove the columns if the migration is rolled back
			$table->dropColumn(['provider', 'provider_id', 'provider_token']);
		});
	}
};
