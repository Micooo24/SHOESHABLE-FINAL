document.addEventListener('DOMContentLoaded', (event) => {
    const inputs = document.querySelectorAll('.input-group_input');

    inputs.forEach(input => {
        const label = input.previousElementSibling;

        input.addEventListener('input', () => {
            if (input.value !== '') {
                label.style.top = '-12px';
                label.style.left = '8px';
                label.style.fontSize = '12px';
                label.style.color = '#001f3f'; /* Dark Blue */
                input.classList.add('has-focus'); /* Add class to animate input */
            } else {
                label.style.top = '12px';
                label.style.left = '12px';
                label.style.fontSize = '16px';
                label.style.color = '#001f3f'; /* Dark Blue */
                input.classList.remove('has-focus'); /* Remove class to reset animation */
            }
        });

        // Check input value on page load
        if (input.value !== '') {
            label.style.top = '-12px';
            label.style.left = '8px';
            label.style.fontSize = '12px';
            label.style.color = '#001f3f'; /* Dark Blue */
            input.classList.add('has-focus'); /* Add class to animate input */
        }
    });

});
