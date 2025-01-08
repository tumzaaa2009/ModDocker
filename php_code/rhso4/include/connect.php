<?php 
        global $db_r4;

        define("DB_HOST_local","203.157.102.84");
        define("DB_PORT_local","3306");
        define("DB_NAME_local","r4");
        define("DB_USERNAME_local","root");
        define("DB_PASSWORD_local","kethealth4");
        define("DB_ISO_local","utf8");
        
        // define("DB_HOST_local","127.0.0.1");
        // define("DB_PORT_local","3306");
        // define("DB_NAME_local","r4");
        // define("DB_USERNAME_local","root");
        // define("DB_PASSWORD_local","1");
        // define("DB_ISO_local","utf8");
        
        try {
                $db_r4 = new PDO("mysql:host=".DB_HOST_local.";port=".DB_PORT_local.";dbname=".DB_NAME_local.";", DB_USERNAME_local, DB_PASSWORD_local);
                $db_r4->prepare("SET NAMES ".DB_ISO_local."");
                $db_r4->query("SET NAMES ".DB_ISO_local."");
            
        } catch(PDOException $e) {
        echo $e->getMessage();
        }









        
?>