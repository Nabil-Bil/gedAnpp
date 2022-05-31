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
        Schema::create('pharmaceutical_establishments', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email");
            $table->string("fixed"); //Numéro de telephone fixe
            $table->string("mobile");
            $table->string("fax");
            $table->string("address");
            $table->string("nature");
            $table->string("agreement"); //Agrément
            $table->string("status");
            $table->string("manager_name"); //Nom du garant
            $table->string("tech_manager_name"); // nom du directeur technique
            $table->string("activity");
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
        Schema::dropIfExists('pharmaceutical_establishments');
    }
};
