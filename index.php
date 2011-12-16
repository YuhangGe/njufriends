<?php
@session_start();
require_once 'renren/requires.php';

if (!empty($_GET['token'])) {
	if( init_user($_GET['token'])){
		header("Location: home.php");
		//echo "ok!";
	}else{
		header("Location: www.renren.com");
	}
	exit();
}

function init_user($token){
	$client = new RenRenClient();
	$client -> setAccessToken($_GET['token']);

	$rrid = $client->POST('users.getLoggedInUser',null);
	
	if(empty($rrid['uid']))
		return false;
	
	require_once "db/db_function.php";

	//如果数据库中有用户
	$ur = get_UserByRRid($rrid['uid']);
	if ($ur == null) {
		$r_u = $client -> POST('users.getInfo', array($rrid['uid'], 'name,tinyurl'));
		if (empty($r_u[0])) {
			return false;
		}
		$user['name'] = $r_u[0]['name'];

		$ur = insert_user($rrid['uid'], $r_u[0]['name'], $r_u[0]['tinyurl']);
		
	}
	
	$fid = $client->POST('friends.get',array(1,10));
	
	if(!is_array($fid))
		return false;
	
	$u_list = get_UidList($fid);
	
	//var_dump($u_list);
	
	$_SESSION['uid'] = $ur['uid'];
	$_SESSION['uname'] = $ur['uname'];
	$_SESSION['rrid'] = $ur['rrid'];
	$_SESSION['access_token'] = $token;
	$_SESSION['u_list'] = join(",",$u_list);
	
	return true;
}
?>
<!doctype html>
<html>
	<head>
		<title>Login</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
		<script type="text/javascript" src="renren/renren-yc.js"></script>
		<script type="text/javascript">
		  window.onload = function (){
		
				Renren.ui({
					url : 'https://graph.renren.com/oauth/authorize',
					display : 'iframe',
					top : 100,
					params : {
						client_id : '<?php echo $config -> APIKey;?>',
						response_type : 'token',
						redirect_uri : 'http://www.njufriends.com:8080/nju/login_callback.html',
						scope :'send_message status_update'
					}
				});
		 

	}
	function pushToken(token) {
		console.log(token);
		window.location.href = "index.php?token=" + token;
	}

	function error() {
		window.location.reload();
	}
		</script>
	</head>
	<body>
	</body>
</html>