<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Policlínico San Gabriel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/chatbot.css">
    <!-- Google Fonts Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { 
            background: url('https://img.freepik.com/free-vector/clean-medical-background_53876-97927.jpg?w=1380&t=st=1709154321~exp=1709154921~hmac=f231983f318811711e3111711171117111711171117111711171117111711171') no-repeat center center fixed; 
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .sidebar { min-height: 100vh; background: #2c3e50; color: #fff; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 10px 20px; }
        .sidebar a:hover { background: #34495e; color: #fff; }
        .card-custom { border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        /* Ensure main content grows to push footer down */
        main, .flex-grow-1 { flex: 1; }
        /* Semi-transparent backgrounds for readability, but respect colored cards */
        .card:not(.bg-primary):not(.bg-success):not(.bg-danger):not(.bg-info):not(.bg-warning), 
        .table-responsive, 
        .bg-light { 
            background-color: rgba(255, 255, 255, 0.9) !important; 
            border-radius: 10px; 
        }
        /* Sidebar styling fix */
        .sidebar a.bg-secondary { background-color: #34495e !important; color: #fff !important; }
    </style>
</head>
<body>