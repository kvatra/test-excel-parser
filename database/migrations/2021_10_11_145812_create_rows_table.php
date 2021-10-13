<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRowsTable extends Migration
{
    public function up()
    {
        Schema::create('rows', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->string('name');
            $table->date('date');
            $table->string('file_id');

            $table->unique(['id', 'file_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('rows');
    }
}
