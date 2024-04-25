<?php 
include("../dbconnect/dbconnect.php");
include("../dbconnect/queris/select.php");
include("../dbconnect/queris/updateItemData.php");


$submited = true;
$inValid = true;
$closeButton="";


$userid="";
$userName="";
$password="";
$role="";


if(isset($_GET["userid"])){

    $userid=$_GET["userid"];

    $useridresult = getuserLoginid($userid,$conn);

    if($useridresult->num_rows > 0){

        while($row = mysqli_fetch_array($useridresult,MYSQLI_ASSOC)){

            $userName=$row['username'];
            $password=$row['password'];
            $role=$row['role'];
        }
    }


}

if($role=='admin'){

    $rolevalue = "selected";
}else{
    $rolevalue = "";

}

if($role=='user'){

    $rolevalue = "selected";
}else{
    $rolevalue = "";

}

if(isset($_POST['sumbitUpdate'])){

    $id = $_POST['userId'];

    echo $id;
    $username = $_POST['username'];
    $passWord = md5($_POST['password']);
    $userRole = $_POST['role'];

    $userupdateresult =userLoginUpdate($id,$username,$passWord,$userRole,$conn);
    
    
    if($userupdateresult === true){
    
      
        $submited = true;
        $inValid = false;
        $successEvent = "Item Update Successfully";
    
    
    
    
      }else{

        $submited = true;
        $inValid = true;
        $successEvent = "Item Update Not Successfully";

      }
}




  




if(isset($_POST['sumbitClose'])){

    header('Location: /sd_multicare/usercreate.php');
 exit();

}



?>


<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>Stocks Update</title>
</head>
<body class="bg-secondary">

<div class="container text-center position-absolute top-50 start-50 translate-middle w-25 p-3 border border-warning rounded">
    <form  method="post">
    
        <br>
        <div class="input-group mb-3">
  <input type="text" class="form-control disable"  name="userId" value="<?php echo $userid ?>"  >
</div>
<div class="input-group mb-3">
  <input type="text" class="form-control"  name="username" value="<?php echo $userName ?>">
</div>
<div class="input-group mb-3">
  <input type="password" class="form-control"  name="password" value="<?php echo $password ?>">
</div>
 <div class=" input-group mb-3">
        <select class="form-select form-select-sm mb-3" aria-label="Large select example" name="role" > 
            
        <option <?php echo $rolevalue ?>  value="admin">admin</option>
        <option  <?php echo $rolevalue ?> value="user">user</option>
        </select>
          </div>
          

        <button class="btn btn-success" name="sumbitUpdate"><b> Update </b></button>

        <button class="btn btn-danger" name="sumbitClose"><b> Close </b></button>
        </div>


        <br><br>

       
        
            
          
    </form>



    </div>
</body>
</html>
<?php
        if ($submited) {
          if ($inValid) {
        ?>
            <script>
              swal({
                title: "<?php echo $errorEvent ?>",
                // text: "You clicked the button!",
                icon: "warning",
                button: "ok",
              });
            </script>
          <?php } else { ?>
            <script>
              swal({
                title: "<?php echo $successEvent ?>",
                // text: "You clicked the button!",
                icon: "success",
                button: "ok",
              });
            </script>
        <?php }
        } ?>


