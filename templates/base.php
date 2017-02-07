<?php
if(!isset($_SESSION)){
  session_start();
}

?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>JunkTrade</title>
    
    <!-- Bootstrap core CSS -->
    <!-- <link href="../css/bootstrap.css" rel="stylesheet"> -->
    <!-- <link rel ="stylesheet" href ="../css/bootstrap-theme.css" > -->
    <!-- <link href ="../css/main.css" rel ="stylesheet"> -->
    <!--<script src="../bower_components/jquery/dist/jquery.js"></script> -->

    <!-- google font  -->
    <link href="https://fonts.googleapis.com/css?family=Bowlby+One+SC" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Shrikhand" rel="stylesheet">

    <!-- Latest compiled and minified CSS Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet">

    <!-- AngularJS JavaScript file  -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>

    <!-- AngularJS Route module -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-route.js"></script>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <script src="../js/main.js"></script>
    <style>
      .city{
        background-image:url(http://www.buyandsellnow.ca/wp-content/uploads/2016/06/3d_banner_background.png);
        
      }
      /**
 * Demo Styles
 */

html {
  height: 100%;
  box-sizing: border-box;
}

*,
*:before,
*:after {
  box-sizing: inherit;
}

body {
  position: relative;
  margin: 0;
  padding-bottom: 6rem;
  min-height: 100%;
  background-color: #f6f6f6;
   overflow-x: hidden;
  font-family: "Helvetica Neue", Arial, sans-serif;
}

.demo {
  margin: 0 auto;
  padding-top: 64px;
  max-width: 640px;
  width: 94%;
}

.demo h1 {
  margin-top: 0;
}

/**
 * Footer Styles
 */

.footer {
  position: absolute;
  right: 0;
  bottom: 0;
  left: 0;
  padding: 1rem;
  background-color:black;
  text-align: center;
  color:white;
}
th {
    background-color: grey;
    color: white;
} 
table{
  background-color: #FFFFFF;
}
button:active{
    background:red;
}
.panel{
  background-color: #FFFFFF;
  box-shadow: 10px 10px 5px #888888;
}

#wrapper {
    padding-left: 0;
    -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;
}

#wrapper.toggled {
    padding-left: 250px;
}

#sidebar-wrapper {
    z-index: 1000;
    position: fixed;
    left: 250px;
    width: 0;
    height: 100%;
    margin-left: -250px;
    overflow-y: auto;
    background: #f6f6f6;
    -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;
}

#wrapper.toggled #sidebar-wrapper {
    width: 250px;
}

#page-content-wrapper {
    width: 100%;
    position: absolute;
    padding: 15px;
}

#wrapper.toggled #page-content-wrapper {
    position: absolute;
    margin-right: -250px;
}

/* Sidebar Styles */

.sidebar-nav {
    position: absolute;
    top: 0;
    width: 250px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.sidebar-nav li {
    text-indent: 20px;
    line-height: 40px;
}

.sidebar-nav li a {
    display: block;
    text-decoration: none;
    color: #999999;
}

.sidebar-nav li a:hover {
    text-decoration: none;
    color: white;
    background: gray;
}

.sidebar-nav li a:active,
.sidebar-nav li a:focus {
    text-decoration: none;
}

.sidebar-nav > .sidebar-brand {
    height: 65px;
    font-size: 18px;
    line-height: 60px;
}

.sidebar-nav > .sidebar-brand a {
    color: #999999;
}

.sidebar-nav > .sidebar-brand a:hover {
    color: #fff;
    background: none;
}

@media(min-width:768px) {
    #wrapper {
        padding-left: 250px;
    }

    #wrapper.toggled {
        padding-left: 0;
    }

    #sidebar-wrapper {
        width: 250px;
    }

    #wrapper.toggled #sidebar-wrapper {
        width: 0;
    }

    #page-content-wrapper {
        padding: 20px;
        position: relative;
    }

    #wrapper.toggled #page-content-wrapper {
        position: relative;
        margin-right: 0;
    }
}
    </style>

    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
  <div id="wrapper">
  <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a id="menu-toggle" href="#" class="navbar-brand glyphicon glyphicon-align-justify btn-menu toggle"> </a>
        <a class="navbar-brand" href="#"><img alt ="logo" width ="30px" height ="30px" src =../img/logo.png></a>
        <!--<a class ="navbar-brand" href ="homepage.php">junkTrade</a> -->
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Categories<span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#">Electronics</a></li>
              <li><a href="#">Furniture</a></li>
              <li><a href="#">Books & Magazines</a></li>
              <li><a href="#">Clothes</a></li>
            </ul>
          </li>
        </ul>

        <form class="navbar-form navbar-left" role="form">
          <div class="form-group">
            <input type="text" placeholder="Search for junk" class="form-control">
          </div>
            <!--change to icon-->
          <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true" ></i></button>
        </form>

        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user" aria-hidden="true" style="font-size:1.5em"></i><span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="profile.php">My Profile</a></li>
              <li><a href="trade.php">My Requests</a></li>
              <li><a href="login.php">Log Out</a></li>
              <!-- <li><a href="google.com">Help</a></li> -->
            </ul>
          </li>
          
          <li><a href ="homepage.php"><i class="fa fa-home" aria-hidden="true" style="font-size:1.5em"></i></a></li>


          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell" aria-hidden="true" style="font-size:1.5em"></i><span class="label label-danger label-as-badge" style="vertical-align:top" id ="requestsNotify"></span><span class="caret" style="vertical-align:"></span></a>
            <ul class="dropdown-menu" id="requests">
                <!-- <li><a href="#">Dynamically Populated Requets</a></li> -->
                
            </ul>

          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-gavel" aria-hidden="true" style="font-size:1.5em"></i><span class="label label-danger label-as-badge" style="vertical-align:top" id ="decisionsNotify"></span><span class="caret" style="vertical-align:"></span></a>
            <ul class="dropdown-menu" id="decisions">
                <!-- <li><a href="#">Dynamically Populated Requets</a></li> -->
                
            </ul>

          </li>
          
        </ul>
      </div><!--/.navbar-collapse -->
    </div>
  </nav>
  <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                        Home
                    </a>
                </li>
                <li>
                    <a href="homepage.php"><i class="fa fa-home fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;Home</a>
                </li>
                <li>
                    <a href="profile.php"><i class="fa fa-user fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Profile</a>
                </li>
                <li>
                    <a href="profile.php"><i class="fa fa-bell fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;Notifications</a>
                </li>
                <li>
                    <a href="trade.php"><i class="fa fa-gavel fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;Requests</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-users fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;Followers</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-map-marker fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Meet up</a>
                </li>
            </ul>
        </div>
  <div class="jumbotron city">
    <div class="container">
      <h1 style="color:#096790 ;text-shadow: 4px 4px orange;font-family: 'Bowlby One SC', cursive;">Good Afternoon,  
          <?php  echo $_SESSION["user"];?></h1>
    </div>
  </div>

  <!--footer -->
  <div class="footer">
    <p> &copy; JunkTrade 2016 </p>
  </div>


<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>

