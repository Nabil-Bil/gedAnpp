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
        Schema::create('technical_files', function (Blueprint $table) {
            $table->string('code')->primary();
            $table->string('status');
            $table->unsignedBigInteger('dci_medication_id')->nullable();
            $table->foreign('dci_medication_id')->on('dci_medication')->references('id')->onDelete('SET NULL');
            $table->string('device_code')->nullable();
            $table->foreign('device_code')->on('devices')->references('code')->onDelete('SET NULL');
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
        Schema::dropIfExists('technical_files');
    }
};
