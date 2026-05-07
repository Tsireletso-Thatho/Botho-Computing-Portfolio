const wrapper = document.querySelector('.wrapper');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');

registerLink.addEventListener('click', () => {
    wrapper.classList.add('active');
});

loginLink.addEventListener('click', () => {
    wrapper.classList.remove('active');
});


function validate() {
    var username = document.getElementById('name');
    var role = document.getElementById('role');
    var password = document.getElementById('pass');
    var email = document.getElementById('emaill');
    
    if(username.value.trim() === "" || role.value.trim() === "" || password.value.trim() === "" || email.value.trim() === ""){
        alert("Please fill all fields!");
        return false;
    }
    else{
        return true;
    }
}