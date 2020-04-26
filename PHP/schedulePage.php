<html>
    <?php 
    header('Access-Control-Allow-Origin: http://localhost:4200');
    header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding');    
    require("allActions.php");
    session_start(); ?>
    <head>
        <meta charset="UTF-8">
        <title>Schedule Page</title>
        <meta name="viewport" content="initial-scale=1.0">
        <meta charset="utf-8">
        <meta name="author" content="Jennifer Liao">
        <meta name="description" content="Schedule Page">
        <link rel="stylesheet" href="../CSS/main.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" /> 
        <!-- Need the following three lines in order for nav bar to work: https://stackoverflow.com/questions/45756307/bootstrap-4-toggle-button-not-working/45756365 -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>        
 <style>
            .Sameline { 
            /* Makes sure that div with this class label stay in same line */
             display: inline-block;  
             padding:-2px;
             margin:-5px;
             }
             .btn-circle {
            /* Defines circular button format */
            width: 30px;
            height: 30px;
            padding: 6px 0px;
            border-radius: 15px;
            border-color: black;
            text-align: center;
            font-size: 12px;
            line-height: 1.42857;
            }
            .col {
            /* formatting columns */
            display: inline-block;
            border: 1px solid gray;
            padding: 4px 8px;
            }
           </style>
    </head>

