// Mock admin credentials
const ADMIN_EMAIL = "admin@healthstories.com"
const ADMIN_PASSWORD = "admin123"

// Mock user data (in a real application, this would come from a database)
const mockUserData = {
  123456789012: {
    userName: "John Doe",
    bloodGroup: "O+",
    age: "30",
    gender: "Male",
    allergies: "Penicillin",
    chronicConditions: "None",
    currentMedications: "None",
    emergencyName: "Jane Doe",
    emergencyContact: "9876543210",
    emergencyRelation: "Spouse",
  },
}

// Function to validate admin login
function validateLogin(event) {
  event.preventDefault()

  const email = document.getElementById("email").value
  const password = document.getElementById("password").value

  if (email === ADMIN_EMAIL && password === ADMIN_PASSWORD) {
    // Hide login section and show search section
    document.getElementById("loginSection").style.display = "none"
    document.getElementById("searchSection").style.display = "block"

    // Clear login form
    document.getElementById("adminLoginForm").reset()
  } else {
    alert("Invalid credentials. Please try again.")
  }

  return false
}

// Function to search user by Aadhaar number
function searchUser() {
  const aadhaarNumber = document.getElementById("aadhaarSearch").value

  // Validate Aadhaar number format (12 digits)
  if (!/^\d{12}$/.test(aadhaarNumber)) {
    alert("Please enter a valid 12-digit Aadhaar number")
    return
  }

  const userData = mockUserData[aadhaarNumber]

  if (userData) {
    // Display user data
    displayUserData(userData)
  } else {
    alert("User not found")
    document.getElementById("userCard").style.display = "none"
  }
}

// Function to display user data
function displayUserData(userData) {
  // Update all spans with user data
  document.getElementById("userName").textContent = userData.userName
  document.getElementById("bloodGroup").textContent = userData.bloodGroup
  document.getElementById("age").textContent = userData.age
  document.getElementById("gender").textContent = userData.gender
  document.getElementById("allergies").textContent = userData.allergies
  document.getElementById("chronicConditions").textContent = userData.chronicConditions
  document.getElementById("currentMedications").textContent = userData.currentMedications
  document.getElementById("emergencyName").textContent = userData.emergencyName
  document.getElementById("emergencyContact").textContent = userData.emergencyContact
  document.getElementById("emergencyRelation").textContent = userData.emergencyRelation

  // Show the user card
  document.getElementById("userCard").style.display = "flex"
}

// Add event listener for Enter key in search input
document.getElementById("aadhaarSearch")?.addEventListener("keypress", (event) => {
  if (event.key === "Enter") {
    searchUser()
  }
})

// Add event listener for page load to ensure login form is shown
window.addEventListener("load", () => {
  document.getElementById("loginSection").style.display = "block"
  document.getElementById("searchSection").style.display = "none"
  document.getElementById("userCard").style.display = "none"
})

