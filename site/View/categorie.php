<?php 
//idcategorie
//1 all/ 2 children/ 3 teenager/ 4 parents/ 5 professionals

include 'include/element.php'; 
include 'include/navigation.php'; //bar de navigation présent sur toute les pages
$idcategorie = $_GET["idcategorie"];
$statement = $pdo ->prepare ("SELECT * from categorie where idc = :idcategorie");
//le 'prepare' prepare la requete 
$statement -> bindValue(':idcategorie', $idcategorie, PDO::PARAM_INT);
//bindValue donne la valeur :idcategorie au parametre idc 
$statement->execute();   
$result = $statement->fetch(PDO::FETCH_ASSOC);


if(isset($_GET["filtre"])){
    $filtre=$_GET["filtre"];
    // on recupere le filtre dans le lien par la méthode get, si dans le lien on retrouve un des filtres, on demande une requete en PDO
    // il selectionne dans la base de donnée puis avec $result2 il affiche. 

    if($filtre=="croissant"){
        $statement2 = $pdo -> prepare('SELECT * from annonce where categorie = :idcategorie order by prix asc');
        $statement2 -> bindValue(':idcategorie', $idcategorie, PDO::PARAM_INT);
        $statement2 -> execute();
        $result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);

    }
    // dans cette requete on selectionne le prix croissant 

    if($filtre=="decroissant"){
        $statement2 = $pdo -> prepare('SELECT * from annonce where categorie = :idcategorie order by prix desc');
        $statement2 -> bindValue(':idcategorie', $idcategorie, PDO::PARAM_INT);
        $statement2 -> execute();
        $result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
    }
    // dans cette requete on selectionne le prix décroissant

    if($filtre=="livraison"){
        $statement2 = $pdo -> prepare('SELECT * from annonce where categorie = :idcategorie and livraison = 1');
        $statement2 -> bindValue(':idcategorie', $idcategorie, PDO::PARAM_INT);
        $statement2 -> execute();
        $result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);

    }
    // dans cette requete on selectionne si il y a livraison 

    if($filtre=="mainPropre"){
        $statement2 = $pdo -> prepare('SELECT * from annonce where categorie = :idcategorie and livraison = 0');
        $statement2 -> bindValue(':idcategorie', $idcategorie, PDO::PARAM_INT);
        $statement2 -> execute();
        $result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);

    }
    // dans cette requete on selectionne si il n'y a pas de livraison et donc en main propre

    if($filtre=="poche"){
        $statement2 = $pdo -> prepare('SELECT * from annonce where categorie = :idcategorie and poche = 1');
        $statement2 -> bindValue(':idcategorie', $idcategorie, PDO::PARAM_INT);
        $statement2 -> execute();
        $result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);

    }
    // dans cette requete on selectionne si c'est un format poche ou pas

}elseif(isset($_GET["recherche"])){
    $recherche=$_GET["recherche"];
    // on recupere la recherche dans le lien par la méthode get, si dans le lien on retrouve une recherche, on demande une requete en PDO
    // il selectionne dans la base de donnée puis avec $result2 il affiche.

    $statement2 = $pdo -> prepare('SELECT * from annonce where categorie = :idcategorie and titre like :recherche');
    $statement2 -> bindValue(':idcategorie', $idcategorie, PDO::PARAM_INT);
    $statement2 -> bindValue(':recherche', '%'.$recherche.'%', PDO::PARAM_STR);
    $statement2 -> execute();
    $result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);

}else{
$statement2 = $pdo -> prepare('SELECT * from annonce where categorie = :idcategorie');
$statement2 -> bindValue(':idcategorie', $idcategorie, PDO::PARAM_INT);
$statement2 -> execute();
$result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
}
// si il n'a pas d'application de filtres alors on affiche toutes les annonces de la catégorie 




?> <!-- élément php présent sur tout les pages (vérification si session ouvert, connexion bdd etc...) -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>JALE - Great Deal</title>
    <?php include 'include/header.php'; ?>
