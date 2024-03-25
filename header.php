<?php


session_start();
if (!isset($_SESSION['username'])) {

  header('Location: index.php');
  exit();
}



?>

<?php
if (isset($_POST['submit'])) {


  header('Location: logout.php');
}

?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="./assets/css/bootstrap.min.css">
<script src="assets/js/sweetalert.js"></script>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark  ">
  <div class="container-fluid">

    <a class="navbar-brand text-white " href="home.php"><img src="./assets/Images/sdlogo.jpeg" alt="sdlogo" class="img-fluid" style="width: 80px; padding-right: 5px;"></a>

    <a class="navbar-brand text-white " href="home.php">S&D Multicare House</a>
    <button class="navbar-toggler btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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

      </ul>
      <form method="post">

        <button class="btn btn-outline-danger text-white " type="submit" name=submit>Logout</button>

      </form>

    </div>
  </div>
</nav>