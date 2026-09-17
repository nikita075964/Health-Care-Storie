// DOM Elements
document.addEventListener("DOMContentLoaded", () => {
  // Scroll Reveal Animation
  const revealElements = document.querySelectorAll(".reveal")

  function revealOnScroll() {
    const windowHeight = window.innerHeight
    const revealPoint = 150

    revealElements.forEach((element) => {
      const elementTop = element.getBoundingClientRect().top

      if (elementTop < windowHeight - revealPoint) {
        element.classList.add("active")
      } else {
        element.classList.remove("active")
      }
    })
  }

  // Add scroll event listener
  window.addEventListener("scroll", revealOnScroll)

  // Initial check for elements in view
  revealOnScroll()

  // Header scroll effect
  const header = document.querySelector("header")

  function headerScrollEffect() {
    if (window.scrollY > 50) {
      header.classList.add("scrolled")
    } else {
      header.classList.remove("scrolled")
    }
  }

  window.addEventListener("scroll", headerScrollEffect)

  // Scroll to top button
  const scrollTopBtn = document.createElement("div")
  scrollTopBtn.className = "scroll-top"
  scrollTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>'
  document.body.appendChild(scrollTopBtn)

  scrollTopBtn.addEventListener("click", () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    })
  })

  function toggleScrollTopBtn() {
    if (window.scrollY > 300) {
      scrollTopBtn.classList.add("active")
    } else {
      scrollTopBtn.classList.remove("active")
    }
  }

  window.addEventListener("scroll", toggleScrollTopBtn)

  // Mobile Navigation Toggle
  const mobileNavToggle = document.createElement("button")
  mobileNavToggle.className = "mobile-nav-toggle"
  mobileNavToggle.innerHTML = '<i class="fas fa-bars"></i>'

  const navContainer = document.querySelector("nav")
  const navUl = document.querySelector("nav ul")

  if (navContainer && navUl) {
    navContainer.insertBefore(mobileNavToggle, navUl)

    mobileNavToggle.addEventListener("click", () => {
      navUl.classList.toggle("show")

      if (navUl.classList.contains("show")) {
        mobileNavToggle.innerHTML = '<i class="fas fa-times"></i>'
      } else {
        mobileNavToggle.innerHTML = '<i class="fas fa-bars"></i>'
      }
    })
  }

  // Add animation classes to elements
  const cards = document.querySelectorAll(".card")
  cards.forEach((card, index) => {
    card.style.opacity = "1" // Ensure initial visibility
    card.classList.add("reveal")
    card.classList.add(index % 2 === 0 ? "fade-left" : "fade-right")
    card.style.animationDelay = `${0.1 * index}s`
  })

  const sections = document.querySelectorAll("section")
  sections.forEach((section, index) => {
    section.classList.add("reveal")
    section.classList.add("fade-bottom")
  })

  // Form field animation
  const formInputs = document.querySelectorAll("input, select, textarea")
  formInputs.forEach((input) => {
    input.addEventListener("focus", function () {
      this.parentElement.classList.add("focused")
    })

    input.addEventListener("blur", function () {
      this.parentElement.classList.remove("focused")
    })
  })

  // Auto-calculate age from birth date
  const birthDateInput = document.getElementById("birthDate")
  const ageInput = document.getElementById("age")

  if (birthDateInput && ageInput) {
    birthDateInput.addEventListener("change", function () {
      const birthDate = new Date(this.value)
      const today = new Date()
      let age = today.getFullYear() - birthDate.getFullYear()
      const monthDiff = today.getMonth() - birthDate.getMonth()

      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--
      }

      ageInput.value = age
    })
  }

  // Image hover effect for gallery
  const galleryItems = document.querySelectorAll(".gallery-item")
  galleryItems.forEach((item) => {
    item.addEventListener("mouseenter", () => {
      galleryItems.forEach((otherItem) => {
        if (otherItem !== item) {
          otherItem.style.opacity = "0.7"
          otherItem.style.transform = "scale(0.95)"
        }
      })
    })

    item.addEventListener("mouseleave", () => {
      galleryItems.forEach((otherItem) => {
        otherItem.style.opacity = "1"
        otherItem.style.transform = ""
      })
    })
  })

  // Populate form fields with stored data
  const signupForm = document.getElementById("signupForm")
  const additionalInfoForm = document.getElementById("additionalInfoForm")
  const loginForm = document.getElementById("loginForm")
  const medicalHistoryForm = document.getElementById("medicalHistoryForm")

  if (signupForm) {
    const storedData = JSON.parse(localStorage.getItem("userData")) || {}
    if (storedData.username) {
      document.getElementById("username").value = storedData.username || ""
      document.getElementById("email").value = storedData.email || ""
      document.getElementById("aadhaar").value = storedData.aadhaar || ""
      if (document.getElementById("bloodGroup")) {
        document.getElementById("bloodGroup").value = storedData.bloodGroup || ""
      }
    }
  }

  if (additionalInfoForm) {
    const storedData = JSON.parse(localStorage.getItem("userData")) || {}
    if (storedData.username) {
      document.getElementById("infoUsername").value = storedData.username || ""
      document.getElementById("infoEmail").value = storedData.email || ""
      document.getElementById("infoAadhaar").value = storedData.aadhaar || ""
      document.getElementById("infoBloodGroup").value = storedData.bloodGroup || ""
    }
  }

  if (document.getElementById("profileUsername")) {
    const storedData = JSON.parse(localStorage.getItem("userData")) || {}
    if (storedData.username) {
      document.getElementById("profileUsername").textContent = storedData.username || "John Doe"
      document.getElementById("profileEmail").textContent = storedData.email || "johndoe@example.com"
      document.getElementById("profileAadhaar").textContent = storedData.aadhaar || "XXXX-XXXX-XXXX"
      document.getElementById("profileBloodGroup").textContent = storedData.bloodGroup || "O+"
    }
  }

  if (medicalHistoryForm) {
    const storedData = JSON.parse(localStorage.getItem("userData")) || {}
    if (storedData.username) {
      document.getElementById("medicalName").value = storedData.username || ""
      document.getElementById("medicalBloodGroup").value = storedData.bloodGroup || ""
      document.getElementById("allergies").value = storedData.allergies || ""
      document.getElementById("chronicConditions").value = storedData.chronicConditions || ""
      document.getElementById("currentMedications").value = storedData.currentMedications || ""
    }
  }

  // Edit Profile Button
  const editProfileBtn = document.getElementById("editProfileBtn")
  if (editProfileBtn) {
    editProfileBtn.addEventListener("click", () => {
      window.location.href = "additional-info.html"
    })
  }

  // Add loading animation to buttons
  const buttons = document.querySelectorAll(".btn")
  buttons.forEach((button) => {
    button.addEventListener("click", function (e) {
      if (this.type === "submit") {
        const form = this.closest("form")
        if (form && !form.checkValidity()) {
          return
        }

        const originalText = this.innerHTML
        this.innerHTML =
          '<div class="spinner" style="width: 20px; height: 20px; display: inline-block; margin-right: 5px;"></div> Processing...'

        setTimeout(() => {
          this.innerHTML = originalText
        }, 1500)
      }
    })
  })
})

