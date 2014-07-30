
<?php 


require_once '../vendor/autoload.php';
require_once '../com/util/logging/Logger.php';
require_once '../com/DynamicRouter.php';



$appDataFile = getenv("APP_DATA");
if(isset($appDataFile)) {

    $appData = file_get_contents("../$appDataFile");
    $appData = json_decode($appData);
    $debug = (getenv("SLIM_MODE") === "development") ? true : false;
    $app = new \Slim\Slim();
    $app->setName($appData->name);
    $templates = __DIR__."/".$appData->config->templates->folder;
    $app->config(array(
        "debug"=>$debug,
        "templates.path" =>realpath ($templates),
        'view' => new \Slim\Views\Twig()
    ));



    $view = $app->view();

    $view->parserOptions = array(
        'debug' => $debug,
        'auto_reload'=>$debug,
    );
    $view->parserExtensions = array(
        new Twig_Extension_Debug()
    );






    $log = $app->getLog();
    $log->setWriter(new Logger());
    $log->setEnabled(true);


    $router = new DynamicRouter($appData, $app);


    $app->run();

}else{
    echo "no app data";
}


