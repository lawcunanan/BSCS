<?php 
require "function.php";
$id = (isset($_GET['id'])) ? $_GET['id'] : 0;

$data = [
    'index_page' => [
            'check' => [
                'query' => "SELECT us_id, us_type, us_email, us_password, us_verified  FROM `users` 
                            WHERE us_email = ? AND us_status = 'Active'
                            AND us_type IN ('Teacher', 'Principal', 'Secretary', 'Admin', 'Registrar')",
                'bind'  => "s",
                'value' => []
            ],
    ],



    'verify_page' => [
            'check' => [
                'query' => "SELECT  1 FROM `users` WHERE us_email = ?
                            AND us_type IN ('Teacher', 'Principal', 'Secretary', 'Admin', 'Registrar')",
                'bind'  => "s",
                'value' => []
            ],
            
            
            'email' => [
                'query' => "UPDATE `users` SET `us_email`= ? WHERE us_email = ?",
                'bind'  => "ss",
                'value' => []
            ],


            'verify' => [
                'query' => "UPDATE `users` SET `us_verified`= ? WHERE us_email = ?",
                'bind'  => "is",
                'value' => []
            ],
    ],



    'forgot_page' => [
            'check' => [
                'query' => "SELECT 1  FROM `users` 
                            WHERE us_email = ?
                            AND us_type IN ('Teacher', 'Principal', 'Secretary', 'Admin', 'Registrar')",
                'bind'  => "s",
                'value' => []
            ],


            'forget' => [
                'query' => "UPDATE `users` SET `us_password`= ? WHERE us_email = ?",
                'bind'  => "ss",
                'value' => []
            ],
    ],
];




?>