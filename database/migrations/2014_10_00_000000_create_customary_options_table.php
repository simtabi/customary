<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomaryOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('customary_options')) {
            Schema::create('customary_options', function (Blueprint $table) {
                $table->uuid('id')->primary()->index()->unique();
                $table->uuidMorphs('ownerable');
                $table->string('group')->default('default');
                $table->string('key');
                $table->text('value')->nullable();
                $table->string('locale')->nullable()->index();
                $table->unique(['key', 'locale']);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customary_options');
    }
}
