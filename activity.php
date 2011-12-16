<?php 
	session_start();
	
	if(empty($_SESSION['uid'])){
		header("index.php?redir=".urlencode(request_uri()));
	}else{
		$user_id = $_SESSION['uid'];
	}
	
	require("db/db_function.php");
	require("renren/config.inc.php");
	
	$aid = $_REQUEST['aid'];
	$uidListStr="1,2";
	//$uidListStr = $_SESSION['u_list'];
	$uidList = explode(",",$uidListStr);
	$activity = get_Activity($aid,null);
	$joinUsers=null;
	$joinFriends=null;
	$careFriends=null;
	if($activity==null)
	{
		header("Location: home.php");
	}
	$leader = get_UserByUid($activity['leader_id']);
	if($uidList==null)
	{
		$joinUsers = get_JoinUsers($aid,null,7);
	}
	else
	{
		//var_dump($uidList);
		$joinFriends = get_JoinUsers($aid,$uidList,10);
		//var_dump($joinFriends);
		$careFriends = get_CareUsers($aid,$uidList,10);
		//var_dump($careFriends);
		if($joinFriends==null || $careFriends==null)
			$joinUsers = get_JoinUsers($aid,null,7);
	}
	$comments = get_CommentList($aid);
	
	$is_owner = true;// $activity['uid']== $_SESSION['uid'];
	
	
	function request_uri()
{
    if (isset($_SERVER['REQUEST_URI']))
    {
        $uri = $_SERVER['REQUEST_URI']; 
    }
    else
    {
        if (isset($_SERVER['argv']))
        {
            $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
        }
        else
        {
            $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
        }
    }
    return $uri;
}
?>
<!doctype html>
<html>
    <head>
        <title>Hello</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <link style="text/css" rel="stylesheet" href="css/style2.css" />
        <script type="text/javascript" src="renren/renren-yc.js"></script>
        <script type="text/javascript" src="js/jquery-1.6.4.min.js"></script>
        <script type="text/javascript">
        	function invite(){
        		Renren.ui({
					url : 'request',
					display : 'iframe',
					top : 30,
					params : {
						app_id : '<?php echo $config -> APPID;?>',
						accept_label : '参加 <?php echo $activity['name'];?>',
						accept_url : 'http://apps.renren.com/njufriends/activity.php?aid=<?php echo $activity['aid'];?>',
						actiontext : '呼唤朋友参加活动 <?php echo $activity['name'];?>',
						max_friends : 10,
						app_msg : '快来参加我发起的活动 <?php echo $activity['name'];?>',
						redirect_uri : 'http://www.njufriends.com:8080/nju/invite_callback.html',
						access_token : '<?php echo $_SESSION['access_token'];?>'
					}
				});
        	}
        	function feed(){
        		Renren.ui({
					url : 'feed',
					display : 'iframe',
					top : 30,
					params : {
						app_id : '<?php echo $config -> APPID;?>',
						url : 'http://apps.renren.com/njufriends/activity.php?aid=<?php echo $activity['aid'];?>',
						name : '快来参加 <?php echo $activity['name'];?>',
						description : '。。。',
						image : '<?php echo $activity['url'];?>',
						action_name  : '加入活动',
						action_link : 'http://apps.renren.com/njufriends/activity.php?aid=<?php echo $activity['aid'];?>',
						redirect_uri : 'http://www.njufriends.com:8080/nju/feed_callback.html',
						access_token : '<?php echo $_SESSION['access_token'];?>'
					}
				});
        	}
        	<?php if(isset($_REQUEST['invite']) && $is_owner):?>
        	$(function(){
        		feed();
        	});
        	<?php endif;?>
        </script>
    </head>
    <body>

        <div id="logo">
            呼朋唤友
        </div>
        <div id="main">
            <h2>
                <?php echo $activity['name'];?>
            </h2>
            <div class="general">
            	<div class="pic">
            		<img src="<?php echo $activity['url'];?>" />
            	</div>
                <div class="info">
                  	<p>开始时间: <?php echo getDateTime($activity['start_time']);?></p>
                    <p>结束时间: <?php echo getDateTime($activity['end_time']);?></p>
                    <p>地点: <?php echo $activity['location'];?></p>
                    <p>发起人：<a target="_blank" href="http://www.renren.com/profile.do?id=<?php echo $activity['rrid'];?>"><?php echo $leader['uname'];?></a></p>
                    <p>类型：<?php echo $types_config[$activity['type_id']];?></p>
                    <p>共<?php echo $activity['care_num'];?>人关注，<?php echo $activity['join_num'];?>人参加，其中好友<?php echo count($joinFriends);?>位参加</p>   
                </div>
                <div class="op" id="op_panel">
                    <p><a href="javascript:care();">我要关注</a></p>
                    <p><a href="javascript:join();">我要参加</a></p>
                    <?php if($is_owner):?>
                    	<p><a href="javascript:invite();">呼朋唤友</a></p>
                    <?php endif;?>
                    <p><a href="home.php">返回首页</a></p>
                </div>
                <div class="clear"></div>
            </div>
            <h3>
            	活动介绍
            </h3>
            <div>
         
                <div>
				<!-- 活动介绍description -->
                </div>
		<?php if($joinFriends!=null):?>
			<h3>参与好友（共<?php echo count($joinFriends);?>人）</h3>
						<div>
							<?php foreach($joinFriends as $joinFriend):?>
		                    <div class="person">

		                    	<a target="_blank" href="http://www.renren.com/profile.do?id=<?php echo $joinFriend['rrid'];?>" class="p_t">
		                    		 <img src="<?php echo $joinFriend['headurl'];?>"/>
		                    	</a>
		                    	<a target="_blank" href="http://www.renren.com/profile.do?id=<?php echo $joinFriend['rrid'];?>"><?php echo $joinFriend['uname'];?></a>
		                 
		                    </div> 
		                    <?php endforeach?>     
		                </div>
		<?php endif?>
		
		<?php if($careFriends!=null):?>
			<h3>关注好友（共<?php echo count($careFriends)?>人）</h3>
		                <div>
		                	<?php foreach($careFriends as $careFriend):?>
		                     <div class="person">
		                    	<a target="_blank" href="http://www.renren.com/profile.do?id=<?php echo $careFriend['rrid'];?>" class="p_t">
		                    		 <img src="<?php echo $careFriend['headurl'];?>"/>
		                    	</a>
		                    	<a target="_blank" href="http://www.renren.com/profile.do?id=<?php echo $careFriend['rrid'];?>"><?php echo $careFriend['uname'];?></a>
		                     </div>
		                	<?php endforeach?>
		                </div>
		<?php endif?>
		
		<?php if($joinFriends==null && $careFriends==null && $joinUsers!=null):?>
			<h3>参与用户（共<?php echo count($joinUsers)?>人）</h3>
		                <div>
		                <?php foreach($joinUsers as $joinUser):?>
		                     <div class="person">         
		                    	<a href="#" class="p_t">
		                    		 <img src="<?php echo $joinUser['headurl'];?>"/>
		                    	</a>
		                    	<a href="<?php echo $joinUser['rrid']?>"><?php echo $joinUser['uname'];?></a>
		                    </div>
		                 <?php endforeach?>
		                </div>
		<?php endif?>
		    <h3>活动评论</h3>
		                <div class="comment">
		                    <div>
		                        <textarea row="2"></textarea><input type="button" value="提交"/>
		                    </div>
		                    <ul>
		                        <li>
		                        <?php foreach($comments as $comment):?>
		                            <a class="c_head" href="#">
		                            	<img src="<?php echo $comment['headurl'];?>" />
		                            </a>
		                            <div><a href="http://www.renren.com/profile.do?id=<?php echo $comment['rrid'];?>"><?php echo $comment['uname'];?></a>：<?php echo $comment['content'];?></div>
		                            <p class="c_time"><?php echo $comment['time']?></p>
		                        <?php endforeach?>
		                        </li>
		                    </ul>
		                    <p><a id="more_comment" href="#">显示更多评论</a></p>
		                </div>
		            </div>
		        </div>
        <div id="foot">
            呼朋唤友©版权所有
        </div>

    </body>
</html>