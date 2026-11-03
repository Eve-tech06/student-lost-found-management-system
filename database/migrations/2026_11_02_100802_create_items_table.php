```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->string('item_name');
            $table->text('description')->nullable();

            $table->enum('report_type', ['lost', 'found']);
            $table->enum('status', ['open', 'pending', 'claimed', 'returned'])
                  ->default('open');

            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->restrictOnDelete();

            $table->foreignId('location_id')
                  ->constrained('locations')
                  ->restrictOnDelete();

            $table->foreignId('reported_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->date('date_lost_found');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
