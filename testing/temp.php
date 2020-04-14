<div id = "row" class = "row">
        <h1 id="other">Other</h1>
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
                    <th>Category</th>
                    <th style="text-align: center;">(Y)</th>
                    <th>Grade</th>
                    <th>(X)</th>
                </tr>
                <?php 
                $other = $semesters_results["Other"];
                foreach ($other as $course): 
                ?>
                <tr>
                    <td>
                        <?php echo $course['courseID']; // refer to column name in the table ?> 
                    </td>
                    <td>
                        <?php echo $course['category']; ?> 
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
                    <td><input name="courseID" type="text" id="courseID8" size="10"/></td>
                    <td><input name="category" type="text" id="category" value=""/></td>
                    <td><input name="taken" type="text" id="taken8" size="4"/></td>
                    <td><input name="grade" type="text" id="grade8" size="4"/></td>
                    <input name="semester" type="hidden" id="semester" value="Spring 2018"/>                   
                    <input name="courseName" type="hidden" id="courseName" value=""/>
                    <td>
                    <button id="semester8add" name="action" value="+" type="submit" class="btn btn-default btn-circle">+<i class="fa fa-check"></i>
                    </td>
                </tr>
                </form>
            </table>
            <span class="error" id="addclass8-note"></span> 
    </div>