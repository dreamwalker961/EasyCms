<?php

require __DIR__ . '/../autoload/autoload.php';

use Framework\App\App;
use Framework\Database\DB;
use Framework\Database\ORM;
use Framework\Database\Table;
use Framework\View\View;


//$view = new View();
//$view->template('rr.test', ['title' => 'AAAAAAAAAA']);
//echo $view->render();



$app = App::getInstance();

try {
    $app->run();
}
catch(Throwable $ex){

    // todo: id debug_mode = on -> show exception else only log
    if($app->debug())
        print $ex;
}