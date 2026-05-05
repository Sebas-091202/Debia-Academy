const loginForm = document.getElementById("loginForm");
const registerForm = document.getElementById("registerForm");

const switchButtons = document.querySelectorAll(".switch");

switchButtons.forEach(btn => {
    btn.addEventListener("click", () => {
        loginForm.classList.toggle("active");
        registerForm.classList.toggle("active");
    });
});
