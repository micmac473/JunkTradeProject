"use strict";
console.log("hello I'm connected to the world");

//var base_url = "base.php/api";


(function( $ ) {

    //Function to animate slider captions 
    function doAnimations( elems ) {
        //Cache the animationend event in a variable
        var animEndEv = 'webkitAnimationEnd animationend';
        
        elems.each(function () {
            var $this = $(this),
                $animationType = $this.data('animation');
            $this.addClass($animationType).one(animEndEv, function () {
                $this.removeClass($animationType);
            });
        });
    }
    
    //Variables on page load 
    var $myCarousel = $('#carousel-example-generic'),
        $firstAnimatingElems = $myCarousel.find('.item:first').find("[data-animation ^= 'animated']");
        
    //Initialize carousel 
    $myCarousel.carousel();
    
    //Animate captions in first slide on page load 
    doAnimations($firstAnimatingElems);
    
    //Pause carousel  
    $myCarousel.carousel('pause');
    
    
    //Other slides to be animated on carousel slide event 
    $myCarousel.on('slide.bs.carousel', function (e) {
        var $animatingElems = $(e.relatedTarget).find("[data-animation ^= 'animated']");
        doAnimations($animatingElems);
    });  
    
})(jQuery);

//*************************************************************NEW

$(document).ready(function(){
    console.log("All Elements in the Page was successfully loaded, we can begin our application logic");
    getTrade();
    getData();
    getAllItems();
    getUserRequests();
    getDecisions();
    getUserItems();
    $('[data-toggle="tooltip"]').tooltip();   
    
    //alert($('#requests > li').length);
});  
// this acts as the main function in Java



// Angular App
var app = angular.module("myapp", ['ngRoute']);
app.config(["$routeProvider",function($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl : "main.html",
        })// no semicolon, this is called method chaining. 
        .when("/addItem",{
            templateUrl : "views/addItem.html",
            controller: "addController"
        })

}]);

app.controller('mainController', ['$scope', 
    function($scope){
    console.log("Main Controller Executed");
}]);
// takkes two parameters name of conteroller and behaviour of the conteroller
 
 //--------------------------------------------------------------------------------------------------------------------
 // Log in functionality
function login(){
    console.log("Hi");
    var email = $("#email").val();
    var password = $("#password").val();
    //console.log(email + " " + pass);
    var user = {
        "email" : email,
        "password": password
    }

    console.log(user);
    $.post("../index.php/login", user, function(res){
        console.log(res);
        if(res != 400){
            //console.log(res);
            swal({ 
                title: "Welcome " + res,
                text: "You have logged in successfully",
                type: "success" 
            },
                function(){
                    window.location.href = 'homepage.php';
            });
            //window.location.href="homepage.php";
            //return false;
        }
        else{
            swal("Incorrect Login","Please try again","error")
            //return false;
        }
    },"json");
    console.log("Logged In");
    return false;
}
//--------------------------------------------------------------------------------------------------------------------
 // Password Reset functionality
function login1(){
    console.log("Hi");
    var email = $("#email").val();
    var sAnswer = $("#sAnswer").val();
    //console.log(email + " " + pass);
    var user = {
        "email" : email,
        "sAnswer": sAnswer
    }

    console.log(user);
    $.post("../index.php/login1", user, function(res){
        console.log(res);
        if(res != 400){
            //console.log(res);
            swal({ 
                title: "Success " + res,
                text: "You have reset your password",
                type: "" 
            },
                function(){
                    window.location.href = 'login.php';
            });
            //window.location.href="homepage.php";
            //return false;
        }
        else{
            swal("Incorrect Security Answer","Please try again","error")
            //return false;
        }
    },"json");
    console.log("Password Reset");
    return false;
}
//--------------------------------------------------------------------------------------------------------------------
// Registration functionality
function register(){
    console.log("Hi");
    var username = $("#username").val();
    var firstname = $("#firstname").val();
    var lastname = $("#lastname").val();
    var email = $("#email").val();
    //var contact = $("#contact").val();
    //var address = $("#address").val();
    var password = $("#password").val();
    var retypedpassword = $("#retypedpassword").val();
    var securityQuestion = $("#securityquestion").val();
    var securityAnswer = $("#securityanswer").val();
    /*if(password != retypedpassword){
        alert("Password do not match");
        return false;
    } */

    var regUser = {
        "username" : username,
        "firstname" : firstname,
        "lastname" : lastname,
        "email" : email,
        "password" : password,
        "securityquestion" : securityQuestion,
        "securityanswer" : securityAnswer
    };

    $.post("../index.php/register", regUser, function(res){
        if(res){
            console.log(res);
            swal({ 
                title: "Registration Complete!",
                text: "Proceed to login",
                type: "success" 
            },
                function(){
                    window.location.href = 'login.php';
            });
            //window.location.href="homepage.php";
            //return false;
        }
        else{
            swal("Incorrect Login","Please try again","error")
            //return false;
        }
    },"json");

    return false;
}

