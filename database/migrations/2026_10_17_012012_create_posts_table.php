<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); // Field 1 (Primary Key)
            $table->string('title'); // Field 2
            $table->text('content'); // Field 3
            $table->string('slug')->unique(); // Field 4
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft'); // Field 5
            $table->integer('views_count')->default(0); // Field 6
            $table->foreignId('user_id'); // Field 7
            $table->timestamp('published_at')->nullable(); // Field 8
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};