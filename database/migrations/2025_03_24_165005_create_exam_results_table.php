<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->integer('score');
            $table->enum('status', ['passed', 'failed']) ->default('failed');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'exam_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_results');
    }
};