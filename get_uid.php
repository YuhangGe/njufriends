<?php
	@session_start();
    
    require_once 'renren/requires.php';
    $oauth = new RenRenOauth();
    $user = $oauth->getAuthorizeUser();
    $client = new RenRenClient();
    $client->setAccessToken($user['access_token']);
	
	$fid_str = $client->POST("friends.get",array(1,3000));
	
	$fid = json_decode($fid_str);
	
	
?>