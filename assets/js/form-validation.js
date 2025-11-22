/**
 * Basic front-end validation example
 */
document.addEventListener('DOMContentLoaded', () => {
  const registerForm = document.querySelector('form[action*="register"]');
  if (registerForm) {
    registerForm.addEventListener('submit', (e) => {
      const pwd = registerForm.querySelector('input[name="password"]');
      const confirm = registerForm.querySelector('input[name="confirm_password"]');
      if (pwd && confirm && pwd.value !== confirm.value) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas.');
      }
    });
  }
});
