const container = document.getElementById('container');
const body = document.getElementById('body');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
    //body.style.background = 'linear-gradient(to left, #e2e2e2, #013cff)';
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
    //body.style.background = 'linear-gradient(to right, #e2e2e2, #013cff)';
});