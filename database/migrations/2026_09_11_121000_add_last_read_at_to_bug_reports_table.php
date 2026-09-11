<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastReadAtToBugReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('bug_reports') && !Schema::hasColumn('bug_reports', 'last_read_at')) {
            Schema::table('bug_reports', function (Blueprint $table) {
                $table->timestamp('last_read_at')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('bug_reports') && Schema::hasColumn('bug_reports', 'last_read_at')) {
            Schema::table('bug_reports', function (Blueprint $table) {
                $table->dropColumn('last_read_at');
            });
        }
    }
}
