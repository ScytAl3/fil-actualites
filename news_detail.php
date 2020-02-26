<?php
// on démarre la session
session_start ();
// verification que l utilisateur ne passe pas par l URL
if (isset($_SESSION['current_Session']) && $_SESSION['current_Session'] == false) {
    header('location:index.php');
}
// import du script pdo des fonctions sur la database
require 'pdo/pdo_db_functions.php';
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
		<title>Newsfeed - News detail</title>
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
		<link href="css/news_detail.css" rel="stylesheet" type="text/css">
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
            <!-- bouton pour retourner a la liste des actualites -->
            <div class="my-5 mx-auto">
                <a class="btn back-button-bg direction-arrow btn-lg btn-block" href="news_feed.php"><i class="fa fa-chevron-left fa-lg"></i> Retour aux actualités</a>
            </div> 
            <!-- /bouton pour retourner a la liste des actualites -->

            <!---------------------------------//-----------------------------------------
                    debut script php pour recuperer toutes les actualites
            ------------------------------------------------------------------------------>
            <?php   
                //--------------------------------------------------------------------
                // on appelle la fonction qui retourne toutes les actualites
                //--------------------------------------------------------------------
                $newsDetail = newsReader($_GET['newsId']);   
                //
                //var_dump($newsDetail); die;
                //
                // si la requete retourne un objet on recupere les informations de l actualite
                if ($newsDetail) {
                    $newsTitle = $newsDetail['title'] ;
                    $newsBody = $newsDetail['body'] ;
                    $newsPseudo = $newsDetail['pseudo'] ;
                    // on recupere la date renvoyee par MySQL
                    $dateTime = date_create($newsDetail['create_at']);
                    // on appelle la fonction qui la transfome au format choisi
                    $newsCreateDate = formatedDateTime($dateTime);                    
            ?>
                    <!-- on recupere les valeurs des differents champs d une ligne -->             
                    <div class="card border-primary mb-5">
                        <!-- carousel pour afficher les differentes photos d une actualite -->                        
                        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                <?php 
                                    //------------------------------------------------------------------------------------
                                    // on appelle la fonction qui retourne toutes les phtotos de l actualite
                                    //-----------------------------------------------------------------------------------
                                    $newsPictures =  newsPictureReader($_GET['newsId']);
                                    //
                                    //var_dump($newsPictures); die;
                                    //
                                    foreach ($newsPictures as $slide => $filename) {
                                ?>
                                <div class="carousel-item <?=(!$slide) ? 'active' : '' ?>">
                                    <img class="d-block w-100" src="/img/news_feeds_pictures/<?=$newsPictures[$slide]['picture'] ?>" alt="slide">
                                </div>    
                                <?php
                                }
                                ?>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </a>
                        </div>
                        <!-- /carousel pour afficher les differentes photos d une actualite -->
                        
                        <!-- titre de l actualite -->
                        <div class="card-header text-white bg-secondary">
                            <h2><?=$newsTitle?></h2>
                        </div>
                        <!-- /titre de l actualite -->
                        <!-- date et corps de l actualite -->
                        <div class="card-body">
                            <h5 class="card-title">Actualité publiée le <?=$newsCreateDate?></h5>
                            <p class="card-text"><?=$newsBody?></p>
                        </div>
                        <!-- /date et corps de l actualite -->
                        <!-- information sur le createur de l actualite --> 
                            <div class="card-footer ml-auto">Publié par <strong><em><?=$newsPseudo?></em></strong></div>
                        <!-- /information sur le createur de l actualite --> 
                    </div> 
            <?php
                // si la requete ne retourne rien
                } else {
            ?>
            <!-- affiche un message pour dire qu il n y a pas encore d actualite  -->
            <div class="my-3 w-100">                                                                       
                <div class="mx-auto px-3 py-2 text-center info-message-bg">
                    <h2 class="card-title">Il n'y a aucune information concernant cette actualité !</h2>
                </div>
            </div> 
            <!-- /affiche un message pour dire qu il n y a pas encore d actualite -->
            <?php
            }
            ?>            
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
