<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCapacityAndTypeToServersTable extends Migration
{
    public function up()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->integer('capacity')->default(10); // Varsayılan kapasite
            $table->enum('type', ['pro', 'public'])->default('public'); // Sunucu tipi
        });
    }

    public function down()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn(['capacity', 'type']);
        });
    }
}

