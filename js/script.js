document.addEventListener("DOMContentLoaded", function () {
    const registerForm = document.getElementById("registerForm");
    const loginForm = document.getElementById("loginForm");
    const registerMessage = document.getElementById("registerMessage");
    const loginMessage = document.getEleemtnById("loginMessage");

    registerForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const formData = new FormData(registerForm);
        fetch("register.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                registerMessage.textContent = "Registration successful";
                registerMessage.classList.add("success");
                registerForm.reset();
            } 
            else {
               registerMessage.textContent = data.message;
               registerMessage.classList.remove("success"); 
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    });

    loginForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const formData = new FormData(loginForm);
        fetch("login.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loginMessage.textContent = "Login successful";
                loginMessage.classList.add("success");
                loginForm.reset();
            }
            else {
                loginMessage.textContent = data.message;
                loginMessage.classList.remove("success");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    });
});