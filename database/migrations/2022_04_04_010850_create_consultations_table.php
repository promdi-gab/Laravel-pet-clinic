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

        Schema::create("diseases", function (Blueprint $table) {
            $table->increments("id");
            $table->string(column: "diseases");
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('consultations', function (Blueprint $table) {
            $table->increments("id");
            $table->string(column: "date");
            $table->integer(column: "price");
            $table->string(column: "comment");
            $table->integer(column: "hoomans_id")->unsigned();
            $table->integer(column: "pets_id")->unsigned();
            $table->integer(column: "diseases_id")->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table
                ->foreign("hoomans_id")
                ->references("id")
                ->on("hoomans")
                ->onDelete("cascade");
            $table
                ->foreign("pets_id")
                ->references("id")
                ->on("pets")
                ->onDelete("cascade");
                $table
                ->foreign("diseases_id")
                ->references("id")
                ->on("diseases")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consultations');
    }
};
