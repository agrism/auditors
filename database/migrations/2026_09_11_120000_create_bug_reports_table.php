<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBugReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bug_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('email', 255)->nullable();
            $table->string('section', 255)->nullable();
            $table->text('url')->nullable();
            $table->string('status', 50)->default('new');
            $table->timestamps();
        });

        Schema::create('bug_report_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bug_report_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->text('message');
            $table->text('attachments')->nullable();
            $table->boolean('is_admin_reply')->default(false);
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->foreign('bug_report_id')
                ->references('id')
                ->on('bug_reports')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bug_report_items');
        Schema::dropIfExists('bug_reports');
    }
}
