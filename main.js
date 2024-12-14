const form = document.getElementById('form');
const form2 = document.getElementById('form2');


if (form) {
    const email = document.getElementById('email');
    const password = document.getElementById('password');

    form.addEventListener('submit', e => {
        e.preventDefault();
        validateInputs();
    });

    const validateInputs = () => {
        const emailValue = email.value.trim();
        const passwordValue = password.value.trim();
     
        if (emailValue === '') {
            setError(email, 'Email is required');
        } else if (!isValidEmail(emailValue)) {
            setError(email, 'Provide a valid email address');
        } else {
            setSuccess(email);
        }

        if (passwordValue === '') {
            setError(password, 'Password is required');
        } else if (passwordValue.length < 8) {
            setError(password, 'Password must be at least 8 character');
        } else {
            setSuccess(password);
        }
    };
}


if (form2) {
    const username = document.getElementById('username');
    const email2 = document.getElementById('email2');
    const password2 = document.getElementById('password2');
    const passwordC = document.getElementById('passwordC');

    form2.addEventListener('submit', e => {
        e.preventDefault();
        validateInputs2();
    });

    const validateInputs2 = () => {
        const usernameValue = username.value.trim();
        const email2Value = email2.value.trim();
        const password2Value = password2.value.trim();
        const passwordCValue = passwordC.value.trim();

        if (usernameValue === '') {
            setError(username, 'Username is required');
        } else {
            setSuccess(username);
        }
     
        if (email2Value === '') {
            setError(email2, 'Email is required');
        } else if (!isValidEmail(email2Value)) {
            setError(email2, 'Provide a valid email address');
        } else {
            setSuccess(email2);
        }

        if (password2Value === '') {
            setError(password2, 'Password is required');
        } else if (password2Value.length < 8) {
            setError(password2, 'Password must be at least 8 character');
        } else {
            setSuccess(password2);
        }

        if (passwordCValue === '') {
            setError(passwordC, 'Password is required');
        } else if (passwordCValue !== password2Value) {
            setError(passwordC, 'Passwords do not match');
        } else {
            setSuccess(passwordC);
        }
    };
}


const setError = (element, message) => {
    const inputControl = element.parentElement;
    const errorDisplay = inputControl.querySelector('.error, .error2');
    errorDisplay.innerText = message;
    inputControl.classList.add('error');
    inputControl.classList.remove('success');
};

const setSuccess = element => {
    const inputControl = element.parentElement;
    const errorDisplay = inputControl.querySelector('.error, .error2');
    errorDisplay.innerText = '';
    inputControl.classList.add('success');
    inputControl.classList.remove('error');
};

const isValidEmail = email => {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}


