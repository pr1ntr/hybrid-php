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
    private $handlebars;

    public function __construct($data, $app, $handlebars) {
        $this->data = $data;
        $this->app = $app;
        $this->handlebars = $handlebars;
    }
} 