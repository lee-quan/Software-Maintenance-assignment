const imageUpload = document.getElementById('imageUpload')
// const video = document.getElementById('video')
const video = document.getElementById('videoInput')

// const match = document.getElementById('match')


// Promise.all([
//   faceapi.nets.faceRecognitionNet.loadFromUri('models'),
//   faceapi.nets.faceLandmark68Net.loadFromUri('models'),
//   faceapi.nets.ssdMobilenetv1.loadFromUri('models'),
//   faceapi.nets.tinyFaceDetector.loadFromUri('models'),
//   faceapi.nets.faceLandmark68Net.loadFromUri('models'),
//   faceapi.nets.faceExpressionNet.loadFromUri('models')
// ]).then(start)

// Promise.all([
//   faceapi.nets.tinyFaceDetector.loadFromUri('models'),
//   faceapi.nets.faceLandmark68Net.loadFromUri('models'),
//   faceapi.nets.faceRecognitionNet.loadFromUri('models'),
//   faceapi.nets.faceExpressionNet.loadFromUri('models'),
//   faceapi.nets.ssdMobilenetv1.loadFromUri('models'),
// ]).then(startVideo)

Promise.all([
  faceapi.nets.tinyFaceDetector.loadFromUri('models'),
  faceapi.nets.faceLandmark68Net.loadFromUri('models'),
  faceapi.nets.faceRecognitionNet.loadFromUri('models'),
  faceapi.nets.faceExpressionNet.loadFromUri('models'),
  faceapi.nets.ssdMobilenetv1.loadFromUri('models'),
]).then(start)

//get matching photos
// async function start() {
//   const container = document.createElement('div')
//   container.style.position = 'relative'
//   document.body.append(container)
//   const labeledFaceDescriptors = await loadLabeledImages()
//   const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.4)
//   let image
//   let canvas
//   document.body.append('Loaded')
//   imageUpload.addEventListener('change', async () => {
//     if (image) image.remove()
//     if (canvas) canvas.remove()
//     image = await faceapi.bufferToImage(imageUpload.files[0])
//     container.append(image)
//     canvas = faceapi.createCanvasFromMedia(image)
//     container.append(canvas)
//     const displaySize = {
//       width: image.width,
//       height: image.height
//     }
//     faceapi.matchDimensions(canvas, displaySize)
//     const detections = await faceapi.detectAllFaces(image).withFaceLandmarks().withFaceDescriptors()
//     const resizedDetections = faceapi.resizeResults(detections, displaySize)
//     const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor))

//     results.forEach((result, i) => {
//       const box = resizedDetections[i].detection.box
//       console.log(result.toString());
//       const drawBox = new faceapi.draw.DrawBox(box, {
//         label: result.toString()
//       })
//       drawBox.draw(canvas)
//     })
//   })
// }


// video.addEventListener('play', () => {
//   const canvas = faceapi.createCanvasFromMedia(video)
//   document.body.append(canvas)
//   const displaySize = {
//     width: video.width,
//     height: video.height
//   }
//   faceapi.matchDimensions(canvas, displaySize)
//   const detections = async () => { await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceExpressions()}

//   setInterval(async () => {

//     const labeledFaceDescriptors = await loadLabeledImages()
//     const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.4)
//     document.body.append('Loaded')

//     const resizedDetections = faceapi.resizeResults(detections, displaySize)
//     canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height)
//     faceapi.draw.drawDetections(canvas, resizedDetections)
//     faceapi.draw.drawFaceLandmarks(canvas, resizedDetections)
//     faceapi.draw.drawFaceExpressions(canvas, resizedDetections)
//   }, 100)
// })

// function startVideo() {
//   navigator.getUserMedia({
//       video: {}
//     },
//     stream => video.srcObject = stream,
//     err => console.error(err)
//   )
// }

// function loadLabeledImages() {
//   const labels = ['user']
//   return Promise.all(
//     labels.map(async label => {
//       const descriptions = []
//       for (let i = 1; i <= 6; i++) {
//         // const img = await faceapi.fetchImage(`https://raw.githubusercontent.com/WebDevSimplified/Face-Recognition-JavaScript/master/labeled_images/${label}/${i}.jpg`)
//         const img = await faceapi.fetchImage(`labeled_images/${label}/${i}.jpg`)
//         const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor()
//         descriptions.push(detections.descriptor)

//       }
//       return new faceapi.LabeledFaceDescriptors(label, descriptions)
//     })
//   )
// }

var folderExistsResponse = 0;
var retrivedID;
var files=[];
var match = 0;
var entered = 0;

function start() {
  document.body.append('Models Loaded')

  $.when(getSessionID()).done(function (ajaxResults) {
    // console.log("1st layer");
    $.when((checkFolderExists(`labeled_images/${retrivedID}`)), ).done(function (ajax2Results) {
      // console.log("2nd layer");
      // console.log("folderExistsResponse: " + folderExistsResponse)
      // console.log("destination: labeled_images/" + retrivedID)
      // console.log("retrivedID: " + retrivedID)
      $.when((getAllDirFiles(`labeled_images/${retrivedID}`)), ).done(function (ajax3Results) {
        // console.log("3rd layer");
        console.log(files);
        // console.log(files[2]);
        // console.log(files[3]);
        // console.log(files[4]);
        if (folderExistsResponse != 0 && files.length != 0) {
          navigator.getUserMedia({
              video: {}
            },
            stream => video.srcObject = stream,
            err => console.error(err)
          )

          // video.src = '../videos/speech.mp4'
          // console.log('video added')
          recognizeFaces()
        }

      });
    });
    
  });


}

async function recognizeFaces() {

  const labeledDescriptors = await loadLabeledImages()

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

          if(result['label']==retrivedID && match<=10 && entered==0){
            match++
          }else{
            entered++
            //redirect user to chat
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
        document.body.append(label + ' Faces Loaded | ')
        return new faceapi.LabeledFaceDescriptors(label, descriptions)
      })
    )
  }

}