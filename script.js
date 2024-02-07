function resetForm() {
    document.getElementById("loginForm").reset();
    document.getElementById("message").innerHTML = "";
}

function submitForm() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    // Effectuer une requête AJAX vers le backend (process.php) pour vérifier dans la base de données
    $.ajax({
        url: 'process.php',
        type: 'POST',
        data: { action: 'authenticate', username: username, password: password },
        success: function(response) {
            document.getElementById("message").innerHTML = response;
        },
        error: function(error) {
            console.error(error);
        }
    });
}

function addAccount() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    $.ajax({
        url: 'process.php',
        type: 'POST',
        data: { action: 'addAccount', username: username, password: password },
        success: function(response) {
            document.getElementById("message").innerHTML = response;
        },
        error: function(error) {
            console.error(error);
        }
    });
}

