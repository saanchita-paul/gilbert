<?php

use App\Models\GoodtelPlan;
use Database\Seeders\GoodtelPlanSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodtelPlanPaymentLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goodtel_plan_payment_links', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(GoodtelPlan::class)
                ->constrained()->cascadeOnDelete();
            $table->string('modem_type');
            $table->string('payment_link');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => GoodtelPlanSeeder::class,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goodtel_plan_payment_links');
    }
}
