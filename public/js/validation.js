document.addEventListener("DOMContentLoaded", function() {
    const validateForms = document.querySelectorAll('.validate-form');

    function isEmail(email) {
        return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email);
    }

    function isURL(url) {
        try {
            new URL(url);
            return true;
        } catch (e) {
            return false;
        }
    }

    function isValidNumber(input) {
        const value = input.value;
        const number = Number(value);
        const min = input.hasAttribute('min') ? Number(input.getAttribute('min')) : -Infinity;
        const max = input.hasAttribute('max') ? Number(input.getAttribute('max')) : Infinity;
        const step = input.hasAttribute('step') ? Number(input.getAttribute('step')) : 1;
        return !isNaN(number) && number >= min && number <= max && (number - min) % step === 0;
    }

    function isPhoneNumber(value) {
        return /^\d+$/.test(value);
    }

    function isValidPassword(value) {
        return value.length >= 6 && /\d/.test(value);
    }

    function markValidation(element, condition) {
        const messageElement = element.nextElementSibling && element.nextElementSibling.className === 'validation-message' ? element.nextElementSibling : null;
        if (!condition) {
            element.classList.add('no-valid');
            let message = '';
            if (element.required && element.value.trim() === "") {
                message = 'To pole jest wymagane.';
            } else if (element.type === 'email') {
                message = 'Proszę wprowadzić prawidłowy adres email.';
            } else if (element.type === 'url') {
                message = 'Proszę wprowadzić prawidłowy URL.';
            } else if (element.type === 'number') {
                message = 'Proszę wprowadzić prawidłową wartość numeryczną.';
            } else if (element.name === 'phone') {
                message = 'Numer telefonu może zawierać tylko cyfry.';
            } else if (element.type === 'password' || element.name === 'confirmedPassword') {
                message = 'Hasło musi mieć co najmniej 6 znaków i zawierać przynajmniej jedną cyfrę.';
                if (element.name === 'confirmedPassword') {
                    message = 'Hasła nie zgadzają się.';
                }
            } else if (element.tagName.toLowerCase() === 'textarea') {
                message = 'Przekroczono dozwoloną liczbę znaków.';
            }
            if (!messageElement) {
                const div = document.createElement('div');
                div.className = 'validation-message';
                div.textContent = message;
                element.parentNode.insertBefore(div, element.nextSibling);
            } else {
                messageElement.textContent = message;
            }
        } else {
            element.classList.remove('no-valid');
            if (messageElement) {
                messageElement.parentNode.removeChild(messageElement);
            }
        }
    }

    function validatePasswords(passwordInput, confirmedPasswordInput) {
        const passwordValid = isValidPassword(passwordInput.value);
        if (confirmedPasswordInput) {
            const passwordsMatch = passwordInput.value === confirmedPasswordInput.value;
            markValidation(confirmedPasswordInput, passwordsMatch);
            markValidation(passwordInput, passwordValid && passwordsMatch);
        } else {
            // Only one password field present, no confirmation required
            markValidation(passwordInput, passwordValid);
        }
    }

    function validateInput(input) {
        let isValid = true;
        if (input.value.trim() === "") {
            isValid = !input.required;
        } else if (input.type === 'email' || (input.type === 'text' && input.name === 'email')) {
            isValid = isEmail(input.value);
        } else if (input.type === 'url') {
            isValid = isURL(input.value);
        } else if (input.type === 'number') {
            isValid = isValidNumber(input);
        } else if (input.name === 'phone') {
            isValid = isPhoneNumber(input.value);
        } else if (input.type === 'password' || input.name === 'confirmedPassword') {
            const form = input.form;
            const passwordInput = form.querySelector('input[name="password"]');
            const confirmedPasswordInput = form.querySelector('input[name="confirmedPassword"]');
            validatePasswords(passwordInput, confirmedPasswordInput);
            return; // Skip further validation to avoid duplicating validation logic
        } else if (input.tagName.toLowerCase() === 'textarea') {
            isValid = input.maxLength < 0 || input.value.length <= input.maxLength;
        }

        if (input.type !== 'password' && input.name !== 'confirmedPassword') {
            markValidation(input, isValid);
        }
    }

    validateForms.forEach(form => {
        const elements = form.querySelectorAll('input, textarea');
        const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');

        if (submitButton) {
            submitButton.addEventListener('click', function(event) {
                let isFormValid = true;
                elements.forEach(element => {
                    validateInput(element);
                    if (!element.checkValidity()) {
                        isFormValid = false;
                    }
                });

                if (!isFormValid) {
                    event.preventDefault();
                }
            });
        }

        // elements.forEach(element => {
        //     element.addEventListener('keyup', () => validateInput(element));
        //     element.addEventListener('change', () => validateInput(element));
        // });
    });
});
