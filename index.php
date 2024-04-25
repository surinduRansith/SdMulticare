<?php
$error = "";
$setusername = "";
$userName = "";
$userRole = "";

include("../sd_multicare/dbconnect/dbconnect.php");

if (isset($_POST["submit"])) {

    if (empty(trim($_POST["username"])) || empty(trim($_POST["password"])) || empty(trim($_POST["username"])) && empty(trim($_POST["password"]))) {

        $error = "Please fill the user Name and Password";
    } else {

        $userName = trim($_POST["username"]);
        $password = md5(trim($_POST["password"]));


        $sql = "Select * from userlogin where username = '" . $userName . "' and password='" . $password . "'";


        $result = mysqli_query($conn, $sql);
            if($result->num_rows > 0){

                while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){


            header('Location: home.php');
            $userRole = $row['role'];
                }
        } else {
            $setusername = $userName;
            $error = 'User Name or Password Incorrect';
        }
    }
    session_start();
    $_SESSION['username'] = $userName;
    $_SESSION['userrole'] =$userRole;
}





?>



<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <link rel="shortcut icon" type="x-icon" href="assets/images/sdlogo.jpeg">

    <title>Login</title>
</head>

<body class="bg-secondary">
    <div
        class="container text-center position-absolute top-50 start-50 translate-middle w-25 p-3 border border-warning rounded">
        <form method="post">
            <div>
                <img src="./assets/Images/sdlogo.jpeg" alt="invoice" class="img-fluid"
                    style="width:150px; height: 150px;">
            </div>
            <br>
            <?php

            if (isset($_POST["submit"])) {

                echo "<div class='alert alert-danger' role='alert'>" . $error . "</div>";
            } else {

                echo "<div>" . $error . "</div>";
            }



            ?>
            <br>
            <div>
                <input type="text" name="username" class="form-control " placeholder="Username"
                    value="<?php echo $setusername; ?>">
            </div>
            <br>
            <br>
            <div>
                <input type="password" name="password" class="form-control" placeholder="Password">
            </div>
            <br>
            <div>
                <button class="btn btn-success" name="submit"><b> Login Now </b></button>
            </div>


            <br><br>





        </form>



    </div>
</body>

</html>