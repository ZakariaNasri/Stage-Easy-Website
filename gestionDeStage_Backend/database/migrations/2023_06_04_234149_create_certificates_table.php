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
    Schema::create('certificates', function (Blueprint $table) {
        $table->id();
        $table->string('token')->unique();
        $table->unsignedBigInteger('id_Etud');
        $table->unsignedBigInteger('id_Stage');
        $table->timestamps();

        $table->foreign('id_Etud')->references('id_Etud')->on('etudiant')->onDelete('cascade');
        $table->foreign('id_Stage')->references('id_Stage')->on('stage')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certificates');
    }
};
