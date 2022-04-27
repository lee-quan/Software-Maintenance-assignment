<?php
session_start();
include_once "php/config.php";
if (!isset($_SESSION['unique_id'])) {
  header("location: login.php");
}
$sessionID = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = $sessionID";
$query = mysqli_query($conn, $sql);
if (mysqli_num_rows($query) > 0) {
  $row = mysqli_fetch_assoc($query);
}
if (str_contains($row['lock'], $_GET['user_id']) && !isset($_SESSION['chatToken'])) {
  $lock = true;
  $_SESSION['chatToken'] = md5(time());
  $sql2 = "UPDATE users set token = '" . $_SESSION['chatToken'] . "' where user_id = '$sessionID'";
  $query = mysqli_query($conn, "UPDATE users set token = '" . $_SESSION['chatToken'] . "' where user_id = '$sessionID'");
  if($query){
    header('Location: index_camera.php?user_id=' . $_GET['user_id']);
  }
} elseif (str_contains($row['lock'], $_GET['user_id']) && isset($_GET['token']) && $_GET['token'] == $row['token']) {
  unset($_SESSION['chatToken']);
  $query = mysqli_query($conn, 'UPDATE users SET token = NULL where user_id ='.$sessionID);
  $lock = true;
} elseif (str_contains($row['lock'], $_GET['user_id'])) {
  $lock = true;
} elseif (!str_contains($row['lock'], $_GET['user_id'])) {
  $lock = false;
}
?>
<?php include_once "header.php"; ?>

<body>
  <div class="wrapper">
    <section class="chat-area">
      <header>
        <?php
        $chatUserID = $_GET['user_id'];
        $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$user_id}");
        if (mysqli_num_rows($sql) > 0) {
          $row = mysqli_fetch_assoc($sql);
        } else {
          header("location: users.php");
        }

        $getlockTrue = "SELECT users.lock FROM users WHERE user_id = {$sessionID}";
        $getlockResult = $conn->query($getlockTrue);
        $getLock = $getlockResult->fetch_assoc();
        $lock_remove_space = str_replace(' ', '', $getLock['lock']);
        $lock_arr = explode("|", $lock_remove_space); //all the user that this user has lock
        $gotlock = false;
        if (in_array($chatUserID, $lock_arr)) {
          $gotlock = true;
        }
        ?>
        <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="data:<?php echo $row['img_type'] . ";base64," . $row['img'] ?>" alt="">
        <div class="details">
          <span><?php echo $row['fname'] . " " . $row['lname'] ?></span>
          <p><?php echo $row['status']; ?></p>
        </div>
        <!-- <div style="margin-left: 120px;">
          <button id="lockChat">lock me</button>
        </div> -->

        <div style="margin-left: 180px;">

          <i class="fas fa-ellipsis-v fa-lg flex-grow-1" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></i>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <div id="lockorunlock">
              <li><a class="dropdown-item" id="lockChat" href="#" <?php echo ($lock) ? 'hidden' : '' ?>>Lock Chat</a></li>
              <li><a class="dropdown-item" id="unlockChat" href="#" <?php echo ($lock) ? '' : 'hidden' ?>>Unlock Chat</a></li>
            </div>
          </ul>

        </div>

      </header>
      <div class="chat-box">

      </div>
      <form action="#" class="typing-area">
        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
        <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
        <button><i class="fab fa-telegram-plane"></i></button>
      </form>
    </section>
  </div>

  <script src="javascript/chat.js"></script>
  <script>
    $("#lockChat").click(function(e) {
      e.preventDefault();
      var retrivedID = '<?php echo $sessionID ?>';
      $.ajax({
        type: "POST",
        url: "php/lockChat.php",
        data: {
          lock: 'lockChat',
          chatUserID: "<?php echo $chatUserID ?>",
        },
        success: function(response) {
          if (response == 'success') alert("This chat room has been locked, face identification is required on next enter");
          document.getElementById('unlockChat').hidden = false;
          document.getElementById('lockChat').hidden = true;
        }
      });

    });

    $('#unlockChat').on('click', function(event) {
      event.preventDefault();
      var retrivedID = '<?php echo $sessionID ?>';
      $.ajax({
        type: "POST",
        url: "php/lockChat.php",
        data: {
          lock: 'unlockChat',
          chatUserID: "<?php echo $chatUserID ?>",

        },
        success: function(response) {
          // alert("This chat room has been locked, face identification is required on next enter");
          alert(response);
          document.getElementById('unlockChat').hidden = true;
          document.getElementById('lockChat').hidden = false;
        }
      });
    });
  </script>

</body>

</html>

<?php
// unset($_SESSION['chatToken']);

?>