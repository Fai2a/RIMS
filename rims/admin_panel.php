<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- For icons -->
</head>

<body>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="index.html" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2>Admin Panel</h2>
        </div>
        <div class="row">
            <div class="col-md-6">
                <button id="manageWorkshops" class="btn btn-primary btn-block">Manage Workshops</button>
            </div>
            <div class="col-md-6">
                <button id="manageUsers" class="btn btn-primary btn-block">Manage Users</button>
            </div>
            <div class="col-md-6">
                <button id="manageMechanics" class="btn btn-primary btn-block">Manage Mechanics</button>
            </div>
        </div>
        <div id="content">
            <!-- Dynamic content will be loaded here -->
        </div>
    </div>

    <script src="admin.js"></script>
</body>

</html>