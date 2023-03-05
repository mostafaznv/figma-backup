<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('projects', function(Blueprint $table) {
            $table->dateTime('latest_backup_at')->nullable()->after('is_active');
        });
    }
};
