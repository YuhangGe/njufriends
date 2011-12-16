<?php 
	session_start();
	require("db/db_function.php");
	$aid = $_REQUEST['aid'];
	//$uidListStr="1,2";
	$uidListStr = $_SESSION['u_list'];
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
		$joinFriends = get_JoinUsers($aid,$uidList,10);
		$careFriends = get_CareUsers($aid,$uidList,10);
		if($joinFriends==null || $careFriends==null)
			$joinUsers = get_JoinUsers($aid,null,7);
	}
?>
<!doctype html>
<html>
    <head>
        <title>Hello</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <meta property="xn:app_id" content="172440" />
        <link style="text/css" rel="stylesheet" href="css/style2.css" />
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
            		<img src="images/p449897379.jpg" />
            	</div>
                <div class="info">
                  	<p>开始时间: <?php echo getDateTime($activity['start_time']);?></p>
                    <p>结束时间: <?php echo getDateTime($activity['end_time']);?></p>
                    <p>地点: <?php echo $activity['location'];?></p>
                    <p>发起人：<a href="#"><?php echo $leader['uname'];?></a></p>
                    <p>类型：<?php echo $activity['type_id'];?></p>
                    <p>共<?php echo $activity['care_num'];?>人关注，<?php echo $activity['join_num'];?>人参加，其中好友<?php echo count($joinFriends);?>位参加</p>   
                </div>
                <div class="op" id="op_panel">
                    <p><a href="#">我要关注</a></p>
                    <p><a href="#">我要参加</a></p>
                    <p><a href="#">呼唤好友</a></p>
                    <p><a href="#">返回首页</a></p>
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
		                    <div class="person">
		                    <?php foreach($joinFriends as $joinFriend):?>
		                    	<a href="#" class="p_t">
		                    		 <img src="<?php echo $joinFriend['headurl'];?>"/>
		                    	</a>
		                    	<a href="<?php echo $joinFriend['rrid']?>"><?php echo $joinFriend['uname'];?></a>
		                    <?php endforeach?>
		                    </div>      
		                </div>
		<?php endif?>
		
		<?php if($careFriends!=null):?>
			<h3>关注好友（共<?php echo count($careFriends)?>人）</h3>
		                <div>
		                	<?php foreach($careFriends as $careFriend):?>
		                     <div class="person">
		                    	<a href="#" class="p_t">
		                    		 <img src="<?php echo $careFriend['headurl'];?>"/>
		                    	</a>
		                    	<a href="<?php echo $careFriend['rrid']?>"><?php echo $careFriend['uname'];?></a>
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
		                            	<img src="http://hdn.xnimg.cn/photos/hdn421/20111126/2040/h_tiny_HkLm_6f930001ad782f76.jpg" />
		                            </a>
		                            <div><a href="#"><?php echo $comment['uname'];?></a>：<?php echo $comment['content'];?></div>
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