// Trigger initial animations
document.addEventListener("DOMContentLoaded", () => {
  setTimeout(() => {
    document.querySelectorAll(".reveal").forEach((el) => {
      el.classList.add("active")
    })
  }, 100)
})

// Form Validation Functions
function validateSignupForm() {
  const username = document.getElementById("username").value
  const email = document.getElementById("email").value
  const aadhaar = document.getElementById("aadhaar").value
  const password = document.getElementById("password").value
  const bloodGroup = document.getElementById("bloodGroup").value

  // Basic validation
  if (!username || !email || !aadhaar || !password || !bloodGroup) {
    showToast("Please fill in all fields", "error")
    return false
  }

  // Validate Aadhaar (12 digits)
  if (!/^\d{12}$/.test(aadhaar)) {
    showToast("Aadhaar number must be 12 digits", "error")
    return false
  }

  // Validate email format
  if (!/\S+@\S+\.\S+/.test(email)) {
    showToast("Please enter a valid email address", "error")
    return false
  }

  // Store data in localStorage
  const userData = {
    username,
    email,
    aadhaar,
    bloodGroup,
  }

  localStorage.setItem("userData", JSON.stringify(userData))
  showToast("Sign up successful!", "success")
  return true
}

function validateLoginForm() {
  const email = document.getElementById("loginEmail").value
  const password = document.getElementById("loginPassword").value

  // Basic validation
  if (!email || !password) {
    showToast("Please fill in all fields", "error")
    return false
  }

  // Validate email format
  if (!/\S+@\S+\.\S+/.test(email)) {
    showToast("Please enter a valid email address", "error")
    return false
  }

  // In a real application, you would check credentials against a database
  // For demo purposes, we'll just simulate a successful login
  const storedData = JSON.parse(localStorage.getItem("userData")) || {}

  if (storedData.email !== email) {
    showToast("Email not found. Please sign up first.", "error")
    return false
  }

  showToast("Login successful!", "success")
  return true
}

