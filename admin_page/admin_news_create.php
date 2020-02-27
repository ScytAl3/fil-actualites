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
// on détruit les variables d erreur d action de la page admin_news.php
unset ($_SESSION['showErrorAction'], $_SESSION['errorMsgAction']);
// message d erreur de creation
$_SESSION['showErrorCreate']  = (isset($_SESSION['showErrorCreate'])) ? $_SESSION['showErrorCreate'] : false;
$_SESSION['errorMsgCreate']  =  (isset($_SESSION['errorMsgCreate'])) ? $_SESSION['errorMsgCreate'] :'';
// recuperation des champs si le formulaire a ete envoye avec des erreurs
$creaArticleTitle = (isset($_SESSION['inputCreaArticleTitle'])) ? $_SESSION['inputCreaArticleTitle'] : '';
$creaArticleDescription = (isset($_SESSION['inputCreaArticleDescription'])) ? $_SESSION['inputCreaArticleDescription'] : '';
$creaArticleBody = (isset($_SESSION['inputCreaArticleBody'])) ? $_SESSION['inputCreaArticleBody'] : '';
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
		<title>Newsfeed - Admin : news create</title>
		<meta name="author" content="Franck Jakubowski">
		<meta name="description" content="Un mini site d'actualités consultable par tous les membres mais ou seul un admin est autorisé à poster.">
		<!-- 
            favicons
         -->
		<!-- bootstrap stylesheet -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
            integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<!-- default stylesheet -->
		<link href="/css/admin_news.css" rel="stylesheet" type="text/css">
        <!-- includes stylesheet -->
        <link href="/css/header.css" rel="stylesheet" type="text/css">
    </head>    
    
	<body>   
        <!-- import du header -->
        <?php include '../includes/header.php'; ?>
        <!-- /import du header -->
        <!-----------------------------------------------//--------------------------------------------
                 	debut du container global du formulaire de creation d une actualite
        ----------------------------------------------------------------------------------------------->
        <div class="d-md-flex flex-md-equal w-100 mt-5 pl-md-3 justify-content-center">						            
            <div class="mr-md-3 px-md-5 col-md-8 bg-info">  

				<!-- titre de la section du formulaire -->              
				<div class="py-2 text-center">
					<h1><strong>Création d'une actualité</strong></h1>
					<!-- area pour afficher un message d erreur lors de la creation -->
					<div class="show-bg <?=($_SESSION['showErrorCreate']) ? '' : 'visible'; ?> text-center mt-5">
						<p class="lead mt-2"><span><?=$_SESSION['errorMsgCreate']; ?></span></p>
					</div>
					<!-- /area pour afficher un message d erreur lors de la creation -->
					<hr class="mb-1">
				</div>
                <!-- /titre de la section du formulaire -->

				<!----------------------------------------//--------------------------------------------------
                                                debut du container du formulaire de creation
                ---------------------------------------------------------------------------------------------->
				<form class="form-inscription" action="../forms_processing/admin_news_create_process.php" method="POST" enctype="multipart/form-data">
                    
                    <!-- titre -->
					<div class="mb-4">
						<label for="articleTitle">Titre de l'article</label>
						<input class="form-control" name="articleTitle" id="articleTitle" type="text" value="<?=$creaArticleTitle ?>" required
							pattern="^[A-Za-z -]{1,255}$">
                    </div>	
                    		
					<!-- description -->
					<div class="mb-4">
						<label for="articleDescription">Description de l'article</label>
						<input class="form-control" name="articleDescription" id="articleDescription" type="text" value="<?=$creaArticleDescription ?>" required
							pattern="^[A-Za-z -]{1,255}$">
                    </div>		
                    
                    <!-- corps -->
					<div class="mb-4">
						<label for="articleBody">Corps de l'Article</label>
						<textarea class="form-control" name="articleBody"   id="articleBody" placeholder="Corps de l'article.." required><?=$creaArticleBody ?></textarea>
					</div>

					<!-- photo -->
                    <div class="mb-4">
						<label for="fileToUpload">Photo de l'article</label>
                        <input class="form-control" id=" fileToUpload" name="fileToUpload"  type="file">
                    </div>
					
					<hr class="my-4">

					<!-- buttons area -->
					<div class="container mb-3">
						<div class="row justify-content-center">
							<!-- submit button -->
							<div class="col-md-4 mb-3">
								<button class="btn btn-primary btn-lg btn-block" type="submit">Submit</button>
							</div>
							<!-- reset button -->
							<div class="col-md-4 mb-3">
								<button class="btn btn-primary btn-lg btn-block" type="reset">Reset</button>
							</div>
						</div>
					</div>
					<!-- /buttons area -->
				</form>
				<!--------------------------------------------------------------------------------------------
                                            /debut du container du formulaire de login
                -------------------------------------------//--------------------------------------------------->
            </div>
        </div>
        <!------------------------------------------------------------------------------
                 	/debut du container global du formulaire d inscription
		-------------------------------------//----------------------------------------->
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
