const form = document.querySelector(".signup form"),
continueBtn = form.querySelector(".button input"),
errorText = form.querySelector(".error-text");

form.onsubmit = (e)=>{
    e.preventDefault();
}

continueBtn.onclick = ()=>{
  
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/signup.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
              let data = xhr.response;
              if(data.includes("success")){
                sendMail(data.split(',')[1],data.split(',')[2],data.split(',')[3], data.split(',')[4] )
                
              }else{
                errorText.style.display = "block";
                errorText.textContent = data;
              }
          }
      }
    }
    let formData = new FormData(form);
    xhr.send(formData);
}

function sendMail(fname,unique_id, token, email){
  $.ajax({
    type: "POST",
    url: "php/sendMail.php",
    data: {
      fname: fname,
      unique_id: unique_id,
      token: token,
      email: email
    },
    success: function (response) {
      alert(response);
      location.href="login.php";
    }
  });
}