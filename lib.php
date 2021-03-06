<?php
session_start();

function getDBConnection(){
	try{ 
		//$db = new mysqli("198.199.66.99","root","FraF=mAch8qu","junktrader");
		//$db = new mysqli("198.199.66.99","junktrader","j!p@aChU7Ust","junktrader");

		//Use when on Server
		//$db = new mysqli("localhost","junktrader","j!p@aChU7Ust","junktrader");

		//Use when on local machine
		$db = new mysqli("localhost","root","","junktrader");
		if ($db == null && $db->connect_errno > 0)
			return null;
		return $db;
	}catch(Exception $e){ } 
	return null;
}


function checkLogin($email, $password){
	$password = sha1($password);
	$sql = "SELECT * FROM `users` WHERE `email`='$email' OR `username`='$email'";
	//print($email);
	$db = getDBConnection();
	//print_r($db);
	if($db != NULL){
		$res = $db->query($sql);
		if ($res && $row = $res->fetch_assoc()){
			if($row['password'] == $password){
				$_SESSION["user"] = $row['firstname'];
				$_SESSION["id"] = $row['id'];
				login($row['id']);
				return $row['firstname'];
			}
				
		}
	}
	return false;
}

function login($userId){
	$db = getDBConnection();
	$sql = "UPDATE `users` u SET `status` = true WHERE u.id = $userId;";
	$res = null;
	if ($db != NULL){
		$res = $db->query($sql);
		$db->close();
	}
	//return $res;
}

function logout($userId){
	$db = getDBConnection();
	$sql = "UPDATE `users` u SET `status` = false WHERE u.id = $userId;";
	$res = null;
	if ($db != NULL){
		$res = $db->query($sql);
		$db->close();
	}
	//return $res;
}


