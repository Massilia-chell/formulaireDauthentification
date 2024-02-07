<?php

define("ERROR_MISSING_FIELDS", "Erreur: Veuillez remplir tous les champs.");
define("ERROR_USERNAME_EXISTS", "Erreur: L'identifiant existe déjà, veuillez saisir un nouveau.");
define("ERROR_ADD_ACCOUNT", "Erreur lors de l'ajout du compte: ");
define("SUCCESS_ADD_ACCOUNT", "Compte ajouté avec succès!");
define("ERROR_AUTHENTICATION", "Erreur: Identifiant ou mot de passe incorrect. Réessayer");
define("SUCCESS_AUTHENTICATION", "Authentification réussie!");
define("ERROR_UNKNOWN_ACTION", "Erreur: Action non reconnue.");


session_start();
$_SESSION["token"] = bin2hex(random_bytes(32));
$_SESSION["token-expire"] = time() + 3600;

if (isset($_POST["token"]) && isset($_SESSION["token"])) { 
    exit("token no set ");
 }

if($_SESSION["token"] ){
    if(time() >= $_SESSION["token-expire"]){
        exit("Token expired");
    }
 unset($_SESSION["token"]);
 unset($_SESSION["token-expire"]);
}else{
  exit("Invalide token!");
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mybase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données: " . $conn->connect_error);
}

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'addAccount':
            addAccount($conn);
            break;
        case 'authenticate':
            authenticate($conn);
            break;
        default:
            echo htmlspecialchars(ERROR_UNKNOWN_ACTION, ENT_QUOTES, 'UTF-8');
            break;
    }
} else {
    echo htmlspecialchars(ERROR_UNKNOWN_ACTION, ENT_QUOTES, 'UTF-8');
}

$conn->close();

function isStrongPassword($password) {
    // Vérifie si le mot de passe a au moins 8 caractères
    if (strlen($password) < 8) {
        return false;
    }

    // Vérifie s'il contient au moins une lettre majuscule
    if (!preg_match("/[A-Z]/", $password)) {
        return false;
    }

    // Vérifie s'il contient au moins une lettre minuscule
    if (!preg_match("/[a-z]/", $password)) {
        return false;
    }

    // Vérifie s'il contient au moins un chiffre
    if (!preg_match("/[0-9]/", $password)) {
        return false;
    }

    // Vérifie s'il contient au moins un caractère spécial
    if (!preg_match("/[!@#$%^&*()-_=+{};:,<.>]/", $password)) {
        return false;
    }

    return true;
}
function addAccount($conn)
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo htmlspecialchars(ERROR_MISSING_FIELDS, ENT_QUOTES, 'UTF-8');
    } elseif (!isStrongPassword($password)) {
        echo htmlspecialchars("Erreur: Le mot de passe doit avoir au moins 8 caractères, inclure des lettres majuscules, des lettres minuscules, des chiffres et des caractères spéciaux. Réessayer", ENT_QUOTES, 'UTF-8');
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result_check = $stmt->get_result();

        if ($result_check->num_rows > 0) {
            echo htmlspecialchars(ERROR_USERNAME_EXISTS, ENT_QUOTES, 'UTF-8');
        } else {
            // Utilisez password_hash pour hasher le mot de passe
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $password_hash);

            if ($stmt->execute()) {
                echo htmlspecialchars(SUCCESS_ADD_ACCOUNT, ENT_QUOTES, 'UTF-8');
            } else {
                // Ne révèle pas d'informations détaillées sur l'erreur
                echo htmlspecialchars(ERROR_ADD_ACCOUNT, ENT_QUOTES, 'UTF-8');
            }
        }

        $stmt->close();
    }
}

function authenticate($conn)
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo htmlspecialchars(ERROR_MISSING_FIELDS, ENT_QUOTES, 'UTF-8');
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result_check = $stmt->get_result();

        if ($result_check->num_rows > 0) {
            $row = $result_check->fetch_assoc();
            $stored_password_hash = $row['password'];

            if (password_verify($password, $stored_password_hash)) {

                // Stocker des informations de l'utilisateur dans la session
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $username;

                echo htmlspecialchars(SUCCESS_AUTHENTICATION, ENT_QUOTES, 'UTF-8');
            } else {
                echo htmlspecialchars(ERROR_AUTHENTICATION, ENT_QUOTES, 'UTF-8');
            }
        } else {
            echo htmlspecialchars(ERROR_AUTHENTICATION, ENT_QUOTES, 'UTF-8');
        }

        $stmt->close();
    }
}

?>
