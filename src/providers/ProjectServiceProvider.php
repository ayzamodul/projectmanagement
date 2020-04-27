<?php

namespace ayzamodul\projectmanagement\providers;


use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use function foo\func;


Class ProjectServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'Gorev');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $data = [
            'baslik' => 'Projeler',
            'url' => '/yonetim/gorev',
            'aktif_mi' => 1
        ];


        $count = DB::table('moduller')->where('Baslik', 'Projeler')->count();

        if ($count == 0) {
            DB::table('moduller')->insert($data);
        } else {
            return false;
        }


    }

    public function register()
    {

    }


}
