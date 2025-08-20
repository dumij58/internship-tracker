<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/bffea625a4.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h1 id="title">Sign Up</h1>
                <form method="POST" action="register.php" id="loginForm">
                    <div class="input-group">
                        <div class="input-field" id="namefield">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="name" placeholder="Name" required>
                        </div>

                        <div class="input-field">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" name="email" placeholder="Email" required>
                        </div>

                        <div class="input-field">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="password" placeholder="Password" required>
                        </div>
                        <p>Lost password <a href="#">Click here</a></p>
                            <div class="icon" id="iconId">
                            <i class="fa-brands fa-google"></i>
                            <i class="fa-brands fa-facebook"></i>
                        </div>
                    </div>
                        
                    <div class="btn-field">
                        <button type="submit" id="signupBtn" name="signUp">Sign up</button>
                        <button type="button" class="disable" id="signinBtn">Sign in</button>
                    </div>
                </form>
        </div>
    </div>
<script>
    let signupBtn = document.getElementById("signupBtn");
    let signinBtn = document.getElementById("signinBtn");
    let namefield = document.getElementById("namefield");
    let title = document.getElementById("title");
    let iconId = document.getElementById("iconId");
    let loginForm = document.getElementById("loginForm");
    let nameInput = document.querySelector('input[name="name"]');

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
</script>
</body>
</html>