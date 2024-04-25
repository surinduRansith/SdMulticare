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

                <?php echo $userCreateButton; ?>


            </ul>
            <form method="post">

                <button class="btn btn-outline-danger text-white " type="submit" name=submit>
                <svg width="24"
  height="24"
  viewBox="0 0 24 24"
  fill="none"
  xmlns="http://www.w3.org/2000/svg">
  <path d="M13 4.00894C13.0002 3.45665 12.5527 3.00876 12.0004 3.00854C11.4481 3.00833 11.0002 3.45587 11 4.00815L10.9968 12.0116C10.9966 12.5639 11.4442 13.0118 11.9965 13.012C12.5487 13.0122 12.9966 12.5647 12.9968 12.0124L13 4.00894Z"
    fill="currentColor"/>
  <path d="M4 12.9917C4 10.7826 4.89541 8.7826 6.34308 7.33488L7.7573 8.7491C6.67155 9.83488 6 11.3349 6 12.9917C6 16.3054 8.68629 18.9917 12 18.9917C15.3137 18.9917 18 16.3054 18 12.9917C18 11.3348 17.3284 9.83482 16.2426 8.74903L17.6568 7.33481C19.1046 8.78253 20 10.7825 20 12.9917C20 17.41 16.4183 20.9917 12 20.9917C7.58172 20.9917 4 17.41 4 12.9917Z"
    fill="currentColor"/>

</button>

            </form>

        </div>
    </div>
</nav>