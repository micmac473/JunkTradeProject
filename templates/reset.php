
<?php
include "../lib.php";



?>
<!doctype html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>junkTrade Login</title>
    
    <!-- Latest compiled and minified CSS Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Sweetalert CSS-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet">

    <!-- Main CSS -->
    <link href ="../css/main.css" rel ="stylesheet">

    <!-- Bootstrap validator -->
    <link href="https://cdnjs.com/libraries/1000hz-bootstrap-validator" rel="styleshet">

    <!-- Scripts-->
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <!-- Sweetalert JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <!-- Main JS file-->
    <script src="../js/main.js"></script>

    <!-- Bootstrap validator -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
<style>
body {
  position: relative;
  margin: 0;
  padding-bottom: 6rem;
  min-height: 100%;
  background-color: #f6f6f6;
}
form {
  background-color: #FFFFFF;
}
</style>
</head>
<body>
  <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><img alt ="logo" width ="30px" height ="30px" src ="../img/logo.png"></a>
          <a class ="navbar-brand" href ="../">junkTrade</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
      
            <!--this is not rendering the login page as it shoul -->
            <ul class = "nav navbar-nav navbar-right ">
              <li><a href ="registration.phtml"><span class="glyphicon glyphicon-user"></span> Sign up</a></li>
              <li><a href ="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                
            </ul>
           <!--<ul class="nav navbar-nav navbar-right ">
                <li><button type="button" class="btn btn-primary btn-lg" onclick="window.location='templates/login.html'">login</button></li>
            </ul> -->
         
        </div><!--/.navbar-collapse -->
      </div>
    </nav>
  <div class ="container">
    <div class="jumbotron" style="text-align:center;">
    <h1 >JunkTrade </h1>
    <h3> Oops you forgot your password :'(</h3>
  </div>
    <div class ="row main">
      <div class="main-login main-center">
        <!-- <form class="form-horizontal" onsubmit="return login();" method ="POST" action="index.php/users"> -->
          <form  role="form" data-toggle="validator" onsubmit="return login1() ;">
          <fieldset>
            <!-- Form Name -->
            <legend style="text-align: center"> <h2> Forget Password </h2></legend>

            <div class="form-group has-feedback">
              <label for="username" class="cols-xs-2 control-label">Username or email address</label>
              <div class="cols-xs-12">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                  <input type="text" pattern="^[_A-z0-9]{1,}$" minlength="3" maxlength="15" class="form-control" name="email" id="email"  placeholder="Username or email" required="">
                </div>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                <div class="help-block with-errors"></div>
              </div>
            </div>

            <div class="form-group has-feedback">
              <label for="name" class="cols-sm-2 control-label">Security Question</label>
              <div class="cols-sm-10">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-user-secret" aria-hidden="true"></i></span>
                  <select class="form-control" name="securityquestion" id="securityquestion" placeholder="Confirm your Password" required/>
                  <option value="" disabled selected> Select a security question</option>
                    <option value="sport"> What is your favorite sport? </option>
                    <option value="food"> What is your favorite food? </option>
                  </select>
                </div>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
              </div>
            </div>

            <div class="form-group has-feedback">
              <label for="password" class="cols-sm-2 control-label">Security Answer</label>
              <div class="cols-sm-10">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                  <input type="password" data-minlength="6" class="form-control" name="sAnswer" id="sAnswer"  placeholder="Security Answer" required/>
                </div>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                <div class="help-block with-errors"></div>
              </div>
            </div>

            <!-- Button -->
            <div class="form-group">
                <button name="saveBnt" class="btn btn-primary btn-lg btn-block login-button" id="saveBnt" type ="submit">Sign in</button>
              </div>
            </div>
              
          </fieldset>
        </form>

      </div>
    </div>
  </div>
  <!--FAcebook login -->

</div>

<!--footer -->
  <div class="footer">
    <p> &copy; JunkTrade 2016 </p>
  </div>
<body>
</html>