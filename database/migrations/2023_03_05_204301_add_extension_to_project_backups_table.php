<?php

use App\Enums\FileType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('project_backups', function(Blueprint $table) {
            $table->enum('type', enumToNames(FileType::cases()))
                ->after('name');
        });
    }
};
