<?php
include "../lib.php";
include "base.php";

?>

<div class="container-fluid">
  <div class="row">

    <div id = "searchitemblock" class="">

<?php
	$items = [];
	$name = "";
	$requests = [];
	$filterUsers = [];
	$user = $_SESSION["id"];
	$currSearchItems = [];
	if(isset($_POST['searchsubmit'])){ 
		if(isset($_GET['go'])){ 
		  	if(preg_match("/^[  a-zA-Z]+/", $_POST['searchname'])){ 
			 		$name=$_POST['searchname'];
			 		$type=$_POST['search_param'];			 		

			  //-query  the database table
			  		$db=getDBConnection();

			  		$sql="SELECT  itemid, itemname, itemdescription, userid, picture, username, views FROM items, users WHERE $user <> `userid` AND itemname LIKE '%" . $name .  "%' && items.userid = users.id "; 
			  //-run  the query against the mysql query function 
			  		$result=$db->query($sql); 
			  //-create  while loop and loop through result set 
			  		while($result && $row = $result->fetch_assoc()){ 
			          $items[]  =$row;
			   		}

			  		$sql="SELECT  username, firstname, lastname, profilepicture, id FROM users WHERE username LIKE '%" . $name .  "%' OR firstname LIKE '%" . $name .  "%' OR lastname LIKE '%" . $name .  "%' "; 
			  //-run  the query against the mysql query function 
			  		$result=$db->query($sql); 
			  //-create  while loop and loop through result set 
			  		while($result && $row = $result->fetch_assoc()){ 
			          $filterUsers[]  = $row;
			   		}

			   		$sql ="SELECT r.id, r.decision, r.item, r.item2, i.itemname, r.requester FROM `items` i, `requests` r WHERE i.userid <> $user  AND i.itemid = r.item OR i.itemid = r.item2 ORDER BY r.timerequested DESC,  r.decision DESC;";

			   		$result=$db->query($sql); 
			  //-create  while loop and loop through result set 
			  		while($result && $row = $result->fetch_assoc()){ 
			        	$requests[] = $row;
			   		}

			   		$user = getCurrentUser();
					echo "<h2 class='page-header text-center'> Showing results for \"".$_POST['searchname']."\" </h2>";

			if($items != null){
				if($type == "item"){
			   		for($i = 0; $i < count($items); $i++){
			  //-display the result of the array 
			   				//$ID = $val['userid'];
			   				$item = $items[$i];
			   				for($j = 0; $j < count($requests); $j++){
			   					$request = $requests[$j];
			   					if($request['item'] == $item['itemid'] || $request['item2'] == $item['itemid']){
			   						if($request['decision'] == true){
			   							break;
			   						}//end if($request['decision'] == true)
			   						else{
			   							if($request['requester'] == $user){
			   								echo "<div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>";
			   								echo "<div class='panel panel-warning'>";

				            				echo "<div class='panel-heading text-center'><button style='text-decoration:none; type='button' class='btn btn-link' onclick=\"viewItem(".$item['itemid'].")\"><strong>". $item['itemname'] . "</strong> </button><br><small> Views: ".$item['views']." </small><br><button style='color:black;text-decoration:none;' type='button' class='btn btn-default btn-xs' onclick=\"viewTraderProfile(".$item['userid'].")\"> <strong> by ". $item['username'] ."</strong></button></div>";
				          
				            				echo "<div class='panel-body'> <div class='text-center'> </div><img style='cursor: pointer;width:100%;' onclick=\"viewItem(".$item['itemid'].")\" src=\"" . $item['picture'] . "\"  class='img-responsive img-thumbnail mx-auto'> </div>";

				                			if($request['decision']==null){
				                				echo "<div class='panel-footer'> <div class='row'><div class='col-xs-12'><button type='button' class='btn btn-danger btn-block active' onclick=\"cancelMadeRequest(".$request['id'].")\" id='requestbtn'><i class='fa fa-ban fa-lg' aria-hidden='true'></i> Cancel Request</button> </div></div></div>";
				                			}
				                			else{
				                				echo "<div class='panel-footer'> <div class='row'><div class='col-xs-12'><button type='button' class='btn btn-primary btn-block active' onclick=\"displayItemsForRequest(".$item['itemid'].")\" id='requestbtn'><i class='fa fa-cart-plus fa-lg' aria-hidden='true'></i> Make Request</button> </div></div></div>";
				                			}
				             
				            				echo "</div>";
				            				echo "</div>";
				            				$currSearchItems[] = $item;
				            				break;
			   							}//end if($request['requester'] == $user)
			   						}//end else
			   					}//end if($request['item'] == $item['itemid'] || $request['item2'] == $item['itemid'])
			   				}//end for($j = 0; $j < count($requests); $j++)

			   				if($j == count($requests)){
			   					echo "<div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>";
			   					echo "<div class='panel panel-info'>";

				            	echo "<div class='panel-heading text-center'><button style='text-decoration:none; type='button' class='btn btn-link' onclick=\"viewItem(".$item['itemid'].")\"><strong>". $item['itemname'] . "</strong> </button><br><small> Views: ".$item['views']." </small><br><button style='color:black;text-decoration:none;' type='button' class='btn btn-default btn-xs' onclick=\"viewTraderProfile(".$item['userid'].")\"> <strong> by ". $item['username'] ."</strong></button></div>";
				          
				            	echo "<div class='panel-body'> <div class='text-center'> </div><img style='cursor: pointer;width:100%;' onclick=\"viewItem(".$item['itemid'].")\" src=\"" . $item['picture'] . "\"  class='img-responsive img-thumbnail mx-auto'> </div>";
				                
				            	echo "<div class='panel-footer'> <div class='row'><div class='col-xs-12'><button type='button' class='btn btn-primary btn-block active' onclick=\"displayItemsForRequest(".$item['itemid'].")\" id='requestbtn'><i class='fa fa-cart-plus fa-lg' aria-hidden='true'></i> Make Request</button> </div></div></div>";
				             
				            	echo "</div>";
				            	echo "</div>";
				            	$currSearchItems[] = $item;
			   				}//end ($j == count($requests))
			          		
		    		}//end for($i = 0; $i < count($items); $i++)
				}//end if($type == "item")
			}//end if($items != null)

			if($filterUsers != null){
					if($type == "user"){
				   			for($i = 0; $i < count($filterUsers); $i++) {
				  //-display the result of the array 
				   				$filterUser = $filterUsers[$i];
						if($filterUser['id'] != $user){
									echo "<div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>";
				   					echo "<div class='panel panel-default'>";

					            	echo "<div class='panel-heading text-center'><button style='text-decoration:none; color:black' type='button' class='btn btn-link' onclick=\"viewTraderProfile(".$filterUser['id'].")\"><strong>". $filterUser['firstname'] ." ".$filterUser['lastname']." (".$filterUser['username'].")".  "</strong> </button><br></div>";
					          
					            	echo "<div class='panel-body'> <div class='text-center'> </div><img style='cursor: pointer;width:100%;' onclick=\"viewTraderProfile(".$filterUser['id'].")\" src=\"" . $filterUser['profilepicture'] . "\"  class='img-responsive img-thumbnail mx-auto'> </div>";
					             	echo "</div>";
					            	echo "</div>";
						}//end if($filterUsers['id'] != $user)
							}//end	for($i = 0; $i < count($filterUsers); $i++)	
					}//end if($type == "user")

			}//end if($filterUsers == null)

		  		if($type == "item"){
					if($items == null){ 
						echo  "<div class='col-lg-6 col-lg-offset-3'><img src=../img/noresults.jpg style='width:100%; border-radius: 50px;' class='img-responsive img-thumbnail mx-auto'></div>"; 	  
					}else{}//end if($filterUsers == null)

		  		}

		  		if($type == "user"){
					if($filterUsers == null){ 
						echo  "<div class='col-lg-6 col-lg-offset-3'><img src=../img/noresults.jpg style='width:100%; border-radius: 50px;' class='img-responsive img-thumbnail mx-auto'></div>"; 	  
					}else{}//end if($filterUsers == null)

		  		}
		  			 

		  	}//end if(preg_match("/^[  a-zA-Z]+/", $_POST['searchname']))
		}//end 	if(isset($_GET['go']))
	}//end if(isset($_POST['searchsubmit']))
