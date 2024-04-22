<?php
include("../sd_multicare/dbconnect/dbconnect.php");
include("../sd_multicare/dbconnect/queris/Insert.php");
include("../sd_multicare/dbconnect/queris/select.php");

include("header.php");

$submited = false;
$inValid = false;
$successEvent = "";
$errorEvent = "";
$closeButton="";
$Answer ="";

if(isset($_POST['sumbitCreate'])){

    $userName = $_POST['userName'];
    $password = $_POST['password'];
    $userRole = $_POST['role'];

    if(empty($userName)|| empty($password)){

        echo "error";
    }else{

        $usercheckResult = getuserNames($userName,$conn);

        if($usercheckResult->num_rows > 0){

            while($row = mysqli_fetch_array($usercheckResult,MYSQLI_ASSOC)){

                if($row['username']!=$userName){

                    $Answer = false;
               

                }else{
                    $Answer = true;
                    $submited = true;
                    $inValid = true;
                    $errorEvent = "Allready Add This User Name";

                   
                }


            }
        }
 
        if($Answer ==false){
            $userCreateResult = usercreate($userName,$password,$userRole,$conn);

            if($usercheckResult == true){

                $submited = true;
                $inValid = false;
                $successEvent = "Successfully User Create";

            }

        }
        

        $closeButton = "<button class='btn btn-danger' name='sumbitClose'><b> Close </b></button>";




    }





}

if(isset($_POST['sumbitClose'])){

    header('Location: /sd_multicare/home.php');
 exit();

}

if(isset($_POST['userdeleteid'])){
  
    $userID= $_POST['userdeleteid'];
    
    header("Location: /sd_multicare/crud/delete.php?userid=$userID");
  
  }

  if(isset($_POST['userupdateId'])){
  
    $userID= $_POST['userupdateId'];
    
    header("Location: /sd_multicare/crud/userupdate.php?userid=$userID");
  
  }



    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="shortcut icon" type="x-icon" href="assets/images/sdlogo.jpeg">
    <title>User Create</title>
</head>

<body class="bg-secondary">

    <div class="row">
        <div class=" col-5 container">
            <form method="post">
                <div class="input-group mb-3 pt-2">
                    <h2> User Create </h2>
                </div>

                <br>
                <div class="input-group mb-3">
                    <input type="text" class="form-control disable" placeholder="Enter User Name" name="userName">
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Enter password" name="password">
                </div>
                <div class=" input-group mb-3">
                    <select class="form-select form-select-sm mb-3" aria-label="Large select example" name="role">

                        <option value="admin">admin</option>
                        <option value="user">user</option>
                    </select>
                </div>

                <button class="btn btn-success" name="sumbitCreate"><b> Create </b></button>

                <?php 

        echo $closeButton;
?>



                <br><br>





            </form>
        </div>

        <div class=" col-6 container pt-3">
            <div class="input-group mb-3 pt-2">
                <h2> User Table </h2>
            </div>
            <form method="post">
                <table id='myTable' class='table table-striped table-dark'>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>User Name</th>
                            <th>User Role</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

       $userLogins= getuserLogin($conn);

       if($userLogins->num_rows > 0){

        while($row = mysqli_fetch_array($userLogins,MYSQLI_ASSOC)){

            echo "<tr>
      <td>".$row['id']."</td>
      <td>".$row['username']."</td>
      <td>".$row['role']."</td>
      <td>
      <button type='submit' name='userupdateId' class='btn btn-warning' value='".$row['id']."'>
        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' width='16' height='16' stroke='currentColor' class='w-6 h-6'>
  <path stroke-linecap='round' stroke-linejoin='round' d='M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99' />
</svg>

           </button></td>
      <td>
      
      <button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#exampleModal".$row['id']."'>
        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='16' height='16' fill='currentColor' class='w-6 h-6'>
        <path fill-rule='evenodd' d='M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z' clip-rule='evenodd' />
      </svg>
      </button>
      
      
     
      <div class='modal fade' id='exampleModal".$row['id']."' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h1 class='modal-title text-dark fs-5' id='exampleModalLabel'>User Delete</h1>
              <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
               <b class='text-dark'>Do you want to delete this User ".$row['id']."?</b>
            <div class='modal-footer'>
              <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
              <button type='button' class='btn btn-danger'><a style='text-decoration:none' href = './crud/delete.php?userid=".$row['id']."' class='text-light'>Delete</a></button>
            </div>
          </div>
        </div>
      </div>
    </td>
    </tr>";


        }
    }

?>


                    </tbody>
                </table>



            </form>
        </div>
    </div>







</body>

</html>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#myTable').DataTable();
});
</script>
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