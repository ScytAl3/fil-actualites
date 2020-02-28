<?php
// --------------------------------------------------------------
// FONCTION : Generer Salt
// --------------------------------------------------------------
function generateSalt( $lenght = 10 ) {
    $allowedChar = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $maxLenght = strlen($allowedChar);
    $randomString = '';
    for ($i=0; $i < $lenght; $i++) { 
        $randomString .= $allowedChar[rand(0, $maxLenght-1)];
    }
    $encryptedSalt = md5($randomString);
    return $encryptedSalt;
}

// --------------------------------------------------------------
// FONCTION : Hashage du mot de passe
// --------------------------------------------------------------
function CreateEncryptedPassword( $salt, $password )
{
    $md5Pwd = md5($password);
    $encryptedPwd = md5($salt . $md5Pwd);
    return $encryptedPwd;                   // génère 60 caractères
};

// --------------------------------------------------------------
// FONCTION : Verification du mot de passe
// --------------------------------------------------------------
function VerifyEncryptedPassword( $userSalt, $userPwd, $loginPwd )
{
    $encryptLoginPwd = CreateEncryptedPassword($userSalt, $loginPwd);
    return ($userPwd == $encryptLoginPwd) ? true : false;
};

/* 
// pour construire le jeu de donnees users
$pwdIn = "c4tchM3";
// genere le salt
$mySalt = generateSalt(10);
echo 'le salt = '.$mySalt."\n";
// genere le mdp
$myPwd = CreateEncryptedPassword($mySalt, $pwdIn);
echo 'le pwd chiffré : '.$myPwd."\n";
// verifie le mdp et le 
$check = VerifyEncryptedPassword($mySalt, $myPwd, $pwdIn);
var_dump($check);
*/

// --------------------------------------------------------------
// FONCTION : Verification des images
// --------------------------------------------------------------
function ValidateUpload($image) {
    // on initialise le tableau des erreurs
    $errors= array();
    // on verifie si une image est envoyee
    if ($image['name'] == '') {
        return($errors);
    }
    // initialisation des variables avec les informations du fichier uploade
    $file_tmp = $image['tmp_name'];
    $file_name = $image['name'];
    $file_size = $image['size'];
    $file_type = $image['type'];
    $fileNameCmps = explode(".", $file_name);
    $file_ext = strtolower(end($fileNameCmps)); // donne l extension de l image    
    // dossier dans lequel l image sera deplacee
    $target_dir = "../img/news_feeds_pictures/";
    // on verifie si le fichier image est une vrai image ou une fausse image
    $check = getimagesize($file_tmp);
    if($check == false) {
        $errors[]= "Le fichier n'est pas une image !";
        return($errors);
    }
    // on verifie la taille du fichier
    if ($file_size > 2097152) {
        $errors[]= "Le fichier ne doit pas dépasser 2 MB !";
        return($errors);
    }    
    // extensions autorisees pour l upload des images
    $allowedImageExtensions= array("jpeg","jpg","png");
    // on verifie si l extension est valide
    if (in_array($file_ext, $allowedImageExtensions) === false){
        $errors[]= "Extension non autorisée, choisissez un fichier JPEG ou PNG !";
        return($errors);
    } 
};

// --------------------------------------------------------------
// FONCTION : Telechargement  des images
// --------------------------------------------------------------
function UploadImage($image) {
    // si aucune image n est envoyee la valeur pour l insert sera null et on affichera l image par defaut
    if ($image['name'] == '') {
        $newFileName = null;
    } else {
        // initialisation des variables avec les informations du fichier uploade
        $file_tmp = $image['tmp_name'];
        $file_name = $image['name'];
        $fileNameCmps = explode(".", $file_name);
        $file_ext = strtolower(end($fileNameCmps)); // donne l extension de l image
        // on supprime les espaces et caracteres speciaux
        $newFileName = md5(time() . $file_name) . '.' . $file_ext;
        // dossier dans lequel l image sera deplacee
        $target_dir = "../img/news_feeds_pictures/";
        $dest_path = $target_dir . $newFileName;
        // on deplace le fichier du repertoire temp a celui choisi
        move_uploaded_file($file_tmp, $dest_path);        
    }    
    return $newFileName;
}

// --------------------------------------------------------------
// FONCTION : Formatage de la date a afficher
// --------------------------------------------------------------
function formatedDateTime($mysqlDate){
    $date = date_format($mysqlDate,"d/m/Y");
    $hour = date_format($mysqlDate, "H");
    $minute = date_format($mysqlDate, "i");
    // on retourne la au format desire
    return  $date.' à '.$hour.'h'.$minute.'.';
};

// --------------------------------------------------------------
// FONCTION : Creation d un cookie
// --------------------------------------------------------------
function setUserCookie($userPseudo) {
    $cookie_name = "Pseudo-utilisateur";
    $cookie_value = $userPseudo;
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30)); // 86400 = 1 day 
};