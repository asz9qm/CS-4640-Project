<html>
    <?php 
    require("allActions.php");
    ?>
    <head>
        <title>Edit Page</title>
        <meta name="viewport" content="initial-scale=1.0">
        <meta charset="utf-8">
        <meta name="author" content="Alan Zhai, Jennifer Liao">
        <meta name="description" content="edit">
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
            echo "<h1>There was nothing set to edit, taking you back to the requirements page</h1>";
            header('refresh:3; url=requirementsPage.php');
        }
        else {
            $class = getOneTask($_SESSION['id']);
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            //updateTaskInfo($id, $category, $courseID, $courseName, $taken, $semester, $grade)
            updateTaskInfo($_SESSION['id'], $_POST['category'], $_POST['courseID'], $_POST['courseName'],
                 $_POST['taken'], $_POST['semester'], $_POST['grade']);
            
            header('Location: requirementsPage.php');

        }
    ?>

    <div class="container" style="text-align: center;">
      
      <h3>
          Editing Class <?php echo $class[0]["courseName"];?>
      </h3>
      <!-- a form -->
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" name="editForm" method="post">
            <div class="form-group">
                <label for="Category">Category of Course</label>
                <select id="Category" name="category" class="form-control"> 
                    <option value="General">General Requirement</option>
                    <option value="Computing">Computing Elective </option>   
                    <option value="Integration">Integration Elective</option>   
                    <option value="College">College Requirements</option> 
                </select>             
            </div>
            <div class="form-group">
                <label for="courseID">Course Mnemonic</label>
                <input type="text" class="form-control" id="courseID" name="courseID" placeholder="Course Mnemonic"
                value="<?php echo $class[0]["courseID"]?>">
            </div>
            <div class="form-group">
                <label for="Course name">Course Name</label>
                <input type="text" class="form-control" id="courseName" name="courseName" placeholder="Course Mnemonic"
                value="<?php echo $class[0]["courseName"]?>">
            </div>   
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="taken">Class Taken?</label>
                    <select id="taken" name="taken" class="form-control"> 
                        <option value=1>Yes</option>
                        <option value=0>No </option>

                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="semester">Semester</label>
                    <select id="semester" name="semester" class="form-control"> 
                        <option value="Fall 2017">Fall 2017</option>
                        <option value="Spring 2018">Spring 2018</option>
                        <option value="Fall 2018">Fall 2018</option>
                        <option value="Spring 2019">Spring 2019</option>
                        <option value="Fall 2019">Fall 2019</option>
                        <option value="Spring 2020">Spring 2020</option>
                        <option value="Fall 2020">Fall 2020</option>
                        <option value="Spring 2021">Spring 2021</option>
                        <option value="Other">Other</option>
                    </select>                    
                </div>
                <div class="form-group col-md-4">
                    <label for="grade">Grade</label>
                    <select id="grade" name="grade" class="form-control"> 
                        <option value="A+">A+</option>
                        <option value="A">A</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B">B</option>
                        <option value="B-">B-</option>
                        <option value="C+">C+</option>
                        <option value="C">C</option>
                        <option value="C-">C-</option>
                        <option value="D+">D+</option>
                        <option value="D">D</option>
                        <option value="D-">D-</option>
                        <option value="F">F</option>
                        <option value="CR">CR</option>
                        <option value="NC">NC</option>
                        

                    </select>
                </div>
            </div>   
          
          
          <button type="submit" class="btn btn-primary">Submit Changes</button>
        </form>
          
  
    </div>




    <?php 
    }
    else
    {
        echo 'Please <a href="LoginPage.php" ><button>Log in</button></a>';
    }
    ?>   
    
    
</html>