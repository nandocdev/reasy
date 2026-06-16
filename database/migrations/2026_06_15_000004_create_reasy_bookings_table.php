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
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('birth_date')->nullable();
            
            $table->string('status')->default('active'); // active, inactive, banned
            $table->jsonb('meta')->nullable();
            $table->text('internal_notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'id']);
            $table->index(['tenant_id', 'email']);
            $table->index(['tenant_id', 'phone']);
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('location_id');
            $table->uuid('service_id');
            $table->uuid('resource_id');
            $table->uuid('customer_id');
            
            // Scheduling
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('timezone')->default('America/Panama');
            
            // Status & Lifecycle
            $table->string('status')->default('draft'); // draft, pending_payment, confirmed, expired, completed, no_show, cancelled, rescheduled
            $table->string('token')->unique()->comment('Unique token for guest management');
            
            // Pricing
            $table->integer('price')->comment('Total price in cents');
            $table->integer('deposit_paid')->default(0)->comment('Deposit paid in cents');
            $table->string('currency', 3)->default('USD');
            
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'id']);
            $table->index(['tenant_id', 'location_id', 'start_time']);
            $table->index(['tenant_id', 'resource_id', 'start_time']);
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'customer_id']);

            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });

        if (DB::getDriverName() === 'pgsql') {
            foreach (['customers', 'bookings'] as $table) {
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
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('customers');
    }
};
