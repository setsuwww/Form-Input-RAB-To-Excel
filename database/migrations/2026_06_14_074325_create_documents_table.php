<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("documents", function (Blueprint $table) {
            $table->id();

            // Customer Info
            $table->string("customer_name")->nullable();
            $table->string("company_name")->nullable();
            $table->text("address")->nullable();
            $table->text("muat_address")->nullable();
            $table->string("muat_city")->nullable();
            $table->string("muat_province")->nullable();
            $table->string("muat_pic")->nullable();
            $table->string("muat_phone")->nullable();
            $table->text("bongkar_address")->nullable();
            $table->string("bongkar_city")->nullable();
            $table->string("bongkar_province")->nullable();
            $table->string("bongkar_pic")->nullable();
            $table->string("bongkar_phone")->nullable();

            // Cargo
            $table->string("cargo_name")->nullable();
            $table->decimal("length", 10, 2)->default(0);
            $table->decimal("width", 10, 2)->default(0);
            $table->decimal("height", 10, 2)->default(0);
            $table->decimal("cubication", 10, 2)->default(0);
            $table->decimal("unit_weight", 10, 2)->default(0);
            $table->integer("quantity")->default(0);
            $table->decimal("total_weight", 10, 2)->default(0);
            $table->decimal("total_cubication", 10, 2)->default(0);

            // Vehicle
            $table->string("vehicle_brand")->nullable();
            $table->string("vehicle_plate")->nullable();
            $table->string("vehicle_type")->nullable();

            // Perjalanan (Journey)
            $table->decimal("distance_garage_to_muat", 10, 2)->default(0);

            // Muatan
            $table->decimal("distance_muatan", 10, 2)->default(0);
            $table->decimal("speed_muatan", 10, 2)->default(0);
            $table->decimal("work_hours_muatan", 10, 2)->default(0);
            $table->decimal("travel_days_muatan", 10, 2)->default(0);
            $table->integer("muat_days")->default(0);
            $table->integer("bongkar_days")->default(0);
            $table->decimal("total_days_muatan", 10, 2)->default(0);

            // Kosongan
            $table->decimal("distance_kosongan", 10, 2)->default(0);
            $table->decimal("speed_kosongan", 10, 2)->default(0);
            $table->decimal("work_hours_kosongan", 10, 2)->default(0);
            $table->decimal("travel_days_kosongan", 10, 2)->default(0);

            // BBM
            $table->decimal("bbm_distance_muatan", 10, 2)->default(0);
            $table->decimal("bbm_ratio_muatan", 10, 2)->default(0);
            $table->decimal("bbm_usage_muatan", 10, 2)->default(0);
            $table->decimal("bbm_distance_kosongan", 10, 2)->default(0);
            $table->decimal("bbm_ratio_kosongan", 10, 2)->default(0);
            $table->decimal("bbm_usage_kosongan", 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("documents");
    }
};
