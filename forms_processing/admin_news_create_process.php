<!-- php treatment -->
<?php
    // import pdo fonction sur la database
    require '../pdo/pdo_db_functions.php';

    // si le formulaire a ete envoyer les variables existes
    if (isset($_POST['articleTitle'], $_POST['articleDescription'],$_POST['articleBody']))  {      
        // on demarre une session la session
        session_start();  
        // avec en parametres les informations envoyees pour ne pas devoir tout ressaisir
        $_SESSION['inputArticleTitle'] = $_POST['articleTitle'];
        $_SESSION['inputArticleDescription'] = $_POST['articleDescription'];
        $_SESSION['inputArticleBody'] = $_POST['articleBody'];       
        // on recupere les informations saisies pour creer l article associe a l admin qui vient de le creer    
        $articleData = [
            $_SESSION['current_Id'],
            $_POST['articleTitle'], 
            $_POST['articleDescription'], 
            $_POST['articleBody']
        ];
       
        // -----------------------------------------------------------------------------------------------------------
        // on appelle la fonction qui creer un article en passant toutes les informations necessaires
        // -----------------------------------------------------------------------------------------------------------
        $newArticle = createArticle($articleData);
        // si l enregistrement s est bien deroule
        if ($newArticle > 0) {            
            // si il on a un nom de fichier image
             if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['name'] != '') {      
                // ---------------------------------------------------------------------------------------------------------------
                // on appelle les fonctions pour l upload d image
                // ---------------------------------------------------------------------------------------------------------------
                $pictureUpload = UploadImage($_FILES['fileToUpload']);
                // si la fonction ne revoie aucune erreur = photo deplacee dans le dossier img/        
                if (empty($pictureUpload) == true) {
                    $pictureName = $_FILES['fileToUpload']['name'];
                } else {
                    // on demarre une session avec les parametres de gestion d erreur de  l upload de l image
                    $_SESSION['showErrorCreate'] = true;
                    $_SESSION['errorMsgCreate'] = $userUpload[0];
                    // on redirige vers la page de creation article avec les parametres pour afficher le message d erreur
                    header('location:/../admin_page/admin_news_create.php');
                    exit;
                }     
            } else { 
                $pictureName = null;
            }
            // on recupere les informations saisies pour creer l article associe a l admin qui vient de le creer    
            $pictureData = [
                $newArticle,
                $pictureName
            ];            
            // ---------------------------------------------------------------------------------------------------------------
            // on appelle les fonctions pour creer l image associee a l article
            // ---------------------------------------------------------------------------------------------------------------
            $newPicture = createPicture($pictureData);
            if ($newPicture > 0) {
                // on détruit les variables d erreurs liees au formulaire et celles des champs de saisie
                unset ($_SESSION['showErrorCreate'], $_SESSION['errorMsgCreate'], $_SESSION['inputArticleTitle'], $_SESSION['inputArticleDescription'], $_SESSION['inputArticleBody']);       
                // on redirige vers la page de la liste des messagerie
                header('location:/../admin_page/admin_news.php');       
                exit; 
            } else {
                 // on demarre une session avec les parametres de gestion d erreur de creation de l image
                $_SESSION['showErrorCreate'] = true;
                $_SESSION['errorMsgCreate'] = "Il y a eu un problème lors de la creation de l'image !";
                // on redirige vers la page de creation article avec les parametres pour afficher le message d erreur
                header('location:/../admin_page/admin_news_create.php');
                exit;
            }
        } else {
            // on demarre une session avec les parametres de gestion d erreur de creation article
            $_SESSION['showErrorCreate'] = true;
            $_SESSION['errorMsgCreate'] = "Il y a eu un problème lors de la creation de l'article !";
            // on redirige vers la page de creation article avec les parametres pour afficher le message d erreur
            header('location:/../admin_page/admin_news_create.php');
            exit;
        }                 
    } else {
            // on demarre une session avec les parametres de gestion d erreur de reception du formulaire
            $_SESSION['showErrorCreate'] = true;
            $_SESSION['errorMsgCreate'] = "Il y a eu un problème lors de l'envoi de votre formulaire !";
            // on redirige vers la page de creation article avec les parametres pour afficher le message d erreur
            header('location:/../admin_page/admin_news_create.php');
            exit;
    }
?>