//--------------------------------------------------------------------------------------------------------------------
//Dsiplay All items available (except user items) on homepage
function getAllItems(){//alter for slim 
    $.get("../index.php/homepage", processAllItems, "json");
}

function processAllItems(records){
    console.log(records);
    listAllItems(records)
}

function listAllItems(records){
    var itemdiv = "<div>";
    records.forEach(function(el){
        //htmlStr += "<tr>";
        //htmlStr += "<td><img style='cursor: pointer' onclick=\"views("+el.itemid+"); window.open(this.src)\" src=\"" + el['picture'] + "\" width=\"150\" height=\"128\"></td>";
        itemdiv += "<div class='panel panel-default'>";
        itemdiv += "<div class='panel-heading'> <button type='button' class='btn btn-link' onclick=\"viewTraderProfile("+el.userid+")\">" +  el['username'] + "</button> <span style='float:right'> <em> Uploaded on: "+  el['uploaddate'] +"</em></span></div>"; 
        //itemdiv += "<div class='panel-heading'> Uploaded on: "+  el['uploaddate'] + "</div>"; 
        itemdiv += "<div class='panel-heading text-center lead'><strong>"+  el['itemname'] + "</strong></div>"; 
        itemdiv += "<div class='panel-body'> <img style='cursor: pointer;width:100%;' onclick=\"viewItem("+el.itemid+")\" src=\"" + el['picture'] + "\"  class='img-responsive img-thumbnail mx-auto'> </div>";
        //itemdiv += "<div class='panel-footer'> <a href='item.php' class='btn btn-info btn-block'><span class='glyphicon glyphicon-eye-open'></span> View more....</a> </div>"; 
        itemdiv += "<div class='panel-footer'> <div class='row'><div class='col-lg-4'><button type='button' class='btn btn-success btn-block' onclick=\"displayItemsForRequest("+el.itemid+")\" id='requestbtn'><i class='fa fa-cart-plus fa-lg' aria-hidden='true'></i> Make Request</button> </div><div class='col-lg-4'><button type='button' class='btn btn-info btn-block' onclick=\"viewItem("+el.itemid+")\"><i class='fa fa-eye fa-lg' aria-hidden='true'></i> View more</button> </div> <div class='col-lg-4'> <button type='button' class='btn btn-warning btn-block' onclick=\"addToSavedItems("+el.itemid+")\"><i class='fa fa-bookmark fa-lg' aria-hidden='true'></i> Save</button></div></div></div>";
        itemdiv += "</div>";
        /*
        htmlStr += "<td><img style='cursor: pointer' onclick=\"views("+el.itemid+")\" src=\"" + el['picture'] + "\" width=\"150\" height=\"128\"></td>";
        htmlStr += "<td>"+ el['itemname'] +"</td>";
        htmlStr += "<td>"+ el['views'] +"</td>";
        htmlStr += "<td>"+ el['itemdescription'] +"</td>";
        htmlStr += "<td>"+ el['username'] +"</td>";
        //htmlStr += "<td><button type='button' class='btn btn-primary' data-toggle='modal' data-target='#requestModal' id='requestbtn'><i class='fa fa-cart-plus' aria-hidden='true'></i></button></td>";
        htmlStr += "<td><button type='button' class='btn btn-primary' onclick=\"displayItemsForRequest("+el.itemid+")\" id='requestbtn'><i class='fa fa-cart-plus' aria-hidden='true'></i></button>";
        //htmlStr += "<button type='button' class='btn btn-info' ><i class='fa fa-eye' aria-hidden='true'></i></button></td>";
        htmlStr += "<td>" + el['uploaddate'] + "</td>";
        htmlStr +=" </tr>" ; */
        
        
    });
    $("#itemblock").html(itemdiv);
    //htmlStr += "</tbody></table>";
    //$(sec_id).html(htmlStr);
    
}
//--------------------------------------------------------------------------------------------------------------------

