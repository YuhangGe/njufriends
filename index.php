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
    
         if($r_u[0]){
            $user['name']=$r_u[0]['name'];
            
            //$user['id'] = insert_user();
         }
    }
?>
<!doctype html>
<html>
    <head>
        <title>Hello</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <script type="text/javascript" src="renren/renren-yc.js"></script>
        <script type="text/javascript">
            dialog = null;
            function test_ui(){
                dialog = Renren.ui({
                    url : 'feed',
                    display : 'iframe',          
                   
                    params : {
                        app_id : 172440,
                        url:'http://www.njufriends.com:8080/nju/index.php?aid=888',
                        name:'发起了活动-自习',
                        description:'测试dialog',
                        image:'http://at-img4.tdimg.com/board/2011/5/46465.jpg',
                        redirect_uri : 'http://www.njufriends.com:8080/nju/callback.html',
                        access_token: '<?php echo $user['access_token'];?>',
                        action_name : '去呼朋唤友逛逛',
                        action_link : 'http://apps.renren.com/njufriends/ '
                    }
                });
      
            }
            
            function closeDialog(){
                dialog.closeUI();
            }
        </script>
    </head>
    <body>
       <div><?php echo $user['name'];?></div>
       <div><input type="button" onclick="test_ui();" value="Test" /></div>
       
    </body>
</html>