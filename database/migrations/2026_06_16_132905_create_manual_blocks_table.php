<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('manual_blocks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('resource_id');
            
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('reason')->nullable();
            $table->jsonb('meta')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'resource_id', 'start_time']);
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE manual_blocks ENABLE ROW LEVEL SECURITY;');
            DB::statement('ALTER TABLE manual_blocks FORCE ROW LEVEL SECURITY;');
            DB::statement("CREATE POLICY tenant_isolation ON manual_blocks USING (tenant_id::text = current_setting('app.tenant_id')) WITH CHECK (tenant_id::text = current_setting('app.tenant_id'));");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_blocks');
    }
};