//Dsiplay All user items on profile
function getUserItems(){//alter for slim 
    $.get("../index.php/profile", processUserItems, "json");
}

function processUserItems(records){
    console.log(records);
    listUserItems(records)
}

function listUserItems(records){
    var key;
    var sec_id = "#table_secp";
    var htmlStr = $("#table_headingp").html(); //Includes all the table, thead and tbody declarations

    records.forEach(function(el){
        htmlStr += "<tr>";
        htmlStr += "<td style='display:none;'>"+ el['itemid'] +"</td>";
        htmlStr += "<td><img src=\"" + el['picture'] + "\" width=\"150\" height=\"128\"></td>";
        htmlStr += "<td>"+ el['itemname'] +"</td>";
        htmlStr += "<td>"+ el['itemdescription'] +"</td>";
        htmlStr += "<td><button type='button' class='btn btn-primary' onclick =\"showUpdateForm("+el.itemid+")\"><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button> ";
        htmlStr += "<button type='button' class='btn btn-danger' onclick=\"deleteItem("+el.itemid+")\"><i class='fa fa-trash' aria-hidden='true'></i></button></td>";
        htmlStr += "<td>" + el['uploaddate'] + "</td>";
        htmlStr +=" </tr>" ;
    });
    //count = $("#mylist li").size();
    htmlStr += "</tbody></table>";
    $(sec_id).html(htmlStr);
} 

//--------------------------------------------------------------------------------------------------------------------
function viewItem(itemid){
    $.get("../index.php/getitem/"+itemid, processItem,"json");
    views(itemid);
}

function processItem(records){
    console.log(records);
    //displayItem(records);
    window.location.href = "item.php?item="+records.itemid;
    return false;
}

function displayItem(records){
    
    // Display item description in modal when "view more" is clicked
    /*$("#description").val(records.itemdescription);   
    $('#itemModal').modal('show'); */
}
//--------------------------------------------------------------------
//Redirects to trader.php and displays the items and other items of the trader clicked
function viewTraderProfile(userid){
    $.get("../index.php/items/"+userid, processTraderProfile, "json");
}

function processTraderProfile(records){
    console.log(records);
    //alert(records[0].userid);
    window.location.href = 'trader.php?trader='+records[0].userid;
    //listProfileItems(records);
    return false;

}
function listProfileItems(records){
    // Display user and their items in a modal
    /*var htmlStr;
       $("#trader").val(records[0].username);
        records.forEach(function(el){
            htmlStr += "<option value='"+el.itemid+"''>" +el.itemname + "</option>";
        });
        $("#items").html(htmlStr);
    $('#profileModal').modal('show'); */

    var itemdiv = "<div>";
    $("#itemP").val(records[0].username);
    console.log(records[0].username);
    records.forEach(function(el){
        itemdiv += "<div class='panel panel-default'>";
        itemdiv += "<div class='panel-heading'>"+  el['itemname'] + "</div>"; 
        itemdiv += "</div>";
    });
    $("#itemblockP").html(itemdiv);
    window.location.href = 'trader.php';
    return false;
}
//---------------------------------------------------------------------------------
//Dsiplay requests for user items in the notification icon
function getUserRequests(){
    $.get("../index.php/requests", notifications, "json");  
}

function notifications(records){
    console.log(records);
    records.forEach(function(el){
        var htmlStr = "<li><a href=profile.php>"+ el.username + " is requesting "+ el.itemname + "</a></li>";
        $("#requests").append(htmlStr);
    });
    var countR = $("#requests li").length;
    $("#requestsNotify").append(countR);
    displayRequests(records);

}

