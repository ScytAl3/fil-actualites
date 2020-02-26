<?php
// -- import du script de connexion a la db
require 'pdo_db_connect.php'; 
// -- import du script des fonctions speciales
require 'special_functions.php';

// ---------------------------------------------//-----------------------------------------
//      fonction pour verifier l existence du pseudo qui doit être unique
// ---------------------------------------------//-----------------------------------------
function pseudoExiste($pseudoToTest) {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();
    // preparation de la  requete preparee pour verifier si l adresse email (email unique) est deja utilisee
    $query = "SELECT * FROM users WHERE userPseudo = :bp_pseudo";
    // preparation de l execution de la requete
    try {
        $statement = $pdo -> prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // passage de l email saisi en  parametre
        $statement->bindParam(':bp_pseudo', $pseudoToTest, PDO::PARAM_STR);
        // execution de la requete
        $statement -> execute(); 
        $user_count = $statement->rowCount();       
        // --------------------------------------------------------------
        //var_dump($user_row = $statement->fetch()); die; 
        // --------------------------------------------------------------
        // si on trouve un resultat
        if ($user_count == 1) {
            // on recupere les donnees trouvees dans 
            $user_row = $statement->fetch();
        } else {
            $user_row = false;
        }         
        $statement -> closeCursor();
    } catch(PDOException $ex) {         
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO check pseudo...' . $ex->getMessage();
        die($msg); 
    }
    $statement = null;
    $pdo = null;
    // on retourne le resultat
    return $user_row; 
}

// ---------------------------------------------//-----------------------------------------
//      fonction pour verifier l existence du email qui doit être unique !! TO-DO une fonction qui prend en parametre le where pour ne pas faire doublon !!
// ---------------------------------------------//-----------------------------------------
function emailExiste($emailToTest) {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();
    // preparation de la  requete preparee pour verifier si l adresse email (email unique) est deja utilisee
    $query = "SELECT * FROM users WHERE userEmail = :bp_email";
    // preparation de l execution de la requete
    try {
        $statement = $pdo -> prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // passage de l email saisi en  parametre
        $statement->bindParam(':bp_email', $emailToTest, PDO::PARAM_STR);
        // execution de la requete
        $statement -> execute(); 
        $user_count = $statement->rowCount();       
        // --------------------------------------------------------------
        //var_dump($user_row = $statement->fetch()); die; 
        // --------------------------------------------------------------
        // si on trouve un resultat
        if ($user_count == 1) {
            // on recupere les donnees trouvees dans 
            $user_row = $statement->fetch();
        } else {
            $user_row = false;
        }         
        $statement -> closeCursor();
    } catch(PDOException $ex) {         
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO check email...' . $ex->getMessage();
        die($msg); 
    }
    $statement = null;
    $pdo = null;
    // on retourne le resultat
    return $user_row; 
}
// ------------------------------------------------------------
//           fonction pour verifier le mot de passe
// ------------------------------------------------------------
function validPassword($loginPwd, $user) {
    // on on appelle la fonction speciale qui verifie le mot de passe saissi grace au Salt et mot de passe chiffre associes a l utilisateur
    $checkPwd = VerifyEncryptedPassword($user['userSalt'], $user['userPassword'], $loginPwd);
    // ------------------------------------
    //var_dump($checkPwd); die;
    // ------------------------------------
    // si identique
    if ($checkPwd) {        
        $user_valid = true;
    } else {        
        $user_valid = false;
    } 
    // on retourne le resultat
    return $user_valid; 
}

