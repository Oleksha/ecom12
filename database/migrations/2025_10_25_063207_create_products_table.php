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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->integer('brand_id');
            $table->integer('admin_id');
            $table->string('admin_type');

            $table->string('product_name');
            $table->string('product_code');
            $table->string('product_color');
            $table->string('family_color');
            $table->string('group_code');
            $table->float('product_price');
            $table->float('product_discount');
            $table->float('product_discount_amount');

            $table->string('product_applied_on');
            $table->float('product_gst');

            $table->float('final_price');
            $table->string('main_image');

            $table->float('product_weight');
            $table->string('product_video');
            $table->text('description');
            $table->text('wash_care');
            $table->text('search_keywords');
            $table->string('fabric');
            $table->string('pattern');
            $table->string('sleeve');
            $table->string('fit');
            $table->string('occasion');
            $table->integer('stock');
            $table->integer('sort');

            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('meta_keywords');
            $table->enum('is_featured', ['No', 'Yes']);
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
