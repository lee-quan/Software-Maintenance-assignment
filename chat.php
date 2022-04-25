<?php
session_start();
include_once "php/config.php";
if (!isset($_SESSION['unique_id'])) {
  header("location: login.php");
}
$sessionID = $_SESSION['user_id'];
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

        $img_explode = explode('.////.', $row['img']);
        ?>
        <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="data:<?php echo $img_explode[0].";base64,".$img_explode[1] ?>" alt="">
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
            <div>
              <?php
              if (!$gotlock) {
                echo '<li><a class="dropdown-item" id="lockChat" href="#">Lock Chat</a></li>';
              } else {
                echo '<li><a class="dropdown-item" id="unlockChat" href="#">Unlock Chat</a></li>';
              }
              ?>

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
      //check whther this person has submitted image or not for face identificaiton
      var retrivedID = '<?php echo $sessionID ?>';
      $.when((checkFolderExists(`labeled_images/${retrivedID}`)), ).done(function(ajax2Results) {
        if (folderExistsResponse != 0) {
          $.when((getAllDirFiles(`labeled_images/${retrivedID}`)), ).done(function(ajax3Results) {
            if (folderExistsResponse != 0 && files.length != 0) {
              $.ajax({
                type: "POST",
                url: "php/lockChat.php",
                data: {
                  chatUserID: "<?php echo $chatUserID ?>"
                },
                success: function(response) {
                  alert("This chat room has been locked, face identification is required on next enter")
                }
              });
            } else {
              if (confirm("Empty face data, do you wish to add face for face unlock?")) {
                window.location.href = 'imageSubmit.php'; //redirect to submit page
              }

            }

          });
        } else {
          if (confirm("Empty face data, do you wish to add face for face unlock?")) {
            window.location.href = 'imageSubmit.php'; //redirect to submit page
          }
        }
      });



      function checkFolderExists(path) {
        return $.ajax({
          type: "GET",
          url: "php/folderExists.php",
          data: {
            folder_path: path
          },
          success: function(response) {
            folderExistsResponse = response;
          }
        });
      }

      function getAllDirFiles(dir) {
        return $.ajax({
          type: "GET",
          url: "php/scanDirectory.php",
          data: {
            dir: dir
          },
          success: function(response) {
            files = Object.values(JSON.parse(response))
          }
        });
      }

    });

    $("#unlockChat").click(function(e) {
      e.preventDefault();
      if (confirm("Are you sure?")) {
        $.ajax({
          type: "POST",
          url: "php/unlockChat.php",
          data: {
            chatUserID: "<?= $chatUserID ?>"
          },

          success: function(response) {
            alert("This chat room has been unlocked, face identification is not required on next enter")
          }
        });
      }

    });
  </script>

</body>

</html>