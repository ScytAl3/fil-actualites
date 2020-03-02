<!-- php treatment -->
<?php
    // import pdo fonction sur la database
    require '../pdo/pdo_db_functions.php'; 
    // on demarre une session
    session_start();

    // si le formulaire a ete envoyer les variables existes
    if (isset($_POST['articleTitle'], $_POST['articleDescription'],$_POST['articleBody'], $_FILES['fileToUpload'], $_FILES['fileToUpload']))  { 
        // on creer un array de session avec les champs envoyes pour ne pas avoir a les ressaisir si il y a une erreur dans le formulaire
        $_SESSION['updateNews']['inputArticleTitle'] = $_POST['articleTitle'];
        $_SESSION['updateNews']['inputArticleDescription'] = $_POST['articleDescription'];
        $_SESSION['updateNews']['inputArticleBody'] = $_POST['articleBody'];
        // avec l identifiant de l'article qu on modifie (qui a ete passe en GET par l URL lors du choix dans la page admin_news.php)
        $_SESSION['updateNews']['inputArticleId'] = $_POST['articleId'];       
        // on recupere l identifiant de l'article 
        $articleId = $_POST['articleId'];
        // on recupere les informations saisies pour creer l article associe a l admin connecte
        $articleData = [
            $_POST['articleTitle'], 
            $_POST['articleDescription'], 
            $_POST['articleBody'],
            $articleId 
        ];
        // -------------------------------------------------------------------------------------------------------------------
        // on verifie s il y a un nom de fichier pour remplacer l image par defaut par un nouvelle image
        // -------------------------------------------------------------------------------------------------------------------
        if ($_FILES['fileToUpload']['name'] != '') {      
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
                header('location:/../admin_page/admin_news_edit.php');
                exit;
            } 
            // ---------------------------------------------------------------------------------------------------------------
            // on appelle la fonction pour l upload d image
            // ---------------------------------------------------------------------------------------------------------------
            $pictureUpload = UploadImage($_FILES['fileToUpload']);
            // on recupere les informations de la picture et de l identifiant de l article 
            $pictureData = [
                $pictureUpload,
                $articleId
            ];        
            // ----------------------------------------------------------------
            // on appelle la fonctions pour mettre a jour l image 
            // ----------------------------------------------------------------
            $updatedPicture = updatePicture($pictureData);
        }
        // -----------------------------------------------------------------------------------------------------------------
        // on appelle la fonction qui met a jour un article en passant toutes les informations necessaires
        // -----------------------------------------------------------------------------------------------------------------
        $updatedArticle = updateArticle($articleData);
        // on renvoie un message de creation reussi
        $_SESSION['error']['page'] = 'adminNews';
        $_SESSION['error']['show'] = true;
        $_SESSION['error']['message'] = $updatedPicture.' - '.$updatedArticle;
        // on détruit les variables d erreurs liees au formulaire et celles des champs de saisie
        unset ($_SESSION['updateNews']);       
        // on redirige vers la page de la liste des messagerie
        header('location:/../admin_page/admin_news.php');       
        exit;
    } else {
        //  on renvoie une message d erreur de reception du formulaire
        $_SESSION['error']['show'] = true;
        $_SESSION['error']['message'] = "Il y a eu un problème lors de l'envoi de votre formulaire !";
        // on redirige vers la page de creation article
        header('location:/../admin_page/admin_news_edit.php');
        exit;
    }
?>