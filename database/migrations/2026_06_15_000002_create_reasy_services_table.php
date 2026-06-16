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
        Schema::create('cancellation_policies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('name');
            $table->jsonb('rules'); // Array of rules: [{ 'before_hours': 48, 'refund_percent': 100 }, ...]
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index(['tenant_id', 'id']);
        });

        Schema::create('service_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('name');
            $table->string('slug');
            $table->string('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['tenant_id', 'id']);
            $table->index(['tenant_id', 'slug']);
        });

        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('category_id')->nullable();
            $table->uuid('cancellation_policy_id')->nullable();
            
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            
            // Duration & Buffers
            $table->integer('duration')->comment('Duration in minutes');
            $table->integer('buffer_before')->default(0)->comment('Preparation time in minutes');
            $table->integer('buffer_after')->default(0)->comment('Cleanup time in minutes');
            
            // Pricing & Deposit
            $table->integer('price')->comment('Price in cents');
            $table->string('currency', 3)->default('USD');
            $table->string('deposit_type')->default('none'); // none, percentage, fixed
            $table->integer('deposit_value')->default(0)->comment('Value in cents or percentage');
            
            // Capacity & Visibility
            $table->integer('capacity')->default(1);
            $table->boolean('is_active')->default(true);
            $table->boolean('visible_on_widget')->default(true);
            $table->integer('order')->default(0);
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'id']);
            $table->index(['tenant_id', 'category_id']);
            $table->index(['tenant_id', 'slug']);

            $table->foreign('category_id')->references('id')->on('service_categories')->onDelete('set null');
            $table->foreign('cancellation_policy_id')->references('id')->on('cancellation_policies')->onDelete('set null');
        });

        if (DB::getDriverName() === 'pgsql') {
            foreach (['cancellation_policies', 'service_categories', 'services'] as $table) {
                DB::statement("ALTER TABLE \"{$table}\" ENABLE ROW LEVEL SECURITY;");
                DB::statement("ALTER TABLE \"{$table}\" FORCE ROW LEVEL SECURITY;");
                DB::statement("CREATE POLICY tenant_isolation ON \"{$table}\" USING (tenant_id::text = current_setting('app.tenant_id')) WITH CHECK (tenant_id::text = current_setting('app.tenant_id'));");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_categories');
        Schema::dropIfExists('cancellation_policies');
    }
};
