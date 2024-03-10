<?php





if (!isset($_SESSION['username'])){

    session_start();
session_unset();

session_destroy();

header('Location: index.php');
}else{ 

    header('Location: index.php');

}


?>