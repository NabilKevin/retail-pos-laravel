function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(fieldId === 'password' ? 'eyeIcon' : 'eyeIconConfirm');
    const eyeOffIcon = document.getElementById(fieldId === 'password' ? 'eyeOffIcon' : 'eyeOffIconConfirm');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.classList.add('hidden');
        eyeOffIcon.classList.remove('hidden');
    } else {
        passwordField.type = 'password';
        eyeIcon.classList.remove('hidden');
        eyeOffIcon.classList.add('hidden');
    }
}

const form = document.querySelector('form');

document.querySelector('button[type=submit]').addEventListener('click', function() {
    let allInputFilled = true
    const inputs = form.querySelectorAll("input"); // ambil semua input
    inputs.forEach(input => {
      if (input.type !== 'hidden' && !input.value.trim()) {
        if(input.type !== 'password') {
            allInputFilled = false
        }
      }
    });

    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');

    form.addEventListener('submit', function(e) {
        if (password.value !== passwordConfirmation.value) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak cocok!');
            passwordConfirmation.focus();
        }
        if(allInputFilled) {
            setTimeout(() => {
                this.disabled = true;
                this.style.opacity = '0.5';
                this.style.cursor = 'not-allowed';
            }, 1)
        }
    });
})
