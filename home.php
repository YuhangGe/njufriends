<?php
	@session_start();
	require("db/db_function.php");
	$types = get_TypeList();
	
	//var_dump($_SESSION);
	$order_by = "time";
	if(!empty($_REQUEST['order']))
		$order_by=$_REQUEST['order'];
	$type_id = 0;
	if(!empty($_REQUEST['type']))
		$type_id = $_REQUEST['type'];
	
	if(!empty($_SESSION['u_list']))
	{
		$rridList = explode(",",$_SESSION['u_list']);
		$activities = getActivityListByCareFriends($type_id,$order_by,$rridList);
	}
	else
	{
		$activities = get_ActivityList($type_id,$order_by);
	}
	
	function get_href($tid,$ord){
		return "home.php?type=$tid&order=$ord";
	}
?>
<!doctype html>
<html>
    <head>
        <title>Hello</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <link style="text/css" rel="stylesheet" href="css/style.css" />
    </head>
    <body>
       <div id="frame">
        <div id="logo">
            呼朋唤友
        </div>
        <div id="main">
            <div id="left">
            	<ul>
            		<li><a href="<?php echo get_href($type_id,"time");?>">时间</a></li>
            		<li><a href="<?php echo get_href($type_id,"join_number");?>">热门度</a></li>
            		<li><a href="<?php echo get_href($type_id,"time");?>">好友参与</a></li>
            	</ul>
                <ul>
                    <li><a href="<?php echo get_href(0,$order_by);?>" <?php echo ($type_id==0)?"class='cur_class'":"";?>>所有类别</a></li>
                    <?php 
                    	foreach($types as $type)
                    	{
                    		$tid = $type['type_id'];
							$t_href = get_href($tid,$order_by);
							echo "<li><a href='$t_href' ".($tid==$type_id?"class='cur_class'":"").">".$type['tname']."</a></li>";
                    	}
                    ?>
                </ul>
            </div>
            <div id="right">
                <ul>
                	<?php foreach($activities as $activity):?>
                    <li class="list-item">
                    	<div class="i-left">
                    	<?php 
                    		if(empty($activity['pic_url']))
                    		{
                    			echo "<a href='#'><img src='images/e563905.jpg' /></a>";
                    		}
                    		else
                    		{
                    			echo "<a href='#'><img src='".$activity['pic_url']."' /></a>";
                    		}
                    	?>
                    	</div>
                    	<div class="i-right">
                    		<h3><a href="activity.php?aid=<?php echo $activity['aid'];?>"><?php echo $activity['name']?></a></h3>
	                        <p>时间：<?php echo transDate($activity['start_time'],$activity['end_time']);?></p>
	                        <p>地点：<?php echo $activity['location'];?></p>
	                        <p>类型：<?php echo $activity['type_id']?></p>
	                        <p>发起人：<a href="http://www.renren.com/profile.do?id=<?php echo $activity['rrid'];?>" target="_blank"><?php echo $activity['uname']?></a></p>
	                        <p>共<?php echo $activity['join_num']?>人参加，<?php echo $activity['care_num']?>人关注；其中好友<?php if($activity['num']==null) echo 0;else echo $activity['num'];?>人参加</p>
                    	</div>
                        <div class="clear"></div>
                    </li>
                    <?php  endforeach;?>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
        <div id="foot">
            呼朋唤友©版权所有
        </div>
       </div>
    </body>
</html>