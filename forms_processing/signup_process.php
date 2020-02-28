<!-- php treatment -->
<?php
    // import pdo fonction sur la database
    require '../pdo/pdo_db_functions.php';
    // on demarre une session la session
    session_start(); 

    // si le formulaire a ete envoyer les variables existes
    if (isset( $_POST['lastName'], $_POST['firstName'],$_POST['pseudo'], $_POST['email'], $_POST['password']))  {      
        // on creer un array de session avec les champs envoyes pour ne pas avoir a les ressaisir si il y a une erreur dans le formulaire
        $_SESSION['signupForm']['inputLastName'] = $_POST['lastName'];
        $_SESSION['signupForm']['inputFirstName'] = $_POST['firstName'];
        $_SESSION['signupForm']['inputPseudo'] = $_POST['pseudo'];
        $_SESSION['signupForm']['inputMail'] = $_POST['email']; 
        // -------------------------------------------------------------------------------------------------
        // on appelle la fonction qui verifie l existence d un utilisateur - condition pseudo
        // -------------------------------------------------------------------------------------------------
        $wherePseudo = 'userPseudo';
        $pseudoTest = userExist($wherePseudo, $_POST['pseudo']); 
        //
        //var_dump($pseudoTest); die;
        //
        //------------------------------------------------------------
        // si on a trouver une correspondance dans la base
        //------------------------------------------------------------
        if ($pseudoTest) { 
            // on renvoie une message d erreur sur le pseudo
            $_SESSION['error']['show'] = true;
            $_SESSION['error']['message'] = "Ce pseudo est déjà utilisé !";                                  
            // on redirige vers la page du formulaire d inscription
            header('location:/../sign_up.php');
            exit;
        }
        // -----------------------------------------------------------------------------------------------
        // on appelle la fonction qui verifie l existence d un utilisateur - condition email
        // -----------------------------------------------------------------------------------------------
        $whereEmail = 'userEmail';
        $emailTest = userExist($whereEmail, $_POST['email']);
        //
        //var_dump($emailTest); die;
        //
        //------------------------------------------------------------
        // si on a trouver une correspondance dans la base
        //------------------------------------------------------------
        if ($emailTest) { 
            // on renvoie une message d erreur sur l email
            $_SESSION['error']['show'] = true;
            $_SESSION['error']['message'] = "Cette email est déjà utilisée !";
            // on redirige vers la page du formulaire d inscription
            header('location:/../sign_up.php');
            exit;
        }
        // ------------------------------------------------------------------------------------
        // si pseudo et email unique
        // on appelle la fonction qui creer le Salt et chiffre le mot de passe
        // ------------------------------------------------------------------------------------  
        // creation du Salt associe a l utilisateur
        $userSalt = generateSalt(10);
        // creation du mot de passe associe a l utilisateur avec son Salt
        $userEncryptPwd = CreateEncryptedPassword($userSalt, $_POST['password']);
        // on recupere les informations saisies et le mot de passe chiffre dans un tableau       
        $userData = [             
            $_POST['lastName'],
            $_POST['firstName'], 
            $_POST['pseudo'],
            $_POST['email'], 
            $userEncryptPwd,
            $userSalt,
        ];
        // --------------------------------------------------------
        // on appelle la fonction qui creer l utilisateur
        // --------------------------------------------------------
        $newUser = createUser($userData);
        // si l enregistrement s est bien deroule
        if ($newUser > 0) {
            // on creer une variable de session login
            $_SESSION['current_Session'] = true; 
            // on passe en parametre le nouvel pseudo cree
            $_SESSION['current_Pseudo'] = $_POST['pseudo'];            
            // si l utilisateur a cocher la case "Remember me" on passe la variable de session qui permet d appeler la fonction qui creera le cookie sur la page "news_feed.php" a "true"
            if (!empty($_POST['rememberMe'])) {
                $_SESSION['rememberMe'] = true;
            }
            // on détruit les variables d erreurs liees au formulaire et celles des champs de saisie - deux tableaux
            unset ($_SESSION['error'], $_SESSION['signupForm']);
            // on redirige vers la page de la liste des news
            header('location:/../news_feed.php');       
            exit;        
        } else {
            // on renvoie un message d erreur de gestion d erreur email
            $_SESSION['error']['show'] = true;
            $_SESSION['error']['message'] = "Problème lors de la création de votre compte !";
            // on redirige vers la page index.php avec les parametres pour afficher le message d erreur
            header('location:/../sign_up.php');
            exit;
        }            
    } else {
        // on renvoie un message d erreur de reception du formulaire
        $_SESSION['error']['show'] = true;
        $_SESSION['error']['message'] = "Il y a eu un problème lors de l'envoi de votre formulaire !";
        // on redirige vers la page signup
        header('location:/../sign_up.php');
        exit;
    }
?>