?>




      </div>
     <div class="col-xs-2">
    
    </div>

  </div>
</div>

<script>

var search = <?php echo json_encode($_POST['searchname']) ?>;
var currItems = <?php echo json_encode($currSearchItems) ?>;
var currDeniedRequests = <?php echo json_encode(getItemRequestDeniedStatusSearch()) ?>;


setInterval(function(){
  querySearchItemsChange(search, currItems);
  queryDeniedRequestsChange(currItems);
},2500);


function querySearchItemsChange(search, currItems){
  //console.log(userItems);
  //$.get("../index.php/items/"+traderId, function(items){
    $.get("../index.php/search/"+search, function(results){
      //console.log(itemsRequests);
      //console.log(currItems);
      currItems.forEach(function(el){
        for(var i =0; i < results.length; i++){
          if(el['itemid'] == results[i]['item'] || el['itemid'] == results[i]['item2'])
            itemChange(el['itemname']);
        }
      });
    },"json");
  //},"json");

}

function queryDeniedRequestsChange(currItems){
	$.get("../index.php/itemsdeniedstatussearch", function(results){
		//console.log(results);
		currItems.forEach(function(el){
	        for(var i =0; i < results.length; i++){
	          if(el['itemid'] == results[i]['item'] && results[i]['decision'] == false)
	            deniedResponse(results[i]['itemname']);
	        }
		});
    },"json");
}

function itemChange(itemName){
  swal({ 
        title: "Sorry,\"" +itemName+"\" has been traded",
        text: "Page shall be refreshed",
        type: "warning",
        timer: 2000,
        showConfirmButton: false
        },
        function(){
            window.location.reload();
        }
    );      
}

function deniedResponse(itemName){
    swal({ 
        title: itemName +" Request was Denied!",
        text: "Redirecting to Outgoing Requests to view reason",
        type: "warning",
        timer: 2000,
        showConfirmButton: false
    },
        function(){
            window.location.href = 'trade.php';
        }
    );       
}

</script>
