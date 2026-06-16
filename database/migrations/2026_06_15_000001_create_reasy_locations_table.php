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
        Schema::create('locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('name');
            $table->string('slug');
            $table->string('description')->nullable();
            
            // Contact & Address
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            
            // Settings
            $table->string('timezone')->default('America/Panama');
            $table->boolean('is_active')->default(true);
            $table->jsonb('meta')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'id']);
            $table->index(['tenant_id', 'slug']);
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE "locations" ENABLE ROW LEVEL SECURITY;');
            DB::statement('ALTER TABLE "locations" FORCE ROW LEVEL SECURITY;');
            DB::statement("CREATE POLICY tenant_isolation ON \"locations\" USING (tenant_id::text = current_setting('app.tenant_id')) WITH CHECK (tenant_id::text = current_setting('app.tenant_id'));");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
