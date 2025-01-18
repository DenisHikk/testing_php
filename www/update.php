<?php
namespace www;
include_once "./classes/ConnectToDB.php";
include_once "./classes/CsvDB.php";
$conn = classes\ConnectToDB::getInstance();
$cvsDB = new classes\CsvDB($conn);



session_start();

if(isset($_SESSION["user_id"])) {
    $userID = $_SESSION["user_id"];
} else {
    $userID = 1;
}

if($_SERVER["REQUEST_METHOD"] === 'GET') {
    $file = $cvsDB->exportToCsvFromDB($userID);
    readfile($file);
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_POST['command'] === 'import') {
        if (isset($_FILES['file'])) {
            if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
                $file = $_FILES['file']['tmp_name'];
                $cvsDB->importFromCsvToDB($file, $userID);
            } else {
                echo "Error while upload file. Code of erros: " . $_FILES['file']['error'];
            }
        } else {
            echo "File wasn't received";
        }
    } else if($_POST['command'] === 'update_session') {
        if(isset($_POST["userId"])) {
            $_SESSION["user_id"] = $_POST["userId"];
            echo "Save!";
        }
    } else {
        echo "";
    } 
}










