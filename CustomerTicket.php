<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
}

// INCLUDING DATABASE AND MAKING OBJECT
require __DIR__.'/classes/Database.php';
$db_connection = new Database();
$con = $db_connection->dbConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

// IF REQUEST METHOD IS NOT POST
if($_SERVER["REQUEST_METHOD"] != "POST"):
    $returnData = msg(0,404,'Page Not Found!');

// CHECK FOR EMPTY FIELDS
elseif(!isset($data->userid) 
	|| !isset($data->message)
    || empty(trim($data->userid))
	|| empty(trim($data->message))
    ):

    $fields = ['fields' => ['userid','message']];
    $returnData = msg(0,422,'Please Fill in all Required Fields!',$fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else:
    
    $userid = trim($data->userid);
	$message = trim($data->message);

	
	elseif(strlen($message) < 15):
        $returnData = msg(0,422,'Your Message must be at least 15 characters long!');	
		
    else:
        try{

            $check_userid = "SELECT `userid` FROM `users` WHERE `userid`=:userid";
            $check_userid_query = $con->prepare($check_userid);
            $check_userid_query->bindValue(':userid', $userid,PDO::PARAM_STR);
            $check_userid_query->execute();

            if($check_userid_query->rowCount()):
                
                $insert_query = "INSERT INTO `customerticket`(`userid`,`message`) VALUES(:userid,:message)";

                $insert_stmt = $con->prepare($insert_query);

                // DATA BINDING
                $insert_stmt->bindValue(':userid', htmlspecialchars(strip_tags($userid)),PDO::PARAM_STR);
				$insert_stmt->bindValue(':message', htmlspecialchars(strip_tags($message)),PDO::PARAM_STR);

                $insert_stmt->execute();

                $returnData = msg(1,201,'You have successfully Submitted the ticket.');

            endif;

        }
        catch(PDOException $e){
            $returnData = msg(0,500,$e->getMessage());
        }
    endif;
    
endif;

echo json_encode($returnData);