function displayRequests(records){
    var key;
    var sec_id = "#table_secr";
    var htmlStr = $("#table_headingr").html(); //Includes all the table, thead and tbody declarations
    var pic;
    records.forEach(function(el){
        htmlStr += "<tr>";
        htmlStr += "<td style='display:none;'>"+ el['id'] +"</td>";
        htmlStr += "<td>"+ el['username'] +"</td>";
        htmlStr += "<td>"+ el['itemname'] +"</td>";
        //htmlStr += "<td><img src=\"" + pic + "\" width=\"150\" height=\"128\"></td>";    
        htmlStr += "<td><button type='button' class='btn btn-info' onclick=\"viewRequest("+el.id+")\"><i class='fa fa-eye' aria-hidden='true'></i></button> ";    
        htmlStr += "<td><button type='button' class='btn btn-success' onclick=\"acceptRequest("+el.id+")\"><i class='fa fa-check-square-o' aria-hidden='true'></i></button> ";
        htmlStr += "<button type='button' class='btn btn-danger' onclick=\"denyRequest("+el.id+")\"><i class='fa fa-ban' aria-hidden='true'></i></button></td>";
        htmlStr +=" </tr>" ;
    });

    htmlStr += "</tbody></table>";
    $(sec_id).html(htmlStr);
}
//--------------------------------------------------------------------------------------------------------------------
// Add the item clicked to the user's saved items
function addToSavedItems(itemid){
    swal("Item Saved!", "You can view item in Saved Items!", "success")
}
//--------------------------------------------------------------------------------------------------------------------
// Adds the trader clicked to the user's followers
function followTrader(userid){
    alert(userid);
    swal("Trader Followed!", "You can view followed trader in People!", "success")
}

//--------------------------------------------------------------------------------------------------------------------
//Dsiplay decisions for requests made by user
function getDecisions(){
    $.get("../index.php/decisions", decisions, "json");  
}

function decisions(records){
    console.log(records);
    var htmlStr;
    records.forEach(function(el){
        if(el.decision == true){
            htmlStr = "<li><a href=trade.php>"+ el.itemname + " request was ACCEPTED" + "</a></li>";
        }
        else{
            htmlStr = "<li><a href=trade.php>"+ el.itemname + " request was DENIED" + "</a></li>";
        }
        
        $("#decisions").append(htmlStr);
    });
    var countD = $("#decisions li").length;
    $("#decisionsNotify").append(countD);
    //displayRequests(records);

}

function displayDecisions(records){
    var key;
    var sec_id = "#table_secr";
    var htmlStr = $("#table_headingr").html(); //Includes all the table, thead and tbody declarations
    var pic;
    records.forEach(function(el){
        htmlStr += "<tr>";
        htmlStr += "<td style='display:none;'>"+ el['id'] +"</td>";
        htmlStr += "<td>"+ el['username'] +"</td>";
        htmlStr += "<td>"+ el['itemname'] +"</td>";

        $.get("../index.php/itemimage/"+el['item'], function(res){
            //alert(res.picture);
            htmlStr += "<td><img src=\"" + res.picture + "\" width=\"150\" height=\"128\"></td>";
        }, "json");

        htmlStr += "<td><img src=\"" + pic + "\" width=\"150\" height=\"128\"></td>";        
        htmlStr += "<td><button type='button' class='btn btn-success' onclick=\"acceptRequest("+el.id+")\"><i class='fa fa-check-square-o' aria-hidden='true'></i></button> ";
        htmlStr += "<button type='button' class='btn btn-warning' onclick=\"denyRequest("+el.id+")\"><i class='fa fa-ban' aria-hidden='true'></i></button></td>";
        htmlStr +=" </tr>" ;
    });

    htmlStr += "</tbody></table>";
    $(sec_id).html(htmlStr);
}

//--------------------------------------------------------------------------------------------------------------------

//Dsiplay decisions for requests made by user
//Dsiplay All user items on profile
function getTrade(){//alter for slim 
    $.get("../index.php/trade", processUserTrade, "json");
}

function processUserTrade(records){
    console.log(records);
    listUserTrade(records);
    showRequestData(records);
}

function listUserTrade(records){
    var key;
    var sec_id = "#table_sect";
    var htmlStr = $("#table_headingt").html(); //Includes all the table, thead and tbody declarations

    records.forEach(function(el){
        htmlStr += "<tr>";
        htmlStr += "<td>"+ el['username'] +"</td>";
        htmlStr += "<td>"+ el['itemname'] +"</td>";
        htmlStr += "<td>" + el['timerequested'] + "</td>";
        if(el['decision'] == null){
            htmlStr += "<td> Pending </td>";
        }
        else if(el['decision'] == true){
            htmlStr += "<td> Accepted </td>";
        }
        else{
            htmlStr += "<td> Denied </td>";
        }
        htmlStr +=" </tr>" ;
    });
    //count = $("#mylist li").size();
    htmlStr += "</tbody></table>";
    $(sec_id).html(htmlStr);
} 

