const searchBar = document.querySelector(".search input"),
  searchIcon = document.querySelector(".search button"),
  usersList = document.querySelector(".users-list");

searchIcon.onclick = () => {
  searchBar.classList.toggle("show");
  searchIcon.classList.toggle("active");
  searchBar.focus();
  if (searchBar.classList.contains("active")) {
    searchBar.value = "";
    searchBar.classList.remove("active");
  }
}

searchBar.onkeyup = () => {
  let searchTerm = searchBar.value;
  if (searchTerm != "") {
    searchBar.classList.add("active");
  } else {
    searchBar.classList.remove("active");
  }
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/search.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        usersList.innerHTML = data;
      }
    }
  }
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("searchTerm=" + searchTerm);
}

setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "php/users.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        if (!searchBar.classList.contains("active")) {
          usersList.innerHTML = data;
        }
      }
    }
  }
  xhr.send();
}, 1000);

setInterval(() => {
  $.ajax({
    type: "GET",
    url: "php/notification.php",
    data: "data",
    success: function (response) {
      console.log(response)
      $("#notification-count").text(response);
    }
  });
}, 1000);

$("#dropdownMenuButton2").click(function (e) { 
  $.ajax({
      type: "GET",
      url: "php/get-notification.php",
      data: "data",
      success: function (response) {
          // console.log(response);
          $('#notification_dropdown').html(response);
      }
  });
  
});

$(document).ready(function () {
  console.log('Focus');
  $.ajax({
    type: "POST",
    url: "php/setActive.php",
    data: {
      active : "Active now"
    },
    success: function (response) {
      console.log("Active")
    }
  });
});


$(window).focus(function () {
  console.log('Focus');
  $.ajax({
    type: "POST",
    url: "php/setActive.php",
    data: {
      active : "Active now"
    },
    success: function (response) {
      console.log("Active")
    }
  });
});

$(window).blur(function () {
  console.log('Blur');
  $.ajax({
    type: "POST",
    url: "php/setActive.php",
    data: {
      active : "Offline now"
    },
    success: function (response) {
      console.log("Not Active")
    }
  });
});