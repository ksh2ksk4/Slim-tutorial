<?php
namespace My;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquenttt\Model;

class Db
{
    public $manager;

    function __construct() {
        $this->manager = new Manager;
        $this->manager->addConnection([
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'slim_tutorial',
            'username' => 'phpapp',
            'password' => '8G4mr*Z-7ap.Rm@Uz-e@',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => ''
        ]);
        $this->manager->setAsGlobal();
        $this->manager->bootEloquent();
    }
}
