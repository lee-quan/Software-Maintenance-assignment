const imageUpload = document.getElementById("imageUpload");
const video = document.getElementById("videoInput");

Promise.all([
  faceapi.nets.tinyFaceDetector.loadFromUri("models"),
  faceapi.nets.faceLandmark68Net.loadFromUri("models"),
  faceapi.nets.faceRecognitionNet.loadFromUri("models"),
  faceapi.nets.faceExpressionNet.loadFromUri("models"),
  faceapi.nets.ssdMobilenetv1.loadFromUri("models"),
]).then(start);

var folderExistsResponse = 0;
var retrivedID;
var files = [];
var match = 0;
var not_match = 0;
var entered = 0;
var images;
var images_arr;
function getBase64image(id) {
  return $.ajax({
    type: "GET",
    url: "getBase64Image.php?id="+id,
    success: function (response) {
      images = response;
      images_arr = images.split('***');

    },
  });
}

function start() {
  $.when(getSessionID()).done(function (ajaxResults) {
    $.when(getBase64image(retrivedID)).done(function (ajax2Results) {
      navigator.getUserMedia(
        { video: {} },
        (stream) => (video.srcObject = stream),
        (err) => console.error(err)
      );
      recognizeFaces();

      // else{
      //   window.location.href = 'users.php?fli=nm'; //fli = face lock invalid
      // }
    });
  });
}

async function recognizeFaces() {
  const labeledDescriptors = await loadLabeledImages();
  console.log(labeledDescriptors);
  if (labeledDescriptors) {
    const faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.5);
    $("#videoInput").trigger("play");
    $("#camera_change").text("Validation in progress");
    $("#progressMatchHeader").prop("hidden", false);
    video.addEventListener("play", async () => {
      // console.log('Playing')
      const canvas = faceapi.createCanvasFromMedia(video);
      document.body.append(canvas);
      const displaySize = {
        width: video.width,
        height: video.height,
      };
      faceapi.matchDimensions(canvas, displaySize);
      setInterval(async () => {
        const detections = await faceapi
          .detectAllFaces(video)
          .withFaceLandmarks()
          .withFaceDescriptors();
        const resizedDetections = faceapi.resizeResults(
          detections,
          displaySize
        );
        canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);
        const results = resizedDetections.map((d) => {
          return faceMatcher.findBestMatch(d.descriptor);
        });
        results.forEach((result, i) => {
          const box = resizedDetections[i].detection.box;
          const drawBox = new faceapi.draw.DrawBox(box, {
            label: result.toString(),
          });

          if (result["label"] != retrivedID) {
            //does not match
            not_match++;
          }

          if (not_match >= 10) {
            //redirect user after a number of not match
            window.location.href = "users.php?notmatch=nm";
          }

          if (result["label"] == retrivedID && match <= 10 && entered == 0) {
            match++;
            var matchProgress = (match / 10) * 100;
            $("#progressMatch").text(matchProgress + "%");
          }

          if (match >= 10 && entered == 0) {
            //match
            entered++;
            window.location.href = 'users.php';
          }

          drawBox.draw(canvas);
        });
      }, 100);
    });
  }
}

function getSessionID() {
  return $.ajax({
    type: "GET",
    url: "php/getSessionID.php",
    success: function (response) {
      retrivedID = response;
    },
  });
}

function getAllDirFiles(dir) {
  return $.ajax({
    type: "GET",
    url: "php/scanDirectory.php",
    data: {
      dir: dir,
    },
    success: function (response) {
      files = Object.values(JSON.parse(response));
    },
  });
}

function loadLabeledImages() {
  var get_user_id = retrivedID;
  const labels = [get_user_id]; // for WebCam (Need to make it recognize users)
  var access = folderExistsResponse;
  // console.log(folderExistsResponse)
  // See if the file exists
  return Promise.all(
    labels.map(async (label) => {
      const descriptions = [];
      for (let i = 0; i < images_arr.length-1; i++) {
        const img = await faceapi.fetchImage(images_arr[i]);
        const detections = await faceapi
          .detectSingleFace(img)
          .withFaceLandmarks()
          .withFaceDescriptor();
        // descriptions.push(detections.descriptor);
      }

      // document.body.append(label + ' Faces Loaded | ')
      return new faceapi.LabeledFaceDescriptors(label, descriptions);
    })
  );
}
