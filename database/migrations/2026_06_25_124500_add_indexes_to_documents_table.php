<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->index('deleted_at');
            $table->index('document_date');
            $table->index('document_category_id');
            $table->index('department_id');
            $table->index('document_code_id');
            $table->index('document_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex(['deleted_at']);
            $table->dropIndex(['document_date']);
            $table->dropIndex(['document_category_id']);
            $table->dropIndex(['department_id']);
            $table->dropIndex(['document_code_id']);
            $table->dropIndex(['document_number']);
        });
    }
};