function getUserStatus($traderId){
	$userid = $_SESSION['id'];
	$sql = "SELECT u.status FROM `users` u WHERE u.id = $traderId;";
	$rec =[];
	$db = getDBConnection();
	if ($db != null){
		$res = $db->query($sql);
		if ($res){
			$rec = $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function checkLogin1($email, $securityQuestion, $sAnswer){
	$sAnswer = sha1($sAnswer);
	$sql = "SELECT * FROM `users` where `email`='$email' OR `username`='$email' AND `sQuestion` = '$securityQuestion' ";
	//print($email);
	$db = getDBConnection();
	//print_r($db);
	if($db != NULL){
		$res = $db->query($sql);
		if ($res && $row = $res->fetch_assoc()){
			if($row['sAnswer'] == $sAnswer){
				$_SESSION["user"] = $row['username'];
				return $row['firstname'];
			}
				
		}
	}
	return false;
}


function saveUser($username, $firstname, $lastname, $email, $telephone, $password, $securityQuestion, $securityAnswer){
	$password = sha1($password);
	$securityAnswer = sha1($securityAnswer);
	$sql = "INSERT INTO `users` (`username`, `firstname`, `lastname`, `email`, `telephone`, `password`, `sQuestion`, `sAnswer`) VALUES ('$username', '$firstname', '$lastname', '$email', '$telephone', '$password','$securityQuestion','$securityAnswer');";
	$id = -1;
	$db = getDBConnection();
	//echo($db);
	if ($db != NULL){
		$res = $db->query($sql);
		if ($res && $db->insert_id > 0){
			$id = $db->insert_id;
		}
		$db->close();
	}
	return $id;
}


function isExist($firstName){
	/*$sql = "SELECT * FROM `users` WHERE id = '$FBID';";
	$db = getDBConnection();
	$rec = null;
	if($db != NULL){
		$res = $db->query($sql);
		if($res && $row = $res->fetch_assoc()){
			//var_dump($data);
			if($row["id"]!=NUll)return true;
		}
		return false;
	} */
	$db = getDBConnection();
	$rec = null;
	if ($db != null){
		$sql = "SELECT * FROM `users` u WHERE u.firstname = '$firstName';";
		$res = $db->query($sql);
		if ($res){
			$rec = $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function isExistFbId($fbId){
	$db = getDBConnection();
	$rec = null;
	if ($db != null){
		$sql = "SELECT * FROM `users` u WHERE u.fbid = $fbId;";
		$res = $db->query($sql);
		if ($res){
			$rec = $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function saveFBUser($fbFirstName, $fbEmail, $fbId){
	$sql = "INSERT INTO `users` (`fbid`,`username`,`firstname`,`email`) VALUES ($fbId,'$fbFirstName','$fbFirstName', '$fbEmail');";
	$id = -1;
	$db = getDBConnection();
	if ($db != NULL){
		$res = $db->query($sql);
	
		if ($res && $db->insert_id > 0){
			$id = $db->insert_id;
		}
		$db->close();
	}
	return $id;
}


function updateFbId($userId, $fbId, $fbPicture){
	$db = getDBConnection();
	$sql = "UPDATE `users` u SET `fbid` = $fbId, `profilepicture` = '$fbPicture' WHERE u.id = $userId;";
	$res = null;
	if ($db != NULL){
		$res = $db->query($sql);
		$db->close();
	}
	return $res;
}

function saveMessage($sentFrom, $sentTo, $message){
	$id = -1;
	$db = getDBConnection();
	//$message = mysql_real_escape_string($db, $message);
	//$message = str_replace("'","\\'", $message);
	$sql = "INSERT INTO `chat` (`sentfrom`, `sentto`, `message`) VALUES ($sentFrom, $sentTo, '$message');";
	
	//echo($db);
	if ($db != NULL){
		$res = $db->query($sql);
		if ($res && $db->insert_id > 0){
			$id = $db->insert_id;
		}
		$db->close();
	}
	return $id;
}

function getMessages($traderId){
	$userid = $_SESSION['id'];
	$sql = "SELECT * FROM `chat` c WHERE c.sentto = $traderId AND c.sentfrom = $userid OR c.sentto = $userid AND c.sentfrom = $traderId ORDER BY senton ASC;";
	$messages =[];
	$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
				$messages[] = $row;
		}//while
		$db->close();
	}//if
	return $messages;
}


function getNewMessages(){
	$userid = $_SESSION['id'];
	$sql = "SELECT * FROM `chat` c WHERE c.sentto = $userid;";
	$messages =[];
	$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
				$messages[] = $row;
		}//while
		$db->close();
	}//if
	return $messages;
}


function getNewMessagesNotification(){
	$userid = $_SESSION['id'];
	$sql = "SELECT u.username, c.sentfrom, u.profilepicture, COUNT(*) As messages FROM `chat` c, `users` u WHERE c.sentfrom = u.id AND c.sentto = $userid AND c.readindicator = false GROUP BY u.username, c.sentfrom, u.profilepicture;";
	$messages =[];
	$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
				$messages[] = $row;
		}//while
		$db->close();
	}//if
	return $messages;
}

function checkReadMessage($chatId){
	$userid = $_SESSION['id'];
	$sql = "SELECT message FROM `chat` c WHERE c.chatid = $chatId AND c.sentto = $userid AND c.readindicator = false;";
	$rec =[];
	$db = getDBConnection();
	if ($db != null){
		$res = $db->query($sql);
		if ($res){
			$rec = $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function readMessage($chatId){
	$userid = $_SESSION['id'];
	$sql = "UPDATE `chat` c SET `readindicator` = true WHERE `chatid` = $chatId";
	$isRead = checkReadMessage($chatId);
	$res = null;
	if($isRead == null){
		return $res;
	}
	else{
		$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			$db->close();
		}
		return $res;
	}
	//return $res;
}

function checkSavedItem($itemId, $itemOwner){
	$userid = $_SESSION['id'];
	$sql = "SELECT * FROM `saved` s where s.itemid = $itemId and s.userid = '$userid' and s.itemowner = $itemOwner;";
	$db = getDBConnection();
	$rec = null;
	if ($db != null){
		$res = $db->query($sql);
		if ($res){
			$rec = $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;	
}

function addItemToSaved($itemId, $itemOwner){
	$userId = $_SESSION['id'];
	$itemExists = checkSavedItem($itemId, $itemOwner);
	//echo $itemExists;
	$db = getDBConnection();
	//var_dump($itemExists);
	$savedId = $itemExists['savedid'];

	if($itemExists == null){
		$sql = "INSERT INTO `saved` (`itemid`, `userid`, `itemowner`, `savedindicator`) VALUES ($itemId, $userId, $itemOwner, true);";
		$id = -1;
		if ($db != NULL){
			$res = $db->query($sql);
			if ($res && $db->insert_id > 0){
				$id = $db->insert_id;
			}
			$db->close();
		}
		return $id;
	}
	else{
		$sql = "UPDATE `saved` s SET `savedindicator` = true, `saveddate`=now() WHERE `savedid` = $savedId;";
		$res = null;
		if ($db != NULL){
			$res = $db->query($sql);
			$db->close();
			}
		return $res;
	}	
}

function removeItemFromSaved($savedId){
	$db = getDBConnection();
	$sql = "UPDATE `saved` s SET `savedindicator` = false WHERE `savedid` = $savedId;";
	$res = null;
	if ($db != NULL){
		$res = $db->query($sql);
		$db->close();
	}
	return $res;
} 

function checkFollowee($followee){
	$userid = $_SESSION['id'];
	$sql = "SELECT * FROM `follow` f where f.followee = $followee and f.follower = $userid;";
	$db = getDBConnection();
	$rec = null;
	if ($db != null){
		$res = $db->query($sql);
		if ($res){
			$rec = $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;	
}

function addFollowee($followee){
	$follower = $_SESSION['id'];
	$db = getDBConnection();
	$followeeExists = checkFollowee($followee);
	$followId = $followeeExists['followid'];

	if($followeeExists == null){
		$sql = "INSERT INTO `follow` (`follower`, `followee`, `followindicator`) VALUES ($follower, $followee, true);";
		$id = -1;
		if ($db != NULL){
			$res = $db->query($sql);
			if ($res && $db->insert_id > 0){
				$id = $db->insert_id;
			}
			$db->close();
		}
		return $id;
	}
	else{
		$sql = "UPDATE `follow` f SET `followindicator` = true, `followdate`=now() WHERE `followid` = $followId;";
		$res = null;
			if ($db != NULL){
				$res = $db->query($sql);
				$db->close();
			}
		return $res;
	}
	
}

function removeFollowee($followee){
	$follower = $_SESSION['id'];
	$db = getDBConnection();
	$sql = "UPDATE `follow` f SET `followindicator` = false WHERE `followee` = $followee AND f.follower = $follower;";
	$res = null;
	if ($db != NULL){
		$res = $db->query($sql);
		$db->close();
	}
	return $res;
} 

function saveRequesterFeedback($tradeId, $rating, $comment){
	$db = getDBConnection();
	$sql = "UPDATE `trade` t SET `requesterfeedbackrating` = '$rating', `requesterfeedbackcomment` = '$comment', `requesterfeedbackindicator` = true WHERE `tradeid` = $tradeId;";
	$res = null;
	if ($db != NULL){
		$res = $db->query($sql);
		$db->close();
	}
	return $res;
} 

function saveRequesteeFeedback($tradeId, $rating, $comment){
	$db = getDBConnection();
	$sql = "UPDATE `trade` t SET `requesteefeedbackrating` = '$rating', `requesteefeedbackcomment` = '$comment', `requesteefeedbackindicator` = true WHERE `tradeid` = $tradeId;";
	$res = null;
	if ($db != NULL){
		$res = $db->query($sql);
		$db->close();
	}
	return $res;
} 

function getFollowID($userId){
	$userid = $_SESSION['id'];
	$sql = "SELECT * FROM `follow` f where f.followee = $userId and f.follower = $userid;";
	$db = getDBConnection();
	$rec = null;
	if ($db != null){
		$res = $db->query($sql);
		if ($res){
			$rec = $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;	
}


function saveTradeArrangement($requestId, $tradeDate, $tradeLocation, $requesteeContact, $requesterContact){
	$sql = "INSERT INTO `trade` (`requestid`, `tradedate`, `tradelocation`,`requesteecontact`, `requestercontact`) VALUES ($requestId, '$tradeDate', '$tradeLocation','$requesteeContact','$requesterContact');";
	$id = -1;
	$db = getDBConnection();
	if ($db != NULL){
		$res = $db->query($sql);
		if ($res && $db->insert_id > 0){
			$id = $db->insert_id;
		}
		$db->close();
	}
	return $id;
}

function getRequestedMeetupRequestee(){
	$userid = $_SESSION['id'];
	$sql = "SELECT * FROM `requests` r, `trade` t, `users` u, `items` i where r.id = t.requestid and r.requestee = u.id and r.item = i.itemid and r.requester = '$userid' AND t.requesterfeedbackindicator = false ORDER BY t.tradedate ASC;";
	$items =[];
	$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
				$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getRequestedMeetupRequester(){
	$userid = $_SESSION['id'];
	$sql = "SELECT `itemname` FROM `requests` r, `trade` t, `users` u, `items` i where r.requester = '$userid' and r.id = t.requestid and r.item2 = i.itemid and r.requester = u.id ORDER BY t.tradedate ASC ;";
	$items =[];
	$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
				$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getRequestsMeetupRequestee(){
	$userid = $_SESSION['id'];
	$sql = "SELECT * FROM `requests` r, `trade` t, `items` i, `users` u where r.requestee = '$userid' and r.id = t.requestid and r.item = i.itemid and r.requestee = u.id ORDER BY t.tradedate ASC ;";
	$items =[];
	$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
				$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getRequestsMeetupRequester(){
	$userid = $_SESSION['id'];
	$sql = "SELECT * FROM `requests` r, `trade` t, `items` i, `users` u where r.requestee = '$userid' and r.id = t.requestid and r.item2 = i.itemid and r.requester = u.id AND t.requesteefeedbackindicator = false ORDER BY t.tradedate ASC;";
	$items =[];
	$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
				$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getUserMeetUp(){
	$userid = $_SESSION['id'];
	//$sql = "SELECT * FROM `trade` t, `requests` r WHERE r.id = t.requestid AND r.decision = true AND r.requestee = $userid OR r.requester = $userid ORDER BY t.tradedate ASC;";
	$sql = "SELECT t.tradedate, t.tradelocation, t.requestid, r.requester, u.username FROM `trade` t, `requests` r, `users` u WHERE r.id = t.requestid AND r.requestee = u.id AND r.decision = true AND r.requester = $userid AND t.requesterfeedbackindicator = false ORDER BY t.tradedate ASC;";
	$sql .= "SELECT t.tradedate, t.tradelocation, t.requestid, r.requester, u.username FROM `trade` t, `requests` r, `users` u WHERE r.id = t.requestid AND r.requester = u.id AND r.decision = true AND r.requestee = $userid AND t.requesteefeedbackindicator = false ORDER BY t.tradedate ASC;";

	$events =[];
	$db = getDBConnection();
	if ($db != NULL){
		if($db->multi_query($sql)){
			do{
				if($res = $db->store_result()){
					while($row = $res->fetch_row()){
						$events[] = $row;
					}
					$res->free();
				}
			} while($db->more_results() && $db->next_result());
		} 
	}//if
	return $events;
}

function getUserFollowerUpdates(){
	$userid = $_SESSION['id'];
	$sql = "SELECT i.itemname, u.username, i.itemid, i.uploaddate FROM `follow` f, `users` u, `items` i WHERE u.id = f.followee AND u.id = i.userid AND f.follower = $userid AND f.followindicator = true AND i.uploaddate >= f.followdate ORDER BY i.uploaddate DESC ;";
	$updates =[];
	$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
				$updates[] = $row;
		}//while
		$db->close();
	}//if
	return $updates;
}

function getUserFollowerUpdatesRequests(){
	$userid = $_SESSION['id'];
	$sql = "SELECT i.itemname, r.decision FROM `follow` f, `users` u, `items` i, `requests` r  WHERE u.id = f.followee AND u.id = i.userid AND f.follower = $userid AND r.item = i.itemid AND f.followindicator = true AND i.uploaddate >= f.followdate AND r.decision = true ORDER BY i.uploaddate DESC, r.decision DESC;";
	$sql .= "SELECT i.itemname, r.decision FROM `follow` f, `users` u, `items` i, `requests` r  WHERE u.id = f.followee AND u.id = i.userid AND f.follower = $userid AND r.item2 = i.itemid AND f.followindicator = true AND i.uploaddate >= f.followdate AND r.decision = true ORDER BY i.uploaddate DESC, r.decision DESC;";
	$requests =[];
	$db = getDBConnection();
	if ($db != NULL){
		if($db->multi_query($sql)){
			do{
				if($res = $db->store_result()){
					while($row = $res->fetch_row()){
						$requests[] = $row;
					}
					$res->free();
				}
			} while($db->more_results() && $db->next_result());
		} 
	}//if
	return $requests;
}

function getTradeHistoryRequested(){
	$userid = $_SESSION['id'];
	$sql = "SELECT *  FROM `requests` r, `trade` t, `users` u, `items` i WHERE r.id = t.requestid AND r.requestee = u.id AND r.item = i.itemid AND r.requester = $userid AND r.decision = true AND requesteefeedbackindicator = true AND requesterfeedbackindicator = true ORDER BY t.tradedate DESC;";
	//$sql .= "SELECT t.tradedate, t.tradelocation, i.itemname AS 'requesteeitemuserrequester', u.username AS 'requesteeusernameuserrequester' FROM `requests` r, `trade` t, `users` u, `items` i WHERE r.id = t.requestid AND r.requestee = u.id AND r.item = i.itemid AND r.requester = $userid AND r.decision = true ORDER BY t.tradedate DESC;";

	$trades =[];
	$db = getDBConnection();
	if ($db != NULL){
		$res = $db->query($sql);
		while($res && $row = $res->fetch_assoc()){
			$trades[] = $row;
		}//while
		$db->close();
	}
	/*if ($db != NULL){
		if($db->multi_query($sql)){
			do{
				if($res = $db->store_result()){
					while($row = $res->fetch_row()){
						$trades[] = $row;
					}
					$res->free();
				}
			} while($db->more_results() && $db->next_result());
		} 
	} */
	return $trades;
}

function getTradeHistoryRequestedUserInfo(){
	$userid = $_SESSION['id'];
	$sql = "SELECT *  FROM `requests` r, `trade` t, `users` u, `items` i WHERE r.id = t.requestid AND r.requester = u.id AND r.item2 = i.itemid AND r.requester = $userid AND r.decision = true AND requesteefeedbackindicator = true AND requesterfeedbackindicator = true ORDER BY t.tradedate DESC;";
	//$sql .= "SELECT t.tradedate, t.tradelocation, i.itemname AS 'requesteeitemuserrequester', u.username AS 'requesteeusernameuserrequester' FROM `requests` r, `trade` t, `users` u, `items` i WHERE r.id = t.requestid AND r.requestee = u.id AND r.item = i.itemid AND r.requester = $userid AND r.decision = true ORDER BY t.tradedate DESC;";

	$trades =[];
	$db = getDBConnection();
	if ($db != NULL){
		$res = $db->query($sql);
		while($res && $row = $res->fetch_assoc()){
			$trades[] = $row;
		}//while
		$db->close();
	}
	/*if ($db != NULL){
		if($db->multi_query($sql)){
			do{
				if($res = $db->store_result()){
					while($row = $res->fetch_row()){
						$trades[] = $row;
					}
					$res->free();
				}
			} while($db->more_results() && $db->next_result());
		} 
	} */
	return $trades;
}

function getTradeHistoryRequests(){
	$userid = $_SESSION['id'];
	$sql = "SELECT *  FROM `requests` r, `trade` t, `users` u, `items` i WHERE r.id = t.requestid AND r.requester = u.id AND r.item2 = i.itemid AND r.requestee = $userid AND r.decision = true AND requesteefeedbackindicator = true AND requesterfeedbackindicator = true ORDER BY t.tradedate DESC;";
	//$sql .= "SELECT t.tradedate, t.tradelocation, i.itemname AS 'requesteritemuserrequestee', u.username AS 'requesterusernameuserrequestee' FROM `requests` r, `trade` t, `users` u, `items` i WHERE r.id = t.requestid AND r.requester = u.id AND r.item2 = i.itemid AND r.requestee = $userid AND r.decision = true ORDER BY t.tradedate DESC;";
	
	$trades =[];
	$db = getDBConnection();
	if ($db != NULL){
		$res = $db->query($sql);
		while($res && $row = $res->fetch_assoc()){
			$trades[] = $row;
		}//while
		$db->close();
	}
	/*if ($db != NULL){
		if($db->multi_query($sql)){
			do{
				if($res = $db->store_result()){
					while($row = $res->fetch_row()){
						$trades[] = $row;
					}
					$res->free();
				}
			} while($db->more_results() && $db->next_result());
		} 
	} */
	return $trades;
}

function getTradeHistoryRequestsUserInfo(){
	$userid = $_SESSION['id'];
	$sql = "SELECT *  FROM `requests` r, `trade` t, `users` u, `items` i WHERE r.id = t.requestid AND r.requestee = u.id AND r.item = i.itemid AND r.requestee = $userid AND r.decision = true AND requesteefeedbackindicator = true AND requesterfeedbackindicator = true ORDER BY t.tradedate DESC;";
	//$sql .= "SELECT t.tradedate, t.tradelocation, i.itemname AS 'requesteritemuserrequestee', u.username AS 'requesterusernameuserrequestee' FROM `requests` r, `trade` t, `users` u, `items` i WHERE r.id = t.requestid AND r.requester = u.id AND r.item2 = i.itemid AND r.requestee = $userid AND r.decision = true ORDER BY t.tradedate DESC;";
	
	$trades =[];
	$db = getDBConnection();
	if ($db != NULL){
		$res = $db->query($sql);
		while($res && $row = $res->fetch_assoc()){
			$trades[] = $row;
		}//while
		$db->close();
	}
	/*if ($db != NULL){
		if($db->multi_query($sql)){
			do{
				if($res = $db->store_result()){
					while($row = $res->fetch_row()){
						$trades[] = $row;
					}
					$res->free();
				}
			} while($db->more_results() && $db->next_result());
		} 
	} */
	return $trades;
}

function getIncomingRequestsHistory(){
	$userid = $_SESSION['id'];
	$sql = "SELECT * FROM `requests` r, `items` i, `users` u WHERE r.requester = u.id AND r.item2 = i.itemid AND r.requestee = $userid AND r.decision IS NOT NULL ORDER BY r.timerequested ASC;";
	
	$incomingRequests =[];
	$db = getDBConnection();
	if ($db != NULL){
		$res = $db->query($sql);
		while($res && $row = $res->fetch_assoc()){
			$incomingRequests[] = $row;
		}//while
		$db->close();
	} 
	
	return $incomingRequests;
}

function getIncomingRequestsHistoryUser(){
	$userid = $_SESSION['id'];
	$sql = "SELECT i.itemname FROM `requests` r, `items` i, `users` u WHERE r.requestee = u.id AND r.item = i.itemid AND r.requestee = $userid AND r.decision IS NOT NULL ORDER BY r.timerequested ASC;";
	
	$incomingRequests =[];
	$db = getDBConnection();
	if ($db != NULL){
		$res = $db->query($sql);
		while($res && $row = $res->fetch_assoc()){
			$incomingRequests[] = $row;
		}//while
		$db->close();
	} 
	
	return $incomingRequests;
}


function getUserFollowersCount($traderId){
	$userid = $_SESSION['id'];
	$sql = "SELECT u.id, u.username FROM `follow` f, `users` u WHERE f.follower = u.id AND f.followee = $traderId
	 AND f.followindicator = true;";
	$updates =[];
	$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
				$updates[] = $row;
		}//while
		$db->close();
	}//if
	return $updates;
}


function getUserTradeCount($traderId){
	$userid = $_SESSION['id'];
	$sql = "SELECT COUNT(*) AS numtrades FROM `requests` r, `trade` t WHERE r.id = t.requestid AND r.requester = $traderId  AND r.decision = true AND t.tradedate < now();";
	$sql .= "SELECT COUNT(*) AS numtrades FROM `requests` r, `trade` t WHERE r.id = t.requestid AND r.requestee = $traderId AND r.decision = true AND t.tradedate < now();";
	$updates =[];
	$db = getDBConnection();
		if ($db != NULL){
			if($db->multi_query($sql) ){
			do{
				if($res = $db->store_result()){
					while($row = $res->fetch_row()){
						$updates[] = $row;
					}
					$res->free();
				}
			} while($db->more_results() && $db->next_result());
		} 
		$db->close();
	}//if
	return $updates;
}

function getAcceptedUserItems(){
	$userid = $_SESSION['id'];
	$sql = "SELECT * FROM `requests` r WHERE r.requestee = $userid OR r.requester = $userid ORDER BY r.decision DESC;";
	$items =[];
	$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
				$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}



function saveItem($picture,$picture2, $picture3, $itemname, $itemDescription){

	$userid =$_SESSION['id'];
	//$sql = "INSERT INTO items(`userId`,`picture`,`itemDescription`) VALUES('userid','$picture','$itemDescription')"; */
	$db = getDBConnection();
	$userId = $_SESSION['id'];
	$sql = "INSERT INTO items(`itemname`, `userid`,`picture`,`picture2`,`picture3`,`itemdescription`) VALUES('$itemname','$userId','$picture','$picture2','$picture3','$itemDescription');";
	$id = -1;
	if ($db != NULL){
		$res = $db->query($sql);
		if ($res && $db->insert_id > 0){
			$id = $db->insert_id;
		}
		$db->close();
	}
	return $id;
}

function getAllUsers(){
	$db = getDBConnection();
	$users = [];
	if ($db != null){
		$sql = "SELECT * FROM `users`";
		$res = $db->query($sql);
		while($res && $row = $res->fetch_assoc()){
			$users[] = $row;
		}
		$db->close();
	}
	return $users;
}

function getCurrentUser(){
	return $_SESSION["id"];
}



function getAllUserItems(){
	$userID = $_SESSION["id"];
	$sql ="SELECT * FROM `items` where `userid` =$userID ORDER BY `uploaddate` DESC;";
	$items =[];
	//print($sql);
		$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
			$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getUserSavedItems(){
	$userID = $_SESSION["id"];
	$sql ="SELECT * FROM `saved` s, `items` i, `users` u WHERE s.itemid = i.itemid AND i.userid = u.id AND s.userid = $userID AND `savedindicator` = true ORDER BY `saveddate` DESC;";
	$items =[];
	//print($sql);
	$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
				$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getUserFollowees(){
	$userID = $_SESSION["id"];
	$sql ="SELECT * FROM `follow` f, `users` u WHERE f.followee = u.id AND f.follower = $userID AND `followindicator` = true ORDER BY `followdate` DESC;";
	$items =[];
	//print($sql);
	$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
				$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getUserFollowers(){
	$userID = $_SESSION["id"];
	$sql ="SELECT * FROM `follow` f, `users` u WHERE f.follower = u.id AND f.followee = $userID AND `followindicator` = true ORDER BY `followdate` DESC;";
	$items =[];
	//print($sql);
	$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
				$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getProfileItems($userID){
	//$userID = $_SESSION["id"];
	$sql ="SELECT * FROM `items` where `userid` = $userID ORDER BY `uploaddate` DESC;";
	$items =[];
	//print($sql);
		$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
			$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getAllUserTrade(){
	$userID = $_SESSION["id"];
	$sql ="SELECT r.viewed, r.requestee, u.username, i.itemid, i.itemname, r.timerequested, r.decision, r.id, r.denyreason, u.profilepicture FROM `requests` r, `items` i, `users` u where r.item = i.itemid AND r.requestee = u.id AND  r.requester = $userID ORDER BY r.timerequested DESC;";
	$items =[];
	//print($sql);
		$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
			$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getOutgoingRequestItems(){
	$userId = $_SESSION["id"];
	$db = getDBConnection();
	$items = [];
	if ($db != null){
		$sql = "SELECT i.itemname, r.item2 FROM `requests` r, `items` i WHERE i.itemid = r.item2 AND r.requester = $userId ORDER BY r.timerequested DESC;";
		$res = $db->query($sql);
		while($res && $row = $res->fetch_assoc()){
			$items[] = $row;
		}
		$db->close();
	}
	//var_dump($requests);
	return $items;
}

function getAcceptedTradeStatus(){
	$userID = $_SESSION["id"];
	$sql ="SELECT t.requesterfeedbackindicator, r.id FROM `requests` r, `trade` t WHERE r.id = t.requestid AND r.requester = $userID ORDER BY r.timerequested DESC;";
	$items =[];
	//print($sql);
		$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
			$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getUserItems($userid){
	//$userID = $_SESSION["id"];
	$sql ="SELECT * FROM `items` i, `users` u where u.id = i.userid AND `userid` = $userid ORDER BY `itemname` ASC;";
	$items =[];
	//print($sql);
		$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
			$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getUserItemsTraded($userid){
	//$userID = $_SESSION["id"];
	$sql ="SELECT * FROM `items` i, `users` u where u.id = i.userid AND `userid` = $userid ORDER BY `itemname` ASC;";
	$items =[];
	//print($sql);
		$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
			$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getAllItems($sort){
	$userID = $_SESSION["id"];
	if($sort == "mra")
		$sql ="SELECT * FROM `items` i, `users` u WHERE i.userid = u.id AND `userid` <> $userID ORDER BY `uploaddate` DESC;";
	else if($sort == "lra")
		$sql ="SELECT * FROM `items` i, `users` u WHERE i.userid = u.id AND `userid` <> $userID ORDER BY `uploaddate` ASC;";
	else if($sort == "mv")
		$sql ="SELECT * FROM `items` i, `users` u WHERE i.userid = u.id AND `userid` <> $userID ORDER BY `views` DESC;";
	else if($sort == "lv")
		$sql ="SELECT * FROM `items` i, `users` u WHERE i.userid = u.id AND `userid` <> $userID ORDER BY `views` ASC;";
	else if($sort == "ia-z")
		$sql ="SELECT * FROM `items` i, `users` u WHERE i.userid = u.id AND `userid` <> $userID ORDER BY `itemname` ASC;";
	else if($sort == "iz-a")
		$sql ="SELECT * FROM `items` i, `users` u WHERE i.userid = u.id AND `userid` <> $userID ORDER BY `itemname` DESC;";
	else if($sort == "ta-z")
		$sql ="SELECT * FROM `items` i, `users` u WHERE i.userid = u.id AND `userid` <> $userID ORDER BY `username` ASC;";
	else if($sort == "tz-a")
		$sql ="SELECT * FROM `items` i, `users` u WHERE i.userid = u.id AND `userid` <> $userID ORDER BY `username` DESC;";

	$items =[];
	//print($sql);
		$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
			$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getAllUserRequests(){
	$userID = $_SESSION["id"];
	$sql ="SELECT * FROM `items` i, `requests` r WHERE i.itemid = r.item AND r.requester = $userID AND i.userid <> $userID  ORDER BY `uploaddate` DESC;";
	$items =[];
	//print($sql);
		$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
			$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getAllNonUserItemsState(){
	$userID = $_SESSION["id"];
	//$sql ="SELECT r.id, r.decision, r.item, r.item2 FROM `items` i, `requests` r, `users` u WHERE i.itemid = r.item AND i.userid = u.id AND i.userid <> $userID  ORDER BY r.timerequested DESC;";
	//$sql = "SELECT * FROM `requests` r WHERE r.requestee = $userID OR r.requester = $userID;";
	$sql ="SELECT r.id, r.decision, r.item, r.item2, i.itemname, r.requester FROM `items` i, `requests` r WHERE i.userid <> $userID  AND i.itemid = r.item OR i.itemid = r.item2 ORDER BY r.timerequested DESC,  r.decision DESC;";

	$items =[];
	//print($sql);
		$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
			$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getAllNonUserItemRequestsForSpecificTrader($traderId){
	$userID = $_SESSION["id"];
	$sql ="SELECT * FROM `requests` r WHERE r.requester = $traderId OR r.requestee = $traderId ORDER BY r.timerequested DESC, r.decision DESC;";
	$items =[];
	//print($sql);
		$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
			$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getTraderProfileItemsTradedStatus($traderId){
	$userID = $_SESSION["id"];
	$sql ="SELECT * FROM `requests` r, `items` i WHERE r.item2 = i.itemid AND r.requester = $traderId AND r.decision = true OR r.item = i.itemid AND r.requestee = $traderId AND r.decision = true ORDER BY r.timerequested DESC;";
	$items =[];
	//print($sql);
		$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
			$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getSearchItems(){
	$userID = $_SESSION["id"];
	$sql ="SELECT * FROM `requests` r WHERE r.decision = true;";
	$items =[];
	//print($sql);
		$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
			$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}

function getUserRating($traderId){
	$userID = $_SESSION["id"];
	//SUM(COUNT(t.requesterfeedbackcomment) + COUNT(t.requesteefeedbackrating))/2
	$sql ="SELECT SUM(t.requesteefeedbackrating)/COUNT(t.requesteefeedbackrating) AS rating FROM `requests` r, `trade` t WHERE r.id = t.requestid AND r.requester = $traderId AND r.decision = true AND t.requesteefeedbackindicator = 1;";
	$sql .="SELECT SUM(t.requesterfeedbackrating)/COUNT(t.requesterfeedbackrating) as Rating FROM `requests` r, `trade` t WHERE r.id = t.requestid AND r.requestee = $traderId AND r.decision = true AND t.requesterfeedbackindicator = 1;";

	/*$items =[];
	//print($sql);
		$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
			$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items; */
	$rating =[];
	$db = getDBConnection();
	if ($db != NULL){
		if($db->multi_query($sql) ){
			do{
				if($res = $db->store_result()){
					while($row = $res->fetch_row()){
						$rating[] = $row;
					}
					$res->free();
				}
			} while($db->more_results() && $db->next_result());
		} 
	}//if
	return $rating;
}

function getRequesterItemsState(){
	$userID = $_SESSION["id"];
	$sql ="SELECT r.id, r.requester, r.decision, r.item FROM `items` i, `requests` r, `users` u WHERE i.itemid = r.item2 AND i.userid = u.id AND i.userid <> $userID  ORDER BY r.timerequested DESC;";
	$items =[];
	//print($sql);
		$db = getDBConnection();
		if ($db != NULL){
			$res = $db->query($sql);
			while($res && $row = $res->fetch_assoc()){
			$items[] = $row;
		}//while
		$db->close();
	}//if
	return $items;
}



function getUserItem($val){
	$db = getDBConnection();
	$rec = null;
	if ($db != null){
		$sql = "SELECT * FROM items WHERE itemid = $val;";
		$res = $db->query($sql);
		if ($res){
			$rec = $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function getUserDetails($userId){
	$db = getDBConnection();
	$rec = null;
	if ($db != null){
		$sql = "SELECT u.username, u.firstname, u.lastname, u.email, u.telephone FROM `users` u WHERE u.id = $userId;";
		$res = $db->query($sql);
		if ($res){
			$rec = $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function getUserInfo($userId){
	$db = getDBConnection();
	$rec = null;
	if ($db != null){
		$sql = "SELECT u.username, u.firstname, u.lastname FROM `users` u WHERE u.id = $userId;";
		$res = $db->query($sql);
		if ($res){
			$rec = $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function getProfilePicture($userId){
	$db = getDBConnection();
	$rec = null;
	if ($db != NULL){
		$sql = "SELECT `profilepicture` FROM `users` u WHERE u.id = $userId;";
		$res = $db->query($sql);
		if ($res){
			$rec= $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function getProfilePictureNavBar($userId){
	$db = getDBConnection();
	$rec = null;
	if ($db != NULL){
		$sql = "SELECT `profilepicture` FROM `users` u WHERE u.id = $userId;";
		$res = $db->query($sql);
		if ($res){
			$rec= $res->fetch_assoc();
		}
		$db->close();
	}
	$rec = json_encode($rec['profilepicture']);
	return $rec;
}

function getItemImages($itemId){
	$db = getDBConnection();
	$rec = null;
	if ($db != null){
		$sql = "SELECT  i.picture, i.picture2, i.picture3 FROM `items` i WHERE i.itemid = $itemId;";
		$res = $db->query($sql);
		if ($res){
			$rec = $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}



function getRequesterInfo($requestId){
	$db = getDBConnection();
	$rec = null;
	if ($db != null){
		$sql = "SELECT `username`, `itemname`, `requestercontact`, i.itemid FROM `requests` r, `items` i, `users` u WHERE r.id = $requestId AND r.requester = u.id AND r.item2 = i.itemid;" ;
		$res = $db->query($sql);
		if ($res){
			$rec = $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function getRequesteeInfo($requestId){
	$db = getDBConnection();
	$rec = null;
	if ($db != null){
		$sql = "SELECT i.itemname, i.itemid, u.telephone FROM `requests` r, `items` i, `users` u WHERE r.id = $requestId AND r.requestee = u.id AND r.item = i.itemid;" ;
		$res = $db->query($sql);
		if ($res){
			$rec = $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function getUsername($val){
	$db = getDBConnection();
	$rec = null;
	if ($db != null){
		$sql = "SELECT `username`,`firstname`,`lastname`, `status` FROM `users` WHERE id = $val;";
		$res = $db->query($sql);
		if ($res){
			$rec = $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}


function getRequesterItem(){
	$userId = $_SESSION["id"];
	$db = getDBConnection();
	$requests = [];
	if ($db != null){
		$sql = "SELECT i.itemname, i.itemid FROM `requests` r, `items` i WHERE i.itemid = r.item2 AND r.requestee = $userId AND `decision` IS NULL ORDER BY r.timerequested DESC;";
		$res = $db->query($sql);
		while($res && $row = $res->fetch_assoc()){
			$requests[] = $row;
		}
		$db->close();
	}
	//var_dump($requests);
	return $requests;
}



function getRequests(){
	$user = $_SESSION["id"];
	$db = getDBConnection();
	$requests = [];
	if ($db != null){
		$sql = "SELECT u.username, r.id, r.requester, i.itemname, u.profilepicture FROM `users` u, `requests` r, `items` i WHERE r.requester = u.id AND i.itemid = r.item AND r.requestee = $user AND `decision` IS NULL ORDER BY r.timerequested DESC;";
		$res = $db->query($sql);
		while($res && $row = $res->fetch_assoc()){
			$requests[] = $row;
		}
		$db->close();
	}
	//var_dump($requests);
	return $requests;
}

function getRequestDetails($requestid){
	$user = $_SESSION["id"];
	$db = getDBConnection();
	$rec = null;
	if ($db != null){
		$sql = "SELECT * FROM `requests` r, `items` i, `users` u WHERE r.requester = u.id AND i.itemid = r.item2 AND r.requestee = $user AND r.id = $requestid;";
		$res = $db->query($sql);
		if ($res){
			$rec = $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}
function getDecisions(){
	$user = $_SESSION["id"];
	$db = getDBConnection();
	$requests = [];
	if ($db != null){
		$sql = "SELECT r.id, i.itemname, r.decision, r.viewed FROM `users` u, `requests` r, `items` i WHERE r.requester = u.id AND i.itemid = r.item AND r.requester = $user AND `decision` IS NOT NULL ORDER BY i.itemname ASC;";
		$res = $db->query($sql);
		while($res && $row = $res->fetch_assoc()){
			$requests[] = $row;
		}
		$db->close();
	}
	//var_dump($requests);
	return $requests;
}

function getDecisionsRequesteeUsername(){
	$user = $_SESSION["id"];
	$db = getDBConnection();
	$requests = [];
	if ($db != null){
		$sql = "SELECT u.username, u.profilepicture FROM `users` u, `requests` r, `items` i WHERE r.requestee = u.id AND i.itemid = r.item AND r.requester = $user AND `decision` IS NOT NULL ORDER BY i.itemname ASC;";
		$res = $db->query($sql);
		while($res && $row = $res->fetch_assoc()){
			$requests[] = $row;
		}
		$db->close();
	}
	//var_dump($requests);
	return $requests;
}


function getItemOwner($itemid){
	$db = getDBConnection();
	$user = null;
	if ($db != NULL){
		$sql = "SELECT * FROM `users` u, `items` i WHERE i.userid = u.id AND i.itemid = '$itemid';";
		$res = $db->query($sql);
		if ($res){
			$user = $res->fetch_assoc();
		}
		$db->close();
	}
	return $user;
} 

function getRequesteeId($requestee){
	$db = getDBConnection();
	$rec = null;
	if ($db != NULL){
		$sql = "SELECT `id` FROM `users` WHERE username = '$requestee';";
		$res = $db->query($sql);
		if ($res){
			$rec = $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function getItemId($item){
	$db = getDBConnection();
	$rec = null;
	if ($db != NULL){
		$sql = "SELECT `itemid` FROM `items` WHERE itemname = '$item';";
		$res = $db->query($sql);
		if ($res){
			$rec= $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function getItem($itemid){
	$db = getDBConnection();
	$rec = null;
	if ($db != NULL){
		$sql = "SELECT * FROM `items` i WHERE i.itemid = $itemid;";
		$res = $db->query($sql);
		if ($res){
			$rec= $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function getItemRequestForCurrentUser($itemid){
	$userId = $_SESSION["id"];
	$db = getDBConnection();
	$rec = null;
	if ($db != NULL){
		$sql = "SELECT * FROM `requests` r WHERE r.item = $itemid  AND r.requester = $userId ORDER BY r.timerequested DESC LIMIT 1;";
		$res = $db->query($sql);
		if ($res){
			$rec = $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function checkItemSaved($itemid){
	$userId = $_SESSION["id"];
	$db = getDBConnection();
	$rec = null;
	if ($db != NULL){
		$sql = "SELECT * FROM `saved` s WHERE s.itemid = $itemid AND s.userid = $userId;";
		$res = $db->query($sql);
		if ($res){
			$rec= $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}



function getProfileImage($userid){
	$db = getDBConnection();
  	$rec = null;
	if ($db != NULL){
		$sql = "SELECT `profilepicture` FROM `users` WHERE id = '$userid';";
	    $res = $db->query($sql);
	    if ($res){
	    	$rec= $res->fetch_assoc();
	    }
	    $db->close();
	}
	$pp =  json_encode($rec['profilepicture']);
	return "<img src= $pp style='width:auto; height: auto; max-width: 150px; border-radius: 20px;text-shadow: 2px 2px black' class='img-responsive img-thumbnail mx-auto'>"; 
}

function saveRequest($requestee, $requesteeItem, $requesterItem, $requesterContact){
	//$owner = getItemOwner($itemid);
	$requesteeId = getRequesteeId($requestee);
	$requesteeItemId = getItemId($requesteeItem);
	$rId = $requesteeId['id'];
	$iId = $requesteeItemId['itemid'];
	//echo ($requesteeId['id']);
	$db = getDBConnection();
	$requester = $_SESSION['id'];
	$sql = "INSERT INTO `requests` (`requester`,`item2`,`requestercontact`, `requestee`,`item`) VALUES($requester,$requesterItem, '$requesterContact', $rId,$iId);";
	$id = -1;
	if ($db != NULL){
		$res = $db->query($sql);
		if ($res && $db->insert_id > 0){
			$id = $db->insert_id;
		}
		$db->close();
	}
	return $id;
} 

function getItemStatus($itemId){
	$db = getDBConnection();
	$rec;
	if ($db != NULL){
		$sql = "SELECT * FROM `requests` r WHERE r.item = $itemId OR r.item2 = $itemId;";
		$res = $db->query($sql);
		if ($res){
			$rec= $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function getItemTradedStatus($itemId){
	$db = getDBConnection();
	$rec = null;
	if ($db != NULL){
		$sql = "SELECT * FROM `requests` r WHERE r.decision = true AND r.item = $itemId OR r.item2 = $itemId AND r.decision = true;";
		$res = $db->query($sql);
		if ($res){
			$rec= $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function getItemRequestDeniedStatus($itemId){
	$userId = $_SESSION["id"];
	$db = getDBConnection();
	$rec = null;
	if ($db != NULL){
		$sql = "SELECT r.id, r.decision FROM `requests` r WHERE r.item = $itemId AND r.requester = $userId AND r.viewed = false ORDER BY r.timerequested DESC;";
		$res = $db->query($sql);
		if ($res){
			$rec= $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function getItemRequestDeniedStatusTrader($traderId){
	$userId = $_SESSION["id"];
	$db = getDBConnection();
	$deniedRequests = [];
	if ($db != NULL){
		$sql = "SELECT r.item, r.decision, i.itemname FROM `requests` r, `items` i WHERE r.item = i.itemid AND r.requestee = $traderId AND r.requester = $userId AND r.viewed = false ORDER BY r.timerequested DESC;";
		$res = $db->query($sql);
		while($res && $row = $res->fetch_assoc()){
			$deniedRequests[] = $row;
		}
		$db->close();
	}
	return $deniedRequests;
}

function getItemRequestDeniedStatusSearch(){
	$userId = $_SESSION["id"];
	$db = getDBConnection();
	$deniedRequests = [];
	if ($db != NULL){
		$sql = "SELECT r.item, r.decision, i.itemname FROM `requests` r, `items` i WHERE r.item = i.itemid AND r.requester = $userId AND r.viewed = false ORDER BY r.timerequested DESC;";
		$res = $db->query($sql);
		while($res && $row = $res->fetch_assoc()){
			$deniedRequests[] = $row;
		}
		$db->close();
	}
	return $deniedRequests;
}

//In progress
function getRequestStatus($itemId){
	$db = getDBConnection();
	$pending = [];
	if ($db != NULL){
		$sql = "SELECT COUNT(*) AS pending FROM `requests` r WHERE r.item = $itemId AND r.decision IS NULL;";
		$sql .= "SELECT COUNT(*) AS pending2 FROM `requests` r WHERE r.item2 = $itemId AND r.decision IS NULL;";
		$res = $db->query($sql);
		
		if($db->multi_query($sql)){
			do{
				if($res = $db->store_result()){
					while($row = $res->fetch_row()){
						$pending[] = $row;
					}
					$res->free();
				}
			} while($db->more_results() && $db->next_result());
		} 
	}
	return $pending;
}

function deleteItem($itemid){
	$db = getDBConnection();
	$sql = "DELETE FROM `items` WHERE itemid = $itemid";
	$res = null;
	if ($db != NULL){
		$res = $db->query($sql);
		$db->close();
	}
	return $res;


} 

function cancelRequest($requestId){
	$db = getDBConnection();
	$sql = "DELETE FROM `requests`  WHERE  `id` = $requestId;";
	$res = null;
	if ($db != NULL){
		$res = $db->query($sql);
		$db->close();
	}
	return $res;
} 

function acceptRequest($requestId, $requesteeItem, $requesterItem){
	$db = getDBConnection();
	$sql = "UPDATE `requests` r SET r.decision = true WHERE r.id = $requestId;";
	$sql .= "UPDATE `requests` r SET r.decision = false, r.denyreason = 'Trader accepted another request, sorry' WHERE r.item = $requesteeItem AND r.id <> $requestId AND r.denyreason IS NULL;";
	$sql .= "UPDATE `requests` r SET r.decision = false, r.denyreason = 'Trader accepted another request, sorry' WHERE r.item = $requesterItem AND r.id <> $requestId AND r.denyreason IS NULL;";
	$sql .= "UPDATE `requests` r SET r.decision = false, r.denyreason = 'Trade already accepted for your item, sorry' WHERE r.item2 = $requesterItem AND r.id <> $requestId AND r.denyreason IS NULL;";
	$sql .= "DELETE FROM `saved`  WHERE  `itemid` = $requesteeItem OR `itemid` = $requesterItem;";
	$sql .= "DELETE FROM `requests`  WHERE  `item2` = $requesteeItem AND `id` <> $requestId;";
	$res = null;
	if ($db != NULL){
		$res = $db->multi_query($sql);
	}
	$db->close();

	return $res;
}

function denyRequest($requestId, $denyReason){
	$db = getDBConnection();
	$sql = "UPDATE `requests` r SET `decision` = false, `denyreason` = '$denyReason' WHERE r.id = $requestId;";
	$res = null;
	if ($db != NULL){
		$res = $db->query($sql);
		$db->close();
	}
	return $res;
}

function changeTradeDate($tradeId, $newTradeDate){
	$db = getDBConnection();
	$sql = "UPDATE `trade` t SET `tradedate` = '$newTradeDate' WHERE t.tradeid = $tradeId;";
	$res = null;
	if ($db != NULL){
		$res = $db->query($sql);
		$db->close();
	}
	return $res;
}

function changeTradeLocation($tradeId, $newTradeLocation){
	$db = getDBConnection();
	$sql = "UPDATE `trade` t SET `tradelocation` = '$newTradeLocation' WHERE t.tradeid = $tradeId;";
	$res = null;
	if ($db != NULL){
		$res = $db->query($sql);
		$db->close();
	}
	return $res;
}


function checkViewedStatus($requestId){
	$db = getDBConnection();
	$rec=null;
	if ($db != NULL){
		$sql = "SELECT r.viewed FROM `requests` r WHERE r.id = $requestId;";
		$res = $db->query($sql);
		if ($res){
			$rec= $res->fetch_assoc();
		}
		$db->close();
	}
	return $rec;
}

function setRequestToViewed($requestId){
	$db = getDBConnection();
	$isViewed = checkViewedStatus($requestId);
	$res = null;
	if($isViewed == '1'){
		return $res;
	}
	else{
		$sql = "UPDATE `requests` r SET `viewed` = true WHERE r.id = $requestId;";
		if ($db != NULL){
			$res = $db->query($sql);
			$db->close();
		}
		return $res;
	} 
}

function productViews($itemid){
	$sql = "UPDATE `items` i SET `views` = `views`+1 WHERE i.itemid = $itemid";
    $res = null;
    $db = getDBConnection();
    if ($db != NULL){
      $res = $db->query($sql);
      $db->close();
    }
  	return $res;
}

function updatePassword($password){
	$password = sha1($password);
	$user = $_SESSION["user"];
	$sql = "UPDATE `users` SET `password` = '$password' WHERE `username` = '$user' OR `email` = '$user'";
    $res = null;
    $db = getDBConnection();
    if ($db != NULL){
		
      $res = $db->query($sql);
      $db->close();
    }
	//echo $res;
  	return $res;
}

function getGraphData(){
	$user = $_SESSION["id"];
	$db = getDBConnection();
	$data = [];
	if ($db != null){
		$sql = "SELECT `itemname`, `views` FROM `items` WHERE `userid` <> $user ORDER BY views DESC LIMIT 5";
		$res = $db->query($sql);
		while($res && $row = $res->fetch_assoc()){
			$data[] = $row;
		}
		$db->close();
	}
	return $data;

}


?>