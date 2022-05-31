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
        Schema::create('devices', function (Blueprint $table) {
            $table->String('code')->primary();
            $table->string('name');
            $table->string('type');
            $table->string('de_holder'); //Detenteur DE
            $table->foreignId('pharmaceutical_establishment_id')->nullable()->constrained()->onDelete('SET NULL');
            $table->string('designation_id')->nullable()->constrained()->onDelete('SET NULL');;
            $table->string('classification_id')->nullable()->constrained()->onDelete('SET NULL');;
            $table->mediumText('characteristic');
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('devices');
    }
};
