<?php
//pr�pare les sessions
session_start();
//charge les noms des tables, les emails, r�glages avanc�s
require_once("conf.php");

function afficher_proprietaires($t) {return true;}
function ajouter_commentaire($c) {return true;}

function afficher_entete($titre="")
{	
	require_once("entete.html");
	//gestion du titre
	echo "\n<title>CardFightersDB : " . ucfirst($titre) . "</title>";
	require_once("entete-t.html");
	echo "<div class=\"page-header fspan12\"><h2 class=\"features\">". ucfirst($titre)."</h2></div><br><br><br><p>";
}

function afficher_bas($jsinclude="")
{
	require_once("basdepage1.html");
	echo "\nThis site is not affiliated with SNK or Capcom. All trademarks, trade names, services marks and logos belong to their respective companies.";
	if (DEBUG===true)
	{
			$timeend=microtime(true);
			$time=$timeend-TIME_START;
			//Afficher le temps d'�xecution
			$page_load_time = number_format($time, 3);
			echo "\n<br>SQL: ". SQL . ". Computing time: " . $page_load_time . "s. Generated @ " . date(DATE_RFC2822) . "\n";
	}
	require_once("basdepage2.html");
	echo "</body></html>";
	sql_close();
}

	



function creer_url_characters($id, $nom, $fin=null)
{
	//formatage d'url pour la fiche de personnage
	return "<a href=\"svc" . $_SESSION["svc"] . "-characters-" . $id . "-" . nettoyer_chaine($nom) . "\"" . $fin;
}

function creer_url_action($id, $nom, $fin=null, $reaction=false)
{
	//formatage d'url pour la fiche d'action
	if ($reaction) $re="re";
	else $re=null;
	return "<a href=\"svc" . $_SESSION["svc"] . "-" . $re ."action-" . $id . "-" . nettoyer_chaine($nom). "\"" . $fin;
}

function nettoyer_chaine($chaine)
{
	//virer les caract�res � la con
	setlocale(LC_ALL, 'fr_FR');
	$chaine = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $chaine);
	return preg_replace("#[^a-zA-Z]#", "", trim($chaine));
}

function emailog($mail,$sujet,$message,$header,$err=0)
{
	return true;
}


function retourner_nom_jeu($num=1)
{
	//les jeux connus
	switch ($num)
	{
		case 1 : return "SvC Card Fighter's Clash";
		case 2 : return "SvC Card Fighters 2 Expand Edition";
		case 3 : return "SvC Card Fighters DS";
		default : return $num;
	}
}

function marquer_champs($tri, $choix)
{
	//s�lectionner un �lement dans une liste d�roulante
	$msg = " value=\"" . $tri . "\" ";
	if ($choix == $tri) $msg .= " selected";
	return $msg;
}

function marquer_bouton($texte, $tri, $choix, $url="characters")
{
	$msg = "";
	if ($choix == $tri) $msg = "active";
	return "\n<a href=\"svc" . $_SESSION["svc"] . "-" . $url . "-" . $tri . "\" class=\"btn btn-default btn-lg ". $msg . "\">". $texte ."</a>";
}

function afficher_type_capacite($cap)
{
	//formatage des capacit�s des fiches de personnages
	switch ($cap)
	{
		case '(' : case '()' : $type_img = "rond.gif"; break;
		case '[' : case '[]' : $type_img = "carre.gif"; break;
		case '/' : case '/\\' : $type_img = "triangle.gif"; break;
	}
	if (empty($type_img)) return $cap;
	else return "\n<img src=i/type_of_ability/$type_img>";
}	

function afficher_rarete($rar)
{
	//mise en page de la raret� d'une carte
	return "\n<img src=i/rarity/$rar.gif>";
}

function capturer_type_capacite($texte)
{
	//formatage des capacit�s des fiches de personnages dans un texte
	$texte = str_replace("[]","<img src=i/type_of_ability/carre.gif>",$texte);
	$texte = str_replace("()","<img src=i/type_of_ability/rond.gif>",$texte);
	$texte = str_replace("/\\","<img src=i/type_of_ability/triangle.gif>",$texte);
	return "\n" . $texte;
}

function capturer_rarete($texte)
{
	//mise en page de la raret� d'une carte dans un texte 
	$texte = str_replace("[A]","<img src=i/rarity/A.gif>",$texte);
	$texte = str_replace("[B]","<img src=i/rarity/B.gif>",$texte);
	$texte = str_replace("[C]","<img src=i/rarity/C.gif>",$texte);
	$texte = str_replace("[D]","<img src=i/rarity/D.gif>",$texte);
	$texte = str_replace("[S]","<img src=i/rarity/S.gif>",$texte);
	return "\n" . $texte;
}



//FUNCTIONS SQL : asbtraction SGBD minimaliste
function sql_connect($readonly=true)
{
	switch (SQL)
	{
		case "mysql" :
		//conf mySQL
		$_SESSION["LIEN_BASE_SQL"] = mysqli_connect(IP, USER, PASS, NOM_BASE) or die("Connexion impossible au serveur"); 
		break;
		
		case "sqlite" :
		//serveur SQLite
		if ($readonly) $_SESSION["LIEN_BASE_SQL"] = new SQLite3(FIC_SQLITE, SQLITE3_OPEN_READONLY);
		else $_SESSION["LIEN_BASE_SQL"] = new SQLite3(FIC_SQLITE);
		break;
	}
}

function sql_close()
{
	switch (SQL)
	{
		case "mysql" : return mysqli_close($_SESSION["LIEN_BASE_SQL"]);
		case "sqlite" : return $_SESSION["LIEN_BASE_SQL"]->close();
	}
}

function sql_query($req)
{
	switch (SQL)
	{
		case "mysql" : return mysqli_query($_SESSION["LIEN_BASE_SQL"], $req);
		case "sqlite" : return $_SESSION["LIEN_BASE_SQL"]->query($req);
	}
}

function sql_error()
{
	switch (SQL)
	{
		case "mysql" : return null;
		case "sqlite" : return $_SESSION["LIEN_BASE_SQL"]->lastErrorMsg() . "\n";;
	}
}

function sql_num_rows($res)
{ 
	switch (SQL)
	{
		case "mysql" : return mysqli_num_rows($res);
		case "sqlite" : //cette fonction n'existe pas, on l'impl�mente
			$numRows = 0;
			while ($rowR = $res->FetchArray()) $numRows++;
			$res->reset();
			return $numRows;
	}
}

function sql_fetch_array($res)
{ 
	switch (SQL)
	{
		case "mysql" : return mysqli_fetch_array($res);
		case "sqlite" : return $res->fetchArray();
	}
}

?>
