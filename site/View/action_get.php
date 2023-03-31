<?php
session_start();
include "include/connexion_bdd.php";
$action=$_GET["action"];

//supprimer une annonce
if($action=="supAnnonce"){ 
    $ida=$_GET["ida"]; //récupère l'id de l'annonce que l'on veut supprimer
    $req=$pdo->prepare("delete from annonce where ida=:ida"); //requête qui permet de supprimer dans la base de données les informations de l'annonce selectionnée
    $req->execute(array("ida" =>$ida));
    $req=$pdo->prepare("DELETE from favoris where ida=:ida");
    $req->execute(array("ida" =>$ida));
    $req=$pdo->prepare("DELETE from conversation where idan=:ida");
    $req->execute(array("ida" =>$ida));
    header("location:mesAnnonces.php");
}

//ajouter une oeuvre aux favoris
if($action=="ajoutFavori") 
{
  $idu = $_GET["idu"]; 
  $ida = $_GET["ida"];
    $verif = $pdo->prepare("SELECT * FROM favoris WHERE idu = ? AND ida = ?"); //requête qui permet de vérifier si l'annonce est dans les favoris de l'utilisateur ou non 
    $verif->execute(array($idu, $ida));
    $verif = $verif->fetch();
    //si l'annonce n'est pas dans les favoris 
    if($verif == false) 
    {
        $recup = $pdo->prepare("SELECT * FROM annonce WHERE ida = ?"); //requête qui récupère les informations de l'annonce ayant cet id dans la bdd
        $recup->execute(array($ida));
        $recup = $recup->fetch();
        $totalFavoris = $recup["favoris"] + 1; //récupère le nombre de favoris et lui ajoute 1 s'il n'est pas déjà ajouté aux favoris
        $req = $pdo->prepare("UPDATE annonce SET favoris = ? WHERE ida = ?"); //modifie le nombre de favoris de l'annonce dans la base de données
        $req->execute(array($totalFavoris, $ida));
        $req = $pdo->prepare("INSERT INTO favoris (idf,idu, ida) VALUES (null,?, ?)"); //insérer dans favoris l'id de l'annonce et celui de l'utilisateur
        $req->execute(array($idu, $ida));
    }else{
      //si l'annonce est dans les favoris 
        $recup = $pdo->prepare("SELECT * FROM annonce WHERE ida = ?");
        $recup->execute(array($ida));
        $recup = $recup->fetch();
        $totalFavoris = $recup["favoris"] - 1; //enlève 1 au nombre de favoris 
        $req = $pdo->prepare("UPDATE annonce SET favoris = ? WHERE ida = ?");
        $req->execute(array($totalFavoris, $ida));
        $req = $pdo->prepare("DELETE FROM favoris WHERE idu = ? AND ida = ?"); //supprime l'annonce de la table favoris dans la bdd
        $req->execute(array($idu, $ida));
    }
    $route = $_GET["route"];
  header("$route");
}










?>