<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->String('code')->primary();
            $table->string('name');
            $table->string('type');
            $table->string('de_holder');//Detenteur DE
            $table->string('conditioning');
            $table->foreignId('pharmaceutical_establishment_id')->nullable()->constrained()->onDelete('SET NULL');
            $table->foreignId('form_id')->nullable()->constrained()->onDelete('SET NULL');
            $table->foreignId('presentation_id')->nullable()->constrained()->onDelete('SET NULL');
            $table->foreignId('dosage_id')->nullable()->constrained()->onDelete('SET NULL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medications');
    }
};
