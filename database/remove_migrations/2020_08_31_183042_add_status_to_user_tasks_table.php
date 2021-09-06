<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToUserTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_tasks', function (Blueprint $table) {
            $table->unsignedSmallInteger('status')->nullable()->after('task_id');
            $table->timestamp('completed_at')->nullable()->after('status');
            $table->unique(['user_id', 'task_id']);
            $table->boolean('active')->nullable()->change();
            $table->dateTime('start_datetime')->nullable()->change();
            $table->dateTime('end_datetime')->nullable()->change();
            $table->boolean('payed')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_tasks', function (Blueprint $table) {
            $table->dropColumn(['link']);
        });
    }
}
