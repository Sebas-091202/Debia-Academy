const loginForm = document.getElementById("loginForm");
const registerForm = document.getElementById("registerForm");

const tabLogin = document.getElementById("tabLogin");
const tabRegister = document.getElementById("tabRegister");


function toggle(){
    loginForm.classList.toggle("active");
    registerForm.classList.toggle("active");

    tabLogin.classList.toggle("active");
    tabRegister.classList.toggle("active");
}

tabLogin.onclick = () => {
    loginForm.classList.add("active");
    registerForm.classList.remove("active");

    tabLogin.classList.add("active");
    tabRegister.classList.remove("active");
};

tabRegister.onclick = () => {
    registerForm.classList.add("active");
    loginForm.classList.remove("active");

    tabRegister.classList.add("active");
    tabLogin.classList.remove("active");
};

/* MOSTRAR CONTRASEÑA */

function togglePassword(id, icon){
    const input = document.getElementById(id);

    if(input.type === "password"){
        input.type = "text";
        icon.classList.replace("bx-show", "bx-hide");
    } else {
        input.type = "password";
        icon.classList.replace("bx-hide", "bx-show");
    }
}


