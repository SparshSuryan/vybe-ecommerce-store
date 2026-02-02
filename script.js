//SIGN-IN 

// Wait for the DOM to fully load
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("register-form");

    form.addEventListener("submit", function (e) {
        // Get form inputs
        const userId = document.getElementById("user_id").value.trim();
        const fullName = document.getElementById("fullname").value.trim();
        const email = document.getElementById("email").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const password = document.getElementById("password").value.trim();
        const confirmPassword = document.getElementById("confirm_password").value.trim();
        const postalCode = document.getElementById("postal_code").value.trim();
        const privacyPolicy = document.getElementById("privacy_policy").checked;
        const termsConditions = document.getElementById("terms_conditions").checked;

        // Predefined list of existing User IDs (for demonstration purposes)
        const existingUserIds = ["user123", "admin", "guest2023"];

        // Validation checks
        let errors = [];

        // User ID validation
        const userIdPattern = /^[a-zA-Z0-9_]{5,15}$/; // Alphanumeric, underscores, 5-15 characters
        if (!userIdPattern.test(userId)) {
            errors.push("User ID must be 5-15 characters long, alphanumeric (underscores allowed).");
        }

        // Check for unique User ID (simulate backend check)
        if (existingUserIds.includes(userId)) {
            errors.push("User ID is already taken. Please choose another.");
        }

        // Full name check
        if (fullName === "") {
            errors.push("Full name is required.");
        }

        // Email check
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            errors.push("Enter a valid email address.");
        }

        // Phone number check (10-digit numbers)
        const phonePattern = /^[0-9]{10}$/;
        if (!phonePattern.test(phone)) {
            errors.push("Enter a valid 10-digit phone number.");
        }

        // Password check
        if (password.length < 8) {
            errors.push("Password must be at least 8 characters long.");
        }

        // Confirm password check
        if (password !== confirmPassword) {
            errors.push("Passwords do not match.");
        }

        // Postal code check (numeric and at least 5 digits)
        const postalCodePattern = /^[0-9]{5,}$/;
        if (!postalCodePattern.test(postalCode)) {
            errors.push("Postal code must be numeric and at least 5 digits.");
        }

        // Checkbox validation
        if (!privacyPolicy) {
            errors.push("You must agree to the Privacy Policy.");
        }

        if (!termsConditions) {
            errors.push("You must agree to the Terms and Conditions.");
        }

        // If errors exist, prevent form submission and show alerts
        if (errors.length > 0) {
            e.preventDefault(); // Prevent form submission
            alert(errors.join("\n")); // Display errors as a list
        }
    });
});


