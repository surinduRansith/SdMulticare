<?php


session_start();
if (!isset($_SESSION['username'])) {

  header('Location: index.php');
  exit();
}

if($_SESSION['userrole']=="admin"){

  $userCreateButton = " <li class='nav-item'>
  <a class='nav-link  text-white ' href='usercreate.php'>
   Create Users
  </a>

</li>";




}elseif($_SESSION['userrole']=="user"){

  $userCreateButton="";
}


if (isset($_POST['submit'])) {


  header('Location: logout.php');
}

?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="./assets/css/bootstrap.min.css">
<script src="assets/js/sweetalert.js"></script>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark  ">
    <div class="container-fluid">

        <a class="navbar-brand text-white " href="home.php"><img src="./assets/Images/sdlogo.jpeg" alt="sdlogo"
                class="img-fluid" style="width: 80px; padding-right: 5px;"></a>

        <a class="navbar-brand text-white " href="home.php">S&D Multicare House</a>
        <button class="navbar-toggler btn-outline-danger" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link text-white " href="dailyinvoice.php">Daily Invoice </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  text-white " href="report.php">
                        Invoice Reports
                    </a>

                </li>
                <li class="nav-item">
                    <a class="nav-link  text-white " href="stocks.php">
                        Stocks
                    </a>

                </li>
                <li class="nav-item">
                    <a class="nav-link  text-white " href="customer.php">
                        Customer Details
                    </a>

                </li>
                <li class="nav-item">
                    <a class="nav-link  text-white " href="qutation.php">
                       Qutation
                    </a>

                </li>
                <?php echo $userCreateButton; ?>


            </ul>
            <form method="post">

                <button class="btn btn-outline-danger text-white " type="submit" name=submit>

                <?php 

                echo $_SESSION['userroleName'];
                ?>
               <svg
  width="24"
  height="24"
  viewBox="0 0 24 24"
  fill="none"
  xmlns="http://www.w3.org/2000/svg"
>
  <path
    d="M8.51428 20H4.51428C3.40971 20 2.51428 19.1046 2.51428 18V6C2.51428 4.89543 3.40971 4 4.51428 4H8.51428V6H4.51428V18H8.51428V20Z"
    fill="currentColor"
  />
  <path
    d="M13.8418 17.385L15.262 15.9768L11.3428 12.0242L20.4857 12.0242C21.038 12.0242 21.4857 11.5765 21.4857 11.0242C21.4857 10.4719 21.038 10.0242 20.4857 10.0242L11.3236 10.0242L15.304 6.0774L13.8958 4.6572L7.5049 10.9941L13.8418 17.385Z"
    fill="currentColor"
  />
</svg>



</button>

            </form>

        </div>
    </div>
</nav>