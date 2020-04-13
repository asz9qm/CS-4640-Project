<?php


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

function createBasicCourseWork($user)
{
    addCourse("General", $user, "CS 1110", "Introduction to Programming", 0, "", "");
    addCourse("General", $user, "CS 2102", "Discrete Mathematics", 0, "", "");
    addCourse("General", $user, "CS 2110", "Software Development Methods", 0, "", "");
    addCourse("General", $user, "CS 2150", "Program and Data Representation", 0, "", "");
    addCourse("General", $user, "CS 3330", "Computer Architecture", 0, "", "");
    addCourse("General", $user, "CS 4102", "Algorithms", 0, "", "");

    addCourse("Computing", $user, "", "Computing Elective 1", 0, "", "");
    addCourse("Computing", $user, "", "Computing Elective 2", 0, "", "");
    addCourse("Computing", $user, "", "Computing Elective 3", 0, "", "");
    addCourse("Computing", $user, "", "Computing Elective 4", 0, "", "");

    addCourse("Integration", $user, "", "Integration Elective 1", 0, "", "");
    addCourse("Integration", $user, "", "Integration Elective 2", 0, "", "");
    addCourse("Integration", $user, "", "Integration Elective 3", 0, "", "");
    addCourse("Integration", $user, "", "Integration Elective 4", 0, "", "");

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

function deleteTask($id)
{
	require('connect-db.php');
	
	$query = "DELETE FROM courses WHERE id=:id";
	$statement = $db->prepare($query);
	$statement->bindValue(':id', $id);
	$statement->execute();
	$statement->closeCursor();
}


//uncomment this part and then recomment after clicking button
// if ($_SERVER["REQUEST_METHOD"] == "POST")
// {
//     createUserTable();
//     createCourseTable();
    
// }


?>

<!-- Uncomment this part and click the button to set up, then recomment this -->
<!-- <html>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" name="setup" method="post">
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
</html> -->

