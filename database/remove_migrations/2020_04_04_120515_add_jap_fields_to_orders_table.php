<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJapFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_id', 16)->unique()->nullable()->after('id');
            $table->unsignedInteger('packet_id')->nullable()->after('status');
            $table->foreign('packet_id')->references('id')->on('packets')->onDelete('set null')->onUpdate('cascade');
            $table->string('jap_id', 16)->unique()->nullable()->after('packet_id');
            $table->enum('jap_status', config('enumerations')['order_jap_statuses'])->nullable()->after('jap_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'packet_id', 'jap_id', 'jap_status']);
        });
    }
}
