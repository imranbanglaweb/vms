<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            // Change menu_oder column to have a default value
            $table->integer('menu_oder')->default(0)->change();
        });

        // Update existing records to have sequential order
        $menus = DB::table('menus')->orderBy('id')->get();
        $order = 1;
        foreach ($menus as $menu) {
            DB::table('menus')
                ->where('id', $menu->id)
                ->update(['menu_oder' => $order]);
            $order++;
        }
    }

    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->integer('menu_oder')->change();
        });
    }
}; 