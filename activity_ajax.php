<?php 
	require("db/db_function.php");
	$aid = $_REQUEST['aid'];
	$uid = $_SESSION['uid'];
	$uidList = $_REQUEST['uidList'];
	$comments = get_CommentList($aid);
	$joinUsers=null;
	$joinFriends=null;
	$careFriends=null;
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
<?php if($joinFriends!=null):?>
    <div id="friends_number" style="display:none;"><?php echo count($joinFriends).",".count($careFriends)?></div>
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
                     <div class="person">
                     <?php foreach($careFriends as $careFriend):?>
                    	<a href="#" class="p_t">
                    		 <img src="<?php echo $careFriend['headurl'];?>"/>
                    	</a>
                    	<a href="<?php echo $careFriend['rrid']?>"><?php echo $careFriend['uname'];?></a>
                    <?php endforeach?>
                </div>
<?php endif?>

<?php if($joinFriends==null && $careFriends==null && $joinUsers!=null):?>
	<h3>参与用户（共<?php echo count($joinUsers)?>人）</h3>
                <div>
                     <div class="person">
                     <?php foreach($joinUsers as $joinUser):?>
                    	<a href="#" class="p_t">
                    		 <img src="<?php echo $joinUser['headurl'];?>"/>
                    	</a>
                    	<a href="<?php echo $joinUser['rrid']?>"><?php echo $joinUser['uname'];?></a>
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