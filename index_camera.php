<?php
session_start();
$user_id = $_SESSION['user_id'];
$navigateTo = $_GET['user_id']
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script>
    var succeedRedirect = "chat.php?user_id=<?= $navigateTo ?>";
  </script>
  <script defer src="face-api.min.js"></script>
  <script defer src="script.js"></script>
  <title>Face Recognition</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      width: 100vw;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column
    }

    canvas {
      position: absolute;
      /* top: 0;
      left: 0; */
      z-index: -2;
    }

    div {
      position: absolute;
      background-color: white;
      height: 500px;
      width: 500px;
      z-index: 2;

    }

    h3 {
      z-index: 4;
    }
  </style>
</head>

<body>
  <!-- <input type="file" id="imageUpload" > -->
  <h3>Please make sure your surrounding is bright</h3>
  <h3 id="camera_change">Camera is starting, please wait for awhile </h3>
  <h3 id="progressMatchHeader" hidden>Progress: <span id="progressMatch"></span></h3>
  <!-- Loading Spinner -->
  <div class="spinner-border" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
  </section>
  <div ></div>
  <video id="videoInput" width="10" height="20" muted>
</body>

</html>