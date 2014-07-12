<?php
/**
 * Created by PhpStorm.
 * User: pavel.zagoskin
 * Date: 6/24/14
 * Time: 5:01 PM
 */




class DynamicRouter {


    private $data;
    private $app;

    public function __construct($data, $app) {
        $this->data = $data;
        $this->app = $app;


        $this->createApiRoutes();
        $this->createRoutes();

    }

    private function createApiRoutes() {
        $appPaths = $this->data->config->paths;
        $app = $this->app;

        $app->get("/api/routes" , function () use ($app, $appPaths) {
            header("Content-type:application/json");
            echo json_encode($appPaths);
        });
    }

    private function createRoutes() {

        $appPaths = $this->data->config->paths;
        $app = $this->app;
        foreach($appPaths as $path) {
            $route = $path->path;
            $view = $path->view;
            $data = $path->data;

            $app->get($route, function () use ($app, $view, $data, $appPaths) {
                $request = $app->request;
                $isAjax = $request->isAjax();
                $data = DynamicRouter::getData($data, $appPaths);
                if(!$isAjax) {
                    $app->render($view, $data);
                }else{
                    header("Content-type:application/json");
                    echo json_encode($data);
                }
            });

        }
    }

    private static function getData($data, $appPaths, $params = array()) {

        //To Do
        //If Needed, parse params into supplanted fields of call

        $response = array(
            "status"=>"success",
            "routes"=>$appPaths,
            "content"=>$data
        );

        $url = filter_var($data, FILTER_VALIDATE_URL);
        if(isset($url) && $url !== false) {
            if($data) {
                try {
                    $ch = curl_init($data);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        "Content-Type: application/json"
                    ));
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //Turn this on once the server is live
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,15); //This can change.

                    $result = curl_exec($ch);
                    $result = json_decode($result);

                    $response['status'] = "success";
                    $response['content'] = $result;
                } catch (Exception $e) {
                    $response['status'] = "error";
                    $response['content'] = $e;
                }
            }
        }



        return $response;





    }
} 