<?php 
	require("db/db_function.php");
	$aid = $_REQUEST['aid'];
	$activity = get_Activity($aid,null);
	if($activity==null)
	{
		header("Location: home.php");
	}
	$leader = get_UserByUid($activity['leader_id']);
?>
<!doctype html>
<html>
    <head>
        <title>Hello</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <meta property="xn:app_id" content="172440" />
        <link style="text/css" rel="stylesheet" href="style2.css" />
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
                    <p>共<?php echo $activity['care_num'];?>人关注，<?php echo $activity['join_num'];?>人参加</p>   
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
                <!--  
                <h3>参与好友（共5人）</h3>
                <div>
                    <div class="person">
                    	<a href="#" class="p_t">
                    		 <img src="http://hdn.xnimg.cn/photos/hdn421/20111126/2040/h_tiny_HkLm_6f930001ad782f76.jpg"/>
                    	</a>
                    	<a href="#">王小扬</a>
                    </div>
                    <div class="person">
                       <a href="#" class="p_t">
                    		 <img src="http://hdn.xnimg.cn/photos/hdn421/20111126/2040/h_tiny_HkLm_6f930001ad782f76.jpg"/>
                    	</a>
                        <a href="#">Daisy</a>
                    </div>
                    <div class="person">
                        <a href="#" class="p_t">
                    		 <img src="http://hdn.xnimg.cn/photos/hdn421/20111126/2040/h_tiny_HkLm_6f930001ad782f76.jpg"/>
                    	</a>
                        <a href="#">DaisyLoveYou</a>
                    </div>
                   
                </div>
                <h3>关注好友（共5人）</h3>
                <div>
                     <div class="person">
                    	<a href="#" class="p_t">
                    		 <img src="http://hdn.xnimg.cn/photos/hdn421/20111126/2040/h_tiny_HkLm_6f930001ad782f76.jpg"/>
                    	</a>
                    	<a href="#">王小扬</a>
                    </div>
                    <div class="person">
                       <a  href="#" class="p_t">
                    		 <img src="http://hdn.xnimg.cn/photos/hdn421/20111126/2040/h_tiny_HkLm_6f930001ad782f76.jpg"/>
                    	</a>
                        <a href="#">Daisy</a>
                    </div>
                    <div class="person">
                        <a href="#" class="p_t">
                    		 <img src="http://hdn.xnimg.cn/photos/hdn421/20111126/2040/h_tiny_HkLm_6f930001ad782f76.jpg"/>
                    	</a>
                        <a href="#">DaisyLoveYou</a>
                    </div>
                </div>
                
                <h3>活动评论</h3>
                <div class="comment">
                    <div>
                        <textarea row="2"></textarea><input type="button" value="提交"/>
                    </div>
                    <ul>
                        <li>
                            <a class="c_head" href="#">
                            	<img src="http://hdn.xnimg.cn/photos/hdn421/20111126/2040/h_tiny_HkLm_6f930001ad782f76.jpg" />
                            </a>
                            <div><a href="#">葛羽航</a>：我要参加这个活动</div>
                            <p class="c_time">2011-12-8 23:46</p>
                        </li>
                        <li>
                           <a class="c_head" href="#">
                            	<img src="http://hdn.xnimg.cn/photos/hdn421/20111126/2040/h_tiny_HkLm_6f930001ad782f76.jpg" />
                            </a>
                            <div><a href="#">葛羽航</a>：我要参加这个活动</div>
                            <p class="c_time">2011-12-8 23:46</p>
                        </li>
                        <li>
                           <a class="c_head" href="#">
                            	<img src="http://hdn.xnimg.cn/photos/hdn421/20111126/2040/h_tiny_HkLm_6f930001ad782f76.jpg" />
                            </a>
                            <div><a href="#">葛羽航</a>：我要参加这个活动</div>
                            <p class="c_time">2011-12-8 23:46</p>
                        </li>
                        <li>
                            <a class="c_head" href="#">
                            	<img src="http://hdn.xnimg.cn/photos/hdn421/20111126/2040/h_tiny_HkLm_6f930001ad782f76.jpg" />
                            </a>
                            <div><a href="#">葛羽航</a>：我要参加这个活动</div>
                            <p class="c_time">2011-12-8 23:46</p>
                        </li>
                    </ul>
                    <p><a id="more_comment" href="#">显示更多评论</a></p>
                </div>
                -->
            </div>
        </div>
        <div id="foot">
            呼朋唤友©版权所有
        </div>

    </body>
</html>