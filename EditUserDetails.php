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
elseif(!isset($data->fname) 
	|| !isset($data->lname)
    || empty(trim($data->fname))
	|| empty(trim($data->lname))
    ):

    $fields = ['fields' => ['fname','lname']];
    $returnData = msg(0,422,'Please Fill in all Required Fields!',$fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else:
    
    $fname = trim($data->fname);
	$lname = trim($data->lname);

    if(strlen($fname) < 3):
        $returnData = msg(0,422,'Your FirstName must be at least 3 characters long!');
		
	else if(strlen($lname) < 3):
        $returnData = msg(0,422,'Your LastName must be at least 3 characters long!');

    else:
        try{

                $update_query = "UPDATE `users` SET fname`=:fname,`lname`=:lname";

                $update_data = $con->prepare($update_query);

                // DATA BINDING
                $update_data->bindValue(':fname', htmlspecialchars(strip_tags($fname)),PDO::PARAM_STR);
				$update_data->bindValue(':lname', htmlspecialchars(strip_tags($lname)),PDO::PARAM_STR);

                $update_data->execute();

                $returnData = msg(1,201,'You have successfully registered.');

            endif;

        }
        catch(PDOException $e){
            $returnData = msg(0,500,$e->getMessage());
        }
    endif;
    
endif;

echo json_encode($returnData);
