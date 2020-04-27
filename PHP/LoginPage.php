<html>

  <?php 
  //the login page
    
    require('connect-db.php');
    require('allActions.php');
    session_start();
    header('Access-Control-Allow-Origin: http://localhost:4200');
    // header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
  ?>

  <style>
    .error_message {  color: crimson; font-style:italic; }       
  </style>

    <head>

    <!-- tried to google login but that isn't working right now -->
    <!-- <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="763652494101-tlmv0rv7an6bj872o37gkip2q9ho5fhp.apps.googleusercontent.com">
    <link rel="stylesheet" href="../CSS/login.css">
    <script src="https://apis.google.com/js/platform.js" async defer></script> -->
    <title>Login Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="author" content="Alan Zhai, Jennifer Liao">
    <meta name="description" content="Login Page">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/login.css">
    <link rel="stylesheet" href="../CSS/main.css">

    <!-- Need the following three lines in order for nav bar to work: https://stackoverflow.com/questions/45756307/bootstrap-4-toggle-button-not-working/45756365 -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>        
</head>

    <!-- Header Navigation Bar, resource: https://getbootstrap.com/docs/4.2/components/navbar/#how-it-works-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <h3> UVA Semester Scheduler</h3>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    
      <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          <li class="nav-item active">
            <a class="nav-link" href="schedulePage.php">Home<span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="loginPage.php">Account</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" a href="requirementsPage.php">Requirements</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" a href="<?php if(isset($_SESSION['user'])){echo "LogOut.php";} else{echo "LoginPage.php";}?>"><?php if(isset($_SESSION['user'])){echo "Log Out";} else{echo "";}?></a>
          </li>
          
        </ul>
      </div>
   </nav>

   <?php
   //checks to see if user is logged in
    if (!isset($_SESSION['user'])){

   ?>

   <?php 
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
      
      if (!empty($_POST['user']) && !empty($_POST['pass']))
      {
        if (isset($_SESSION['blank']))
        {
          unset($_SESSION['blank']);
        }
        $email = $_POST['user'];
      

        $query = "SELECT pass FROM users WHERE email = :email";
        
        $statement = $db->prepare($query); 
        $statement->bindParam(':email', $email);
        $statement->execute();
        $results = $statement->fetchAll();

        if (!empty($results))
        {
          if (password_verify($_POST['pass'], $results[0]['pass'])) 
          {
            session_start();
            $_SESSION['user'] = $email;
            
            
            
  
            header("Location: requirementsPage.php");
          } 
        }

      }
      else{
        $_SESSION['blank'] = "yes";
      }
      
      

    }
   
   
   ?>
    
   <!-- the container for all of the components of the login bar -->
   <!-- Going to add more form verification once we switch to angular -->
    <div class="container" style="text-align: center;">
      
                <h3>
                    Login
                </h3>
                <!-- a form -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" name="LoginForm" method="post">
                <span class="error_message" id="msg_email"><?php if(!empty($email)) echo "Incorrect Username or Password or User does not exist"?></span>
                <span class="error_message" id="msg_email"><?php if(!empty($_SESSION['blank'])) echo "Please fill in all fields"?></span>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Email address</label>
                      <input type="email" class="form-control" id="user" name="user" aria-describedby="emailHelp" placeholder="Enter email">
                      
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Password</label>
                      <input type="password" class="form-control" id="pass" name="pass" placeholder="Password">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </form>
                <div>Or</div>
                <br/>
                <a href="AccountCreate.php"><button class="btn btn-primary">Create an Account</button></a>     
                       
            
    </div>
  
    <!-- Footer Navigation Bar-->
    

  </div>
  <?php 
  }
  else
  {
  ?>
    <script>
      localStorage.setItem("user", '<?php echo $_SESSION["user"];?>');
      sessionStorage.setItem("user", "<?php echo $_SESSION["user"];?>");
      console.log(sessionStorage.getItem("user"));
    </script>
    <?php
  ?>


<?php 
 //if user is logged in, changes the page to change user information page
$user_info = getUserInfo($_SESSION['user']);
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if (!empty($_POST['action2']) && ($_POST['action2'] == 'Yes'))
  {
    header("Location: requirementsPage.php");
  }
  else if (!empty($_POST['action2']) && ($_POST['action2'] == 'Change Password'))
  {
    header("Location: changePass.php");
  }
  else 
  {
    $schoolyear = (int)$_POST['year'];
    $major = $_POST['major'];
    $email = $_SESSION['user'];

    $query = "UPDATE users SET schoolyear=:schoolyear, major=:major WHERE email=:email";
	  $statement = $db->prepare($query);
	  $statement->bindValue(':schoolyear', $schoolyear);
	  $statement->bindValue(':major', $major);
    $statement->bindValue(':email', $email);
	  $statement->execute();
    $statement->closeCursor();
    
    header("Location: requirementsPage.php");

  }
}

