<?php
namespace App\core;
use Exception;

$gAction;                           // set exactly ONCE per request. forced to be global by function call limitation

class Application {
    public $requestType;    
    protected $controller;          // current controller object
    protected $controllerPath;      // file path to file
    // protected $action;           // = 'index';
    //protected $params = [];

    public function __construct() {

    }

    public function run() {
        $GLOBALS['AppObj'] = $this;
        $result = true;
        try {
            $this->setReqMethod();
            $this->parseURL();
            $this->invoke();

        } catch( \ErrorException $er ) {
            $result = false;
            $this->throwProblem( 'ErrorEx: ' . $er->getMessage(), $er->getFile(), $er->getLine() ); 
        } catch( \Exception $e ) {
            $result = false;
            $this->throwProblem( 'Exception: ' . $e->getMessage(), $e->getFile(), $e->getLine() ); 
        } catch( \Error $err ) {
            $result = false;
            $this->throwProblem( 'Error: ' . $err->getMessage(), '', '' ); 
        }
        return $result;
    }


    protected function setReqMethod() {
        // can add any number of other REST Request Method types like PUT, DELETE etc.
        $val = strtoupper( $_SERVER['REQUEST_METHOD'] );
        switch( $val ) {
            case 'GET':
                $this->requestType = GET;
                break;
            case 'POST':
                $this->requestType = POST;
                break;
        }
    }


    protected function parseURL() {
        global $gAction;
        // controller and action paths and names are set

        $request = trim( $_SERVER['REQUEST_URI'], '/' );
        if( empty($request) ) {
            // by convention, if no querystring; then default home controller and index action are set
            $this->controllerPath = CONTROLLER . 'home.php';
            $this->controller = 'App\\controller\\home';
            $gAction = 'index';
            return;
        }

       $url = parse_url( $request );
       $urlPath = explode( '/', $url['path'] );
        // by convention, if only one element, it's a 'default' home page action - see views/sidebar
        if( count($urlPath) == 1 ) {
            $this->controllerPath = CONTROLLER . 'home.php';
            $this->controller = 'App\\controller\\home';
            $gAction = $urlPath[0];
            return;
        }

        // based upon index position, we know api, non-api / controller / action-page / params
        if( count($urlPath) > 1 ) {
            if( $urlPath[0] == 'api' ) {
                $this->controllerPath = API . $urlPath[1] . '.php'; // now current controller PATH
                $this->controller = "App\\api\\" . $urlPath[1];     // now current controller CLASS
                // $this->action = $urlPath[2];                     // method / function
                $gAction = $urlPath[2];
            } else {
                $this->controllerPath = CONTROLLER . $urlPath[0] . '.php';  // now current controller
                $this->controller = "App\\controller\\" . $urlPath[0];      // now current controller CLASS
                // $this->action = $urlPath[1];                             // method / function
                $gAction = $urlPath[1];
            }
        }
    }


    // Exceptions / Errors are now caught by global handlers in index.php
    protected function invoke() {
        global $gAction;

        // auto class loader from file path, controller class gets instantiated and action / function invoked
        if( file_exists($this->controllerPath) ) {
            require_once $this->controllerPath;
            $this->controller = new $this->controller();
            $this->controller->reqType = $this->requestType;

        } else {
            $this->throwProblem( 'Error: Controller ' . $this->controller . ' Missing', __FILE__, __LINE__ );
            return false;
        }
    
        if( method_exists($this->controller, $gAction) ) {
                // invoke an instance method. 
                // below 'old' code saved for "documentation" shwoing using params
                // call_user_func_array( [$this->controller, $this->action], $this->params );
                // $gAction = $this->action; ugh... not on every request
                    //$this->controller->$gAction( $this->params );    // The MAGIC - extreamly fast

                    $this->controller->$gAction();    // The MAGIC - extreamly fast
            } else { // error
                $classObj = new \ReflectionClass( $this->controller );
                $tmsg = $classObj->getName() . '\\' . $gAction . ' Function Missing File: ' .  __FILE__. ' Line: ' . __LINE__;
                throw new \ErrorException( $tmsg );
                return false;
            }
    } //invoke


    public function throwProblem( $msg, $file, $line ) {
        $tmsg = $msg . '<br>' . 'FILE: ' . $file . '<br>' . 'LINE: ' . $line . '<br>' . 'Press BACK Button' ; 
        throw new \ErrorException(  $tmsg );
    }
}
