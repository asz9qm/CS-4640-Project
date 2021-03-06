<html>
  <?php 
    require('connect-db.php');
    require('allActions.php');

  ?>

  <style>
    .error_message {  color: crimson; font-style:italic; }       
  </style>

    <head>
    
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
          
        </ul>
      </div>
    </nav>

  <?php 
  
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
      //creating the user in the database
      $email = $_POST['Email1'];
      $pass = password_hash($_POST['pass1'], PASSWORD_BCRYPT);
      $year = (int)$_POST['year'];
      $major = $_POST['major'];
      $query = "SELECT * FROM users WHERE email = :email";
      
      $statement = $db->prepare($query); 
      $statement->bindParam(':email', $email);
      $statement->execute();
      $results = $statement->fetchAll();
      $check = sizeof($results);
      $statement->closecursor();

      //making sure that the user doesn't exist in the database already
      if ($check == 0)
      {
        
        $_POST['taken'] = 0;
        $query = "INSERT INTO users (email, pass, schoolyear, major) VALUES (:email, :pass, :schoolyear, :major)";
        $statement = $db->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':pass', $pass);
        $statement->bindValue(':schoolyear', $year);
        $statement->bindValue(':major', $major);
        $statement->execute();
        $statement->closeCursor();
        //creates all the basic coursework for the user
        createBasicCourseWork($email);

        
        header("Location: LoginPage.php");
      }
      

    }     
   
   ?>

    
   <!-- the container for all of the components of the login bar -->
   <!-- Going to add more form verification once we switch to angular -->
    <div class="container" style="text-align: center;">

          <!-- one of the columns in the container -->
          <!-- the sign up column -->
                <h3>
                    Sign Up
                </h3>
                <!-- registration form -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" name="RegisterForm" method="post" onsubmit="return checkRegistration()">
                
                    <div class="form-group">
                      <label for="exampleInputEmail1">Email address</label>
                      <input type="email" class="form-control" name="Email1" id="Email1" aria-describedby="emailHelp" placeholder="Enter email" >
                      <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                      <span class="error_message" id="msg_email"><?php if(!empty($email)) echo "Email already taken, please 
                        enter another"?></span>
                    </div>
                    
                    <div class="form-group">
                      <label for="exampleInputPassword1">Password</label>
                      <input type="password" class="form-control" name="pass1" id="pass1" placeholder="Password" >
                      <span class="error_message" id="msg_pass"></span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Re-enter Password</label>
                        <input type="password" class="form-control" name="pass2" id="pass2" placeholder="Password" >
                    </div>

                    <div class ="form-group">
                        <label for="year">School Year</label>
                        <input type="text" class = "form-control" name="year" id="year" placeholder="Enter your year" 
                          value="<?php if(!empty($email)) echo $_POST['year']?>">
                        <small id="Year Help" class="form-text text-muted">Please enter an integer</small>
                        <span class="error_message" id="msg_year"></span>
                        
                    </div>

                    <div class ="form-group">
                        <label for="Major">Enter your Major</label>
                        <input type="text" class = "form-control" name="major" id="major" placeholder="Enter your major" 
                          value="<?php if(!empty($email)) echo $_POST['major']?>">
                        <span class="error_message" id="msg_major"></span>
                    </div>
                    
                    <div>
                      <span class="error_message" id="overall"></span>
                    </div>
                    
                    <button type="submit" class="btn btn-secondary">Submit</button>
                    
                  </form>




    </div>
  
    <!-- Footer Navigation Bar-->
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

  </div>

  <!-- type checking and form validation using javascript -->
  <script>
  // checking that the year is a string
  function isInt(str)
  {
    var val = parseInt(str);
    return (val > 0);
  }
  // checking that the email is an email
  function isEmail(str)
  {
    var re = /\S+@\S+\.\S+/;
    var match_test = re.test(str);
    return match_test;
  }

  //making sure that all elements are filled out
  function checkRegistration()
  {
    var email = document.getElementById("Email1").value;
    var pass1 = document.getElementById("pass1").value;
    var pass2 = document.getElementById("pass2").value;
    var year = document.getElementById("year").value;
    var major = document.getElementById("major").value;
    var errors = 0; 

    if ( !isEmail(email))
    {
      errors++;
      document.getElementById("msg_email").innerHTML = "Please enter a valid email address";
    }
    else 
    {
      document.getElementById("msg_email").innerHTML = "";
    }
       
    //check if passwords match
    if (pass1 == "" || pass2 == "")
    {
      errors++;
      document.getElementById("msg_pass").innerHTML = "Your passwords cannot be empty";      
    }
    else if(pass1 != pass2)
    {
      errors++;
      document.getElementById("msg_pass").innerHTML = "Your passwords must match";
    }
    else 
    {
      document.getElementById("msg_pass").innerHTML = "";
    }      

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

</html>