?>

<div class="container" style="text-align: center;">

<!-- one of the columns in the container -->
<!-- the sign up column -->
      <h3>
          Edit Account information
      </h3>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" name="editForm" method="post" onsubmit="return checkEdit()">

          <div class ="form-group">
              <label for="year">School Year</label>
              <input type="text" class = "form-control" name="year" id="year" placeholder="Enter your year" 
                value="<?php echo $user_info[0]['schoolyear'];?>">
              <small id="Year Help" class="form-text text-muted">Please enter an integer</small>
              <span class="error_message" id="msg_year"></span>              
          </div>

          <div class ="form-group">
              <label for="Major">Enter your Major</label>
              <input type="text" class = "form-control" name="major" id="major" placeholder="Enter your major" 
                value="<?php echo $user_info[0]['major'];?>">
              <span class="error_message" id="msg_major"></span>
          </div>
          
          <div>
            <span class="error_message" id="overall"></span>
          </div>
          
          <button type="submit" class="btn btn-secondary">Submit</button>
          
        </form>
        <br/>
        <div class="form-row">
        <div class="form-group col-md-6">
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                        <label for="action2">Change Password Here</label>
                        <br/>
                        <input type="submit" value="Change Password" name="action2" class="btn btn-danger" />      
                    </form>
        </div>
        <div class="form-group col-md-6">
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                        <label for="action2">Would you like to cancel? </label>
                        <br/>
                        <input type="submit" value="Yes" name="action2" class="btn btn-primary" />      
                    </form>
        </div>
        </div>




</div>

<script>

  function isInt(str)
  {
    var val = parseInt(str);
    return (val > 0);
  }

  function isEmail(str)
  {
    var re = /\S+@\S+\.\S+/;
    var match_test = re.test(str);
    return match_test;
  }

  function checkRegistration()
  {
    var year = document.getElementById("year").value;
    var major = document.getElementById("major").value;
    var errors = 0; 
   

    //checking that year is an integer
    if ( !isInt(year))
    {
      errors++;
      document.getElementById("msg_year").innerHTML = "Your year is not an integer or it is blank";
    }
    else 
    {
      document.getElementById("msg_year").innerHTML = "";
    }

    if ( major == "")
    {
      errors++;
      document.getElementById("msg_major").innerHTML = "Please enter a major";
    }
    else 
    {
      document.getElementById("msg_major").innerHTML = "";
    }

    if (errors > 0)
    {
      document.getElementById("overall").innerHTML = "Please fix the errors and then resubmit";
      return false;
    }
    else
    {
      document.getElementById("overall").innerHTML = "";
      return true;
    }  
  }

  </script>
  


  <?php }?>

  <nav class="navbar navbar-expand-lg">      
      <div class="collapse navbar-collapse" id="navbarFooter">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          <li class="nav-item active">
            <a class="nav-link" href="schedulePage.php">Home<span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="loginPage.php">Account</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" a href="requirementsPage.php">Requirements</a>
          </li>
        </ul>
      </div>
   </nav>
  
</html>