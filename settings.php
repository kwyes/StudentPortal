<?php

const VERSION = '1.0.5';

function getCommonSettings() {

    $version = VERSION;
    return array(
        'version' => VERSION,
        'title' => "Bodwell Student Portal (v{$version})",
        'hostName' => $_SERVER['SERVER_NAME'],
        'hostAddr' => $_SERVER['SERVER_ADDR'],
    );

}

function getEnvironmentSettings() {

    $version = VERSION;
    $hostName = $_SERVER['SERVER_NAME'];
    $scriptFileName = "student-portal-web-{$version}.js";
    $adminScriptName = "student-portal-admin-{$version}.js";
    $returnUrl = "";
    $backdoor = array(
        '45c0x:2|ch//',
    );

    switch($hostName) {
        case 'student.bodwell.edu':
            return array(
                'env' => 'production',
                'debug' => false,
                'basePath' => '/',
                'adminPath' => '/admin/',
                'returnUrl' => $returnUrl,
                'script' => '/assets/'.$scriptFileName,
                'adminScript' => '/assets/'.$adminScriptName,
                'apiPath' => "https://{$hostName}/api/index.php",
                'pdo' => array(
                    
                ),
                'smtp' => array(
                    'debug' => false,
                    'host' => 'smtp.sendgrid.net',
                    'port' => '587',
                    'secure' => 'TLS',
                    'auth' => true,
                    
                ),
                'backdoor' => $backdoor,
            );
        case 'dev.bodwell.edu':
            return array(
                'env' => 'staging',
                'debug' => false,
                'basePath' => '/student.bodwell.edu/',
                'adminPath' => '/admin.bodwell.edu/BHS/SPAdmin/?page=dashboard',
                'returnUrl' => $returnUrl,
                'script' => '/student.bodwell.edu/assets/'.$scriptFileName,
                'adminScript' => '/admin.bodwell.edu/BHS/SPAdmin/assets/'.$adminScriptName,
                'apiPath' => "http://{$hostName}/student.bodwell.edu/api/index.php",
                'adminApiPath' => "http://{$hostName}/admin.bodwell.edu/BHS/SPAdmin/api/index.php",
                'pdo' => array(
                   
                ),
                'bypassAuth' => false,
                'smtp' => array(
                    'debug' => false,
                    'host' => 'smtp.sendgrid.net',
                    'port' => '587',
                    'secure' => 'TLS',
                    'auth' => true,
                    
                ),
                'backdoor' => $backdoor,
            );
        case 'localhost':
        return array(
            'env' => 'production',
            'debug' => true,
            'basePath' => '/student.bodwell.edu/',
            'adminPath' => '/SPadmin/?page=dashboard',
            'returnUrl' => $returnUrl,
            'script' => '/assets/'.$scriptFileName,
            'adminScript' => '/assets/'.$adminScriptName,
            'apiPath' => "http://{$hostName}/student.bodwell.edu/api/index.php",
            'adminApiPath' => "http://{$hostName}/api/index.php",
            'pdo' => array(
                
            ),
            'bypassAuth' => false,
            'smtp' => array(
                'debug' => false,
                
                'port' => '587',
                'secure' => 'TLS',
                'auth' => false,
                'username' => '',
                'password' => '',
            ),
            'backdoor' => $backdoor,
        );
        default:
            return array(
                'env' => 'development',
                'debug' => true,
                'basePath' => '/',
                'adminPath' => '/admin/',
                'returnUrl' => $returnUrl,
                'script' => '/assets/'.$scriptFileName,
                'adminScript' => '/assets/'.$adminScriptName,
                'apiPath' => "http://{$hostName}/api/index.php",
                'pdo' => array(
                    'database' => 'mysql',
                    'dsn' => 'mysql:host=localhost;dbname=bodwell',
                    'user' => 'root',
                    'pass' => 'root',
                ),
                'testing' => array(
                    'staffId' => 'F0123',
                    'staffRole' => '99',
                    'studentId' => '201500126',
                    
                    'password' => 'c4e7e3792c',
                ),
                'bypassAuth' => true,
                'smtp' => array(
                    'debug' => 0,
                    
                    'port' => '25',
                    'secure' => '',
                    'auth' => false,
                    'username' => '',
                    'password' => '',
                ),
            );
    }

}

$settings = array_merge(getCommonSettings(), getEnvironmentSettings());
