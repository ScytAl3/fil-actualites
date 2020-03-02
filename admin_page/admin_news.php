<?php
// on démarre la session
session_start ();
// verification que l utilisateur ne passe pas par l URL
if (isset($_SESSION['current_Session']) && $_SESSION['current_Role'] !='Admin') {
    header('location: ../../index.php');
}
// import du script pdo des fonctions sur la database
require '../pdo/pdo_db_functions.php';
// ----------------------------//---------------------------
//                  variables de session
// ---------------------------------------------------------
// ----------------------//------------------------
//      messages d erreur admin news list
//$_SESSION['error']['page'] = (isset($_SESSION['error']['page'])) ? $_SESSION['error']['page'] : 'adminNews';
$_SESSION['error']['show'] = ($_SESSION['error']['page'] != 'adminNews') ? false : $_SESSION['error']['show'];
$_SESSION['error']['message'] =  ($_SESSION['error']['page'] != 'adminNews') ? '' : $_SESSION['error']['message'];
$_SESSION['error']['page'] = 'adminNews';
//     messages d erreur admin news list
// ----------------------//------------------------
// on détruit les variables inutiles des autres pages
unset($_SESSION['createNews'], $_SESSION['updateNews']);
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
		<title>Newsfeed - Admin : news feed list</title>
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
		<link href="/css/admin_news.css" rel="stylesheet" type="text/css">
        <!-- includes stylesheet -->
        <link href="/css/header.css" rel="stylesheet" type="text/css">
    </head>    
    
	<body>   
        <!-- import du header -->
        <?php include '../includes/header.php'; ?>
        <!-- /import du header -->
        <!------------------------------------------------------//------------------------------------------------------
                            debut du container pour afficher les actualités postees par l admin connecte
        --------------------------------------------------------------------------------------------------------------->           
        <div class="mt-5 container">            
            <!-- message a l attention de l administrateur connecte -->
            <div class="my-3 w-100">                                                                       
                <div class="mx-auto px-3 py-2 text-center info-message-bg">
                    <h2 class="card-title">Gestion des actualités postées par l'adminstrateur <strong><em><?=$_SESSION['current_Pseudo'] ?></em></strong></h2>
                </div>
            </div> 
            <!-- /message a l attention de l administrateur connecte --> 
            <hr>
            <!-- area pour afficher un message d erreur lors de la creation -->
            <div class="show-bg <?=($_SESSION['error']['show']) ? '' : 'visible'; ?> text-center mt-5">
                <p class="lead mt-2"><span><?=$_SESSION['error']['message'] ?></span></p>
            </div>
            <!-- /area pour afficher un message d erreur lors de la creation -->
           
           <!-- bouton qui dirige vers le formulaire admin pour creer une actualite -->
            <div class="my-5 mx-auto">
                <a class="btn btn-success btn-lg btn-block" href="/admin_page/admin_news_create.php">- Ajouter une actualité -</a>
            </div> 
            <!-- /bouton qui dirige vers le formulaire admin pour creer une actualite -->
            <!---------------------------------//-----------------------------------------
                    debut script php pour recuperer toutes les actualites
            ------------------------------------------------------------------------------>
                <?php   
                    // on appelle la fonction qui retourne toutes les actualites
                    $myNewsFeed = adminNewsReader($_SESSION['current_Id']);   
                    //
                    //var_dump($myNewsFeed); die;
                    //
                    // si la requete retourne un objet
                    if ($myNewsFeed) {
                    // boucle pour afficher les differentes news
                    foreach ($myNewsFeed as $myNews => $column) {
                ?>
                <!-- on recupere les informations pour chaque actualite -->                     
                    <div class="card bg-light border-success mb-3 w-100">
                        <div class="card-body">  
                            <div class="row">                                   
                                <div class="col-8 ml-4 align-self-center">
                                    <h2 class="card-title"><?=$myNewsFeed[$myNews]['title'] ?></h2>
                                    <h4 class="card-text text-truncate"><?=$myNewsFeed[$myNews]['resum'] ?></h4>
                                </div>
                                <!-- boutons pour modifier ou supprimer une actualite --> 
                                <div class="d-flex ml-auto align-self-center">
                                    <a class="btn btn-primary" href="admin_news_edit.php?articleId=<?=$myNewsFeed[$myNews]['articleId'] ?>">Edit</a>
                                    <form method="post" action="/forms_processing/admin_news_delete_process.php" onsubmit=" return confirm('are you really sure')">
                                        <!-- on associe l article et la value pour les passer en hidden en POST -->
                                        <input name="articleId" type="hidden" value="<?=$myNewsFeed[$myNews]['articleId'] ?>">
                                        <button class="btn btn-danger ml-1">Delete</button>
                                    </form>
                                </div>
                                <!-- /boutons pour modifier ou supprimer une actualite --> 
                            </div>                            
                        </div>
                    </div>                 
                <!-- /on recupere les informations pour chaque actualite -->    
                <?php
                } 
                // si la requete ne retourne rien
                } else {
                ?>
                <!-- affiche un message pour dire qu il n y a pas encore d actualite  -->
                <div class="my-3 w-100">                                                                       
                    <div class="mx-auto px-3 py-2 text-center info-message-bg">
                        <h2 class="card-title">Vous n'avez posté aucune actualité  !</h2>
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
         <!--------------------------------------------------------------------------------------------------------------
                            debut du container pour afficher les actualités postees par l admin connecte
        ---------------------------------------------------------//------------------------------------------------------>   
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
