
<?php 


require '../vendor/autoload.php';
require '../vendor/util/logging/Logger.php';



$app = new \Slim\Slim();

use Handlebars\Handlebars;
use Handlebars\Loader\FilesystemLoader;

$templates = __DIR__."/templates";
$templateLoader = new FilesystemLoader($templates, [
   "extension" => "hbs"
]);

$handlebars = new Handlebars([
    "loader"=>$templateLoader,
    "partials_loader"=>$templateLoader
]);


$app->config(array(
    "debug"=>true
));

$app->setName("Hybrid PHP");


$log = $app->getLog();
$log->setWriter(new Logger());
$log->setEnabled(true);





$app->get('/' , function () use ($handlebars) {

});

$app->run();