//--------------------------------------------------------------------------------------------------------------------
// Show and hide add item form
function showForm(){
    $('#uploadItem').show("slow");

}
function hideForm(){
    $('#uploadItem').hide("slow");

}
//----------------------------------------------------------------------------------------------------------------------
//show and hide search bar 
function showSearch(){
    $('#ProfileSearch').show("slow");

}
function hideSearch(){
    $('#ProfileSearch').hide("slow");

}
//--------------------------------------------------------------------------------------------------------------------
// Show and hide edit item form
function showUpdateForm(itemid){
   $('#updateItemform').show("slow");
   $.get("../index.php/edititem/"+itemid, function(item){
       //$("#imageU").val(item.picture);
        $("#id").val(item.itemid);
        $("#itemnameU").val(item.itemname);
        $("#itemdescriptionU").val(item.itemdescription);
    }, "json");
}
function hideUpdateForm(){
    $('#updateItemform').hide("slow");

}
//----------------------------------------------------------------------------------------------------------------------

// Add item image, name and description to database
function addItem(){
    var image = $("#image").val();
    var itemName = $("#itemname").val();
    var itemDescription = $("#itemdescription").val();
    //alert(image);
    var slash = image.indexOf("\\",5);
    image = image.substring(slash+1, image.length);
    //alert(image);
    var item = {
        "image" : image,
        "itemname" : itemName,
        "itemdescription" : itemDescription
    };

    console.log(item);
    $.post("../index.php/additem", item, function(res){
        if (res.id && res.id > 0)swal("Uploaded", "Item Saved", "success");
        else swal("Upload Error", "Unable to save item", "error");
        hideForm();
        getUserItems();
        clearFields();
    },"json");
    return false;
}

function clearFields(){
    $("#image").val(""); 
    $("#itemname").val("");
    $("#itemdescription").val("");
}

//--------------------------------------------------------------------------------------------------------------------

// Update existing item data
function updateItem(){
    
    return false;
}

//--------------------------------------------------------------------------------------------------------------------
// Inserts a records to the database when a user makes a request to that item
function makeRequest(itemid){
    $.get("../index.php/user", function(res){
        console.log(res);
        $.get("../index.php/items/"+res, function(res){
            //console.log(res);
            //displayItemsForRequest(res);
        }, "json");
    }, "json");
    $.get("../index.php/request/"+itemid, function(res){
        if (res.id && res.id > 0)
            swal("Request Made!", "", "success");
        else 
            swal("Record", "Unable to save record", "error");
    }, "json");
}

function displayItemsForRequest(itemid){
    $.get("../index.php/user", function(res){
        var user = res;
        //console.log(user);
        $.get("../index.php/items/"+user, function(res){
            displayInModal(res, itemid);

        }, "json")
    }, "json");
    
}

function displayInModal(records, itemid){
    if ($("#myitems").length > 0){ // the country select is available so we can display all countries
        var htmlStr;
        records.forEach(function(item){
            htmlStr += "<option value='"+item.itemid+"'>"+item.itemname+"</option>";
        });
        
        $("#myitems").html(htmlStr);
    } 
    $.get("../index.php/owner/"+itemid, function(res){
        //console.log(res);
        $("#requestee").val(res.username);
        $("#requesteditem").val(res.itemname);
    }, "json") 

    $("#requestModal").modal();
}

function sendRequest(){
    var requestee = $("#requestee").val();
    var requestedItem = $("#requesteditem").val();
    var myItem = $("#myitems").val();
    var request = {
        "requestee" : requestee,
        "requesteditem" : requestedItem,
        "myitem" : myItem
    };

    console.log(request);
    $.post("../index.php/request", request, function(res){
        console.log(res);
        if (res.id && res.id > 0)
            swal("Request Made!", "", "success");
        else 
            swal("Unable to make request", "Please try again", "error");
    },"json");
    $('#requestModal').modal('hide');
    return false;
}
//--------------------------------------------------------------------------------------------------------------------
// Deletes a user item from the list
function deleteItem(itemid){
    swal({
        title: "Delete Item?",
        text: "You will not be able to undo this operation!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function(isConfirm){
        
        if (isConfirm) {
            $.get("../index.php/itemstatus/"+itemid, function(res){
                if(res == true){
                    $.get("../index.php/requeststatus/"+itemid, function(res){

                    },"json");
                    swal("Cannot delete", "Requests pending", "error")
                }
                else{
                    $.get("../index.php/deleteitem/"+itemid, function(res){
                        swal("Deleted!", "Your item has been deleted.", "success");
                        getUserItems(); 
                    }, "json"); 
                }  
            }, "json");
        } else {
            swal("Cancelled", "Your item is safe", "error");
        }
    });
    
}
//-------------------------------------------------------------------------------------------------------------------
function views(itemid){
    $.get("../index.php/itemimage/"+itemid, function(res){
        console.log(res);
        var url = res.picture;
        console.log(url);
        //$('.imagepreview').attr('src', url);
        //$('#imagemodal').modal('show'); 
    }, "json");
    
    $.get("../index.php/viewitem/"+itemid, function(res){
                //swal("Viewed!", "You view the item.", "success");
                console.log(res);
    }, "json");

    getAllItems();
    getData();
}
//--------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------
function viewRequest(requestId){
    console.log(requestId);
    $.get("../index.php/requestdetails/"+requestId, function(res){
            console.log(res);
            
            $("#requester").val(res.username);
            $("#requesteritem").val(res.itemname);
            $('#imagepreview').attr('src', res.picture);
            $("#requestModalP").modal();
        }, "json");
}

