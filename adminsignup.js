function validateSignupForm() {
    // Get form inputs
    const drName = document.getElementById('drName').value.trim();
    const email = document.getElementById('email').value.trim();
    const hospitalName = document.getElementById('hospitalName').value.trim();
    const contactNo = document.getElementById('contactNo').value.trim();
    const address = document.getElementById('address').value.trim();
    const aadhaarNo = document.getElementById('aadhaarNo').value.trim();
    const city = document.getElementById('city').value.trim();
    const bloodGroup = document.getElementById('bloodGroup').value;

    // Validation rules
    const nameRegex = /^[A-Za-z\s]+$/; // Only letters and spaces
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Basic email validation
    const contactRegex = /^\d{10}$/; // Exactly 10 digits
    const aadhaarRegex = /^\d{12}$/; // Exactly 12 digits

    // Validate Dr. Name
    if (!drName.match(nameRegex)) {
        alert("Please enter a valid Dr. Name (only letters and spaces allowed).");
        return false;
    }

    // Validate Email
    if (!email.match(emailRegex)) {
        alert("Please enter a valid Email ID.");
        return false;
    }

    // Validate Hospital Name
    if (hospitalName === "") {
        alert("Please enter the Hospital Name.");
        return false;
    }

    // Validate Contact No
    if (!contactNo.match(contactRegex)) {
        alert("Please enter a valid 10-digit Contact No.");
        return false;
    }

    // Validate Address
    if (address === "") {
        alert("Please enter the Address.");
        return false;
    }

    // Validate Aadhaar No
    if (!aadhaarNo.match(aadhaarRegex)) {
        alert("Please enter a valid 12-digit Aadhaar No.");
        return false;
    }

    // Validate City
    if (city === "") {
        alert("Please enter the City.");
        return false;
    }

    // Validate Blood Group
    if (bloodGroup === "") {
        alert("Please select a Blood Group.");
        return false;
    }

    // If all validations pass, return true
    return true;
}