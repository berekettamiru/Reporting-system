<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('reports', function (Blueprint $table) {
        $table->text('feedback')->nullable()->after('report_type');
        $table->foreignId('feedback_by')->nullable()->constrained('users')->after('feedback');
        $table->timestamp('feedback_at')->nullable()->after('feedback_by');
        $table->boolean('seen')->default(false)->after('feedback_at');
    });
}

public function down()
{
    Schema::table('reports', function (Blueprint $table) {
        $table->dropColumn(['feedback', 'feedback_by', 'feedback_at', 'seen']);
    });
}
};
