<?php

//contains all the functions that we used and need for php

//creates the basic user table
function createUserTable()
{
   require('connect-db.php');


   $query = "CREATE TABLE users (
             email VARCHAR(200) PRIMARY KEY,
             pass VARCHAR(300),
             schoolyear int,
             major VARCHAR(100))";

   $statement = $db->prepare($query);
   $statement->execute();   
   $statement->closeCursor();

}

//creates the basic course table
function createCourseTable()
{
    require('connect-db.php');

    $query = "CREATE TABLE courses (
        id INT PRIMARY KEY AUTO_INCREMENT,
        category VARCHAR(50),
        email VARCHAR(200),
        courseID VARCHAR(9),
        courseName VARCHAR(60),
        taken TINYINT,
        semester VARCHAR(15),
        grade VARCHAR(2))";
    
    $statement = $db->prepare($query);      
    $statement->execute();   
    $statement->closeCursor();
}


//allows for the user to logout
function logout()
{
    session_start();
    if (count($_SESSION) > 0) 
    {
        foreach ($_SESSION as $key => $value)
        {
            unset($_SESSION[$key]);
        }     
    session_destroy();
    }
    header('refresh:3; url=LoginPage.php');
}






//adds a course into the database, not currently being used as we used angular to perform the request
function addCourse($category, $email, $courseID, $courseName, $taken, $semester, $grade)
{
    require('connect-db.php');
    $query = "INSERT INTO courses (category, email, courseID, courseName, taken, semester, grade)
         VALUES (:category, :email, :courseID, :courseName, :taken, :semester, :grade)";

	$statement = $db->prepare($query);
	$statement->bindValue(':category', $category);
	$statement->bindValue(':email', $email);
    $statement->bindValue(':courseID', $courseID);
    $statement->bindValue(':courseName', $courseName);
    $statement->bindValue(':taken', $taken);
    $statement->bindValue(':semester', $semester);
    $statement->bindValue(':grade', $grade);
    $statement->execute();     
    $statement->closeCursor();
    
}

//creates the basic coursework for all users when they register for the site
function createBasicCourseWork($user)
{
    //general cs classes
    addCourse("General", $user, "CS 1110", "Introduction to Programming", 0, "", "");
    addCourse("General", $user, "CS 2102", "Discrete Mathematics", 0, "", "");
    addCourse("General", $user, "CS 2110", "Software Development Methods", 0, "", "");
    addCourse("General", $user, "CS 2150", "Program and Data Representation", 0, "", "");
    addCourse("General", $user, "CS 3330", "Computer Architecture", 0, "", "");
    addCourse("General", $user, "CS 4102", "Algorithms", 0, "", "");

    //computing electives
    addCourse("Computing", $user, "", "Computing Elective 1", 0, "", "");
    addCourse("Computing", $user, "", "Computing Elective 2", 0, "", "");
    addCourse("Computing", $user, "", "Computing Elective 3", 0, "", "");
    addCourse("Computing", $user, "", "Computing Elective 4", 0, "", "");

    //integration electives
    addCourse("Integration", $user, "", "Integration Elective 1", 0, "", "");
    addCourse("Integration", $user, "", "Integration Elective 2", 0, "", "");
    addCourse("Integration", $user, "", "Integration Elective 3", 0, "", "");
    addCourse("Integration", $user, "", "Integration Elective 4", 0, "", "");

    //general college requirements
    addCourse("College", $user, "", "First Writing Requirement", 0, "", "");
    addCourse("College", $user, "", "Second Writing Requirement", 0, "", "");
    addCourse("College", $user, "", "Foreign Language Requirement 1", 0, "", "");
    addCourse("College", $user, "", "Foreign Language Requirement 2", 0, "", "");
    addCourse("College", $user, "", "Foreign Language Requirement 3", 0, "", "");
    addCourse("College", $user, "", "Foreign Language Requirement 4", 0, "", "");
    addCourse("College", $user, "", "Social Science Requirement 1", 0, "", "");
    addCourse("College", $user, "", "Social Science Requirement 2", 0, "", "");
    addCourse("College", $user, "", "Humanities Requirement 1", 0, "", "");
    addCourse("College", $user, "", "Humanities Requirement 2", 0, "", "");
    addCourse("College", $user, "", "Historical Studies Requirement 1", 0, "", "");
    addCourse("College", $user, "", "Non-Western Perspective 1", 0, "", "");
    addCourse("College", $user, "", "Natural Science/Math Requirement 1", 0, "", "");
    addCourse("College", $user, "", "Natural Science/Math Requirement 2", 0, "", "");
    addCourse("College", $user, "", "Natural Science/Math Requirement 3", 0, "", "");
    addCourse("College", $user, "", "Natural Science/Math Requirement 4", 0, "", "");


}

