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
        //������ݿ������û�
        /*
         * $user['id'] = get_uid();
         * */
        //����
        
        //������ݿ���û���û���Ϣ
        
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
    <head></head>
    <body>
       <?php
        var_dump($user);
       ?>
    </body>
</html>