let signupBtn = document.getElementById("signupBtn");
let signinBtn = document.getElementById("signinBtn");
let namefield = document.getElementById("namefield");
let title = document.getElementById("title");
let iconId = document.getElementById("iconId");
let loginForm = document.getElementById("loginForm");
let nameInput = document.querySelector('input[name="name"]');

// Initialize with signin as default
signinBtn.classList.remove("disable");
signupBtn.classList.add("disable");
signinBtn.name = "signIn";
signinBtn.type = "submit";
signupBtn.name = "";
signupBtn.type = "button";
nameInput.required = false;

signinBtn.onclick = function(){
    namefield.style.maxHeight="0";
    title.innerHTML="Sign In";
    signupBtn.classList.add("disable");
    signinBtn.classList.remove("disable");
    signupBtn.name = ""; // Remove name attribute
    signinBtn.name = "signIn"; // Add name attribute
    signinBtn.type = "submit"; // Change to submit
    signupBtn.type = "button"; // Change to button
    iconId.style.marginTop = "30px";
    nameInput.required = false; // Make name not required for signin
}

signupBtn.onclick=function(){
    namefield.style.maxHeight="60px";
    title.innerHTML="Sign Up";
    signupBtn.classList.remove("disable");
    signinBtn.classList.add("disable");
    signupBtn.name = "signUp"; // Add name attribute
    signinBtn.name = ""; // Remove name attribute
    signupBtn.type = "submit"; // Change to submit
    signinBtn.type = "button"; // Change to button
    iconId.style.marginTop = "10px";
    nameInput.required = true; // Make name required for signup
}