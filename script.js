const imageUpload = document.getElementById('imageUpload')
// const video = document.getElementById('video')
const video = document.getElementById('videoInput')

Promise.all([
  faceapi.nets.tinyFaceDetector.loadFromUri('models'),
  faceapi.nets.faceLandmark68Net.loadFromUri('models'),
  faceapi.nets.faceRecognitionNet.loadFromUri('models'),
  faceapi.nets.faceExpressionNet.loadFromUri('models'),
  faceapi.nets.ssdMobilenetv1.loadFromUri('models'),
]).then(start)

var folderExistsResponse = 0;
var retrivedID;
var files=[];
var match = 0;
var not_match = 0;
var entered = 0;

function start() {
  // document.body.append('Models Loaded')
  $.when(getSessionID()).done(function (ajaxResults) {
    $.when((checkFolderExists(`labeled_images/${retrivedID}`)), ).done(function (ajax2Results) {
      $.when((getAllDirFiles(`labeled_images/${retrivedID}`)), ).done(function (ajax3Results) {
        if (folderExistsResponse != 0 && files.length != 0) {
          navigator.getUserMedia({ video: {} },
            stream => video.srcObject = stream,
            err => console.error(err)
          )
          recognizeFaces()
        }else{
          window.location.href = 'users.php?fli=nm'; //fli = face lock invalid
        }

      });
    });
  });
}



async function recognizeFaces() {
  const labeledDescriptors = await loadLabeledImages()
  // console.log(labeledDescriptors)
  if (labeledDescriptors) {
    const faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.5)
    $('#videoInput').trigger('play');
    $('#camera_change').text("Validation in progress")
    video.addEventListener('play', async () => {
      // console.log('Playing')
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

          if(result['label']!=retrivedID){
            not_match++;
          }

          if(not_match >= 10){
            window.location.href = 'users.php?notmatch=nm';
          }

          if(result['label']==retrivedID && match<=10 && entered==0){
            match++
            console.log(match);
          }

          if(match >= 10){
            entered++
            window.location.href = succeedRedirect;
          }

          drawBox.draw(canvas)
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
    success: function (response) {
      folderExistsResponse = response;
    }
  });;
}

function getSessionID() {
  return $.ajax({
    type: "GET",
    url: "php/getSessionID.php",
    success: function (response) {
      retrivedID = response
    }
  });
}

function getAllDirFiles(dir){
  return $.ajax({
    type: "GET",
    url: "php/scanDirectory.php",
    data: {
      dir: dir
    },
    success: function (response) {
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