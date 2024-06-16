<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelVersionsTable extends Migration
{
    public function up()
    {
        Schema::create('model_versions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('model_type');
            $table->ulid('model_id');
            $table->json('data');
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('model_versions');
    }
}
