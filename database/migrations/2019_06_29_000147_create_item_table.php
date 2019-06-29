<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('checklist_id');
            $table->string('name');
            $table->integer('due_interval');
            $table->string('due_unit');
            $table->dateTime('due')->nullable();
            $table->integer('urgency');
            $table->boolean('is_completed')->default(false);
            $table->integer('completed_by')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('checklist_detail');
    }
}
