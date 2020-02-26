<!-- php treatment -->
<?php
    // import pdo fonction sur la database
    require '../pdo/pdo_db_functions.php';

    // si le formulaire a ete envoye on verifie si les variables existes
    if (isset($_POST['pseudo']) && isset($_POST['password']))  {
        // on appelle la fonction qui verifie si un utilisateur avec ce pseudo existe
        $testUser = pseudoExiste($_POST['pseudo']);
        // si la fonction a retournee un utilisateur
        if ($testUser) { 
            // on appelle la fonction qui verifie le mot de passe saisi avec celui chiffre dans la base de donnees 
            $testPwd = validPassword($_POST['password'], $testUser);
            // si le mot de passe saisi est correct
            if ($testPwd) {
                // on demarre la session avec les informations de l'utilisateur qui vient de se connecter
                session_start ();
                // on enregistre les paramètres comme variables de session (pseudo et role) et identifiant si admin          
                $_SESSION['current_Pseudo'] = $testUser['userPseudo'];
                $_SESSION['current_Role'] = $testUser['userRole']; 
                // on enregiste le numero d identifiant si l utilisateur qui se connect est admin
                $_SESSION['current_Id'] = ($testUser['userRole'] == 'Admin') ? $testUser['userId'] : false;                
                // on creer une variable de session login en cours
                $_SESSION['current_Session'] = true;                
                // on détruit les variables d erreur de login de notre session
                unset ($_SESSION['showErrorLog'], $_SESSION['errorMsgLog']);
                // si l utilisateur a cocher la case "Remember me" on appelle la fonction qui creer le cookie
                if (!empty($_POST['rememberMe'])) {
                    $_SESSION['rememberMe'] = true;
                }       
                // on  redirige vers la page d accueil
                header('location: /../news_feed.php');  
                exit(); 
            } else {
                // on demarre notre session avec les messages d erreur (mot de passe non valide)
                session_start ();
                // on enregistre comme variables de session l erreur d identifiaction et on passe la variable pour afficher le message a true
                $_SESSION['showErrorLog'] = true;
                $_SESSION['errorMsgLog'] = "Erreur de connexion, veuillez vérifier vos identifiants de connexion";
                // on redirige vers la page index.php
                header('location:/../index.php');
                exit();
            }        
        // sinon pas trouve de correspondance email dans la base        
        } else {
            // on demarre notre session avec les messages d erreur (pseudo n existe pas dans la table)
            session_start ();
            // on enregistre comme variables de session l erreur d identifiaction et on passe la variable pour afficher le message a true
            $_SESSION['showErrorLog'] = true;
            $_SESSION['errorMsgLog'] = "Erreur de connexion, veuillez vérifier vos identifiants de connexion";
            // on redirige vers la page index.php avec les parametres pour afficher le message d erreur
            header('location:/../index.php');
            exit();
        }
    }
?>