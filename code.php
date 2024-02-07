<?php 
session_start();
$_SESSION["token"] = bin2hex(random_bytes(32));
$_SESSION["token-expire"] = time() + 3600;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>Login Form</title>
</head>
<body>
    <div class="container mt-5">
        <div class="card text-center">
            <div class="card-header">
                <img src="logo.png" alt="Logo" class="logo mx-auto d-block">
            </div>
            <div class="card-body">
                <form id="loginForm">
                    <div class="form-group">

                        <label for="username">Identifiant</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="buttons">
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                          
                        <form methode ="POST" action="process.php">
                            <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>"/>
                            <button type="button" class="btn btn-success" onclick="submitForm()">Valider</button>
                            <button type="button" class="btn btn-primary" onclick="addAccount()">Ajout compte</button>    
                        </form>
                    </div>
                </form>
            </div>
        </div>

        <div id="message" class="mt-3 text-center font-weight-bold"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
