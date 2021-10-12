<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRowsTable extends Migration
{
    public function up()
    {
        Schema::create('rows', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique();
            $table->string('name');
            $table->date('date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rows');
    }
}
