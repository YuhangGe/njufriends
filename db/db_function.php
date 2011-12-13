<?php	 
	require("config.php");
	$db=mysql_connect($dbhost,$dbuser,$dbpassword);
	mysql_select_db($dbdatabase,$db);
	mysql_query("set names utf8");
	
	/*
	 * if the user with rrid exists
	 * return the user's information
	 * eg:array(4) { ["uid"]=> string(1) "1" ["rrid"]=> string(4) "asdf" 
	 * 		["uname"]=> string(6) "龚晨" ["headurl"]=> string(9) "asfd.adsf" } 
	 * else return null
	 */
	function get_UserByRRid($rrid)
	{	
		$sqlstr="select * from user where rrid='$rrid';";
		$result = mysql_query($sqlstr);
		$rows = mysql_num_rows($result);
		if($rows==0)
		{
			return null;
		}
		else
		{
			$row = mysql_fetch_assoc($result);
			return $row;
		}
	}
	
	function get_UserByUid($uid)
	{
	$sqlstr="select * from user where uid='$uid';";
		$result = mysql_query($sqlstr);
		$rows = mysql_num_rows($result);
		if($rows==0)
		{
			return null;
		}
		else
		{
			$row = mysql_fetch_assoc($result);
			return $row;
		}
	}
	
	/*
	 * return the users who cares about an activity
	 * uidList is the list of the friends of the user
	 * limitNum limtis the num to renturn
	 * if no one cares return null
	 * else return 
	 * eg:array(2) { [0]=> array(3) { ["uid"]=> string(1) "1" ["uname"]=> string(6) "龚晨" ["headurl"]=> string(9) "asfd.adsf" } 
	 * 				 [1]=> array(3) { ["uid"]=> string(1) "2" ["uname"]=> string(9) "葛羽航" ["headurl"]=> string(9) "ghjk.ghjk" } }
	 */
	function get_CareUsers($aid,$uidList,$limitNum)
	{	
		$strIn="";
		$strLimit="";
		if($uidList!=null)
		{	
			$strIn="and user.uid in (";
			$strIn = $strIn."'$uidList[0]'";
			for($i=1;$i<count($uidList);$i++)
			{
				$strIn = $strIn.",'$uidList[$i]'";
			}
			$strIn = $strIn.")";
		}
		if($limitNum!=null)
		{
			$strLimit = "limit 0,$limitNum";
		}
		$sqlstr="select caremember.uid as uid,user.uname as uname,user.headurl as headurl from caremember,user where caremember.aid='$aid' and user.uid=caremember.uid $strIn $strLimit;";
		$result = mysql_query($sqlstr);
		//echo $sqlstr;
		$rows = mysql_num_rows($result);
		if($rows==0)
		{
			return null;
		}
		$users=array();
		while($user = mysql_fetch_assoc($result))
		{
			array_push($users, $user);
		}
		return $users;
	}
	
	/*
	 * return the users who joins to an activity
	 * uidList is the list of the friends of the user
	 * limitNum limtis the num to renturn
	 * if no one joins return null
	 * else return 
	 * eg:array(2) { [0]=> array(3) { ["uid"]=> string(1) "1" ["uname"]=> string(6) "龚晨" ["headurl"]=> string(9) "asfd.adsf" } 
	 * 				 [1]=> array(3) { ["uid"]=> string(1) "2" ["uname"]=> string(9) "葛羽航" ["headurl"]=> string(9) "ghjk.ghjk" } }
	 */
	function get_JoinUsers($aid,$uidList,$limitNum)
	{
		$strIn="";
		$strLimit="";
		if($uidList!=null)
		{	
			$strIn="and user.uid in (";
			$strIn = $strIn."'$uidList[0]'";
			for($i=1;$i<count($uidList);$i++)
			{
				$strIn = $strIn.",'$uidList[$i]'";
			}
			$strIn = $strIn.")";
		}
		if($limitNum!=null)
		{
			$strLimit = "limit 0,$limitNum";
		}
		$sqlstr="select joinmember.uid as uid,user.uname as uname,user.headurl as headurl from joinmember,user where joinmember.aid='$aid' and user.uid=joinmember.uid $strIn $strLimit;";
		$result = mysql_query($sqlstr);
		$rows = mysql_num_rows($result);
		if($rows==0)
		{
			return null;
		}
		$users=array();
		while($user = mysql_fetch_assoc($result))
		{
			array_push($users, $user);
		}
		return $users;
	}
	
	/*
	 * return the rrid(String) of the user selected by uid
	 * if no one found it will renturn null
	 */
	function get_UserRRid($uid)
	{
		$sqlstr="select user.rrid from user where user.uid=$uid;";
		$result = mysql_query($sqlstr);
		$rows = mysql_num_rows($result);
		if($rows==0)
		{
			return null;
		}
		$rrid = mysql_fetch_assoc($result);
		return $rrid['rrid'];
	}
	
	/*
	 * insert the new user from the renren's user infromation
	 * if failed return null
	 */
	function insert_User($rrid,$uname,$headurl)
	{
		$sqlstr = "select * from user where user.rrid='$rrid'";
		$result = mysql_query($sqlstr);
		$rows = mysql_num_rows($result);
		if($rows!=0)
		{
			return null;
		}
		else
		{
			$sqlstr = "insert into user(rrid, uname, headurl) values('$rrid','$uname','$headurl');";
			mysql_query($sqlstr);
			$sqlstr = "select * from user where user.rrid='$rrid'";
			$result = mysql_query($sqlstr);
			$rows = mysql_num_rows($result);
			if($rows==0)
			{
				return null;
			}
			else
			{
				$user = mysql_fetch_assoc($result);
				return $user;
			}
		}
	}
	
	/*
	 * return the activity with the selected aid
	 * if failed renturn null
	 */
	function get_Activity($aid,$uidList)
	{
		$strIn="";
		$strgroup="";
		if($uidList!=null)
		{
			$strIn="and activity.aid=joinmember.aid and joinmember.uid in (";
			$strIn = $strIn."'$uidList[0]'";
			for($i=1;$i<count($uidList);$i++)
			{
				$strIn = $strIn.",'$uidList[$i]'";
			}
			$strIn = $strIn.")";
			$strgroup = " group by activity.aid ";
			$strplue = "count(*) as num,activity.aid as aid, activity.name as name,activity.start_time as start_time,
						activity.end_time as end_time,activity.location as location,
						activity.type_id as type_id,activity.leader_id as leader_id,
						activity.care_num as care_num, activity.join_num as join_num,
						user.uname as uname,activity.description as description, user.rrid as rrid";
			$sqlstr="select $strplue from activity,user,joinmember where activity.aid=$aid $strIn $strgroup;";
		}
		else
		{
			$strplue = "activity.aid as aid, activity.name as name,activity.start_time as start_time,
						activity.end_time as end_time,activity.location as location,
						activity.type_id as type_id,activity.leader_id as leader_id,
						activity.care_num as care_num, activity.join_num as join_num,
						user.uname as uname,activity.description as description, user.rrid as rrid";
			$sqlstr="select $strplue from activity,user where activity.aid=$aid;";
		}
		//echo $sqlstr;
		$result =mysql_query($sqlstr);
		$rows = mysql_num_rows($result);
		if($rows==0)
		{
			return null;
		}
		$activity = mysql_fetch_assoc($result);
		return $activity;
	}
	
	/*
	 * get the uid List from the given rrid list
	 */
	function get_UidList($RRidList)
	{
		$uidList = array();
		if($RRidList!=null)
		{	
			$strIn="where rrid in(";
			$strIn = $strIn."'$RRidList[0]'";
			for($i=1;$i<count($RRidList);$i++)
			{
				$strIn = $strIn.",'$RRidList[$i]'";
			}
			$strIn = $strIn.")";
		}
		$sqlstr = "select uid from user $strIn";
		//echo $sqlstr;
		$result = mysql_query($sqlstr);
		$num = mysql_num_rows($result);
		if($num==0)
		{
			return array();
		}
		while($row=mysql_fetch_assoc($result))
		{
			array_push($uidList,$row['uid']);
		}
		return $uidList;
	}
	
	/*
	 * get the activity list selected by typeId and the order
	 * order: time, order_num, join_num
	 * if typeId==0 then return all the type of activities
	 */
	function get_ActivityList($typeId,$orderBy)
	{
		$strplue = "activity.aid as aid, activity.name as name,activity.start_time as start_time,
					activity.end_time as end_time,activity.location as location,
					activity.type_id as type_id,activity.leader_id as leader_id,
					activity.care_num as care_num, activity.join_num as join_num,
					user.rrid as rrid,user.uname as uname";
		$strTime = "end_time>=NOW()";
		if($typeId==0)
		{
			switch($orderBy)
			{
			case null:
				$sqlstr = "select $strplue from activity,user where activity.leader_id=user.uid and $strTime;";
				break;
			case "time":
				$sqlstr = "select $strplue from activity,user where activity.leader_id=user.uid and $strTime order by aid desc;";
				break;
			case "care_num":
				$sqlstr = "select $strplue from activity,user where activity.leader_id=user.uid and $strTime order  by order_num desc where $strTime;";
				break;
			case "join_num":
				$sqlstr = "select $strplue from activity,user where activity.leader_id=user.uid and $strTimeorder by join_num desc;";
				break;
			default:
				$sqlstr = "select $strplue from activity,user where activity.leader_id=user.uid and $strTime;";
				break;
			}
		}
		else
		{
			switch($orderBy)
			{
				case null:
					$sqlstr = "select $strplue from activity,user where activity.leader_id=user.uid and type_id=$typeId and $strTime;";
					break;
				case "time":
					$sqlstr = "select $strplue from activity,user where activity.leader_id=user.uid and type_id=$typeId  and $strTime order by aid desc;";
					break;
				case "care_num":
					$sqlstr = "select $strplue from activity,user where activity.leader_id=user.uid and type_id=$typeId  and $strTime order by order_num desc;";
					break;
				case "join_num":
					$sqlstr = "select $strplue from activity,user where activity.leader_id=user.uid and type_id=$typeId  and $strTime order by join_num desc;";
					break;
				default:
					$sqlstr = "select $strplue from activity,user where activity.leader_id=user.uid and type_id=$typeId  and $strTime;";
					break;
			}
			
		}
		//echo $sqlstr;
		//echo $sqlstr;
		$result = mysql_query($sqlstr);
		$rows = mysql_num_rows($result);
		if($rows==0)
		{
			return null;
		}	
		$activities=array();
		while($activity = mysql_fetch_assoc($result))
		{
			array_push($activities, $activity);
		}
		return $activities;
	}
	
	function getActivityListByCareFriends($typeId,$orderBy,$uidList)
	{
		$strIn="";
		$strgroup="";
		if($uidList!=null)
		{	
			$strIn="and activity.aid=caremember.aid and caremember.uid in (";
			$strIn = $strIn."'$uidList[0]'";
			for($i=1;$i<count($uidList);$i++)
			{
				$strIn = $strIn.",'$uidList[$i]'";
			}
			$strIn = $strIn.")";
			$strgroup = " group by activity.aid ";
		}
		$strplue = "count(*) as num,activity.aid as aid, activity.name as name,activity.start_time as start_time,
					activity.end_time as end_time,activity.location as location,
					activity.type_id as type_id,activity.leader_id as leader_id,
					activity.care_num as care_num, activity.care_num as care_num,
					user.uname as uname, user.rrid as rrid";
		$strTime = "end_time>=NOW()";
		if($typeId==0)
		{
			switch($orderBy)
			{
			case null:
				$sqlstr = "select $strplue from activity,caremember,user where user.uid=activity.leader_id and $strTime $strIn $strgroup ;";
				break;
			case "time":
				$sqlstr = "select $strplue from activity,caremember,user where user.uid=activity.leader_id and $strTime $strIn $strgroup order by aid desc;";
				break;
			case "care_num":
				$sqlstr = "select $strplue from activity,caremember,user where user.uid=activity.leader_id and $strTime $strIn $strgroup order  by order_num desc where $strTime;";
				break;
			case "join_num":
				$sqlstr = "select $strplue from activity,caremember,user where user.uid=activity.leader_id and $strTime $strIn $strgroup order by _num desc;";
				break;
			default:
				$sqlstr = "select $strplue from activity,caremember,user where user.uid=activity.leader_id and $strTime $strIn $strgroup ;";
				break;
			}
		}
		else
		{
			switch($orderBy)
			{
				case null:
					$sqlstr = "select $strplue from activity,caremember,user where user.uid=activity.leader_id and type_id=$typeId and $strTime $strIn $strgroup ;";
					break;
				case "time":
					$sqlstr = "select $strplue from activity,caremember,user where user.uid=activity.leader_id and type_id=$typeId  and $strTime  $strIn $strgroup order by aid desc;";
					break;
				case "care_num":
					$sqlstr = "select $strplue from activity,caremember,user where user.uid=activity.leader_id and type_id=$typeId  and $strTime  $strIn $strgroup order by order_num desc;";
					break;
				case "join_num":
					$sqlstr = "select $strplue from activity,caremember,user where user.uid=activity.leader_id and type_id=$typeId  and $strTime  $strIn $strgroup order by _num desc;";
					break;
				default:
					$sqlstr = "select $strplue from activity,caremember,user where user.uid=activity.leader_id type_id=$typeId  and $strTime $strIn $strgroup ;";
					break;
			}
			
		}
		//echo $sqlstr;
		//echo $sqlstr;
		$result = mysql_query($sqlstr);
		$rows = mysql_num_rows($result);
		if($rows==0)
		{
			return null;
		}	
		$activities=array();
		while($activity = mysql_fetch_assoc($result))
		{
			array_push($activities, $activity);
		}
		return $activities;
	}
	
	function getActivityListByJoinFriends($typeId,$orderBy,$uidList)
	{
		$strIn="";
		$strgroup="";
		if($uidList!=null)
		{	
			$strIn="and activity.aid=joinmember.aid and joinmember.uid in (";
			$strIn = $strIn."'$uidList[0]'";
			for($i=1;$i<count($uidList);$i++)
			{
				$strIn = $strIn.",'$uidList[$i]'";
			}
			$strIn = $strIn.")";
			$strgroup = " group by activity.aid ";
		}
		$strplue = "count(*) as num,activity.aid as aid, activity.name as name,activity.start_time as start_time,
					activity.end_time as end_time,activity.location as location,
					activity.type_id as type_id,activity.leader_id as leader_id,
					activity.care_num as care_num, activity.join_num as join_num,
					user.rrid as rrid,user.uanme as uname";
		$strTime = "end_time>=NOW()";
		if($typeId==0)
		{
			switch($orderBy)
			{
			case null:
				$sqlstr = "select $strplue from activity,joinmember,user where user.uid=activity.leader_id and $strTime $strIn $strgroup;";
				break;
			case "time":
				$sqlstr = "select $strplue from activity,joinmember,user where user.uid=activity.leader_id and $strTime $strIn $strgroup order by aid desc; ";
				break;
			case "care_num":
				$sqlstr = "select $strplue from activity,joinmember,user where user.uid=activity.leader_id and $strTime $strIn $strgroup order  by order_num desc;";
				break;
			case "join_num":
				$sqlstr = "select $strplue from activity,joinmember,user where user.uid=activity.leader_id and $strTime $strIn $strgroup order by join_num desc;";
				break;
			default:
				$sqlstr = "select $strplue from activity,joinmember,user where user.uid=activity.leader_id and $strTime $strIn $strgroup;";
				break;
			}
		}
		else
		{
			switch($orderBy)
			{
				case null:
					$sqlstr = "select $strplue from activity,joinmember,user where user.uid=activity.leader_id and type_id=$typeId and $strTime $strIn $strgroup;";
					break;
				case "time":
					$sqlstr = "select $strplue from activity,joinmember,user where user.uid=activity.leader_id and type_id=$typeId  and $strTime  $strIn $strgroup order by aid desc;";
					break;
				case "care_num":
					$sqlstr = "select $strplue from activity,joinmember,user where user.uid=activity.leader_id and type_id=$typeId  and $strTime  $strIn $strgroup order by order_num desc;";
					break;
				case "join_num":
					$sqlstr = "select $strplue from activity,joinmember,user where user.uid=activity.leader_id and type_id=$typeId  and $strTime  $strIn $strgroup order by join_num desc;" ;
					break;
				default:
					$sqlstr = "select $strplue from activity,joinmember,user where user.uid=activity.leader_id and type_id=$typeId  and $strTime $strIn $strgroup;";
					break;
			}
			
		}
		//echo $sqlstr;
		//echo $sqlstr;
		$result = mysql_query($sqlstr);
		$rows = mysql_num_rows($result);
		if($rows==0)
		{
			return null;
		}	
		$activities=array();
		while($activity = mysql_fetch_assoc($result))
		{
			array_push($activities, $activity);
		}
		return $activities;
	}
	
	/*
	 * insert the new activity 
	 * if failed return null
	 */
	function insert_Activity($name,$start_time,$end_time,$location,$type_id,$description,$leader_id,$pic_id)
	{
		$name= mysql_real_escape_string($name);
		$start_time = mysql_real_escape_string($start_time);
		$end_time =  mysql_real_escape_string($end_time);
		$location =  mysql_real_escape_string($location);
		$type_id =  mysql_real_escape_string($type_id);
		$description =  mysql_real_escape_string($description);
		$leader_id = mysql_real_escape_string($leader_id);
		$pic_id = mysql_real_escape_string($pic_id); 
		$sqlstr = "insert into activity(name, start_time, end_time,location,type_id,description,leader_id,pic_id) 
		values('$name','$start_time','$end_time','$location','$type_id','$description','$leader_id','$pic_id');";
		//echo $sqlstr;
		$result = mysql_query($sqlstr);
		if($result==false)
		{
			return null;
		}
		$aid = mysql_insert_id();
		return $aid;
	}
	
	/*
	 * return all the types list
	 * if failed return null
	 */
	function get_TypeList()
	{
		$sqlstr = "select * from type;";
		$result = mysql_query($sqlstr);
		$rows = mysql_num_rows($result);
		if($rows==0)
		{
			return null;
		}
		$types=array();
		while($type = mysql_fetch_assoc($result))
		{
			array_push($types, $type);
		}
		return $types;
	}
	
	/*
	 * the user care one activity with the uid and aid
	 * if failed return null
	 */
	function insert_Care($uid,$aid)
	{
		$care_num=0;
		//check if the user exists
		$sqlstr = "select * from user where uid='$uid';";
		$result = mysql_query($sqlstr);
		if(mysql_num_rows($result)!=1)
		{
			echo "no user";
			return null;
		}
		//check if the activity exists
		$sqlstr = "select * from activity where aid='$aid';";
		$result = mysql_query($sqlstr);
		if(mysql_num_rows($result)!=1)
		{
			echo "no activity";
			return null;
		}
		else
		{
			$activity = mysql_fetch_assoc($result);
			$care_num = $activity['care_num'];
		}
		//check if the user has cares the activity
		$sqlstr = "select * from caremember where aid='$aid' and uid='$uid';";
		$result = mysql_query($sqlstr);
		$rows = mysql_num_rows($result);
		//echo $sqlstr;
		if($rows!=0)
		{
			echo "has cared";
			return null;
		}
		
		$sqlstr = "insert into caremember(aid,uid) values('$aid','$uid');";
		if(mysql_query($sqlstr)!=true)
		{
			return null;
		}
		$care_num = $care_num +1;
		$sqlstr = "update activity set care_num='$care_num' where aid='$aid';";
		mysql_query($sqlstr);
		return true;
	}
	
	/*
	 * the user join one activity with the uid and aid
	 * if failed return null
	 */
	function insert_Join($uid,$aid)
	{
		$join_num=0;
		//check if the user exists
		$sqlstr = "select * from user where uid='$uid';";
		$result = mysql_query($sqlstr);
		if(mysql_num_rows($result)!=1)
		{
			echo "no user";
			return null;
		}
		//check if the activity exists
		$sqlstr = "select * from activity where aid='$aid';";
		$result = mysql_query($sqlstr);
		if(mysql_num_rows($result)!=1)
		{
			echo "no activity";
			return null;
		}
		else
		{
			$activity = mysql_fetch_assoc($result);
			$join_num = $activity['care_num'];
		}
		//check if the user has joins into the activity
		$sqlstr = "select * from joinmember where aid='$aid' and uid='$uid';";
		$result = mysql_query($sqlstr);
		$rows = mysql_num_rows($result);
		//echo $sqlstr;
		if($rows!=0)
		{
			echo "has cared";
			return null;
		}
		
		$sqlstr = "insert into joinmember(aid,uid) values('$aid','$uid');";
		if(mysql_query($sqlstr)!=true)
		{
			return null;
		}
		$join_num = $join_num +1;
		$sqlstr = "update activity set join_num='$join_num' where aid='$aid';";
		mysql_query($sqlstr);
		return true;
	}
	
	/*
	 * get the comment list with the selected activity
	 * if no one exist return null
	 */
	function get_CommentList($aid)
	{
		$sqlstr = "select * from comment,user where aid='$aid' and user.uid=comment.uid;";
		$result = mysql_query($sqlstr);
		$rows = mysql_num_rows($result);
		$comments=array();
		while($comment = mysql_fetch_assoc($result))
		{
			array_push($comments, $comment);
		}
		return $comments;
	}
	
	/*
	 * insert the comment into the database
	 * if failed return null
	 */
	function insert_Comment($aid,$uid,$content,$time)
	{
		$sqlstr = "insert into comment(aid,uid,content,time) values('$aid','$uid','$content','$time');";
		if(mysql_query($sqlstr)!=true)
		{
			return null;
		}
		$cid = mysql_insert_id();
		return $cid;
	}
	
	function transDate($dateStrStart,$dateStrEnd)
	{
		$week =array('周日','周一','周二','周三','周四','周五','周六');
		$strStar="";
		$strEnd="";
		$dateStart = strtotime($dateStrStart);
		$dateEnd = strtotime($dateStrEnd);
		if(date("Y-m-d",$dateStart)==date("Y-m-d",$dateEnd))
		{
			$strDate = date("n",$dateStrStart)."月".date("d",$dateStart)."日 ";
			$strStartTime = date("g",$dateStart).":".(date("i",$dateStart)+1)." ".date("A",$dateStart);
			$strEndTime = date("g",$dateEnd).":".(date("i",$dateEnd)+1)." ".date("A",$dateStart);
			$result = $strDate." ".$strStartTime." - ".$strEndTime;
		}
		else
		{
			$strStart = date("n",$dateStart)."月".date("d",$dateStart)."日  ".$week[date('w',$dateStart)];
			$strEnd = date("n",$dateEnd)."月".date("d",$dateEnd)."日  ".$week[date('w',$dateEnd)];
			$result = $strStart." - ".$strEnd;
		}
		return $result;
	}
	
	function getDateTime($dateStr)
	{
		$date = strtotime($dateStr);
		$strDate =date("n",$date)."月".date("d",$date)."日  ".$week[date('w',$date)];
		$strTime =  date("g",$date).":".(date("i",$date))." ".date("A",$date);
		$result = $strDate." ".$strTime;
		return $result;
	}
?>