// -----------------------------------------------------------
//              fonction pour creer un utilisateur
// -----------------------------------------------------------
function createUser($userData) {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();
    // preparation de la requete pour creer un utilisateur
    $sqlInsert = "INSERT INTO 
                                users (`userLastName`, `userFirstName`, `userPseudo`, `userEmail`, `userPassword`, `userSalt`, `accountCreated_at`, `userRole`) 
                            VALUES 
                                (?, ?, ?, ?, ?, ?, now(), DEFAULT)";
    // preparation de la requete pour execution
    try {
        $statement = $pdo -> prepare($sqlInsert, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // execution de la requete
        $statement -> execute($userData);
        $statement -> closeCursor();
    } catch(PDOException $ex) {         
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO create user...' . $ex->getMessage();
        die($msg); 
    }
    // on retourne le dernier Id cree
    return $pdo -> lastInsertId(); 
}

// ----------------------------------------------------------------------
//      fonction pour renvoyer la liste des actualites
// ----------------------------------------------------------------------
function allFeedReader() {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();   
    // preparation de la requete preparee 
    $queryList = "SELECT  a.articlesId AS aticleId,
                                            a.articlesTitle AS title,
                                            a.articlesDescription AS resum,
                                            p.pictureFilename AS picture
                            FROM `articles` a
                            INNER JOIN users u ON u.userId = a.articleUserId
                            INNER JOIN pictures p ON p.articlesId = a.articlesId
                            GROUP BY a.articlesId
                            ORDER BY a.created_at DESC";   
    // preparation de la requete pour execution
    try {
        $statement = $pdo -> prepare($queryList, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // execution de la requete
        $statement -> execute();
        // on verifie s il y a des resultats
        // --------------------------------------------------------
        //var_dump($statement->rowCount()); die; 
        // --------------------------------------------------------
        if ($statement->rowCount() > 0) {
            $myReader = $statement->fetchAll();            
        } else {
            $myReader = false;
        }   
        //$statement -> closeCursor();
    } catch(PDOException $ex) {         
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO News Feed list...' . $e->getMessage(); 
        die($msg);    
    }
    // on retourne le resultat
    return $myReader; 
}

// ----------------------------------------------------------------------
//      fonction pour renvoyer les informations d une actualites
// ----------------------------------------------------------------------
function newsReader($newsId) {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();   
    // preparation de la requete preparee 
    $queryList = "SELECT u.userPseudo AS pseudo,
                                            a.articlesTitle AS title,
                                            a.articlesBody AS body,
                                            a.created_at AS create_at
                            FROM `articles` a
                            INNER JOIN users u ON u.userId = a.articleUserId
                            WHERE a.articlesId = :bp_articlesId";   
    // preparation de la requete pour execution
    try {
        $statement = $pdo -> prepare($queryList, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // passage de l identifiant utilisateur
        $statement->bindParam(':bp_articlesId', $newsId, PDO::PARAM_STR);
        // execution de la requete
        $statement -> execute();
        // on verifie s il y a des resultats
        // --------------------------------------------------------
        //var_dump($statement->fetchColumn()); die; 
        // --------------------------------------------------------
        if ($statement->rowCount() > 0) {
            $myReader = $statement->fetch();            
        } else {
            $myReader = false;
        }   
        $statement -> closeCursor();
    } catch(PDOException $ex) {         
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO News detail...' . $ex->getMessage(); 
        die($msg);
    }
    // on retourne le resultat
    return $myReader; 
}

// -------------------------------------------------------------------------------
//      fonction pour renvoyer les photos associees a une actualite
// -------------------------------------------------------------------------------
function newsPictureReader($newsId) {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();   
    // preparation de la requete preparee 
    $queryList = "SELECT p.picturesId AS pictureId, p.pictureFilename AS picture
                            FROM `articles` a
                            INNER JOIN pictures p ON p.articlesId = a.articlesId
                            WHERE p.articlesId = :bp_articlesId";   
    // preparation de la requete pour execution
    try {
        $statement = $pdo -> prepare($queryList, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // passage de l identifiant utilisateur
        $statement->bindParam(':bp_articlesId', $newsId, PDO::PARAM_STR);
        // execution de la requete
        $statement -> execute();
        // on verifie s il y a des resultats
        // --------------------------------------------------------
        //var_dump($statement->fetchColumn()); die; 
        // --------------------------------------------------------
        if ($statement->rowCount() > 0) {
            $myReader = $statement->fetchAll();            
        } else {
            $myReader = false;
        }   
        $statement -> closeCursor();
    } catch(PDOException $ex) {         
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO News pictures...' . $ex->getMessage(); 
        die($msg);
    }
    // on retourne le resultat
    return $myReader; 
}

// -------------------------------------------------------------------------------
//      fonction pour renvoyer la liste des actualites d un admin
// -------------------------------------------------------------------------------
function adminNewsReader($adminId) {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();   
    // preparation de la requete preparee 
    $queryList = "SELECT  a.articlesId AS aticleId,
                                            a.articlesTitle AS title,
                                            a.articlesDescription AS resum
                            FROM `articles` a
                            INNER JOIN users u ON u.userId = a.articleUserId
                            WHERE a.articleUserId = :bp_adminId
                            ORDER BY a.created_at DESC";   
    // preparation de la requete pour execution
    try {
        $statement = $pdo -> prepare($queryList, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
         // passage de l identifiant utilisateur
        $statement->bindParam(':bp_adminId', $adminId, PDO::PARAM_STR);
        // execution de la requete
        $statement -> execute();
        // on verifie s il y a des resultats
        // --------------------------------------------------------
        //var_dump($statement->rowCount()); die; 
        // --------------------------------------------------------
        if ($statement->rowCount() > 0) {
            $myReader = $statement->fetchAll();            
        } else {
            $myReader = false;
        }   
        //$statement -> closeCursor();
    } catch(PDOException $ex) {         
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO Admin news list...' . $e->getMessage(); 
        die($msg);    
    }
    // on retourne le resultat
    return $myReader; 
}

// ------------------------------------------------------------------------------------------
//    fonction pour creer une entree receiver-sender dans la table messages
// ------------------------------------------------------------------------------------------
function createMessage($arrayMsg) {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();
    // preparation de la requete pour creer un utilisateur
    $sqlInsert = "INSERT INTO 
                                `messages`(`messagingId`, `create_at`, `messageBody`)
                            VALUES 
                                (?, now(), ?)";
    // preparation de la requete pour execution
    try {
        $statement = $pdo -> prepare($sqlInsert, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // execution de la requete
        $statement -> execute($arrayMsg);
        $statement -> closeCursor();
    } catch(PDOException $ex) {         
        $statement = null;
        $pdo = null;
        die("Secured"); 
    }
    // on retourne le dernier Id cree
    return $pdo -> lastInsertId(); 
}
