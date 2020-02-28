<!-- php treatment -->
<?php
    // import pdo fonction sur la database
    require '../pdo/pdo_db_functions.php';
    // on demarre notre session 
    session_start ();

    // si le formulaire a ete envoye on verifie si les variables existes
    if (isset($_POST['pseudo']) && isset($_POST['password']))  {
        // on appelle la fonction qui verifie si un utilisateur avec ce pseudo existe
        $wherePseudo = 'userPseudo';
        $pseudoValid = userExist($wherePseudo, $_POST['pseudo']);
        //
        //var_dump($pseudoValid); die;
        //
        // si la fonction a retournee un utilisateur
        if ($pseudoValid) { 
            // on appelle la fonction qui verifie le mot de passe saisi avec celui chiffre dans la base de donnees 
            $testPwd = validPassword($_POST['password'], $pseudoValid);
            //
            //var_dump($testPwd); die;
            //
            // si le mot de passe saisi est correct
            if ($testPwd) {
                // on enregistre les paramètres comme variables de session (pseudo et role) et identifiant si admin          
                $_SESSION['current_Pseudo'] = $pseudoValid['userPseudo'];
                $_SESSION['current_Role'] = $pseudoValid['userRole']; 
                // on enregiste le numero d identifiant si l utilisateur qui se connect est admin
                $_SESSION['current_Id'] = ($pseudoValid['userRole'] == 'Admin') ? $pseudoValid['userId'] : false;                
                // on creer une variable de session login en cours
                $_SESSION['current_Session'] = true;                
                // on détruit les variables d erreur de login de notre session
                unset ($_SESSION['error']);
                // si l utilisateur a cocher la case "Remember me" on appelle la fonction qui creer le cookie
                if (!empty($_POST['rememberMe'])) {
                    $_SESSION['rememberMe'] = true;
                }       
                // on  redirige vers la page d accueil
                header('location: /../news_feed.php');  
                exit(); 
            } else {
                // on renvoie un message d erreur (mot de passe non valide)
                $_SESSION['error']['page'] = 'login';
                $_SESSION['error']['show'] = true;
                $_SESSION['error']['message'] = "Erreur de connexion, veuillez vérifier vos identifiants de connexion";
                // on redirige vers la page index.php
                header('location:/../index.php');
                exit();
            }        
        // sinon pas trouve de correspondance email dans la base        
        } else {            
            // on renvoie un message d erreur (pseudo n existe pas dans la table)
            $_SESSION['error']['page'] = 'login';
            $_SESSION['error']['show'] = true;
            $_SESSION['error']['message'] = "Erreur de connexion, veuillez vérifier vos identifiants de connexion";
            // on redirige vers la page index.php
            header('location:/../index.php');
            exit();
        }
    }
?>