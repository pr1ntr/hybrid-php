<?php
/**
 * Created by PhpStorm.
 * User: pavel.zagoskin
 * Date: 6/24/14
 * Time: 5:01 PM
 */




class DynamicRouter {


    private $tree;
    private $data;
    private $app;
    private $debug = true;

    public function __construct($data, $app) {
        $this->data = $data;
        $this->app = $app;
        $this->debug = $app->config('debug');
        $this->tree = $this->data->config->paths;


        $this->createApiRoutes();
        $this->createRoutes();

    }


    /**
     * @param $paths
     * @param $data
     * @return mixed

     */
    private function createRouteObject($paths, $data)
    {
        foreach ($paths as $i => $path) {
            $data[$i]['id'] = $path->id;
            $data[$i]['path'] = $path->path;
            if(isset($path->view))
                $data[$i]['view'] = "/".$this->data->config->templates->folder . $path->view;

            if(isset($path->paths)) {
                $data[$i]['paths'] = $this->createRouteObject($path->paths, array());
            }
        }
        return $data;
    }

    private function createApiRoutes() {
        $data = array();


        $data['cdn'] = $this->debug ?  $this->data->config->cdn->dev->cdn_root : $this->data->config->cdn->prod->cdn_root;
        $data['debug'] = $this->debug;


        $data['routes'] = $this->createRouteObject($this->tree, array());

        $app = $this->app;

        $app->get("/api/data" , function () use ($app, $data) {
            header("Content-type:application/json");
            echo json_encode($data);
        });
    }

    private function createRoutes() {
        $this->setRoutes($this->tree, $this->app);
    }

    /**
     * @param $appPaths
     * @param $app
     * @param $parent
     */
    private function setRoutes($appPaths, $app, $parent = array())
    {
        $tree = $this->tree;
        foreach ($appPaths as $path) {
            $route = $path->path;

            foreach($parent as  $k=>$v) {
                if($k === "path") {
                    $route = $v . $route;
                }
            }


            if(isset($path->paths))
                $this->setRoutes($path->paths, $app, $path);

            if(isset($path->view) && isset($path->data)) {
                $view = $path->view;
                $data = $path->data;
                $id = $path->id;

                $app->get($route, function () use ($app, $view, $data, $tree, $id, $path) {
                    $request = $app->request;
                    $isAjax = $request->isAjax();
                    $data = DynamicRouter::getData($data);
                    $data['content']->id = $id;
                    $data['name'] = $path->name;
                    if (!$isAjax) {
                        $data = array_merge($data, array("routes" => $tree));
                        $app->render($view, $data);
                    } else {
                        header("Content-type:application/json");
                        $data['ajax'] = $isAjax;
                        echo json_encode($data);
                    }
                });
            }

        }
    }

    private static function getData($data, $params = array()) {

        //To Do
        //If Needed, parse params into supplanted fields of call

        $response = array(
            "status"=>"success",
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