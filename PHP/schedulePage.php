<html>
    <?php 
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
    <?php
    //define all functions here
    function getAllCoursesPerSemester($email, $semester)
    {
        //return array of courses that match the specified semester
        require('connect-db.php');
        $query = "SELECT * FROM courses WHERE email = :email AND semester = :semester";
        $statement = $db->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':semester', $semester);
        $statement->execute();
        
        // fetchAll() returns an array for all of the rows in the result set
        $results = $statement->fetchAll();
        
        // closes the cursor and frees the connection to the server so other SQL statements may be issued
        $statement->closecursor();
        
        return $results;
    }

    function addCourseBySemestertoDB($semester, $courseID, $taken, $grade, $courseInfo)
    {
        require('connect-db.php');
        echo "add course to DB placeholder";
    }

    ?>
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
    //update tables from database here
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $semesters = array("Fall 2017", "Spring 2018", "Fall 2018", "Spring 2019", "Fall 2019", "Spring 2020", "Fall 2020", "Spring 2021");
            $semesters_results = array();
            foreach ($semesters as $semestername):
                $semesters_results[$semestername] = getAllCoursesPerSemester($_SESSION['user'], $semestername);
            endforeach;
        }
        else if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            echo "ran post else if";
            echo $_POST['semester'];
            var_dump($_POST);
            // header("Location: add.php");

            // if (!empty($_POST['action']) && ($_POST['action'] == '+'))
            // { 
            //     header("Location: add.php");
            // }
        }
    ?>

    <!-- Semester Container-->
    <div class="container-fluid">
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
                        <input type=button class='btn btn-default btn-circle' value=' x ' onClick=''>
                    </td>                                
                </tr>
                <?php endforeach; ?>
                <!-- form for adding class to table -->
                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" >
                <tr>
                    <td><input name="courseID" type="text" id="courseID1" size="10" /></td>
                    <td><input name="taken" type="text" id="taken1" size="4"/></td>
                    <td><input name="grade" type="text" id="grade1" size="4"/></td>
                    <input name="semester" type="hidden" id="semester" value="Fall 2017"/>                   
                    <input name="courseName" type="hidden" id="courseName" value=""/>
                    <input name="category" type="hidden" id="category" value=""/>
                    <td>
                    <button id="semester1add" name="action" type="submit" class="btn btn-default btn-circle">+<i class="fa fa-check"></i>
                    </td>
                </tr>
                </form>
            </table>
            <span class="error" id="addclass1-note"></span> 
        </div>

        <!-- Semester 2 -->
        <div class="column">
            <h1>Spring 2018</h1>
            <table class="table table-striped table-bordered" style="width:100%" id = "integration">
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
                        <input type=button class='btn btn-default btn-circle' value=' x ' onClick=''>
                    </td>                                
                </tr>
                <?php endforeach; ?>
                
            </table>


            <div class="Sameline">
                <div class="form-group">
                <input type="text" id="addclass2" class="form-control" name="desc" />
            </div>
            </div> 
                <div class="Sameline">
                    <!-- <button id="semester2add" type="button" class="btn btn-default btn-circle" onclick="addTableRow('addclass2', 'semester2table', 'addclass2-note')">+<i class="fa fa-check"></i>      -->
                </div>
            </br>
            <span class="error" id="addclass2-note"></span>  
            </div>

            <!--Semester 2-->
            <!-- <div class="column">
                <h1>Spring 2018</h1>
                    <table id="semester2table" class="table" >
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>(x)</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="Sameline">
                        <div class="form-group">
                        <input type="text" id="addclass2" class="form-control" name="desc" />
                        </div>
                    </div> 
                    <div class="Sameline">
                        <button id="semester2add" type="button" class="btn btn-default btn-circle" onclick="addRow('addclass2', 'semester2table', 'addclass2-note')">+<i class="fa fa-check"></i>     
                    </div>
                    </br>
                    <span class="error" id="addclass1-note"></span>  
            </div> -->

        </div>
            
            <div class="column">
                <div class = "Sameline">
                    <p>Add a semester</p>
                </div>
                <div class="Sameline">
                    <button id="addSemesterCol" type="button" class="btn btn-default btn-circle" onclick="addSemester()">+<i class="fa fa-check"></i>     
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

    </div>
    <div id="output">waiting for action</div>

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

    document.getElementById("semester1add").addEventListener("click", function() 
        {
            addTableRow('1','semester1table','addclass1-note');
        });

</script>

    <?php 
    }
    else{
        
        echo '<h5 style="text-align:center">You need to log in first before viewing this page <a href="LoginPage.php" ></br/><button class="btn btn-primary">Log in</button></a></h5>';
        }
    ?>
</body>

</html>