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
	function get_User($rrid)
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
		echo $sqlstr;
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
	function get_Activity($aid)
	{
		$sqlstr="select * from activity where aid=$aid;";
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
		for($i=0;$i<count($RRidList);$i++)
		{
			$row = get_User($RRidList[$i]);
			if($row==null)
				continue;
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
					activity.care_num as care_num, activity.join_num as join_num";
		$strTime = "end_time>=NOW()";
		if($typeId==0)
		{
			switch($orderBy)
			{
			case null:
				$sqlstr = "select $strplue from activity where $strTime;";
				break;
			case "time":
				$sqlstr = "select $strplue from activity where $strTime order by aid desc;";
				break;
			case "care_num":
				$sqlstr = "select $strplue from activity where $strTime order  by order_num desc where $strTime;";
				break;
			case "join_num":
				$sqlstr = "select $strplue from activity where $strTimeorder by join_num desc;";
				break;
			default:
				$sqlstr = "select $strplue from activity where $strTime;";
				break;
			}
		}
		else
		{
			switch($orderBy)
			{
				case null:
					$sqlstr = "select $strplue from activity where type_id=$typeId and $strTime;";
					break;
				case "time":
					$sqlstr = "select $strplue from activity where type_id=$typeId  and $strTime order by aid desc;";
					break;
				case "care_num":
					$sqlstr = "select $strplue from activity where type_id=$typeId  and $strTime order by order_num desc;";
					break;
				case "join_num":
					$sqlstr = "select $strplue from activity where type_id=$typeId  and $strTime order by join_num desc;";
					break;
				default:
					$sqlstr = "select $strplue from activity where type_id=$typeId  and $strTime;";
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
		if($uidList!=null)
		{	
			$strIn="and activity.aid=caremember.aid and caremember.uid in (";
			$strIn = $strIn."'$uidList[0]'";
			for($i=1;$i<count($uidList);$i++)
			{
				$strIn = $strIn.",'$uidList[$i]'";
			}
			$strIn = $strIn.")";
		}
		$strplue = "distinct activity.aid as aid, activity.name as name,activity.start_time as start_time,
					activity.end_time as end_time,activity.location as location,
					activity.type_id as type_id,activity.leader_id as leader_id,
					activity.care_num as care_num, activity.care_num as care_num";
		$strTime = "end_time>=NOW()";
		if($typeId==0)
		{
			switch($orderBy)
			{
			case null:
				$sqlstr = "select $strplue from activity,caremember where $strTime $strIn;";
				break;
			case "time":
				$sqlstr = "select $strplue from activity,caremember where $strTime $strIn order by aid desc;";
				break;
			case "care_num":
				$sqlstr = "select $strplue from activity,caremember where $strTime $strIn order  by order_num desc where $strTime;";
				break;
			case "join_num":
				$sqlstr = "select $strplue from activity,caremember where $strTime $strIn order by _num desc;";
				break;
			default:
				$sqlstr = "select $strplue from activity,caremember where $strTime $strIn;";
				break;
			}
		}
		else
		{
			switch($orderBy)
			{
				case null:
					$sqlstr = "select $strplue from activity,caremember where type_id=$typeId and $strTime $strIn;";
					break;
				case "time":
					$sqlstr = "select $strplue from activity,caremember where type_id=$typeId  and $strTime  $strIn order by aid desc;";
					break;
				case "care_num":
					$sqlstr = "select $strplue from activity,caremember where type_id=$typeId  and $strTime  $strIn order by order_num desc;";
					break;
				case "join_num":
					$sqlstr = "select $strplue from activity,caremember where type_id=$typeId  and $strTime  $strIn order by _num desc;";
					break;
				default:
					$sqlstr = "select $strplue from activity,caremember where type_id=$typeId  and $strTime $strIn;";
					break;
			}
			
		}
		//echo $sqlstr;
		echo $sqlstr;
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
		if($uidList!=null)
		{	
			$strIn="and activity.aid=joinmember.aid and joinmember.uid in (";
			$strIn = $strIn."'$uidList[0]'";
			for($i=1;$i<count($uidList);$i++)
			{
				$strIn = $strIn.",'$uidList[$i]'";
			}
			$strIn = $strIn.")";
		}
		$strplue = "distinct activity.aid as aid, activity.name as name,activity.start_time as start_time,
					activity.end_time as end_time,activity.location as location,
					activity.type_id as type_id,activity.leader_id as leader_id,
					activity.care_num as care_num, activity.join_num as join_num";
		$strTime = "end_time>=NOW()";
		if($typeId==0)
		{
			switch($orderBy)
			{
			case null:
				$sqlstr = "select $strplue from activity,joinmember where $strTime $strIn;";
				break;
			case "time":
				$sqlstr = "select $strplue from activity,joinmember where $strTime $strIn order by aid desc;";
				break;
			case "care_num":
				$sqlstr = "select $strplue from activity,joinmember where $strTime $strIn order  by order_num desc where $strTime;";
				break;
			case "join_num":
				$sqlstr = "select $strplue from activity,joinmember where $strTime $strIn order by join_num desc;";
				break;
			default:
				$sqlstr = "select $strplue from activity,joinmember where $strTime $strIn;";
				break;
			}
		}
		else
		{
			switch($orderBy)
			{
				case null:
					$sqlstr = "select $strplue from activity,joinmember where type_id=$typeId and $strTime $strIn;";
					break;
				case "time":
					$sqlstr = "select $strplue from activity,joinmember where type_id=$typeId  and $strTime  $strIn order by aid desc;";
					break;
				case "care_num":
					$sqlstr = "select $strplue from activity,joinmember where type_id=$typeId  and $strTime  $strIn order by order_num desc;";
					break;
				case "join_num":
					$sqlstr = "select $strplue from activity,joinmember where type_id=$typeId  and $strTime  $strIn order by join_num desc;";
					break;
				default:
					$sqlstr = "select $strplue from activity,joinmember where type_id=$typeId  and $strTime $strIn;";
					break;
			}
			
		}
		//echo $sqlstr;
		echo $sqlstr;
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
	function insert_Activity($name,$start_time,$end_time,$location,$type_id,$description,$leader_id)
	{
		$sqlstr = "insert into activity(name, start_time, end_time,location,type_id,description,leader_id) 
		values('$name','$start_time','$end_time','$location','$type_id','$description','$leader_id');";
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
		$sqlstr = "select * from comment where aid='$aid';";
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
?>



