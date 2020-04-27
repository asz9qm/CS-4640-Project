<html>
    <!-- this is used to change the password for the user -->
    <?php
    require('connect-db.php'); 
    require("allActions.php");
    session_start();
    ?>

    <head>
        <title>Change Password Page</title>
        <meta name="viewport" content="initial-scale=1.0">
        <meta charset="utf-8">
        <meta name="author" content="Alan Zhai, Jennifer Liao">
        <meta name="description" content="Requirements">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="../CSS/requirements.css">
        <link rel="stylesheet" href="../CSS/main.css">

        <!-- Need the following three lines in order for nav bar to work: https://stackoverflow.com/questions/45756307/bootstrap-4-toggle-button-not-working/45756365 -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>        
    </head>

    <style>
    .error_message {  color: crimson; font-style:italic; }       
    </style>
    
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
    //checks that the user is signed in
    if (isset($_SESSION['user']))
    {
        $user_info = getUserInfo($_SESSION['user']);
                
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            //when the user cancels 
            if (!empty($_POST['action']) && ($_POST['action'] == 'Cancel'))
            {
                header("Location: requirementsPage.php");
            }
            //when the user doesn't cancel
            else
            {
                if( !empty($_POST['pass1']) && !empty($_POST['pass']) && !empty($_POST['pass2']))
                {
                    //meant to be used to allow for error checking
                    if (isset($_SESSION["pass"]))
                    {
                        unset($_SESSION["pass"]);
                    }
                    //checks that both passwords match
                    if( $_POST['pass1'] == $_POST['pass2'] && (password_verify($_POST['pass'], $user_info[0]['pass'])))
                    {
                        
                        $email = $_SESSION['user'];
                        $pass = password_hash($_POST['pass1'], PASSWORD_BCRYPT);
                        $query = "UPDATE users SET pass=:pass WHERE email=:email";
                        $statement = $db->prepare($query);
                        $statement->bindValue(':pass', $pass);
                        $statement->bindValue(':email', $email);
                        $statement->execute();
                        $statement->closeCursor();
                        $_SESSION["pass_error"] = "";
                        header("Location: requirementsPage.php");
                    }
                }
                //shows the error message
                else {
                    $_SESSION["pass"] = "yes";
                }
                
                                
                
            }            
            

        }
    ?>

    <div class="container-fluid" style="text-align: center">

        <h5 style="text-align: center">Changing Password for <?php echo $_SESSION['user'];?></h5>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" name="RegisterForm" method="post">
            <span class="error_message" id="pass_error"><?php if(!empty($_POST['pass1'])&&!empty($_POST['pass'])&&!empty($_POST['pass2'])) echo "Your Passwords do not match or your current password is wrong";?></span>
            <br/>
            <span class="error_message" id="pass_error"><?php if(isset($_SESSION["pass"]) && $_SERVER["REQUEST_METHOD"] == "POST") echo "All blanks must be filled in";?></span>

            <div class="form-group">
                <label for="exampleInputPassword1">Current Password</label>
                <input type="password" class="form-control" name="pass" id="pass" placeholder="Password" >
            </div>

            <div class="form-group">
                <label for="exampleInputPassword1">New Password</label>
                <input type="password" class="form-control" name="pass1" id="pass1" placeholder="Password" >
            
            </div>

            <div class="form-group">
                <label for="exampleInputPassword1">Re-enter New Password</label>
                <input type="password" class="form-control" name="pass2" id="pass2" placeholder="Password" >
            </div>

            <button type="submit" class="btn btn-danger">Submit</button>

        </form>

        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" >
                                  
            <div class="form-group">
                    
                        <br/>
                        <input type="submit" value="Cancel" name="action" class="btn btn-primary" />      
                    
            </div>
        </form>
        
          
  
    </div>

    <?php 
    }
    else
    {
        echo '<h5 style="text-align:center">You need to log in first before viewing this page <a href="LoginPage.php" ></br/><button class="btn btn-primary">Log in</button></a></h5>';
    }
    ?>   
    
    
</html>