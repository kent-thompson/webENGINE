<?php
namespace App\service;

//This file represents an "entry point" to gracefully handle and report errors and exeptions.
// Could go on to LOG errors, display various page types 404, 500 et cetra, and make them end-user 'pretty.'
// What ever the Problem Domains dictates.

//function showError( $msg, $file, $line ) {  Error
    function showError( \Error $err ) {
    // $GLOBALS['IsHalted'] = true;
    $GLOBALS['error_data'] = $err->getMessage();
    $GLOBALS['error_file'] = $err->getFile();
    $GLOBALS['error_line'] =  $err->getLine();

    header('HTTP/1.1 406 Application Error');
    echo 'Message: ' .  $err->getMessage() . ' File: ' . $err->getFile() . ' Line: ' . $err->getLine();
//     echo "Message: $msg \n File: $file \n Line: $line";
    //require_once VIEWS . '404.php';
    // exit();
}

function showErrorOG( $errno, $errstr, $errfile, $errline ) {
    header('HTTP/1.1 406 Application Error');
    echo "Message: $errstr \n File: $errfile \n Line: $errline";
}

function showErrorEx( \Exception $err ) {
//    function showErrorEx( $ex ) {
    // $GLOBALS['IsHalted'] = true;
    // $GLOBALS['error_data'] = $err->getMessage();
    // $GLOBALS['error_file'] = $err->getFile();
    // $GLOBALS['error_line'] = $err->getLine();

    //header("HTTP/1.1 406 Application Error");
    echo 'Message: ' .  $err->getMessage() . ' File: ' . $err->getFile() . ' Line: ' . $err->getLine();
    // exit();
}

function showErrorStr( $estr ) {
        // $GLOBALS['IsHalted'] = true;
        // $GLOBALS['error_data'] = $err->message;
        // $GLOBALS['error_file'] = $err-file;
        // $GLOBALS['error_line'] =  $err->line;
    
        header("HTTP/1.1 406 Application Error");
        echo $etr;
    }
    