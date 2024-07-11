<?php
//filtre des variables postées
foreach ($_REQUEST as $key => $val) 
{
  $val = trim(stripslashes(@htmlentities($val)));
  $_REQUEST[$key] = $val;
}  
//chargement du code du jeu : svc 1 (NGPC), 2 (NGPC) ou 3 (DS) en session
if (isset($_GET["game"])) $_SESSION["svc"] = $_GET["game"];
//TEMPS AU LANCEMENT DU SCRIPT
define("TIME_START", microtime(true));
//EMAIL WEBMASTER
define("MAIL_WEBMASTER", "you@here.com");
//EMAIL POUBELLE
define("MAIL_JUNK", "do-not-reply@here.com");
//ADRESSE SITE
define("URL_SITE", "http://onfray.info/SNKvsCapcomCardFightersClash");
//NOM SITE
define("NOM_SITE", "CardFightersDB");
//TABLES FIXES
define("TABLE_PERSONNAL", "svc_personnal");
//TABLE ENREGISTREMENT DES EMAILS
define("TABLE_EMAILS", "svc_emailog");
//TABLE DES COMMENTAIRES SUR LES CARTES
define("TABLE_SNK_COMMENT", "svc_comment");
//TABLES DES CAPTCHA
define("TABLE_CAPTCHA", "svc_captcha");
// RETENTION DU COOKIE : 1 an
define("COOKIE_EXPIRE", 365*24*3600); 
//DEBUG (binaire)
define("DEBUG", false);
//TABLES DES CARTES
define("TABLE_A", "svc_action");
define("TABLE_CHA", "svc_characters");
//TABLE DES POSSESSIONS DES JOUEURS
define("TABLE_SNK_MISS", "svc_snk_miss");
//CHOIX du SGBD : "mysql" ou "sqlite"
define("SQL", "sqlite");
//NOM DU FICHIER SQLITE
define("FIC_SQLITE", "svc-prod.db");
//connexion au serveur SQL
sql_connect();
?>
