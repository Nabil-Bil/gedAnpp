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
        Schema::create('dci_medication', function (Blueprint $table) {
            $table->id();
            $table->string('medication_code')->nullable();
            $table->foreign('medication_code')->on('medications')->references('code')->onDelete('Cascade');
            $table->foreignId('dci_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('pivot_table_dci_medication');
    }
};
