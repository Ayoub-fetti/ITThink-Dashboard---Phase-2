const form = document.getElementById('form');
const form2 = document.getElementById('form2');


if (form) {
    const email = document.getElementById('email');
    const password = document.getElementById('password');

    form.addEventListener('submit', e => {
        e.preventDefault();
        if (validateInputs()) {
            form.submit();
        }
    });

    const validateInputs = () => {
        let isValid = true;
        const emailValue = email.value.trim();
        const passwordValue = password.value.trim();
     
        if (emailValue === '') {
            setError(email, 'Email is required');
            isValid = false;
        } else if (!isValidEmail(emailValue)) {
            setError(email, 'Provide a valid email address');
            isValid = false;
        } else {
            setSuccess(email);
        }

        if (passwordValue === '') {
            setError(password, 'Password is required');
            isValid = false;
        } else if (passwordValue.length < 8) {
            setError(password, 'Password must be at least 8 character');
            isValid = false;
        } else {
            setSuccess(password);
        }

        return isValid;
    };
}


if (form2) {
    const username = document.getElementById('username');
    const email2 = document.getElementById('email2');
    const password2 = document.getElementById('password2');
    const passwordC = document.getElementById('passwordC');

    form2.addEventListener('submit', e => {
        e.preventDefault();
        if (validateInputs2()) {
            form2.submit();
        }
    });

    const validateInputs2 = () => {
        let isValid = true;
        const usernameValue = username.value.trim();
        const email2Value = email2.value.trim();
        const password2Value = password2.value.trim();
        const passwordCValue = passwordC.value.trim();

        if (usernameValue === '') {
            setError(username, 'Username is required');
            isValid = false;
        } else {
            setSuccess(username);
        }
     
        if (email2Value === '') {
            setError(email2, 'Email is required');
            isValid = false;
        } else if (!isValidEmail(email2Value)) {
            setError(email2, 'Provide a valid email address');
            isValid = false;
        } else {
            setSuccess(email2);
        }

        if (password2Value === '') {
            setError(password2, 'Password is required');
            isValid = false;
        } else if (password2Value.length < 8) {
            setError(password2, 'Password must be at least 8 character');
            isValid = false;
        } else {
            setSuccess(password2);
        }

        if (passwordCValue === '') {
            setError(passwordC, 'Password is required');
            isValid = false;
        } else if (passwordCValue !== password2Value) {
            setError(passwordC, 'Passwords do not match');
            isValid = false;
        } else {
            setSuccess(passwordC);
        }

        return isValid;
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


