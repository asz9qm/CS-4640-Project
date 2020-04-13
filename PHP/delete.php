<html>
    <?php 
    require("allActions.php");
    ?>
    <head>
        <title>Delete Page</title>
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

    <?php session_start(); // make sessions available ?>
    <?php
    if (isset($_SESSION['user'])){
        if (!isset($_SESSION['id'])){
            echo "<h1>There was nothing set to delete, taking you back to the requirements page</h1>";
            header('refresh:3; url=requirementsPage.php');
        }
        else{
            $class = getOneTask($_SESSION['id']);
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if (!empty($_POST['action']) && ($_POST['action'] == 'Yes'))
            {
                deleteTask($_SESSION['id']);
            }            
            
            header('Location: requirementsPage.php');

        }
    ?>

    <div class="container-fluid" style="text-align: center">
        
        <div class="container-fluid">
            <h5 style="text-align: center">Are you sure you wish to delete class: <?php echo $class[0]["courseName"];?></h5>
        </div>
        <div class="form-row">
            
            <div class="form-group col-md-6">
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                        <label for="action1">Yes, I am Sure </label>
                        <br/>
                        <input type="submit" value="Yes" name="action" id="action1" class="btn btn-danger" />           
                    </form> 
            </div>                       
            <div class="form-group col-md-6">
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                        <label for="action2">No, take me back </label>
                        <br/>
                        <input type="submit" value="No" name="action2" class="btn btn-primary" />      
                    </form>
            </div>
        </div>
        
          
  
    </div>




    <?php 
    }
    else
    {
        echo 'Please <a href="LoginPage.php" ><button>Log in</button></a>';
    }
    ?>   
    
    
</html>