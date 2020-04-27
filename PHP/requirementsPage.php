<html>
    <?php session_start(); ?>
    <head>
        <title>Requirements Page</title>
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
            <li class="nav-item">
                <a class="nav-link" a href="<?php if(isset($_SESSION['user'])){echo "LogOut.php";} else{echo "LoginPage.php";}?>"><?php if(isset($_SESSION['user'])){echo "Log Out";} else{echo "";}?></a>
            </li>
          </ul>
        </div>
     </nav>

    <?php
    if (isset($_SESSION['user'])){
    ?>

    <?php 
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            include('allActions.php'); 
            $general = getAllTasks($_SESSION['user'], "General");
            $computing = getAllTasks($_SESSION['user'], "Computing");
            $integration = getAllTasks($_SESSION['user'], "Integration");
            $college = getAllTasks($_SESSION['user'], "College");            
        }
        else if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if (!empty($_POST['action']) && ($_POST['action'] == 'Update'))
            {
                $_SESSION['id'] = $_POST['task_id'];
                header("Location: edit.php");

            }
            if (!empty($_POST['action']) && ($_POST['action'] == 'Delete'))
            { 
                $_SESSION['id'] = $_POST['task_id'];
                header("Location: delete.php");

            }
            if (!empty($_POST['action']) && ($_POST['action'] == 'Click Here'))
            { 
                header("Location: http://localhost:4200/");
            }
        }
    ?>

    <div>
        <!-- Dropdown to allow switching between different types of Requirements -->
        <label for="dropdown">Requirement Type:</label>
        <select id="Choice">
        <option value="1">General Requirements</option>
        <option value="2">Computing Electives</option>
        <option value="3">Integration Electives</option>
        <option value="4">College Requirements</option>
        </select>

    </div>

    <div>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <label>Click here to add a Course</label>
        <input type="submit" value="Click Here" name="action" class="btn btn-primary" />             
        </form> 
    </div>
    
    <!-- Table for the classes -->
    <div class="table-responsive">
        <!-- Make it striped -->
        <table class="table table-striped table-bordered" style="width:100%" id = "general">
            <!-- Defining the different columns -->
            <colgroup>
                <col class="table1">
                <col class="table2">
                <col class="table3">
                <col class="table4">
                <col class="table5">
                <col class="table6">
                <col class="table7">
              </colgroup>
            <!-- Header for the columns -->
            <tr class="thead">
                <th>Mnemonic</th>
                <th>Class Name</th>
                <th style="text-align: center;">Taken?</th>
                <th>Semester</th>
                <th>Grade</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
            <?php foreach ($general as $g): ?>
            <tr>
                <td>
                    <?php echo $g['courseID']; // refer to column name in the table ?> 
                </td>
                <td>
                    <?php echo $g['courseName']; ?> 
                </td>        
                <td>
                    <?php if($g['taken'] == 0){echo "No";} else{echo "Yes";}; ?> 
                </td>
                <td>
                    <?php echo $g["semester"]; ?> 
                </td>
                <td>
                    <?php echo $g["grade"]; ?> 
                </td>                 
                <td>
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                        <input type="submit" value="Update" name="action" class="btn btn-primary" />             
                        <input type="hidden" name="task_id" value="<?php echo $g['id'] ?>" />
                    </form> 
                </td>                        
                <td>
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                        <input type="submit" value="Delete" name="action" class="btn btn-danger" />      
                        <input type="hidden" name="task_id" value="<?php echo $g['id'] ?>" />
                    </form>
                </td>                                
            </tr>
            <?php endforeach; ?>

        </table>

            <!-- Table for Computing -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered" style="width:100%" id = "computing">
                    <!-- Defining the columns for CSS -->
                    <colgroup>
                        <col class="table1">
                        <col class="table2">
                        <col class="table3">
                        <col class="table4">
                        <col class="table5">
                        <col class="table6">
                        <col class="table7">
                      </colgroup>

                    <!-- Column Headers -->
                    <tr>
                        <th>Mnemonic</th>
                        <th>Class Name</th>
                        <th style="text-align: center;">Taken?</th>
                        <th>Semester</th>
                        <th>Grade</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>

                    <?php foreach ($computing as $c): ?>
                    <tr>
                        <td>
                            <?php echo $c['courseID']; // refer to column name in the table ?> 
                        </td>
                        <td>
                            <?php echo $c['courseName']; ?> 
                        </td>        
                        <td>
                            <?php if($c['taken'] == 0){echo "No";} else{echo "Yes";}; ?> 
                        </td>
                        <td>
                            <?php echo $c["semester"]; ?> 
                        </td>
                        <td>
                            <?php echo $c["grade"]; ?> 
                        </td>                 
                        <td>
                            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                                <input type="submit" value="Update" name="action" class="btn btn-primary" />             
                                <input type="hidden" name="task_id" value="<?php echo $c['id'] ?>" />
                            </form> 
                        </td>                        
                        <td>
                            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                                <input type="submit" value="Delete" name="action" class="btn btn-danger" />      
                                <input type="hidden" name="task_id" value="<?php echo $c['id'] ?>" />
                            </form>
                        </td>                                
                    </tr>
                    <?php endforeach; ?>

                    

                </table>
            </div>

            <!-- Table for Integration Electives -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered" style="width:100%" id = "integration">
                    <!-- Defining the Column Headers for CSS -->
                    <colgroup>
                        <col class="table1">
                        <col class="table2">
                        <col class="table3">
                        <col class="table4">
                        <col class="table5">
                        <col class="table6">
                        <col class="table7">
                    </colgroup>
                    <!-- Column Names -->
                    <tr>
                        <th>Mnemonic</th>
                        <th>Class Name</th>
                        <th style="text-align: center;">Taken?</th>
                        <th>Semester</th>
                        <th>Grade</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                    <?php foreach ($integration as $i): ?>
                    <tr>
                        <td>
                            <?php echo $i['courseID']; // refer to column name in the table ?> 
                        </td>
                        <td>
                            <?php echo $i['courseName']; ?> 
                        </td>        
                        <td>
                            <?php if($i['taken'] == 0){echo "No";} else{echo "Yes";}; ?> 
                        </td>
                        <td>
                            <?php echo $i["semester"]; ?> 
                        </td>
                        <td>
                            <?php echo $i["grade"]; ?> 
                        </td>                 
                        <td>
                            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                                <input type="submit" value="Update" name="action" class="btn btn-primary" />             
                                <input type="hidden" name="task_id" value="<?php echo $i['id'] ?>" />
                            </form> 
                        </td>                        
                        <td>
                            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                                <input type="submit" value="Delete" name="action" class="btn btn-danger" />      
                                <input type="hidden" name="task_id" value="<?php echo $i['id'] ?>" />
                            </form>
                        </td>                                
                    </tr>
                    <?php endforeach; ?>
                   

                </table>
            </div>


            <!-- Table for College Requirements -->
            
            <div class="table-responsive" >
                
                <table class="table table-striped table-bordered" style="width:100%" id = "college">
                    <!-- Defining the Column Headers for CSS -->
                    <colgroup>
                        <col class="table1">
                        <col class="table2">
                        <col class="table3">
                        <col class="table4">
                        <col class="table5">
                        <col class="table6">
                        <col class="table7">
                    </colgroup>
                    <!-- Column Names -->
                    <tr>
                        <th>Mnemonic</th>
                        <th>Class Name</th>
                        <th style="text-align: center;">Taken?</th>
                        <th>Semester</th>
                        <th>Grade</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                    <?php foreach ($college as $col): ?>
                    <tr>
                        <td>
                            <?php echo $col['courseID']; // refer to column name in the table ?> 
                        </td>
                        <td>
                            <?php echo $col['courseName']; ?> 
                        </td>        
                        <td>
                            <?php if($col['taken'] == 0){echo "No";} else{echo "Yes";}; ?> 
                        </td>
                        <td>
                            <?php echo $col["semester"]; ?> 
                        </td>
                        <td>
                            <?php echo $col["grade"]; ?> 
                        </td>                 
                        <td>
                            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                                <input type="submit" value="Update" name="action" class="btn btn-primary" />             
                                <input type="hidden" name="task_id" value="<?php echo $col['id'] ?>" />
                            </form> 
                        </td>                        
                        <td>
                            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                                <input type="submit" value="Delete" name="action" class="btn btn-danger" />      
                                <input type="hidden" name="task_id" value="<?php echo $col['id'] ?>" />
                            </form>
                        </td>                                
                    </tr>
                    <?php endforeach; ?>
                   

                </table>
            </div>       

            

        
        </table>
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


        <script>
            //Runs right when the page first loads to show only the general requirements
            var anonfunc = function() {
                document.getElementById("computing").style.display = "none";
                document.getElementById("integration").style.display = "none";
                document.getElementById("college").style.display = "none";
                document.getElementById("general").style.display = "table";
                
            }();
            
            // To change between the different class types
            function changeTable(){
                var page = document.getElementById("Choice").value;
                // General requirements
                if (page == "1") {
                    document.getElementById("computing").style.display = "none";
                    document.getElementById("integration").style.display = "none";
                    document.getElementById("college").style.display = "none";
                    document.getElementById("general").style.display = "table";
                }
                // Computing Elective
                else if (page == "2") {
                    document.getElementById("integration").style.display = "none";
                    document.getElementById("general").style.display = "none";
                    document.getElementById("college").style.display = "none";
                    document.getElementById("computing").style.display = "table";
                }
                // Integration Elective
                else if (page == "3") {
                    document.getElementById("computing").style.display = "none";                    
                    document.getElementById("general").style.display = "none";
                    document.getElementById("college").style.display = "none";
                    document.getElementById("integration").style.display = "table";
                }

                //college requirements
                else
                {
                    document.getElementById("computing").style.display = "none";
                    document.getElementById("integration").style.display = "none";                                        
                    document.getElementById("general").style.display = "none";
                    document.getElementById("college").style.display = "table";                    

                }
                  
            }
            var choice = document.getElementById("Choice");
            choice.addEventListener('change', changeTable, false);
        </script>

    </div>
    <?php 
    }
    else{
        echo '<h5 style="text-align:center">You need to log in first before viewing this page <a href="LoginPage.php" ></br/><button class="btn btn-primary">Log in</button></a></h5>';
    }
    ?>
    <script>
      console.log(sessionStorage.getItem("user"));
    </script>
    
    
    
</html>

