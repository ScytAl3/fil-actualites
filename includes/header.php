<!--Main Navigation-->
<!-- affichage du pseudo du membre connecte et un bouton pour la deconnexion vers la page index.php -->
<header>
    <!-- si l utilisateur n est pas logger color : blue, sinon  color : green  -->   
    <nav class="navbar navbar-expand-md navbar-dark static-top <?=($_SESSION['current_Role'] == 'Admin') ?  'bg-danger' :  'bg-success'; ?>">
        <a class="navbar-brand" href="/index.php">
            <img class="logo" src="/img/default/newsFeed.png" alt="News Feed Logo">
        </a>
        <div class="container">
            <div class="d-flex flex-column">
                <h1 class="align-self-center"><strong>MY NEWS FEEDS</strong></h1>
                <div>
                    <ul class="navbar-nav <?=($_SESSION['current_Session']) ? 'visible ' : 'invisible ' ?>">
                        <li class="nav-item active">
                            <a class="nav-link" href="/news_feed.php">Le fil</a>
                        </li>
                        <li class="nav-item active <?=($_SESSION['current_Role'] == 'Admin') ? 'visible ' : 'invisible ' ?>">
                            <a class="nav-link" href="/admin_page/admin_news.php">Administration</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container align-self-end">
            <div class="col-md-auto ml-auto <?= ($_SESSION['current_Session']) ? 'visible ' : 'invisible '; ?> align-self-end">
                <h2 class="text-muted"><em>Bonjour</em><em>&nbsp;<strong><em><?= $_SESSION['current_Pseudo']; ?></em></strong><h2>
            </div>
            <div class="d-flex ml-2">
                <div class="<?=($_SESSION['current_Session']) ? 'visible ' : 'invisible '; ?> align-self-end">
                    <a class="my-2 my-sm-0 ml-1" href="/logout.php"><img src="/img/default/switch-off.png" alt="Logout" class="logout-button"></a>
                </div>
            </div>
        </div>
    </nav>
</header>
<!-- /affichage du pseudo du membre connecte et un bouton pour la deconnexion vers la page index.php -->
<!--Main Navigation-->