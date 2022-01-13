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

          <!-- <div class="mx-5" style="display: inline-block;">
            <i class="far fa-bell fa-lg " type="button"></i>
            <span class="badge badge-pill badge-primary" style="position: relative; right: 15px; top: -10px;">
              99
            </span>

          </div> -->
          <i class="far fa-bell fa-lg position-relative mx-5" type="button">
            <span class="position-absolute start-100 translate-middle badge rounded-pill bg-danger" style="font-size: .45em; padding: .35em .45em; top:-3px">
              <!-- 99+ -->
              <span class="">unread messages</span>
            </span>
          </i>


          <i class="fas fa-ellipsis-v fa-lg" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></i>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li><a class="dropdown-item" href="#">Face Unlock Settings</a></li>
            <hr>
            <li><a class="dropdown-item" href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>">Logout</a></li>
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

      <!-- Button trigger modal -->
      <!-- <button type="button" id="testing" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
        Testing
      </button> -->
      <!-- Button trigger modal -->
      <!-- <a type="button" class="btn btn-primary faceunlock" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Launch demo modal
      </a> -->



      <div class="users-list">

      </div>
    </section>
  </div>

  <!-- Modal -->
  <!-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Face Unlock</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <video id="videoInput" width="720" height="550" muted controls>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div> -->

  <script src="javascript/users.js"></script>
  <!-- <script>
    const video = document.getElementById('videoInput')

    $(".faceunlock").click(function(e) {
      e.preventDefault();
      start();
    });

    Promise.all([
      faceapi.nets.tinyFaceDetector.loadFromUri('models'),
      faceapi.nets.faceLandmark68Net.loadFromUri('models'),
      faceapi.nets.faceRecognitionNet.loadFromUri('models'),
      faceapi.nets.faceExpressionNet.loadFromUri('models'),
      faceapi.nets.ssdMobilenetv1.loadFromUri('models'),
    ])

    var folderExistsResponse = 0;
    var retrivedID;
    var files = [];
    var match = 0;
    var entered = 0;
    var labeledDescriptor;
    var faceMatcher;

    function start() {
      if (folderExistsResponse != 0 && files.length != 0) {
        navigator.getUserMedia({
            video: {}
          },
          stream => video.srcObject = stream,
          err => console.error(err)
        )
        recognizeFaces()
      }

    }

    $(document).ready(function() {
      $.when(getSessionID()).done(function(ajaxResults) {
        $.when((checkFolderExists(`labeled_images/${retrivedID}`)), ).done(function(ajax2Results) {
          $.when((getAllDirFiles(`labeled_images/${retrivedID}`)), ).done(function(ajax3Results) {
            console.log("complete fetching")
          });
        });
      });
    });

    async function recognizeFaces() {
      const labeledDescriptors = await loadLabeledImages()
      // const labeledDescriptors = labeledDescriptor
      console.log(labeledDescriptors)
      if (labeledDescriptors) {
        const faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.5)
        video.addEventListener('play', async () => {
          console.log('Playing')
          const canvas = faceapi.createCanvasFromMedia(video)
          document.body.append(canvas)

          const displaySize = {
            width: video.width,
            height: video.height
          }
          faceapi.matchDimensions(canvas, displaySize)

          setInterval(async () => {
            const detections = await faceapi.detectAllFaces(video).withFaceLandmarks().withFaceDescriptors()

            const resizedDetections = faceapi.resizeResults(detections, displaySize)

            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height)

            const results = resizedDetections.map((d) => {
              return faceMatcher.findBestMatch(d.descriptor)
            })
            results.forEach((result, i) => {
              const box = resizedDetections[i].detection.box
              const drawBox = new faceapi.draw.DrawBox(box, {
                label: result.toString()
              })


              if (result['label'] == retrivedID && match <= 10 && entered == 0) {
                match++
                console.log("match: " + match)
              } else {
                entered++
                //redirect script here
              }
              // drawBox.draw(canvas)
            })
          }, 100)
        })
      }
    }

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
      });;
    }

    function getSessionID() {
      return $.ajax({
        type: "GET",
        url: "php/getSessionID.php",
        success: function(response) {
          retrivedID = response
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

    function loadLabeledImages() {
      var get_user_id = retrivedID;
      const labels = [get_user_id] // for WebCam (Need to make it recognize users)
      var access = (folderExistsResponse)
      // console.log(folderExistsResponse)
      // See if the file exists
      if (access == '0') {
        return 0;
      } else {
        return Promise.all(
          labels.map(async (label) => {
            const descriptions = []
            for (let i = 0; i < files.length; i++) { //loop 3 times
              console.log(`labeled_images/${label}/${files[i]}`);
              const img = await faceapi.fetchImage(`labeled_images/${label}/${files[i]}`)
              const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor()
              // console.log(label + i + JSON.stringify(detections))
              descriptions.push(detections.descriptor)
            }
            // document.body.append(label + ' Faces Loaded | ')
            return new faceapi.LabeledFaceDescriptors(label, descriptions)
          })
        )
      }

    }
  </script> -->


</body>

</html>