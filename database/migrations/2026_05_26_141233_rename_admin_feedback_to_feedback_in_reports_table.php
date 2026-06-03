<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->renameColumn('admin_feedback', 'feedback');
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->renameColumn('feedback', 'admin_feedback');
        });
    }
};