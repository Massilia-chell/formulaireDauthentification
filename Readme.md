## Informations Techniques

Ce projet consiste en un formulaire d'authentification écrit en PHP, utilisant une base de données MySQL pour stocker les informations des utilisateurs. Le code repose sur Bootstrap pour le style, jQuery pour les interactions côté client, et PHP pour la logique serveur.

### Fonctionnalités Principales

1. **Authentification des Utilisateurs :**
   - Le formulaire permet aux utilisateurs de saisir leur identifiant et leur mot de passe.
   - Les données sont vérifiées par rapport à la base de données MySQL.

2. **Inscription de Nouveaux Comptes :**
   - Si les informations saisies ne correspondent à aucun compte existant, l'utilisateur a la possibilité de s'inscrire.
   - L'inscription nécessite la saisie d'un identifiant et d'un mot de passe forts.

3. **Boutons Fonctionnels :**
   - **Bouton "Reset" :** Réinitialise les champs du formulaire.
   - **Bouton "Valider" :** Vérifie les informations saisies, démarre une session si correctes.
   - **Bouton "Ajouter un Compte" :** Permet d'ajouter un nouveau compte à la base de données.

4. **Sécurité et Protection :**

        1. **Protection contre les Attaques CSRF:**
        - Un jeton CSRF est généré et associé à chaque session utilisateur.
        - Ce jeton est inclus dans le formulaire et vérifié lors du traitement pour prévenir les attaques CSRF.

        2. **Protection contre les Attaques par Force Brute:**
        - Les mots de passe doivent avoir au moins 8 caractères, inclure des majuscules, des minuscules, des chiffres et des caractères spéciaux.

        3. **Protection contre les Injections SQL:**
        - Toutes les requêtes SQL utilisent des requêtes préparées pour éviter les injections SQL.
        - Les paramètres sont liés de manière sécurisée avec `bind_param` dans les requêtes préparées.

        4. **Protection contre les Doublons d'Identifiants:**
        - Avant d'ajouter un nouveau compte, le système vérifie si l'identifiant existe déjà dans la base de données.

        5. **Stockage Sécurisé des Mots de Passe:**
        - Les mots de passe sont stockés de manière sécurisée en utilisant la fonction `password_hash` de PHP.

        6. **Expiration du Token de Session:**
        - Le jeton de session expire après 1 heure pour renforcer la sécurité.

### Structure du Projet

- **`code.php`:** Page principale de connexion.
  - Génère un jeton CSRF pour chaque session utilisateur.
  - Inclut un formulaire de connexion avec des champs pour l'identifiant et le mot de passe.
  - Boutons pour réinitialiser le formulaire, valider la connexion, et ajouter un compte.

- **`process.php`:** Gère les requêtes POST du formulaire.
  - Vérifie la présence du jeton CSRF pour prévenir les attaques CSRF.
  - Effectue des actions en fonction des requêtes, telles que l'ajout d'un compte ou l'authentification.

### Dépendances Externes

- **Bootstrap (4.5.2):** Utilisé pour le style du formulaire.
- **jQuery (3.6.4):** Utilisé pour les interactions côté client.

### Configuration

1. **Base de Données (`process.php`):**
   - Configurez les paramètres de la base de données (hôte, utilisateur, mot de passe, nom de la base de données) dans le fichier `process.php`.

2. **Création de la table users dans la base de données:**
   - CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);
