function validateSignupForm() {
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const aadhaar = document.getElementById('aadhaar').value;
    const password = document.getElementById('password').value;
    const bloodGroup = document.getElementById('bloodGroup').value;

    if (!username || !email || !aadhaar || !password || !bloodGroup) {
        alert('All fields are required.');
        return false;
    }

    if (aadhaar.length !== 12 || isNaN(aadhaar)) {
        alert('Aadhaar must be a 12-digit number.');
        return false;
    }

    return true;
}