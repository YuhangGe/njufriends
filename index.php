<?php
    @session_start();
    
    require_once 'renren/requires.php';
    $oauth = new RenRenOauth();
    $user = $oauth->getAuthorizeUser();
    $client = new RenRenClient();
    $client->setAccessToken($user['access_token']);
    
    getUser();
    
    function getUser(){
        global $client,$user;
        //如果数据库中有用户
        /*
         * $user['id'] = get_uid();
         * */
        //返回
        
        //如果数据库中没有用户信息
        
         $r_u = $client->POST('users.getInfo',array($user['rrid'],'name,tinyurl'));
         var_dump($r_u );
         if($r_u[0]){
            $user['name']=$r_u[0]['name'];
            
            //$user['id'] = insert_user();
         }
    }
?>
<!doctype html>
<html>
    <head>
    	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    </head>
    <body>
       <?php
        var_dump($user);
       ?>
       阿斯顿飞
    </body>
</html>