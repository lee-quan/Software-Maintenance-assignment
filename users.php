<?php
session_start();
include_once "php/config.php";
if (!isset($_SESSION['unique_id'])) {
  header("location: login.php");
}

?>
<?php include_once "header.php"; ?>
<script src="face-api.min.js"></script>
<!-- <script defer src="script.js"></script> -->

<body>
  <div class="wrapper">
    <section class="users">
      <header>
        <div class="content">
          <?php
          $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
          if (mysqli_num_rows($sql) > 0) {
            $row = mysqli_fetch_assoc($sql);
          }
          ?>
          <img src="php/images/<?php echo $row['img']; ?>" alt="">
          <div class="details">
            <span><?php echo $row['fname'] . " " . $row['lname'] ?></span>
            <p><?php echo $row['status']; ?></p>
          </div>
        </div>
        <div class="dropdown" style="margin-right: 20px">
          <i class="far fa-bell fa-lg position-relative mx-5" type="button"  id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="position-absolute start-100 translate-middle badge rounded-pill bg-danger " style="font-size: .45em; padding: .35em .45em; top:-3px">
              <div id="notification-count"></div>
            </span>
          </i>
          <!--  -->
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
            <div id="notification_dropdown">
              
            </div>
          </ul>
          <!--  -->
          <i class="fas fa-ellipsis-v fa-lg" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></i>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <div>
              <li><a class="dropdown-item" href="imageSubmit.php">Face Unlock Settings</a></li>
              <hr>
              <li><a class="dropdown-item" href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>">Logout</a></li>
            </div>
          </ul>
        </div>
        <!-- <a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout">Logout</a> -->
      </header>
      <?php
      if (isset($_GET['notmatch'])) {
        $notmatch = $_GET['notmatch'];
        if ($notmatch == "nm") {
          echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
          Face does not match with owner
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        }
      }
      if (isset($_GET['fli'])) { //face lock invalid, means havent submit photo for validation
        $fli = $_GET['fli'];
        if ($fli == "nm") {
          echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
          Please <a href="#" class="alert-link">submit</a> image for face unlock
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        }
      }
      ?>
      <div class="search">
        <span class="text">Select an user to start chat</span>
        <input type="text" placeholder="Enter name to search...">
        <button><i class="fas fa-search"></i></button>
      </div>

      <div class="users-list">

      </div>
    </section>
  </div>

  <script src="javascript/users.js"></script>

</body>

</html>