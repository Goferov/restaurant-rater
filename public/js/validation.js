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

    function markValidation(element, condition) {
        !condition ? element.classList.add('no-valid') : element.classList.remove('no-valid');
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
            isValid = input.value.length >= 6 && /\d/.test(input.value);
            if (input.form.querySelector('input[name="password"]') && input.form.querySelector('input[name="confirmedPassword"]')) {
                const passwordInput = input.form.querySelector('input[name="password"]');
                const confirmedPasswordInput = input.form.querySelector('input[name="confirmedPassword"]');
                const passwordsMatch = passwordInput.value === confirmedPasswordInput.value;
                markValidation(confirmedPasswordInput, passwordsMatch);
                isValid = isValid && passwordsMatch; // Ensure both validations pass
            }
        } else if (input.tagName.toLowerCase() === 'textarea') {
            isValid = input.maxLength < 0 || input.value.length <= input.maxLength;
        }

        markValidation(input, isValid);
        return isValid;
    }

    validateForms.forEach(form => {
        const elements = form.querySelectorAll('input, textarea');
        const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');

        if (submitButton) {
            submitButton.addEventListener('click', function(event) {
                let isFormValid = true;
                elements.forEach(element => {
                    if (!validateInput(element)) {
                        isFormValid = false;
                    }
                });

                if (!isFormValid) {
                    event.preventDefault(); // Prevent form submission if validation fails
                }
            });
        }

        elements.forEach(element => {
            element.addEventListener('keyup', () => validateInput(element));
            element.addEventListener('change', () => validateInput(element));
        });
    });
});
