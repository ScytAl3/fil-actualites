<!-- php treatment -->
<?php
    // import pdo fonction sur la database
    require '../pdo/pdo_db_functions.php';
    // on demarre une session la session
    session_start();

    // si le formulaire a ete envoyer les variables existes
    if (isset($_POST['articleTitle'], $_POST['articleDescription'],$_POST['articleBody'], $_FILES['fileToUpload']))  {                
        // on creer un array de session avec les champs envoyes pour ne pas avoir a les ressaisir si il y a une erreur dans le formulaire
        $_SESSION['createNews']['inputArticleTitle'] = $_POST['articleTitle'];
        $_SESSION['createNews']['inputArticleDescription'] = $_POST['articleDescription'];
        $_SESSION['createNews']['inputArticleBody'] = $_POST['articleBody'];       
        // on recupere les informations saisies pour creer l article associe a l admin qui vient de le creer    
        $articleData = [
            $_SESSION['current_Id'],
            $_POST['articleTitle'], 
            $_POST['articleDescription'], 
            $_POST['articleBody']
        ];
        // ----------------------------------------------------------------------------------
        // on appelle la fonction qui verifie la validite de l image a uploader
        // ----------------------------------------------------------------------------------
        $pictureToTest = ValidateUpload($_FILES['fileToUpload']);
        // si la fonction retourne une erreur
        if (empty($pictureToTest) != true) {                
            // on renvoie l erreur
            $_SESSION['error']['show'] = true;
            $_SESSION['error']['message'] = $pictureToTest[0];
            // on redirige vers la page de creation article
            header('location:/../admin_page/admin_news_create.php');
            exit;
        } 
        // -----------------------------------------------------------------------------------------------------------
        // on appelle la fonction qui creer un article en passant toutes les informations necessaires
        // -----------------------------------------------------------------------------------------------------------
        $newArticle = createArticle($articleData);
        // si l enregistrement s est bien deroule
        if ($newArticle > 0) {  
            // ---------------------------------------------------------------------------------------------------------------
            // on appelle les fonctions pour l upload d image
            // ---------------------------------------------------------------------------------------------------------------
            $pictureUpload = UploadImage($_FILES['fileToUpload']);
            // on recupere les informations de la picture et de l identifiant de l article 
            $pictureData = [
                $newArticle,
                $pictureUpload
            ];            
            // ---------------------------------------------------------------------------------------------------------------
            // on appelle les fonctions pour creer l image associee a l article
            // ---------------------------------------------------------------------------------------------------------------
            $newPicture = createPicture($pictureData);
            if ($newPicture > 0) {
                // on renvoie un message de creation reussi
                $_SESSION['error']['page'] = 'adminNews';
                $_SESSION['error']['show'] = true;
                $_SESSION['error']['message'] = 'Nouvel article crée.';
                // on détruit les variables d erreurs liees au formulaire et celles des champs de saisie - deux tableaux
                unset ($_SESSION['createNews']);       
                // on redirige vers la page de la liste des news de l admin connecte
                header('location:/../admin_page/admin_news.php');       
                exit; 
            } else {
                // on renvoie un message d erreur de creation de l image dans la table
                $_SESSION['error']['show'] = true;
                $_SESSION['error']['message'] = "Il y a eu un problème lors de la creation de l'image !";
                // on redirige vers la page de creation article
                header('location:/../admin_page/admin_news_create.php');
                exit;
            }
        } else {
            // on renvoie un message d erreur de creation de l article dans la table
            $_SESSION['error']['show'] = true;
            $_SESSION['error']['message'] = "Il y a eu un problème lors de la creation de l'article !";
            // on redirige vers la page de creation article
            header('location:/../admin_page/admin_news_create.php');
            exit;
        }                 
    } else {
            // on renvoie un message d erreur de reception du formulaire
            $_SESSION['error']['show'] = true;
            $_SESSION['error']['message'] = "Il y a eu un problème lors de l'envoi de votre formulaire !";
            // on redirige vers la page de creation article
            header('location:/../admin_page/admin_news_create.php');
            exit;
    }
?>