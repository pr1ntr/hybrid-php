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
    private static $hostname;

    /**
     * @param $data
     * @param $app
     * Class Constructor. Initializs the DynamicRouter
     */
    public function __construct($data, $app) {
        $this->data = $data;
        $this->app = $app;
        $this->debug = $app->config('debug');
        $this->tree = $this->data->config->paths;
        DynamicRouter::$hostname = $data->hostname;

        $this->createApiRoutes();
        $this->createRoutes();

    }


    /**
     * @param $paths
     * @param $data
     * @return mixed
     * Recursive function for creating specialized router data for data api.

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
    /**
    * Creates data api route for front-end application
    */
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

    /**
     * Initializes routes with the path tree
     * Sets /+ wildcard url redirect anything not defined to the first route (hard coded for now)
     */

    private function createRoutes() {
        $this->setRoutes($this->tree, $this->app);

        $app = $this->app;

        $app->get("/+" , function () use ($app) {
            $app->redirect('/home');
        });

    }

    /**
     * @param $appPaths
     * @param $app
     * @param $parent
     * Recursive function to iterating through path tree to create main application routes
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
                    $data['content']['id'] = $id;
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

    /**
     * @param $data
     * @param array $params
     * @return array
     * Uses curl to request data. Data is converted to Association Arrays.
     */
    private static function getData($url, $params = array()) {

        //To Do
        //If Needed, parse params into supplanted fields of call

        $response = array(
            "status"=>"success",
            "content"=>array()
        );

        if(!filter_var($url, FILTER_VALIDATE_URL)){
            $url = "http://".DynamicRouter::$hostname.$url;
        }

        if($url) {
            try {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Content-Type: application/json"
                ));
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //Turn this on once the server is live
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,15); //This can change.

                $result = curl_exec($ch);
                $result = json_decode($result , true);

                $response['status'] = "success";
                $response['content'] = $result;
            } catch (Exception $e) {
                $response['status'] = "error";
                $response['content'] = $e;
            }
        }



        return $response;





    }



} 