<body>

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
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            //get info to fill arrays with
            $semesters = array("Fall 2017", "Spring 2018", "Fall 2018", "Spring 2019", "Fall 2019", "Spring 2020", "Fall 2020", "Spring 2021", "Other");
            $semesters_results = array();
            foreach ($semesters as $semestername):
                $semesters_results[$semestername] = getAllCoursesPerSemester($_SESSION['user'], $semestername);
            endforeach;
            //get user stats
            $stats = getStats($_SESSION['user']);
        }
        else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == '+')
        {
            $taskExists = checkTasks($_SESSION['user'], $_POST['courseID']);
            if ($_POST['taken'] == "Yes" || $_POST['taken'] == "Y"){
                $_POST['taken'] = 1;
            }
            else if ($_POST['taken'] == "No"){
                $_POST['taken'] = 0;
            }
            if ($taskExists){
                // //update if it exists
                echo "exists";
                $result = getSameTask($_SESSION['user'], $_POST['courseID']);
                $items = array('category', 'courseName', 'grade');
                foreach ($items as $item):
                    if ($_POST[$item] == ""){
                        $_POST[$item] = $result[$item];
                    }
                endforeach;
                if ($_POST['taken'] == ""){
                    $_POST['taken'] = 0;
                }
                
                // updateTaskInfo($result['id'], $_POST['category'], $_POST['courseID'], $_POST['courseName'], $_POST['taken'], $_POST['semester'], $_POST['grade']);
                updateTaskInfo($result['id'], $_POST['category'], $_POST['courseID'], $_POST['courseName'],
                    $_POST['taken'], $_POST['semester'], $_POST['grade']);
                $result = getSameTask($_SESSION['user'], $_POST['courseID']);
                
                header('Location: schedulePage.php');
            }
            else{
                //add if it doesn't exist
                addCourse($_POST['category'], $_SESSION['user'], $_POST['courseID'], $_POST['courseName'], $_POST['taken'], $_POST['semester'], $_POST['grade']);
                header('Location: schedulePage.php');
            }
        }
        else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'X')
        {   
            //edit course to have clear all values
            $result = getSameTask($_SESSION['user'], $_POST['courseID']);
            updateTaskInfo($result['id'], $result['category'], $result['courseID'], $result['courseName'],
                0, "", "");
            header('Location: schedulePage.php');
        }
    ?>


    <!-- Semester Container-->
    <div class="container-fluid">    
        <div id="column" class="column">
            <h3>Course Stats:</h3> 
            <div id="row" class="row">
            <div id="column" class="column">
            General Requirements: <?php echo $stats['General']?>
            </div>
            <div id="column" class="column">
            Computing Electives: <?php echo $stats['Computing']?>
            </div>
            <div id="column" class="column">
            Integration Electives: <?php echo $stats['Integration']?>
            </div>
            <div id="column" class="column">
            College Requirements: <?php echo $stats['College']?>
            </div>
            </div>
        </div>
        <div id = "column" class = "column">
        <div id = "row" class = "row">

        <!-- Semester 1 -->
        <div class="column">
                <h1 id="semester1">Fall 2017</h1>
            <table class="table table-striped table-bordered" style="width:100%" id = "semester1table">
                <!-- Defining the Column Headers for CSS -->
                <colgroup>
                    <col class="table1">
                    <col class="table2">
                    <col class="table3">
                    <col class="table4">
                </colgroup>
                <!-- Column Names -->
                <tr>
                    <th>Mnemonic</th>
                    <th style="text-align: center;">(Y)</th>
                    <th>Grade</th>
                    <th>(X)</th>
                </tr>
                <?php 
                $semester1 = $semesters_results["Fall 2017"];
                foreach ($semester1 as $course): 
                ?>
                <tr>
                    <td>
                        <?php echo $course['courseID']; // refer to column name in the table ?> 
                    </td>
                    <td>
                        <?php if($course['taken'] == 1){echo "Yes";}; ?> 
                    </td>
                    <td>
                        <?php echo $course["grade"]; ?> 
                    </td>                     
                    <td>
                    <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" >
                        <input name="courseID" type="hidden" id="courseID1" size="10" value="<?=$course["courseID"]?>"/>
                        <input name="taken" type="hidden" id="taken1" size="4" value="<?=$course['taken']?>"/>
                        <input name="grade" type="hidden" id="grade1" size="4" value="<?=$course['grade']?>"/>
                        <input name="semester" type="hidden" id="semester" value="Fall 2017"/>                   
                        <input name="courseName" type="hidden" id="courseName" value=""/>
                        <input name="category" type="hidden" id="category" value=""/>
                        <input type=submit name="action" class='btn btn-default btn-circle' value='X'>
                    </form>
                    </td>                                
                </tr>
                <?php endforeach; ?>
                <!-- form for adding class to table -->
                <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" >
                <tr>
                    <td><input name="courseID" type="text" id="courseID1" size="10"/></td>
                    <td><input name="taken" type="text" id="taken1" size="4"/></td>
                    <td><input name="grade" type="text" id="grade1" size="4"/></td>
                    <input name="semester" type="hidden" id="semester" value="Fall 2017"/>                   
                    <input name="courseName" type="hidden" id="courseName" value=""/>
                    <input name="category" type="hidden" id="category" value=""/>
                    <td>
                    <button id="semester1add" name="action" value="+" type="submit" class="btn btn-default btn-circle">+<i class="fa fa-check"></i>
                    </td>
                </tr>
                </form>
            </table>
            <span class="error" id="addclass1-note"></span> 
        </div>

        <!-- Semester 2-->
        <div class="column">
            <h1 id="semester2">Spring 2018</h1>
            <table class="table table-striped table-bordered" style="width:100%" id = "semester1table">
                <!-- Defining the Column Headers for CSS -->
                <colgroup>
                    <col class="table1">
                    <col class="table2">
                    <col class="table3">
                    <col class="table4">
                </colgroup>
                <!-- Column Names -->
                <tr>
                    <th>Mnemonic</th>
                    <th style="text-align: center;">(Y)</th>
                    <th>Grade</th>
                    <th>(X)</th>
                </tr>
                <?php 
                $semester2 = $semesters_results["Spring 2018"];
                foreach ($semester2 as $course): 
                ?>
                <tr>
                    <td>
                        <?php echo $course['courseID']; // refer to column name in the table ?> 
                    </td>
                    <td>
                        <?php if($course['taken'] == 1){echo "Yes";}; ?> 
                    </td>
                    <td>
                        <?php echo $course["grade"]; ?> 
                    </td>                     
                    <td>
                    <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" >
                        <input name="courseID" type="hidden" id="courseID1" size="10" value="<?=$course["courseID"]?>"/>
                        <input name="taken" type="hidden" id="taken1" size="4" value="<?=$course['taken']?>"/>
                        <input name="grade" type="hidden" id="grade1" size="4" value="<?=$course['grade']?>"/>
                        <input name="semester" type="hidden" id="semester" value="Spring 2018"/>                   
                        <input name="courseName" type="hidden" id="courseName" value=""/>
                        <input name="category" type="hidden" id="category" value=""/>
                        <input type=submit name="action" class='btn btn-default btn-circle' value='X'>
                    </form>
                    </td>                                
                </tr>
                <?php endforeach; ?>
                <!-- form for adding class to table -->
                <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" >
                <tr>
                    <td><input name="courseID" type="text" id="courseID2" size="10"/></td>
                    <td><input name="taken" type="text" id="taken2" size="4"/></td>
                    <td><input name="grade" type="text" id="grade2" size="4"/></td>
                    <input name="semester" type="hidden" id="semester" value="Spring 2018"/>                   
                    <input name="courseName" type="hidden" id="courseName" value=""/>
                    <input name="category" type="hidden" id="category" value=""/>
                    <td>
                    <button id="semester2add" name="action" value="+" type="submit" class="btn btn-default btn-circle">+<i class="fa fa-check"></i>
                    </td>
                </tr>
                </form>
            </table>
            <span class="error" id="addclass2-note"></span> 
        </div>
            
        <!-- Semester 3 -->
        <div class="column">
            <h1 id="semester3">Fall 2018</h1>
            <table class="table table-striped table-bordered" style="width:100%" id = "semester1table">
                <!-- Defining the Column Headers for CSS -->
                <colgroup>
                    <col class="table1">
                    <col class="table2">
                    <col class="table3">
                    <col class="table4">
                </colgroup>
                <!-- Column Names -->
                <tr>
                    <th>Mnemonic</th>
                    <th style="text-align: center;">(Y)</th>
                    <th>Grade</th>
                    <th>(X)</th>
                </tr>
                <?php 
                $semester3 = $semesters_results["Fall 2018"];
                foreach ($semester3 as $course): 
                ?>
                <tr>
                    <td>
                        <?php echo $course['courseID']; // refer to column name in the table ?> 
                    </td>
                    <td>
                        <?php if($course['taken'] == 1){echo "Yes";}; ?> 
                    </td>
                    <td>
                        <?php echo $course["grade"]; ?> 
                    </td>                     
                    <td>
                    <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" >
                        <input name="courseID" type="hidden" id="courseID1" size="10" value="<?=$course["courseID"]?>"/>
                        <input name="taken" type="hidden" id="taken1" size="4" value="<?=$course['taken']?>"/>
                        <input name="grade" type="hidden" id="grade1" size="4" value="<?=$course['grade']?>"/>
                        <input name="semester" type="hidden" id="semester" value="Fall 2018"/>                   
                        <input name="courseName" type="hidden" id="courseName" value=""/>
                        <input name="category" type="hidden" id="category" value=""/>
                        <input type=submit name="action" class='btn btn-default btn-circle' value='X'>
                    </form>
                    </td>                                
                </tr>
                <?php endforeach; ?>
                <!-- form for adding class to table -->
                <tr>
                    <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" >
                    <td><input name="courseID" type="text" id="courseID2" size="10"/></td>
                    <td><input name="taken" type="text" id="taken2" size="4"/></td>
                    <td><input name="grade" type="text" id="grade2" size="4"/></td>
                    <input name="semester" type="hidden" id="semester" value="Fall 2018"/>                   
                    <input name="courseName" type="hidden" id="courseName" value=""/>
                    <input name="category" type="hidden" id="category" value=""/>
                    <td>
                    <button id="semester2add" name="action" value="+" type="submit" class="btn btn-default btn-circle">+<i class="fa fa-check"></i>
                    </td>
                    </form>
                </tr>
            </table>
            <span class="error" id="addclass3-note"></span> 
        </div>

        <!-- Semester 4-->
        <div class="column">
            <h1 id="semester4">Spring 2019</h1>
            <table class="table table-striped table-bordered" style="width:100%" id = "semester1table">
                <!-- Defining the Column Headers for CSS -->
                <colgroup>
                    <col class="table1">
                    <col class="table2">
                    <col class="table3">
                    <col class="table4">
                </colgroup>
                <!-- Column Names -->
                <tr>
                    <th>Mnemonic</th>
                    <th style="text-align: center;">(Y)</th>
                    <th>Grade</th>
                    <th>(X)</th>
                </tr>
                <?php 
                $semester4 = $semesters_results["Spring 2019"];
                foreach ($semester4 as $course): 
                ?>
                <tr>
                    <td>
                        <?php echo $course['courseID']; // refer to column name in the table ?> 
                    </td>
                    <td>
                        <?php if($course['taken'] == 1){echo "Yes";}; ?> 
                    </td>
                    <td>
                        <?php echo $course["grade"]; ?> 
                    </td>                     
                    <td>
                    <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" >
                        <input name="courseID" type="hidden" id="courseID4" size="10" value="<?=$course["courseID"]?>"/>
                        <input name="taken" type="hidden" id="taken4" size="4" value="<?=$course['taken']?>"/>
                        <input name="grade" type="hidden" id="grade4" size="4" value="<?=$course['grade']?>"/>
                        <input name="semester" type="hidden" id="semester" value="Spring 2019"/>                   
                        <input name="courseName" type="hidden" id="courseName" value=""/>
                        <input name="category" type="hidden" id="category" value=""/>
                        <input type=submit name="action" class='btn btn-default btn-circle' value='X'>
                    </form>
                    </td>                                
                </tr>
                <?php endforeach; ?>
                <!-- form for adding class to table -->
                <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" >
                <tr>
                    <td><input name="courseID" type="text" id="courseID4" size="10"/></td>
                    <td><input name="taken" type="text" id="taken4" size="4"/></td>
                    <td><input name="grade" type="text" id="grade4" size="4"/></td>
                    <input name="semester" type="hidden" id="semester" value="Spring 2019"/>                   
                    <input name="courseName" type="hidden" id="courseName" value=""/>
                    <input name="category" type="hidden" id="category" value=""/>
                    <td>
                    <button id="semester4add" name="action" value="+" type="submit" class="btn btn-default btn-circle">+<i class="fa fa-check"></i>
                    </td>
                </tr>
                </form>
            </table>
            <span class="error" id="addclass4-note"></span> 
        </div>
            
        <!-- Semester 5-->
        <div class="column">
            <h1 id="semester5">Fall 2019</h1>
            <table class="table table-striped table-bordered" style="width:100%" id = "semester1table">
                <!-- Defining the Column Headers for CSS -->
                <colgroup>
                    <col class="table1">
                    <col class="table2">
                    <col class="table3">
                    <col class="table4">
                </colgroup>
                <!-- Column Names -->
                <tr>
                    <th>Mnemonic</th>
                    <th style="text-align: center;">(Y)</th>
                    <th>Grade</th>
                    <th>(X)</th>
                </tr>
                <?php 
                $semester5 = $semesters_results["Fall 2019"];
                foreach ($semester5 as $course): 
                ?>
                <tr>
                    <td>
                        <?php echo $course['courseID']; // refer to column name in the table ?> 
                    </td>
                    <td>
                        <?php if($course['taken'] == 1){echo "Yes";}; ?> 
                    </td>
                    <td>
                        <?php echo $course["grade"]; ?> 
                    </td>                     
                    <td>
                    <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" >
                        <input name="courseID" type="hidden" id="courseID5" size="10" value="<?=$course["courseID"]?>"/>
                        <input name="taken" type="hidden" id="taken5" size="4" value="<?=$course['taken']?>"/>
                        <input name="grade" type="hidden" id="grade5" size="4" value="<?=$course['grade']?>"/>
                        <input name="semester" type="hidden" id="semester" value="Fall 2019"/>                   
                        <input name="courseName" type="hidden" id="courseName" value=""/>
                        <input name="category" type="hidden" id="category" value=""/>
                        <input type=submit name="action" class='btn btn-default btn-circle' value='X'>
                    </form>
                    </td>                                
                </tr>
                <?php endforeach; ?>
                <!-- form for adding class to table -->
                <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" >
                <tr>
                    <td><input name="courseID" type="text" id="courseID5" size="10"/></td>
                    <td><input name="taken" type="text" id="taken5" size="4"/></td>
                    <td><input name="grade" type="text" id="grade5" size="4"/></td>
                    <input name="semester" type="hidden" id="semester" value="Fall 2019"/>                   
                    <input name="courseName" type="hidden" id="courseName" value=""/>
                    <input name="category" type="hidden" id="category" value=""/>
                    <td>
                    <button id="semester5add" name="action" value="+" type="submit" class="btn btn-default btn-circle">+<i class="fa fa-check"></i>
                    </td>
                </tr>
                </form>
            </table>
            <span class="error" id="addclass5-note"></span> 
        </div>

        <!-- Semester 6-->
        <div class="column">
            <h1 id="semester6">Spring 2020</h1>
            <table class="table table-striped table-bordered" style="width:100%" id = "semester1table">
                <!-- Defining the Column Headers for CSS -->
                <colgroup>
                    <col class="table1">
                    <col class="table2">
                    <col class="table3">
                    <col class="table4">
                </colgroup>
                <!-- Column Names -->
                <tr>
                    <th>Mnemonic</th>
                    <th style="text-align: center;">(Y)</th>
                    <th>Grade</th>
                    <th>(X)</th>
                </tr>
                <?php 
                $semester6 = $semesters_results["Spring 2020"];
                foreach ($semester6 as $course): 
                ?>
                <tr>
                    <td>
                        <?php echo $course['courseID']; // refer to column name in the table ?> 
                    </td>
                    <td>
                        <?php if($course['taken'] == 1){echo "Yes";}; ?> 
                    </td>
                    <td>
                        <?php echo $course["grade"]; ?> 
                    </td>                     
                    <td>
                    <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" >
                        <input name="courseID" type="hidden" id="courseID6" size="10" value="<?=$course["courseID"]?>"/>
                        <input name="taken" type="hidden" id="taken6" size="4" value="<?=$course['taken']?>"/>
                        <input name="grade" type="hidden" id="grade6" size="4" value="<?=$course['grade']?>"/>
                        <input name="semester" type="hidden" id="semester" value="Spring 2020"/>                   
                        <input name="courseName" type="hidden" id="courseName" value=""/>
                        <input name="category" type="hidden" id="category" value=""/>
                        <input type=submit name="action" class='btn btn-default btn-circle' value='X'>
                    </form>
                    </td>                                
                </tr>
                <?php endforeach; ?>
                <!-- form for adding class to table -->
                <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" >
                <tr>
                    <td><input name="courseID" type="text" id="courseID6" size="10"/></td>
                    <td><input name="taken" type="text" id="taken6" size="4"/></td>
                    <td><input name="grade" type="text" id="grade6" size="4"/></td>
                    <input name="semester" type="hidden" id="semester" value="Spring 2020"/>                   
                    <input name="courseName" type="hidden" id="courseName" value=""/>
                    <input name="category" type="hidden" id="category" value=""/>
                    <td>
                    <button id="semester6add" name="action" value="+" type="submit" class="btn btn-default btn-circle">+<i class="fa fa-check"></i>
                    </td>
                </tr>
                </form>
            </table>
            <span class="error" id="addclass6-note"></span> 
        </div>

        <!-- Semester 7 -->
        <div class="column">
            <h1 id="semester7">Fall 2020</h1>
            <table class="table table-striped table-bordered" style="width:100%" id = "semester1table">
                <!-- Defining the Column Headers for CSS -->
                <colgroup>
                    <col class="table1">
                    <col class="table2">
                    <col class="table3">
                    <col class="table4">
                </colgroup>
                <!-- Column Names -->
                <tr>
                    <th>Mnemonic</th>
                    <th style="text-align: center;">(Y)</th>
                    <th>Grade</th>
                    <th>(X)</th>
                </tr>
                <?php 
                $semester7 = $semesters_results["Fall 2020"];
                foreach ($semester7 as $course): 
                ?>
                <tr>
                    <td>
                        <?php echo $course['courseID']; // refer to column name in the table ?> 
                    </td>
                    <td>
                        <?php if($course['taken'] == 1){echo "Yes";}; ?> 
                    </td>
                    <td>
                        <?php echo $course["grade"]; ?> 
                    </td>                     
                    <td>
                    <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" >
                        <input name="courseID" type="hidden" id="courseID7" size="10" value="<?=$course["courseID"]?>"/>
                        <input name="taken" type="hidden" id="taken7" size="4" value="<?=$course['taken']?>"/>
                        <input name="grade" type="hidden" id="grade7" size="4" value="<?=$course['grade']?>"/>
                        <input name="semester" type="hidden" id="semester" value="Fall 2020"/>                   
                        <input name="courseName" type="hidden" id="courseName" value=""/>
                        <input name="category" type="hidden" id="category" value=""/>
                        <input type=submit name="action" class='btn btn-default btn-circle' value='X'>
                    </form>
                    </td>                                
                </tr>
                <?php endforeach; ?>
                <!-- form for adding class to table -->
                <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" >
                <tr>
                    <td><input name="courseID" type="text" id="courseID7" size="10"/></td>
                    <td><input name="taken" type="text" id="taken7" size="4"/></td>
                    <td><input name="grade" type="text" id="grade7" size="4"/></td>
                    <input name="semester" type="hidden" id="semester" value="Fall 2020"/>                   
                    <input name="courseName" type="hidden" id="courseName" value=""/>
                    <input name="category" type="hidden" id="category" value=""/>
                    <td>
                    <button id="semester7add" name="action" value="+" type="submit" class="btn btn-default btn-circle">+<i class="fa fa-check"></i>
                    </td>
                </tr>
                </form>
            </table>
            <span class="error" id="addclass7-note"></span> 
        </div>

        <!-- Semester 8-->
        <div class="column">
            <h1 id="semester8">Spring 2021</h1>
            <table class="table table-striped table-bordered" style="width:100%" id = "semester1table">
                <!-- Defining the Column Headers for CSS -->
                <colgroup>
                    <col class="table1">
                    <col class="table2">
                    <col class="table3">
                    <col class="table4">
                </colgroup>
                <!-- Column Names -->
                <tr>
                    <th>Mnemonic</th>
                    <th style="text-align: center;">(Y)</th>
                    <th>Grade</th>
                    <th>(X)</th>
                </tr>
                <?php 
                $semester8 = $semesters_results["Spring 2021"];
                foreach ($semester8 as $course): 
                ?>
                <tr>
                    <td>
                        <?php echo $course['courseID']; // refer to column name in the table ?> 
                    </td>
                    <td>
                        <?php if($course['taken'] == 1){echo "Yes";}; ?> 
                    </td>
                    <td>
                        <?php echo $course["grade"]; ?> 
                    </td>                     
                    <td>
                    <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" >
                        <input name="courseID" type="hidden" id="courseID8" size="10" value="<?=$course["courseID"]?>"/>
                        <input name="taken" type="hidden" id="taken8" size="4" value="<?=$course['taken']?>"/>
                        <input name="grade" type="hidden" id="grade8" size="4" value="<?=$course['grade']?>"/>
                        <input name="semester" type="hidden" id="semester" value="Spring 2021"/>                   
                        <input name="courseName" type="hidden" id="courseName" value=""/>
                        <input name="category" type="hidden" id="category" value=""/>
                        <input type=submit name="action" class='btn btn-default btn-circle' value='X'>
                    </form>
                    </td>                                
                </tr>
                <?php endforeach; ?>
                <!-- form for adding class to table -->
                <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post" >
                <tr>
                    <td><input name="courseID" type="text" id="courseID8" size="10"/></td>
                    <td><input name="taken" type="text" id="taken8" size="4"/></td>
                    <td><input name="grade" type="text" id="grade8" size="4"/></td>
                    <input name="semester" type="hidden" id="semester" value="Spring 2021"/>                   
                    <input name="courseName" type="hidden" id="courseName" value=""/>
                    <input name="category" type="hidden" id="category" value=""/>
                    <td>
                    <button id="semester8add" name="action" value="+" type="submit" class="btn btn-default btn-circle">+<i class="fa fa-check"></i>
                    </td>
                </tr>
                </form>
            </table>
            <span class="error" id="addclass8-note"></span> 
        </div>

        </div>
    <!--- OTHER: Classes not yet taken and need to fulfilled --> 
    <!-- Where the other classes stuff should go -->
    
    </div>
