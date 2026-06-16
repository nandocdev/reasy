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
        Schema::create('resources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('location_id');
            $table->uuid('user_id')->nullable()->comment('Associated system user for staff');
            
            $table->string('name');
            $table->string('type'); // staff, room, equipment
            $table->text('description')->nullable();
            $table->string('avatar_path')->nullable();
            
            $table->integer('capacity')->default(1);
            $table->boolean('is_active')->default(true);
            $table->jsonb('meta')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'id']);
            $table->index(['tenant_id', 'location_id']);
            $table->index(['tenant_id', 'user_id']);

            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('resource_service', function (Blueprint $table) {
            $table->uuid('resource_id');
            $table->uuid('service_id');
            $table->uuid('tenant_id');
            
            $table->primary(['resource_id', 'service_id']);
            $table->index(['tenant_id', 'resource_id']);
            
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });

        Schema::create('schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('resource_id');
            
            $table->unsignedTinyInteger('day_of_week'); // 0 (Sunday) to 6 (Saturday)
            $table->time('start_time');
            $table->time('end_time');
            
            $table->timestamps();

            $table->index(['tenant_id', 'resource_id']);
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
        });

        Schema::create('schedule_exceptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('resource_id');
            
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('is_unavailable')->default(true);
            $table->string('reason')->nullable();
            
            $table->timestamps();

            $table->index(['tenant_id', 'resource_id', 'date']);
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
        });

        if (DB::getDriverName() === 'pgsql') {
            foreach (['resources', 'resource_service', 'schedules', 'schedule_exceptions'] as $table) {
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
        Schema::dropIfExists('schedule_exceptions');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('resource_service');
        Schema::dropIfExists('resources');
    }
};
