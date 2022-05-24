<?php
session_start();
include_once "php/config.php";
if (!isset($_SESSION['unique_id'])) {
  header("location: login.php");
}
$sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
if (mysqli_num_rows($sql) > 0) {
  $row = mysqli_fetch_assoc($sql);
}

?>
<?php include_once "header.php"; ?>
<script src="javascript/face-api.min.js"></script>
<!-- <script defer src="script.js"></script> -->

<body>
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-6">
              <div class="col-12">
                <label for="" class="form-label">First Name</label>
              </div>
              <div class="col-12">
                <input type="text" class="form-control" id="fname" value="<?php echo $row['fname']?>">
              </div>
            </div>
            <div class="col-6">
              <div class="col-12">
                <label for="" class="form-label">Last Name</label>
              </div>
              <div class="col-12">
                <input type="text" class="form-control" id="lname" value="<?php  echo $row['lname']?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <label for="" class="form-label">Email</label>
            </div>
            <div class="col-12">
              <input type="email" class="form-control" id="email" value="<?php  echo $row['email']?>">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-dark" id="saveChangesBtn">Save changes</button>
        </div>
      </div>
    </div>
  </div>
  <div class="wrapper">
    <section class="users">
      <header>
        <div class="content">

          <img src="data:<?php echo $row['img_type'] . ";base64," . $row['img'] ?>" alt="">
          <div class="details">
            <span><?php echo $row['fname'] . " " . $row['lname'] ?></span>
            <p><?php echo $row['status']; ?></p>
          </div>
        </div>
        <div class="dropdown" style="margin-right: 20px">
          <i class="far fa-bell fa-lg position-relative mx-5" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="position-absolute start-100 translate-middle badge rounded-pill bg-danger " style="font-size: .45em; padding: .35em .45em; top:-3px">
              <div id="notification-count"></div>
            </span>
          </i>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
            <div id="notification_dropdown">

            </div>
          </ul>
          <i class="fas fa-ellipsis-v fa-lg" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></i>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <div>
              <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal">Edit Profile</a></li>
              <li><a class="dropdown-item" href="facial_recog_add_photo.php">Face Unlock Settings</a></li>
              <hr>
              <li><a class="dropdown-item" href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>">Logout</a></li>
            </div>
          </ul>
        </div>
        <!-- <a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout">Logout</a> -->
      </header>
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