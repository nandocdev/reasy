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

            $table->index(['tenant_id', 'email']);
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE customers ENABLE ROW LEVEL SECURITY;');
            DB::statement('ALTER TABLE customers FORCE ROW LEVEL SECURITY;');
            DB::statement("CREATE POLICY tenant_isolation ON customers USING (tenant_id::text = current_setting('app.tenant_id')) WITH CHECK (tenant_id::text = current_setting('app.tenant_id'));");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
