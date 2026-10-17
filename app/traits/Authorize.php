<?php
namespace App\traits;
require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

trait Authorize 
{
    public $mIsAuth = false;
    public $mPayload;
    // JWT - put in config file or ENV TODO
    public $secretKey = 'e6311e81b59543c8aae070c54a28b801'; // TODO
    public $requestType;
   // public $IsHalted = false;

    // public function setReqType( $rType ) {
    //     $this->reqType = $rType;
    // }

    public function jwtEncode( &$payload ) {
        return JWT::encode( $payload, $this->secretKey, 'HS256' );
    }

    public function AuthApi() {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if( !empty($headers) ) {
            if( preg_match('/Bearer\s(\S+)/', $headers, $matches) ) {
                try {
                    $this->mPayload = JWT::decode($matches[1], new Key($this->secretKey, 'HS256'));
                    //$this->mPayload = JWT::decode($matches[1], new Key(7, 'HS256'));

                } catch( LogicException $e ) {
                    // errors having to do with environmental setup or malformed JWT Keys
                    require_once SERVICE . 'ErrorHandler.php';
                    \App\service\showErrorEx( $e );
                    return false;
                } catch( UnexpectedValueException $e ) {
                    // errors having to do with JWT signature and claims
                    require_once SERVICE . 'ErrorHandler.php';
                    \App\service\showErrorEx( $e );
                    return false;
                } catch( \Exception $e ) {
                    require_once SERVICE . 'ErrorHandler.php';
                    \App\service\showErrorEx( $e );
                    return false;
                 } catch( \Error $er ) {
                    require_once SERVICE . 'ErrorHandler.php';
                    \App\service\showError( $er );
                    return false;
                }
         }
            $this->mIsAuth = true;
            return true;
        }
        require_once SERVICE . 'ErrorHandler.php';
        \App\service\showError('NOT AUTHORIZED - Please Login ', __FILE__, __LINE__ );
    }


    private function getAuthorizationHeader() {
        $headers = null;
        if (isset($_SERVER['AUTHORIZATION'])) {
            $headers = trim($_SERVER["AUTHORIZATION"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    public function AuthUI() {
        switch( $GLOBALS['AppObj']->requestType ) {
            case GET:
                $token = $_GET['jwt'];
                break;
            case POST:
                $token = $_POST['jwt'];
                break;
        }

        if (isset($token) && $token !== '') {
            try {
                $this->mPayload = JWT::decode($token, new Key($this->secretKey, 'HS256'));

                // TODO: more work
                } catch( LogicException $e ) {
                    // errors having to do with environmental setup or malformed JWT Keys
                    require_once SERVICE . 'ErrorHandler.php';
                    \App\service\showErrorEx( 'NOT AUTHORIZED: ' . $e->getMessage(), __FILE__, __LINE__ );
                    return;
                } catch( UnexpectedValueException $e ) {
                    // errors having to do with JWT signature and claims
                    require_once SERVICE . 'ErrorHandler.php';
                    \App\service\showErrorEx( 'NOT AUTHORIZED: ' . $e->getMessage(), __FILE__, __LINE__ );
                    return;
                } catch( \Exception $e ) {
                    require_once SERVICE . 'ErrorHandler.php';
                    \App\service\showErrorEx( 'Exception: ' . $e->getMessage(), __FILE__, __LINE__ );
                    return;
                } catch( \Error $er ) {
                    require_once SERVICE . 'ErrorHandler.php';
                    \App\service\showError( 'Error: ' . $er->getMessage(), __FILE__, __LINE__ );
                    return;
                }
            $this->mIsAuth = true;
        } else {
            require_once SERVICE . 'ErrorHandler.php';
            \App\service\showError('NOT AUTHORIZED - Please Login ', __FILE__, __LINE__ );
        }
    }

    public function getIsAuth() {
        if( $this->mIsAuth ) {
            return true;
        } else {
            return false;
        }
    }

    public function setPayload( &$data) {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: GET, POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

        $payload = [
            'iat' => time(),
            'exp' => time() + 3600*4, // + 4 hours TODO: get time from single source
            'role' => 'user'
        ];
        $this->mPayload = array_merge($payload, $data);
    }
}