//--------------------------------------------------------------------------------------------------------------------
function acceptRequest(requestId){
    swal({
        title: "Accept Request?",
        //text: "You will not be able to undo this operation!",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#5cd65c",
        confirmButtonText: "Accept",
        cancelButtonText: "Cancel",
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function(isConfirm){
        
        if (isConfirm) {
            $.get("../index.php/acceptrequest/"+requestId, function(res){
                swal("Accepted!", "The user will be notified", "success");
                getUserRequests();
            }, "json");
            
        } else {
            swal("Cancelled", "The item is still pending", "error");
        }
    });
    return false;
}

//--------------------------------------------------------------------------------------------------------------------
function denyRequest(requestId){
    swal({
        title: "Deny Request?",
        //text: "You will not be able to undo this operation!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Deny",
        cancelButtonText: "Cancel",
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function(isConfirm){
        
        if (isConfirm) {
            $.get("../index.php/denyrequest/"+requestId, function(res){
                swal("Denied!", "The user will be notified", "success");
            }, "json");
            getUserRequests();
        } else {
            swal("Cancelled", "The item is still pending", "error");
        }
    });
}

//--------------------------------------------------------------------------------------------------------------------
function getData(){//alter for slim 
    $.get("../index.php/data", processData, "json");
}

function processData(records){
    console.log(records);
    showData(records);
}


function showRequestData(records){
      // Load the Visualization API and the corechart package.
      //google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      //google.charts.setOnLoadCallback(drawChart2);

        var accept =0;
        var deny =0;
        var pending =0;

      records.forEach(function(el){

        if(el['decision'] == true)
            accept++;
        else if (el['decision'] == false)
            deny++;
        else 
            pending++;

      });
      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart2() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'decision');
        data.addColumn('number', 'count');
        data.addRows([
          ['Accepted', accept],
          ['Denied', deny],
          ['Pending', pending]
        ]);

        // Set chart options
        var options = {'title':'Requests Status',
                        is3D: true,
                       'width':430,
                       'height':300};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('trades_chart_div'));
        //var chart = new google.visualization.PieChart($('#trades_chart_div')[0]);

        chart.draw(data, options);
      }
}

function showData(records){
      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
     // google.charts.setOnLoadCallback(drawChart);
      var itemname1 = records[0]['itemname'];
      var itemname2 = records[1]['itemname'];
      var itemname3 = records[2]['itemname'];
      var itemname4 = records[3]['itemname'];
      var itemname5 = records[4]['itemname'];
      var itemviews1 = parseInt(records[0]['views']);
      var itemviews2 = parseInt(records[1]['views']);
      var itemviews3 = parseInt(records[2]['views']);
      var itemviews4 = parseInt(records[3]['views']);
      var itemviews5 = parseInt(records[4]['views']);
      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Items');
        data.addColumn('number', 'Views');
        data.addRows([
          [itemname1, itemviews1],
          [itemname2, itemviews2],
          [itemname3, itemviews3],
          [itemname4, itemviews4],
          [itemname5, itemviews5]
        ]);

        // Set chart options
        var options = {'title':'Top 5 Viewed Items',
                        is3D: true,
                       'width':320,
                       'height':300};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        //var chart = new google.visualization.PieChart($('#chart_div')[0]);

        chart.draw(data, options);
      }
}


console.log("JavaScript file was successfully loaded in the page");