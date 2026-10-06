<?php

declare(strict_types=1);

use App\Support\Permissions\SourcingRole;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        SourcingRole::ensureExists();
    }

    public function down(): void
    {
        // Role may be assigned to users; leave in place on rollback.
    }
};
