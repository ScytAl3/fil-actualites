<!-- php treatment -->
<?php
    // import pdo fonction sur la database
    require '../pdo/pdo_db_functions.php';

    // si le formulaire a ete envoyer les variables existes
    if (isset($_POST['articleTitle'], $_POST['articleDescription'],$_POST['articleBody']))  {      
        // on demarre une session la session
        session_start();  
        // on recupere l identifiant de l'article qu on modifie (passe par l URL)
        $articleId = $_POST['articleId'];
        // on recupere les informations saisies pour creer l article associe a l admin qui vient de le creer    
        $articleData = [
            $_POST['articleTitle'], 
            $_POST['articleDescription'], 
            $_POST['articleBody'],
            $articleId 
        ];
       
        // -----------------------------------------------------------------------------------------------------------------
        // on appelle la fonction qui met a jour un article en passant toutes les informations necessaires
        // -----------------------------------------------------------------------------------------------------------------
        $newArticle = updateArticle($articleData);  

        // si il on a un nom de fichier image
        if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['name'] != '') {      
            // ---------------------------------------------------------------------------------------------------------------
            // on appelle les fonctions pour l upload d image
            // ---------------------------------------------------------------------------------------------------------------
            $pictureUpload = UploadImage($_FILES['fileToUpload']);
            // si la fonction ne revoie aucune erreur = photo deplacee dans le dossier img/        
            if (empty($pictureUpload) == true) {
                $pictureName = $_FILES['fileToUpload']['name'];
                // on recupere les informations saisies pour creer l article associe a l admin qui vient de le creer    
                $pictureData = [
                    $pictureName,
                    $articleId
                ];            
                // ----------------------------------------------------------------
                // on appelle la fonctions pour mettre a jour l image 
                // ----------------------------------------------------------------
                $newPicture = updatePicture($pictureData);
            } else {
                // on demarre une session avec les parametres de gestion d erreur de  l upload de l image
                $_SESSION['showErrorCreate'] = true;
                $_SESSION['errorMsgCreate'] = $userUpload[0];
                // on redirige vers la page de creation article avec les parametres pour afficher le message d erreur
                header('location:/../admin_page/admin_news_edit.php');
                exit;
            }     
        }           
        // on détruit les variables d erreurs liees au formulaire et celles des champs de saisie
        unset ($_SESSION['showErrorUpdate'], $_SESSION['errorMsgUpdate'],);       
        // on redirige vers la page de la liste des messagerie
        header('location:/../admin_page/admin_news.php');       
        exit;
    } else {
        // on demarre une session avec les parametres de gestion d erreur de reception du formulaire
        $_SESSION['showErrorUpdate'] = true;
        $_SESSION['errorMsgUpdate'] = "Il y a eu un problème lors de l'envoi de votre formulaire !";
        // on redirige vers la page de creation article avec les parametres pour afficher le message d erreur
        header('location:/../admin_page/admin_news_edit.php');
        exit;
    }
?>