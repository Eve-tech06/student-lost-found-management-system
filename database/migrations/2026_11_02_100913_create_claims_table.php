```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_id')
                  ->constrained('items')
                  ->cascadeOnDelete();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->text('claim_description');

            $table->enum('claim_status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->timestamp('claimed_at')->useCurrent();
            $table->timestamp('reviewed_at')->nullable();

            $table->foreignId('reviewed_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