//meant to get all the courses that have the same email and category
function getAllTasks($email, $category)
{
    require('connect-db.php');
	$query = "SELECT * FROM courses WHERE email = :email AND category = :category";
    $statement = $db->prepare($query);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':category', $category);
	$statement->execute();
	
	// fetchAll() returns an array for all of the rows in the result set
	$results = $statement->fetchAll();
	
	// closes the cursor and frees the connection to the server so other SQL statements may be issued
	$statement->closecursor();
	
	return $results;
}

function getStats($email)
{
    require('connect-db.php');
	$query = "SELECT * FROM courses WHERE email = :email";
    $statement = $db->prepare($query);
    $statement->bindParam(':email', $email);
	$statement->execute();
	
	// fetchAll() returns an array for all of the rows in the result set
	$results = $statement->fetchAll();
	
	// closes the cursor and frees the connection to the server so other SQL statements may be issued
    $statement->closecursor();
    $categories = array("General" => 0, "Computing" => 0, "Integration" => 0, "College" => 0, "GPA"=>0, "credits"=>0);
    foreach ($results as $result):
        if ($result['grade']){
            $grade = 0.0;
            switch ($result['grade']){
                case ($result['grade'] == "A+") || ($result['grade'] == "A"):
                    $grade += 4.0;
                  break;
                case $result['grade'] == "A-":
                    $grade += 3.7;
                  break;
                case $result['grade'] == "B+":
                    $grade += 3.3;
                  break;
                case $result['grade'] == "B":
                    $grade += 3.0;
                  break;
                case $result['grade'] == "B-":
                    $grade = 2.7;
                  break;
                case $result['grade'] == "C+":
                    $grade = 2.3;
                  break;
                case $result['grade'] == "C":
                    $grade = 2.0;
                  break;
                case $result['grade'] == "C-":
                    $grade = 1.7;
                  break;
                case $result['grade'] == "D+":
                    $grade = 1.3;
                  break;
                case $result['grade'] == "D":
                    $grade = 1.0;
                  break;
                case $result['grade'] == "D-":
                    $grade = 0.7;
                  break;
                case $result['grade'] == "F":       
                    $grade = 0.0;  
                  break;       
            }
            if (($grade != 0) || ($result['grade'] == "F")){
                $categories['GPA'] += $grade*3;
                $categories['credits'] += 3;
            }
        }
        if ($result['category'] == "General" && $result['semester'] == ""){
            $categories['General'] = $categories['General'] + 1;}
        if ($result['category'] == "Computing" && $result['semester'] == ""){
            $categories['Computing'] = $categories['Computing'] + 1;}   
        if ($result['category'] == "Integration" && $result['semester'] == ""){
            $categories['Integration'] = $categories['Integration'] + 1;}
        if ($result['category'] == "College" && $result['semester'] == ""){
            $categories['College'] = $categories['College'] + 1;}
    endforeach;
	
	return $categories;
}

//gets the courses per semester
function getAllCoursesPerSemester($email, $semester)
{
    //return array of courses that match the specified semester
    require('connect-db.php');
    $query = "SELECT * FROM courses WHERE email = :email AND semester = :semester";
    $statement = $db->prepare($query);
    $statement->bindParam(':email', $email);
    if ($semester == "Other"){
        $semester = "";
    }
    $statement->bindParam(':semester', $semester);
    $statement->execute();
    
    // fetchAll() returns an array for all of the rows in the result set
    $results = $statement->fetchAll();
    
    // closes the cursor and frees the connection to the server so other SQL statements may be issued
    $statement->closecursor();
    
    return $results;
}

