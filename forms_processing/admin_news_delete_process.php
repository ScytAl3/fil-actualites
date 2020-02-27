<!-- php treatment -->
<?php
    // import pdo fonction sur la database
    require '../pdo/pdo_db_functions.php';

    // si les variables existes
    if (isset($_POST['articleId'])) {
        // on recupere l identifiant de l article
        $articleId = $_POST['articleId'];
        // on recupere la liste des pictureFilename associes a l article pour pouvoir les supprimer du dossier /img/news_feeds_pictures
        $pictureArrays = pictureNameReader($articleId);
        //
        //var_dump($pictureArrays); die;
        //
        // on appelle la fonction qui supprime la/les images associees a l article (foreign key)
        $deletePicture = deletePicture($articleId);
        // on supprime la/les images de l article du dossier /img/news_feeds_pictures
        foreach($pictureArrays as $picture){
            $target_dir = "../img/news_feeds_pictures/";
            $fileName = $picture['pictureFilename']; 
            // si c est une image par defaut pictureFilename = NULL
            if ($picture['pictureFilename'] !='') {
                unlink($target_dir.$fileName);
            }
        }
        // on appelle la fonction qui supprime l article
        $deleteArticle = deleteArticle($articleId);
    } else {
        // on demarre une session avec les parametres de gestion d erreur de de suppression
        $_SESSION['showErrorAction'] = true;
        $_SESSION['errorMsgAction'] = "Il y a eu un problème avec l'identifiant de l'article !";
        // on redirige vers la page des articles avec les parametres pour afficher le message d erreur
        header('location:/../admin_page/admin_news.php'); 
        exit;
    }
    // on renvoie les messages de la procedure de suppression
    session_start ();
    $_SESSION['showErrorAction'] = true;
    $_SESSION['errorMsgAction'] = $deletePicture." - ".$deleteArticle;
    // on redirige vers la page d administration des articles avec les parametres pour afficher le message
    header('location:/../admin_page/admin_news.php'); 

?>