</head>
<body style="background-color: #f2edf3">
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper container">
                <div class="row">
                    <div class="grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body text-center">
                                <h2 class="card-title text-center">Filtrez mes recherches :</h2>
                                <a  class="btn btn-gradient-warning" href="categorie.php?idcategorie=<?=$result["idc"]?>&filtre=croissant">Croissant</a>
                                <a  class="btn btn-gradient-warning" href="categorie.php?idcategorie=<?=$result["idc"]?>&filtre=decroissant">Décroissant</a>
                                <a  class="btn btn-gradient-warning" href="categorie.php?idcategorie=<?=$result["idc"]?>&filtre=livraison">Livraison</a>
                                <a  class="btn btn-gradient-warning" href="categorie.php?idcategorie=<?=$result["idc"]?>&filtre=mainPropre">Main propre</a>
                                <a  class="btn btn-gradient-warning" href="categorie.php?idcategorie=<?=$result["idc"]?>&filtre=poche">Poche</a>
                            </div>
                            <!-- un bouton est un lien amenant à la méthode get. 
                            Lorsque l'on clique sur le bouton voulu, on rentre dans le if et appliquons la requête -->
                        </div>
                    </div>

                    <div class="grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                            <!-- pour chaque resultat selon la requete on rentre dans la boucle foreach puis on affiche pour chaque annonce :
                            sa photo, son etat, son format, sa livraison puis un bouton pour acceder au detail du produit -->
                                <?php foreach($result2 as $ligne){ ?>
                                <div class= 'p-5 text-center' style='background-color:#F3F781'>
                                    <div class='card'>
                                        <div class='row'>

                                            <div class="card-body col-md-5 product-img-outer text-center">
                                                    <img class="product_image rounded" style="height: 300px; width: 300px" src="../<?= $ligne['photo'] ?>" alt="...">
                                            </div>
                                            
                                            <div class='card-body col-7 text-start'>    
                                                <h1><?=$ligne["titre"]?></h1> <br>
                                                <h2><p class="float-end h3"><?= number_format($ligne['prix'], 0, ',', ' ')  ?> €</p></h2>
                                                <!-- avec le resultat en tant que $ligne on affiche le titre et le prix, 
                                                number_format est une fonction qui te permet de mettre des espaces pour que le chiffre soit plus visible -->
                                            <ul class="product-variation">                                                
                                                                                             
                                                 <span class="badge badge-pill badge-info"><?=$ligne['etat']?> &nbsp<i class="fa-solid fa-thumbs-up"></i></span>
                                                <?php if ($ligne["ville"]==1): ?>
                                                <span class="badge badge-pill badge-danger">Format poche &nbsp<i class="fa-solid fa-pen-nib"></i></span>
                                                <?php else: ?>
                                                <span class="badge badge-pill badge-danger">Format standard &nbsp<i class="fa-solid fa-pen-nib"></i></span>
                                                <?php endif; ?>
                                                <!-- selon si c'est un format poche ou standard, un different bagde est mis -->

                                                <?php if ($ligne["livraison"]==1): ?>
                                                <span class="badge badge-pill badge-success">Livraison &nbsp<i class="fa-solid fa-truck"></i></span>
                                                <?php else: ?>
                                                <span class="badge badge-pill badge-sucess">Main propre &nbsp<i class="fa-solid fa-handshake"></i></span>
                                                <?php endif; ?>
                                                <!-- selon si c'est une livraison ou en main propre, un different bagde est mis -->
                                                </ul>
                                                <p class=''><?=$ligne['detail']?></p> <br>
                                                <a class="btn btn-success float-end"  href="detail.php?ida=<?= $ligne["ida"] ?>">Voir plus</a> 
                                                  <!-- on affiche un bouton voir plus, accedant à un lien vers la page detail de l'annonce  -->
                                            </div>
                                    
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include 'include/footer.php'; // on inclus notre footer à chaque bas de page
include 'include/script.php'; 
?> 
</body>
</html>