</div>
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

    
    
    


<script>

    addRow = (addsemester, addtable, addClassNote) =>
    {
        // Adds a row to the semester 
        var className = document.getElementById(addsemester).value;
        var re_pattern = "([A-Za-z]{2,4}) ([0-9]+)";
        var result = className.match(re_pattern);

        if (className != null && result[1].length >= 2 && result[2].length <= 4 && result[2].length == 4){
            var table = document.getElementById(addtable);
            var newRow = table.insertRow(table.rows.length);
            document.getElementById(addsemester).value = "";

            var col2 = `<input type=button class='btn btn-default btn-circle' value=' x ' onClick='delRow("${addtable}")'>`;
            var rowdata = [className, col2];
            
            var tableRef = document.getElementById(addtable);
            var newRow = tableRef.insertRow(tableRef.rows.length);
            newRow.onmouseover = function() {         
                tableRef.clickedRowIndex = this.rowIndex;   // rowIndex returns the position of a row in the rows collection of a table     
            };
            var newCell = "";       
            var i = 0;          // In this example, each row has 4 columns. 
            // Use insertCell(index) method to insert new cells (<td> elements) at the 1st, 2nd, 3rd position of the new <tr> element        	      
            while (i < 2)
            {
                newCell = newRow.insertCell(i);            // specify which column 
                newCell.innerHTML = rowdata[i];            // assign what content  
                newCell.onmouseover = this.rowIndex;       // attach row index to the row
                i++;
            }
            document.getElementById(addClassNote).textContent = "";
        }
        else {
            //alert
            document.getElementById(addClassNote).textContent = "Please enter correct mnemonic";
        }
    }

    addTableRow = (semesternum, addtable, addClassNote) =>
    {   
        console.log("checkrun");
        // Adds a row to the semester table
        var courseID = document.getElementById("courseID" + semesternum).value;
        var re_pattern = "([A-Za-z]{2,4}) ([0-9]{4})";
        var result = courseID.match(re_pattern);
        var taken = document.getElementById("taken" + semesternum).value;
        var grade = document.getElementById("grade" + semesternum).value;

        if (result != null && result[1].length >= 2 && result[2].length <= 4 && result[2].length == 4){
            var table = document.getElementById(addtable);
            var newRow = table.insertRow(table.rows.length);
            document.getElementById("courseID" + semesternum).value = "";
            document.getElementById("taken" + semesternum).value = "";
            document.getElementById("grade" + semesternum).value = "";

            var col2 = `<input type=button class='btn btn-default btn-circle' value=' x ' onClick='delRow("${addtable}")'>`;
            var rowdata = [courseID, taken, grade, col2]; 
            
            var tableRef = document.getElementById(addtable);
            var newRow = tableRef.insertRow(tableRef.rows.length-2); //TODO FIX THIS: https://stackoverflow.com/questions/25970381/insert-rows-into-table-second-last-with-ajax
            newRow.onmouseover = function() {         
                tableRef.clickedRowIndex = this.rowIndex;   // rowIndex returns the position of a row in the rows collection of a table     
            };
            var newCell = "";       
            var i = 0;         
            // Use insertCell(index) method to insert new cells (<td> elements) at the 1st, 2nd, 3rd position of the new <tr> element        	      
            while (i < 4)
            {
                newCell = newRow.insertCell(i);            // specify which column 
                newCell.innerHTML = rowdata[i];            // assign what content  
                newCell.onmouseover = this.rowIndex;       // attach row index to the row
                i++;
            }
            document.getElementById(addClassNote).textContent = "";
        }
        else {
            //alert
            document.getElementById(addClassNote).textContent = "Please enter correct mnemonic";
        }
        console.log("runs");
        
    }
    
    delRow = removeTable =>
    {
        //deletes a row from the table given the table to delete from as a parameter
        document.getElementById(removeTable).deleteRow(document.getElementById(removeTable).clickedRowIndex);
    }
    
    function addSemester()
    {
        var semesterNum = document.getElementById("row").children.length+1;
        var rowContainer = document.querySelector("#row");
        var templateCol = 
        "<div class='column'> <div class='Sameline'><h1>Semester"+semesterNum+"</h1></div>"+
        "<div class='Sameline'> <button id='removeSemester' type='button' class='btn btn-default btn-circle' " +
        "onclick=\"removeDiv(this)\">-<i class='fa fa-check'></i> </div> <table id='semester"+semesterNum+"table' class='table'>"+            
        "<thead> <tr> <th>Class Name</th> <th>(x)</th> </tr> </thead> </table>"+
        "<div class='Sameline'> <div class='form-group'> <input type='text' id='addclass"+semesterNum+"' class='form-control' name='desc' />"+
        " </div> </div> <div class='Sameline'> <button id='semester"+semesterNum+"add' type='button' class='btn btn-default btn-circle' "+
        "onclick='addRow(\"addclass"+semesterNum+"\", \"semester"+semesterNum+"table\", \"addclass"+semesterNum+"-note\")'>+<i class='fa fa-check'></i> </div>"+     
        "</br>  <span class='error' id='addclass"+semesterNum+"-note'></span> </div> </div>";
        rowContainer.innerHTML = rowContainer.innerHTML + templateCol;
        
    }

    removeDiv = btn =>
    {
        //removes semester from the page
        ((btn.parentNode).parentNode.parentNode).removeChild(btn.parentNode.parentNode);
    }

    // document.getElementById("semester1add").addEventListener("click", function() 
    //     {
    //         addTableRow('1','semester1table','addclass1-note');
    //     });

</script>

    <?php 
    }
    else{
        
        echo '<h5 style="text-align:center">You need to log in first before viewing this page <a href="LoginPage.php" ></br/><button class="btn btn-primary">Log in</button></a></h5>';
        }
    ?>
</body>

</html>