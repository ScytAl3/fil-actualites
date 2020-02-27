<?php
// on démarre la session
session_start ();
// verification que l utilisateur ne passe pas par l URL
if (isset($_SESSION['current_Session']) && $_SESSION['current_Session'] == false) {
    header('location:index.php');
}
// import du script pdo des fonctions sur la database
require 'pdo/pdo_db_functions.php';
// on place le cookie si l utilisateur a coche "remeber me" au login ou a l inscription
if ($_SESSION['rememberMe']) {
    setUserCookie($_SESSION['current_Pseudo']);
}
// ----------------------------//---------------------------
//                  variables de session
// ---------------------------------------------------------
// login en cours
$currentSession = $_SESSION['current_Session'];
// role de l utilisateur connecte
$currentRole = $_SESSION['current_Role'];
// recuperation de l identifiant de l utilisateur connecte
$currentId = $_SESSION['current_Id'];
// pseudo de l utilisateur connecte
$curentPseudo = $_SESSION['current_Pseudo'];
// on détruit les variables d erreur de login de notre session
unset ($_SESSION['showErrorSignup'], $_SESSION['errorMsgSignUp']);
// on détruit les variables d erreur d action de la page admin_news.php
unset ($_SESSION['showErrorAction'], $_SESSION['errorMsgAction']);
unset ($_SESSION['showErrorCreate'], $_SESSION['errorMsgCreate']);
// ----------------------------------------------------------
//                  variables de session
// ----------------------------//-----------------------------
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<!-- default Meta -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Newsfeed - The news feed</title>
		<meta name="author" content="Franck Jakubowski">
		<meta name="description" content="Un mini site d'actualités consultable par tous les membres mais ou seul un admin est autorisé à poster.">
		<!-- 
            favicons
         -->
		<!-- bootstrap stylesheet -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
            integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <!-- font awesome stylesheet -->
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
		<!-- default stylesheet -->
		<link href="css/news_feed.css" rel="stylesheet" type="text/css">
        <!-- includes stylesheet -->
        <link href="css/header.css" rel="stylesheet" type="text/css">
    </head>    
    
	<body>   
        <!-- import du header -->
        <?php include 'includes/header.php'; ?>
        <!-- /import du header -->
        <!--------------------------------------//------------------------------------------------
                            debut du container pour afficher le newsfeed
        ------------------------------------------------------------------------------------------>           
        <div class="mt-5 container">            
            <!-- bouton qui dirige vers le formulaire admin pour gerer ses actualites -->
            <div class="my-5 mx-auto <?=($_SESSION['current_Role'] == 'Admin') ? 'visible ' : 'invisible ' ?>">
                <a class="btn btn-danger btn-lg btn-block" href="/admin_page/admin_news.php">- Administrer vos actualités -</a>
            </div> 
            <!-- /bouton qui dirige vers le formulaire admin pour gere ses actualites -->       
           
            <!---------------------------------//-----------------------------------------
                    debut script php pour recuperer toutes les actualites
            ------------------------------------------------------------------------------>
            <div class="row justify-content-around">
                <?php   
                    // on appelle la fonction qui retourne toutes les actualites
                    $newsFeedList = allFeedReader();   
                    //
                    //var_dump($newsFeedList); die;
                    //
                    // si la requete retourne un objet
                    if ($newsFeedList) {
                    // boucle pour afficher les differentes news
                    foreach ($newsFeedList as $myNews => $column) {
                        // on verifie s il y a une image sinon on affiche celle par defaut
                        $newPicture = (($newsFeedList[$myNews]['picture']) == '') ? 'empty_picture.jpg' : $newsFeedList[$myNews]['picture'];
                ?>
                <!-- on recupere les valeurs des differents champs d une ligne -->             
                <div class="col-sm-5 card border-primary mb-5 py-3">
                    <!-- photo de l actualite -->
                    <img class="card-img-top news-picture mx-auto" src="/img/news_feeds_pictures/<?=$newPicture ?>" alt="first news picture">
                    <!-- /photo de l actualite -->
                    <!-- titre de l actualite et resume -->
                    <div class="card-body">
                        <h2 class="card-title"><strong><?=$newsFeedList[$myNews]['title']; ?></strong></h2>
                        <h4 class="card-text text-truncate"><?=$newsFeedList[$myNews]['resum']; ?></h4>
                    </div>
                    <!-- /titre de l actualite et resume -->
                    <!-- bouton pour afficher les details de l actualite --> 
                        <a class ="btn btn-primary visitedNews"href="news_detail.php?newsId=<?=$newsFeedList[$myNews]['aticleId']; ?>">Voir</a>
                    <!-- /bouton pour afficher les details de l actualite --> 
                </div>
                <?php
                } 
                // si la requete ne retourne rien
                } else {
                ?>
                <!-- affiche un message pour dire qu il n y a pas encore d actualite  -->
                <div class="my-3 w-100">                                                                       
                    <div class="mx-auto px-3 py-2 text-center info-message-bg">
                        <h2 class="card-title">Il n'y a aucune actualité pour l'instant !</h2>
                    </div>
                </div> 
                <!-- /affiche un message pour dire qu il n y a pas encore d actualite -->
                <?php
                }
                ?>            
            </div>
            <!----------------------------------------------------------------------------
                    /debut script php pour recuperer toutes les actualites
            -----------------------------------//----------------------------------------->            
        </div>        
         <!----------------------------------------------------------------------------------------
                            /debut du container pour afficher le newsfeed
        -----------------------------------------//------------------------------------------------->   
<!------------------------------------------>
    <?=var_dump($_SESSION) ?>
<!------------------------------------------>
        <!-- import scripts -->
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
			integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
			crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
			integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
			crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
			integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
			crossorigin="anonymous"></script>
		<!-- /import scripts -->
	</body>
</html>