//verifies that there are no duplicates
function checkTasks($email, $courseID)
{
    require('connect-db.php');
	$query = "SELECT * FROM courses WHERE email = :email AND courseID = :courseID";
    $statement = $db->prepare($query);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':courseID', $courseID);
	$statement->execute();
	
	// fetchAll() returns an array for all of the rows in the result set
    $results = $statement->fetchAll();
    
	// closes the cursor and frees the connection to the server so other SQL statements may be issued
	$statement->closecursor();
    
    if (!empty($results)){
        return true;
    }
    
	return false;
}

//returns the course that matches the information
function getSameTask($email, $courseID)
{
    require('connect-db.php');
	$query = "SELECT * FROM courses WHERE email = :email AND courseID = :courseID";
    $statement = $db->prepare($query);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':courseID', $courseID);
	$statement->execute();
	
	// fetchAll() returns an array for all of the rows in the result set
    $results = $statement->fetchAll();
    
	// closes the cursor and frees the connection to the server so other SQL statements may be issued
	$statement->closecursor();
    
    return $results[0];
}

//gets the course based on the course id which is a unqiue identifier
function getOneTask($id)
{
    require('connect-db.php');
	$query = "SELECT * FROM courses WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->bindParam(':id', $id);
	$statement->execute();
	
	// fetchAll() returns an array for all of the rows in the result set
	$results = $statement->fetchAll();
	
	// closes the cursor and frees the connection to the server so other SQL statements may be issued
	$statement->closecursor();
	
	return $results;
}

//pulls the user information from the user database
function getUserInfo($email)
{
    require('connect-db.php');
	$query = "SELECT * FROM users WHERE email = :email";
    $statement = $db->prepare($query);
    $statement->bindParam(':email', $email);
	$statement->execute();
	
	// fetchAll() returns an array for all of the rows in the result set
	$results = $statement->fetchAll();
	
	// closes the cursor and frees the connection to the server so other SQL statements may be issued
	$statement->closecursor();
	
	return $results;
}

//used to update the task
function updateTaskInfo($id, $category, $courseID, $courseName, $taken, $semester, $grade)
{
	require('connect-db.php');
	
	// example SQL statement to update data 
	// id is a primary identifying a row of data in the table
	// courses (category, email, courseID, courseName, taken, semester, grade)
	$query = "UPDATE courses SET category=:category, courseID=:courseID, courseName=:courseName, taken=:taken,
        semester=:semester, grade=:grade WHERE id=:id";
	$statement = $db->prepare($query);
	$statement->bindValue(':category', $category);
	$statement->bindValue(':courseID', $courseID);
	$statement->bindValue(':courseName', $courseName);
    $statement->bindValue(':taken', $taken);
    $statement->bindValue(':semester', $semester);
    $statement->bindValue(':grade', $grade);
    $statement->bindValue(':id', $id);
	$statement->execute();
	$statement->closeCursor();
}

//used to delete the task
function deleteTask($id)
{
	require('connect-db.php');
	
	$query = "DELETE FROM courses WHERE id=:id";
	$statement = $db->prepare($query);
	$statement->bindValue(':id', $id);
	$statement->execute();
	$statement->closeCursor();
}


//this stuff down here is meant for setup of the database 
//meant to be fast and convienent

//uncomment this part and then recomment after clicking button
// if ($_SERVER["REQUEST_METHOD"] == "POST")
// {
//     createUserTable();
//     createCourseTable();
    
// }

//move the following underneath ? > (get rid of all spaces between ? >) and follow the instructions
// <!-- Uncomment this part and click the button to set up, then recomment this -->
// <!-- <html>
//     <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);? >" name="setup" method="post">
//     <button type="submit" class="btn btn-primary">Submit</button>
// </form>
// </html> -->

?>

