<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tour_packages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('country')->nullable();
            $table->string('tour_type')->default('domestic');
            $table->string('visa_type')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->string('currency', 10)->default('BDT');
            $table->integer('duration_days')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('max_travelers')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        DB::table('tour_packages')->insert([
            ['title'=>"Cox's Bazar Beach Holiday",'description'=>"World's longest natural sea beach — full package with hotel, meals & transport.",'country'=>'Bangladesh','tour_type'=>'domestic','visa_type'=>null,'price'=>12500,'currency'=>'BDT','duration_days'=>3,'max_travelers'=>20,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Sundarbans Mangrove Tour','description'=>'Explore the largest mangrove forest in the world. Spot the Royal Bengal Tiger.','country'=>'Bangladesh','tour_type'=>'domestic','visa_type'=>null,'price'=>18000,'currency'=>'BDT','duration_days'=>4,'max_travelers'=>15,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Sajek Valley Hill Station','description'=>'Queen of Hills — stunning sunrise, tribal culture, and cool mountain air.','country'=>'Bangladesh','tour_type'=>'domestic','visa_type'=>null,'price'=>9800,'currency'=>'BDT','duration_days'=>2,'max_travelers'=>25,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Thailand Bangkok & Pattaya','description'=>'Vibrant Bangkok city and beautiful Pattaya beaches in one amazing package.','country'=>'Thailand','tour_type'=>'international','visa_type'=>'tourist','price'=>55000,'currency'=>'BDT','duration_days'=>6,'max_travelers'=>20,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Malaysia Kuala Lumpur Explorer','description'=>'Petronas Twin Towers, Batu Caves, and authentic Malaysian culture & cuisine.','country'=>'Malaysia','tour_type'=>'international','visa_type'=>'tourist','price'=>48000,'currency'=>'BDT','duration_days'=>5,'max_travelers'=>20,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Umrah Package (Makkah & Madinah)','description'=>'Complete Umrah package — airfare, hotel near Haram, meals, and ziyarah tours.','country'=>'Saudi Arabia','tour_type'=>'international','visa_type'=>'pilgrimage','price'=>120000,'currency'=>'BDT','duration_days'=>14,'max_travelers'=>40,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Singapore City Tour','description'=>'Gardens by the Bay, Sentosa Island, Universal Studios and vibrant city life.','country'=>'Singapore','tour_type'=>'international','visa_type'=>'tourist','price'=>75000,'currency'=>'BDT','duration_days'=>5,'max_travelers'=>15,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Nepal Kathmandu & Pokhara','description'=>'Boudhanath Stupa, Phewa Lake, Annapurna panorama and Himalayan trekking.','country'=>'Nepal','tour_type'=>'international','visa_type'=>'tourist','price'=>35000,'currency'=>'BDT','duration_days'=>5,'max_travelers'=>20,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_packages');
    }
};
