<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('birth_date');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('national_id', 20)->unique();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->date('hire_date');

            $table->foreignId('position_id')->constrained('positions')->onDelete('cascade');

            $table->decimal('transport_expense', 10, 2)->default(0.00);
            $table->decimal('food_expense', 10, 2)->default(0.00);
            $table->decimal('accommodation_expense', 10, 2)->default(0.00);
            $table->decimal('other_expenses', 10, 2)->default(0.00);

            $table->enum('status', ['Active', 'Inactive', 'Suspended'])->default('Active');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
