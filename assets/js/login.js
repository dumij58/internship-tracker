let signupBtn = document.getElementById("signupBtn");
let signinBtn = document.getElementById("signinBtn");
let namefield = document.getElementById("namefield");
let usertypefield = document.getElementById("usertypefield");
let title = document.getElementById("title");
let loginForm = document.getElementById("loginForm");
let nameInput = document.querySelector('input[name="name"]');
let userTypeSelect = document.querySelector('select[name="user_type"]');

// Initialize with signin as default
signinBtn.classList.remove("disable");
signupBtn.classList.add("disable");
signinBtn.name = "signIn";
signinBtn.type = "submit";
signupBtn.name = "";
signupBtn.type = "button";

signinBtn.onclick = function(){
    //console.log("Sign In button clicked");
    namefield.style.maxHeight="0";
    usertypefield.style.maxHeight="0";
    title.innerHTML="Sign In";
    signupBtn.classList.add("disable");
    signinBtn.classList.remove("disable");
    signupBtn.name = ""; // Remove name attribute
    signinBtn.name = "signIn"; // Add name attribute
    signinBtn.type = "submit"; // Change to submit
    signupBtn.type = "button"; // Change to button
    nameInput.required = false; // Make name not required for signin
    userTypeSelect.required = false; // Make user type not required for signin
}

signupBtn.onclick=function(){
    //console.log("Sign Up button clicked");
    namefield.style.maxHeight="60px";
    usertypefield.style.maxHeight="60px";
    title.innerHTML="Sign Up";
    signupBtn.classList.remove("disable");
    signinBtn.classList.add("disable");
    signupBtn.name = "signUp"; // Add name attribute
    signinBtn.name = ""; // Remove name attribute
    signupBtn.type = "submit"; // Change to submit
    signinBtn.type = "button"; // Change to button
    nameInput.required = true; // Make name required for signup
    userTypeSelect.required = true; // Make user type required for signup
}