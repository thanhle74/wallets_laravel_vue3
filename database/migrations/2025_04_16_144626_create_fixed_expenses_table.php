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
        Schema::create('fixed_expense_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // tiền nhà, điện, nước,...
            $table->decimal('amount', 15, 2);
            $table->timestamps();
        });

        Schema::create('fixed_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->foreignId('template_id')
                ->nullable()
                ->constrained('fixed_expense_templates')
                ->nullOnDelete();

            $table->string('name'); // snapshot tên tại thời điểm clone
            $table->decimal('amount', 15, 2); // snapshot số tiền tại thời điểm clone

            $table->string('month'); // YYYY-MM
            $table->boolean('is_deducted')->default(false);

            $table->timestamps();

            $table->unique(['user_id', 'template_id', 'month']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_expense_templates');
        Schema::dropIfExists('fixed_expenses');
    }
};
