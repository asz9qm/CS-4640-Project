<?php
//used to allow for the angular front end to add classes
session_start();

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: http://localhost:4200');
// header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');


// get the size of incoming data
$content_length = (int) $_SERVER['CONTENT_LENGTH'];

// retrieve data from the request
$postdata = file_get_contents("php://input");

$data = json_decode(file_get_contents("php://input"));

// Process data
// (this example simply extracts the data and restructures them back)

// Extract json format to PHP array
$request = json_decode($postdata);

$data = [];
$data[0]['length'] = $content_length;
foreach ($request as $k => $v)
{
  $data[0]['post_'.$k] = $v;
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
if(isset($request->email))
{
  addCourse($request->category, $request->email, $request->courseID, $request->courseName, $request->taken,$request->semester,$request->grade);
}


// Send response (in json format) back the front end
echo json_encode(['content'=>$data]);
?>

