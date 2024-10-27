// Manage Workshops Button Logic
document.getElementById('manageWorkshops').addEventListener('click', function() {
    fetch('manage_workshops.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('content').innerHTML = data;
        });
});

// Manage Users Button Logic
document.getElementById('manageUsers').addEventListener('click', function() {
    fetch('manage_users.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('content').innerHTML = data;
        });
});

// Manage Mechanics Button Logic
document.getElementById('manageMechanics').addEventListener('click', function() {
    fetch('manage_mechanics.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('content').innerHTML = data;
        });
});
