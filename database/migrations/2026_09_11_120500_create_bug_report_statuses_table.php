<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateBugReportStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bug_report_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bug_report_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('status', 50)->default('new');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('bug_report_id')
                ->references('id')
                ->on('bug_reports')
                ->onDelete('cascade');
        });

        // Backfill existing bug reports if any exist
        try {
            $existingReports = DB::table('bug_reports')->get();
            foreach ($existingReports as $report) {
                DB::table('bug_report_statuses')->insert([
                    'bug_report_id' => $report->id,
                    'user_id' => $report->user_id,
                    'status' => $report->status ?? 'new',
                    'comment' => 'Sākotnējais statuss',
                    'created_at' => $report->created_at ?? now(),
                    'updated_at' => $report->updated_at ?? now(),
                ]);
            }
        } catch (\Throwable $e) {
            // Ignore backfill errors if tables empty
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bug_report_statuses');
    }
}
