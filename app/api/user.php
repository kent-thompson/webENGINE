<?php
namespace App\api;
// require_once CORE . 'ControllerBase.php';
require_once MODEL . 'user.php';
require_once SERVICE . 'user.php';
require_once DATABASE . 'userEntity.php';
require_once TRAITS . 'Authorize.php';

// class User extends \App\core\ControllerBase {
    class User {
    use \App\traits\Authorize;
    private $model;
    private $userService;
    //private $reqType;


    // public function __construct( $reqInfo ) {
    public function __construct() {
        $this->reqType = $GLOBALS['AppObj']->requestType;
        //$this->reqType = $reqInfo[0];

        // if EXECEPTION is thrown it's caught in index ON PURPOSE :)
        $this->model = new \App\model\User;
        $this->userService = new \App\service\User( $this->reqType );
}


    public function getAllUsers() {
        // parent::AuthApi();
        if( $this->AuthApi() == false ) {
            exit('Failed Authorization');
        }

        $data = [];
        $this->model->getAllUsers( $data );     // $data passed as an OUT param
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }


    public function getUser() {
        //parent::AuthApi();
        $this->AuthApi();

        if( $this->reqType == POST ) {
            $id = $_POST['docid'];
            $data = [];
            $this->model->getUserByID( $id, $data );
            echo json_encode( $data );
        }
    }


    public function addUser() {
        //parent::AuthApi();
        $this->AuthApi();

        if( $this->reqType == POST ) {
            $user = new \database\userEntity;

            $errors = [];
            $rslt = $this->userService->validate( $user, $errors );
            if( $rslt == false ) {
                header("HTTP/1.1 418 Invalid Data");
                echo json_encode( $errors );
                return;
            }
            $ret = $this->model->createUser( $user );
            echo json_encode( $ret );
        }
    }


    public function updateUser() {
        // parent::AuthApi();
        $this->AuthApi();
        
        if( $this->reqType == POST ) {
            $id = $_POST['docid'];

            $ret = $this->model->updateUserByID( $id );
            echo json_encode( $ret );
        }
    }


    public function deleteUserById() {
        //parent::AuthApi();
        $this->AuthApi();

        if( $this->reqType == POST ) {
            $id = $_POST['docid'];

            $rslt = $this->model->deleteUserByID( $id );
            echo json_encode( $rslt );
        }
    }


    public function login() {
        if( ! $this->reqType == POST ) {
            return;
        }

        $errors = [];
        $user = new \database\userEntity;

        $rslt = $this->userService->validateLogin( $user, $errors );
        if( $rslt == false ) {
            header("HTTP/1.1 406 Invalid Login Data");
            echo json_encode( $errors );
            return;
        }

        $data = [];
        $this->model->getUserByName( $user->uname, $data );       // $data passed as an OUT param

        $rslt = password_verify( $user->password, $data['Password'] ); // compare with encrypted password from db, plain text password is NEVER stored
        if( $rslt == false ) {
            header("HTTP/1.1 406 Invalid Data");
            echo 'Login Data Is Incorrect';
            return;
        }

        $payload = [        // JWT
            'iat' => time(),
            'exp' => time() + 3600*4, // + 4 hours
            'role' => 'user',
            'ID' => $data['UserID'],
            'UserName' => $data['UserName']
        ];
        // send back jwt
         $jwt = $this->jwtEncode( $payload );
        header('HTTP/1.1 200 OK');
        header( 'Content-Type: text/html; charset=UTF-8');
           echo $jwt;
    }


//    private function displayProblem( $msg, $file, $line ) {
    //     require_once SERVICE . 'ErrorHandler.php';
    //         \App\service\showError( $msg, $file, $line );
    //         throw new Exception( $msg . ' '. $file_. ' ' . $line_);
    // } //func

} //class
