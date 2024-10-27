document.addEventListener('DOMContentLoaded', function() {
    // Check if signup is completed
    if (localStorage.getItem('signupCompleted') === 'true') {
        // Get the signup button element
        const signupBtn = document.getElementById('signup-btn');

        // Change the button text to "Login"
        signupBtn.innerText = 'Login';
        signupBtn.href = 'login.php';  // Redirect to login page

        // Optional: Add animation when the button text changes
        signupBtn.classList.add('fade-in');
    }
});

// CSS for fade-in effect (add this in signup.css or within signup.js if needed)
const style = document.createElement('style');
style.innerHTML = `
    .fade-in {
        opacity: 0;
        animation: fadeIn 1s forwards;
    }

    @keyframes fadeIn {
        to {
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);