function validateAdditionalInfoForm() {
  const username = document.getElementById("infoUsername").value
  const email = document.getElementById("infoEmail").value
  const aadhaar = document.getElementById("infoAadhaar").value
  const bloodGroup = document.getElementById("infoBloodGroup").value
  const birthDate = document.getElementById("birthDate").value
  const age = document.getElementById("age").value
  const gender = document.getElementById("gender").value
  const emergencyName = document.getElementById("emergencyName").value
  const emergencyContact = document.getElementById("emergencyContact").value
  const emergencyRelation = document.getElementById("emergencyRelation").value

  // Basic validation
  if (
    !username ||
    !email ||
    !aadhaar ||
    !bloodGroup ||
    !birthDate ||
    !age ||
    !gender ||
    !emergencyName ||
    !emergencyContact ||
    !emergencyRelation
  ) {
    showToast("Please fill in all fields", "error")
    return false
  }

  // Validate Aadhaar (12 digits)
  if (!/^\d{12}$/.test(aadhaar)) {
    showToast("Aadhaar number must be 12 digits", "error")
    return false
  }

  // Validate email format
  if (!/\S+@\S+\.\S+/.test(email)) {
    showToast("Please enter a valid email address", "error")
    return false
  }

  // Validate emergency contact (10 digits)
  if (!/^\d{10}$/.test(emergencyContact)) {
    showToast("Emergency contact must be 10 digits", "error")
    return false
  }

  // Update stored data
  const storedData = JSON.parse(localStorage.getItem("userData")) || {}
  const updatedData = {
    ...storedData,
    username,
    email,
    aadhaar,
    bloodGroup,
    birthDate,
    age,
    gender,
    emergencyName,
    emergencyContact,
    emergencyRelation,
  }

  localStorage.setItem("userData", JSON.stringify(updatedData))
  showToast("Information updated successfully!", "success")
  return true
}

function validateMedicalHistoryForm() {
  const name = document.getElementById("medicalName").value
  const bloodGroup = document.getElementById("medicalBloodGroup").value
  const allergies = document.getElementById("allergies").value
  const chronicConditions = document.getElementById("chronicConditions").value
  const currentMedications = document.getElementById("currentMedications").value

  // Basic validation
  if (!name || !bloodGroup) {
    showToast("Name and Blood Group are required fields", "error")
    return false
  }

  // Update stored data
  const storedData = JSON.parse(localStorage.getItem("userData")) || {}
  const updatedData = {
    ...storedData,
    username: name,
    bloodGroup,
    allergies,
    chronicConditions,
    currentMedications,
  }

  localStorage.setItem("userData", JSON.stringify(updatedData))
  showToast("Medical history updated successfully!", "success")
  return false // Prevent form submission to stay on the same page
}

// Toast notification function
function showToast(message, type = "info") {
  // Create toast container if it doesn't exist
  let toastContainer = document.querySelector(".toast-container")
  if (!toastContainer) {
    toastContainer = document.createElement("div")
    toastContainer.className = "toast-container"
    toastContainer.style.position = "fixed"
    toastContainer.style.top = "20px"
    toastContainer.style.right = "20px"
    toastContainer.style.zIndex = "1000"
    document.body.appendChild(toastContainer)
  }

  // Create toast element
  const toast = document.createElement("div")
  toast.className = `toast toast-${type}`
  toast.style.backgroundColor = type === "error" ? "#e74c3c" : type === "success" ? "#2ecc71" : "#3498db"
  toast.style.color = "#ffffff"
  toast.style.padding = "12px 20px"
  toast.style.borderRadius = "5px"
  toast.style.marginBottom = "10px"
  toast.style.boxShadow = "0 2px 10px rgba(0, 0, 0, 0.2)"
  toast.style.animation = "fadeIn 0.3s ease, slideInFromRight 0.3s ease"
  toast.style.display = "flex"
  toast.style.alignItems = "center"
  toast.style.justifyContent = "space-between"

  // Add icon based on type
  let icon = ""
  if (type === "error") {
    icon = '<i class="fas fa-exclamation-circle"></i>'
  } else if (type === "success") {
    icon = '<i class="fas fa-check-circle"></i>'
  } else {
    icon = '<i class="fas fa-info-circle"></i>'
  }

  toast.innerHTML = `
        <div style="display: flex; align-items: center;">
            <span style="margin-right: 10px;">${icon}</span>
            <span>${message}</span>
        </div>
        <button style="background: none; border: none; color: white; cursor: pointer; margin-left: 10px;">
            <i class="fas fa-times"></i>
        </button>
    `

  // Add to container
  toastContainer.appendChild(toast)

  // Add close button functionality
  const closeBtn = toast.querySelector("button")
  closeBtn.addEventListener("click", () => {
    toast.style.opacity = "0"
    setTimeout(() => {
      toastContainer.removeChild(toast)
    }, 300)
  })

  // Auto remove after 5 seconds
  setTimeout(() => {
    if (toast.parentNode === toastContainer) {
      toast.style.opacity = "0"
      setTimeout(() => {
        if (toast.parentNode === toastContainer) {
          toastContainer.removeChild(toast)
        }
      }, 300)
    }
  }, 5000)
}

