<?php
// -- import du script de connexion a la db
require 'pdo_db_connect.php'; 
// -- import du script des fonctions speciales
require 'special_functions.php';

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                      Les Fonctions utilisateurs                                      //
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

// ---------------------------------------------//-----------------------------------------
//      fonction pour verifier l existence d un champ dans la table users
// ---------------------------------------------//-----------------------------------------
function userExist($where, $valueToTest) {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();
    // preparation de la  requete preparee pour verifier si la condition testee renvoie un resultat
    $query = "SELECT * FROM users WHERE $where = :bp_pseudo";
    // preparation de l execution de la requete
    try {
        $statement = $pdo -> prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // passage de la valeur a tester en  parametre
        $statement->bindParam(':bp_pseudo', $valueToTest, PDO::PARAM_STR);
        // execution de la requete
        $statement -> execute(); 
        $count = $statement->rowCount();       
        // --------------------------------------------------------------
        //var_dump($count = $statement->fetch()); die; 
        // --------------------------------------------------------------
        // si on trouve un resultat
        if ($count == 1) {
            // on recupere les donnees qui sont associees a l utilisateur 
            $userExist= $statement->fetch(); 
        } else {
            $userExist = false;
        }         
        $statement -> closeCursor();
    } catch(PDOException $ex) {         
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO Check User Exist...' . $ex->getMessage();
        die($msg); 
    }
    $statement = null;
    $pdo = null;
    // on retourne le resultat
    return $userExist; 
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

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                      Les Fonctions newsfeed                                        //
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

// -------------------------------------------------------------------------------------------------------------
//      fonction pour renvoyer les informations d une actualites avec l utilisateur qui a poste
// -------------------------------------------------------------------------------------------------------------
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

// ---------------------------------------------------------------------------------
//      fonction pour renvoyer les informations d une actualites 
// ---------------------------------------------------------------------------------
function newsInfoReader($articleId) {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();   
    // preparation de la requete preparee 
    $queryList = "SELECT `articlesTitle`, `articlesDescription`, `articlesBody` 
                            FROM `articles` 
                            WHERE articlesId = :bp_articlesId";   
    // preparation de la requete pour execution
    try {
        $statement = $pdo -> prepare($queryList, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // passage de l identifiant utilisateur
        $statement->bindParam(':bp_articlesId', $articleId, PDO::PARAM_STR);
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
        $msg = 'ERREUR PDO News informations detail...' . $ex->getMessage(); 
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

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                      Les Fonctions Administrateur                               //
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

// -------------------------------------------------------------------------------
//      fonction pour renvoyer la liste des actualites d un admin
// -------------------------------------------------------------------------------
function adminNewsReader($adminId) {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();   
    // preparation de la requete preparee 
    $queryList = "SELECT  a.articlesId AS articleId,
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
        $msg = 'ERREUR PDO Admin news list...' . $ex->getMessage(); 
        die($msg);    
    }
    // on retourne le resultat
    return $myReader; 
}

// -------------------------------------------------------------------------------------------------
//    fonction pour creer une entree dans la table articles - associee a un utilisateur
// -------------------------------------------------------------------------------------------------
function createArticle($arrayArticle) {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();
    // preparation de la requete pour creer un utilisateur
    $sqlInsert = "INSERT INTO `articles`(`articleUserId`, `articlesTitle`, `articlesDescription`, `articlesBody`, `created_at`, `updated_at`)
                            VALUES 
                            (?, ?, ?, ?, now(), null)";
    // preparation de la requete pour execution
    try {
        $statement = $pdo -> prepare($sqlInsert, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // execution de la requete
        $statement -> execute($arrayArticle);
        $statement -> closeCursor();
    } catch(PDOException $ex) {         
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO Admin create article...' . $ex->getMessage(); 
        die($msg); 
    }
    // on retourne le dernier Id cree
    return $pdo -> lastInsertId(); 
}

// --------------------------------------------------------------------------------------------
//    fonction pour creer une entree dans la table pictures - associee a l article
// --------------------------------------------------------------------------------------------
function createPicture($arrayPicture) {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();
    // preparation de la requete pour creer un utilisateur
    $sqlInsert = "INSERT INTO `pictures`(`articlesId`, `pictureFilename`)
                            VALUES 
                            (?, ?)";
    // preparation de la requete pour execution
    try {
        $statement = $pdo -> prepare($sqlInsert, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // execution de la requete
        $statement -> execute($arrayPicture);
        $statement -> closeCursor();
    } catch(PDOException $ex) {         
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO Admin create picture...' . $ex->getMessage(); 
        die($msg); 
    }
    // on retourne le dernier Id cree
    return $pdo -> lastInsertId(); 
}

// -----------------------------------------------------------------------------------------------------------
//    fonction pour mettre a jour une entree dans la table articles - associee a un utilisateur
// -----------------------------------------------------------------------------------------------------------
function updateArticle($arrayArticle) {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();
    // preparation de la  requete preparee pour mettre a jour les informations
    $sql = "UPDATE `articles` SET `articlesTitle` = ?,
                                                        `articlesDescription` = ?,
                                                        `articlesBody` = ?,
                                                        `updated_at` = now()";
    $where = " WHERE articlesId = ?";
    // construction de la requete
    $query = $sql.$where;
    // preparation de l execution de la requete
    try {
        $statement = $pdo -> prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // execution de la requete
        $statement -> execute($arrayArticle); 
        $statement -> closeCursor();  
        $msg =  "Données de l'article modifiées !";
    } catch(PDOException $ex) {     
        $msg = 'ERREUR PDO delete...' . $e->getMessage();     
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO Admin update article...' . $ex->getMessage(); 
        die($msg); 
    }
    $statement = null;
    $pdo = null;
    // on retourne le resultat
    return $msg;
}

// ------------------------------------------------------------------------------
//    fonction pour mettre a jour une entree dans la table pictures 
// ------------------------------------------------------------------------------
function updatePicture($arrayPicture) {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();
    // preparation de la  requete preparee pour mettre a jour les informations
    $sql = "UPDATE `pictures` SET `pictureFilename` = ?";
    $where = " WHERE articlesId = ?";
    // construction de la requete
    $query = $sql.$where;
    // preparation de l execution de la requete
    try {
        $statement = $pdo -> prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // execution de la requete
        $statement -> execute($arrayPicture); 
        $statement -> closeCursor();  
        $msg =  "Données de l'image modifiées !";
    } catch(PDOException $ex) {     
        $msg = 'ERREUR PDO delete...' . $e->getMessage();     
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO Admin update pictures...' . $ex->getMessage(); 
        die($msg); 
    }
    $statement = null;
    $pdo = null;
    // on retourne le resultat
    return $msg;
}

// ---------------------------------------------------------------------------------
//    fonction pour recuperer la liste des photos associees a un article
// ---------------------------------------------------------------------------------
function pictureNameReader($articleId) {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();   
    // preparation de la requete preparee 
    $queryList = "SELECT `pictureFilename` FROM `pictures` 
                            WHERE articlesId = ?";   
    // preparation de la requete pour execution
    try {
        $statement = $pdo -> prepare($queryList, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // passage de l identifiant utilisateur
        $statement->bindParam(1 , $articleId, PDO::PARAM_INT);
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
        $msg = 'ERREUR PDO Pictures list...' . $ex->getMessage(); 
        die($msg);
    }
    // on retourne le resultat
    return $myReader; 
}

// -------------------------------------------------------------------------------
//    fonction pour supprimer un entree dans la table pictures
// -------------------------------------------------------------------------------
function deletePicture($articleId) {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();
    // preparation de la requete pour creer un utilisateur
    $sqlDelete = "DELETE FROM `pictures` 
                            WHERE articlesId = ?";
    // preparation de la requete pour execution
    try {
        $statement = $pdo -> prepare($sqlDelete, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // passage du numero d identification en  parametre
        $statement->bindParam(1, $articleId, PDO::PARAM_INT);
        // execution de la requete
        $statement -> execute();
        $statement -> closeCursor();
        $msg =  'Photo(s) supprimée(s) !';
    } catch(PDOException $ex) {         
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO Admin delete picture...' . $ex->getMessage(); 
        die($msg); 
    }
    $statement = null;
    $pdo = null;
    // on retourne le message 
    return $msg;
}

// ----------------------------------------------------------------------------------------------------------------------------
//    fonction pour supprimer un entree dans la table article et les photos associees dans la table pictures
// ----------------------------------------------------------------------------------------------------------------------------
function deleteArticle($articleId) {
    // on instancie une connexion
    $pdo = my_pdo_connexxion();
    // preparation de la requete pour creer un utilisateur
    $sqlDelete = "DELETE FROM `articles`
                            WHERE articlesId = ?";
    // preparation de la requete pour execution
    try {
        $statement = $pdo -> prepare($sqlDelete, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // passage du numero d identification en  parametre
        $statement->bindParam(1, $articleId, PDO::PARAM_INT);
        // execution de la requete
        $statement -> execute();
        $statement -> closeCursor();
        $msg =  'Article supprimée !';
    } catch(PDOException $ex) {         
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO Admin delete picture...' . $ex->getMessage(); 
        die($msg); 
    }
    $statement = null;
    $pdo = null;
    // on retourne le message 
    return $msg; 
}
