<?php
include 'include/element.php';
//$header = "MIME-Version: 1.0\r\n";
//$header .= 'From: "Nom"<test@jalegreatdeal.fr>' . "\n";
//$header .= 'Content-Type: text/html; charset="utf-8"' . "\n";
//$header .= 'Content-Trasnfer-Encoding: 8bit';

//$message = "Salut";
//$mail = "admin@jalegreatdeal.fr";
//mail($mail, "Sujet", $message, $header);

if(isset($_POST["recherche"])){
    header("location:categorie.php?idcategorie=".$_POST["categorie"]."&recherche=".$_POST["recherchetext"]);
    //envoie vers la page categorie.php avec l'id de la catégorie et la recherche
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Accueil - Great Deal</title>
    <?php include 'include/header.php'; ?>
</head>
<body style="background-color: #f2edf3">
<div class="container-scroller">

    <?php include 'include/navigation.php'; ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper container">


                <div class="row">

                    <div class="grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="text-center">Recherche</h2>

                                <form method="post" action="">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <select class="form-select" name="categorie">
                                                    <?php
                                                    $req=$pdo->query("select * from categorie");
                                                    $resultat=$req->fetchAll();
                                                    foreach($resultat as $categorie){
                                                        echo "<option value='".$categorie["idc"]."'>".$categorie["nomCat"]. "</option><br>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <input type="text" class="form-control" placeholder="Entrer le titre de l'annonce" name="recherchetext" aria-label="Text input with dropdown button">
                                            <div class="input-group-append">
                                                <input type="submit" class="btn btn-warning" name="recherche" value="Rechercher">
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="text-center">
                                    <a class="btn btn-warning" style="background-color: #DFD019" href="<?php if(isset($uid)): ?>nvAnnonces.php <?php else: ?> /jalegreatdeal/View/connexion.php <?php endif; ?>">
                                        <i class="fa-regular fa-square-plus "></i><span class="menu-title "> Nouvelle Annonce</span>
                                    </a>
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="text-center">Les 20 annonces les plus vues </h2>
                                <div class="row product-item-wrapper mt-4">

                                    <?php foreach ($pdo->query("SELECT * from annonce order by vue desc LIMIT 20") as $tableau): ?>
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-12 product-item">
                                        <div class="card">
                                            <div class="card-body">

                                                <div class="action-holder">
                                                    <?php
                                                    $time = time();
                                                    $diff = $time - $tableau['time'];
                                                    if($diff < 604800): ?>
                                                    <div class="sale-badge bg-gradient-success">New</div>
                                                    <?php endif; ?>
                                                    <span class="favorite-button"><a href="<?php if(isset($uid)): ?> action_get.php?action=ajoutFavori&ida=<?= $tableau["ida"] ?>&idu=<?= $uid ?>&route=location:index.php<?php else: ?>connexion.php<?php endif; ?>">
                                                            <?php
                                                            if(isset($uid)){ ?>

                                                                    <?php
                                                                $verif = $pdo->prepare("SELECT * from favoris where ida = ? and idu = ?");
                                                                $verif->execute(array($tableau['ida'], $uid));
                                                                if($verif->rowCount() == 0){
                                                                    echo '<i style="color: #ff0000" class="fa-regular fa-heart"></i>';
                                                                }else{
                                                                    echo '<i style="color: #ff0000" class="fa-solid fa-heart"></i>';
                                                                }
                                                            }else{  ?>
                                                            <i style="color: #ff0000" class="fa-regular fa-heart"></i>
                                                            <?php } ?>
                                                        </a></span>
                                                </div>

                                                <div class="product-img-outer text-center">
                                                    <img class="product_image rounded" style="height: 300px; width: 300px" src="../<?= $tableau["photo"] ?>" alt="prduct image">
                                                </div>
                                                <p class="product-title"><?= $tableau["titre"] ?></p>
                                                <p class="product-price"><?= number_format($tableau["prix"], 0, ',', ' ')  ?> €</p>
                                                <p class="product-actual-price">
                                                    <?php if ($tableau["livraison"]==1): ?>
                                                    <span class="badge badge-pill badge-success">Livraison <i class="fa-solid fa-truck"></i></span>
                                                    <?php else: ?>
                                                    <span class="badge badge-pill badge-danger">Main propre <i class="fa-solid fa-handshake"></i></span>
                                                    <?php endif; ?>
                                                </p>
                                                <ul class="product-variation">
                                                        <?php if ($tableau["categorie"]==1): ?>
                                                        <span class="badge badge-primary">Romance <i class="fa-solid fa-heart mx-2"></i></span>
                                                        <?php elseif ($tableau["categorie"]==2): ?>
                                                        <span class="badge badge-warning">Thriller/Policier <i class="fa-solid fa-user-secret mx-2"></i></span>
                                                        <?php elseif ($tableau["categorie"]==3): ?>
                                                        <span class="badge badge-info">Science Fiction <i class="fa-solid fa-rocket mx-2"></i></span>
                                                        <?php elseif ($tableau["categorie"]==4): ?>
                                                        <span class="badge badge-danger">Développement personnel <i class="fa-solid fa-feather-pointed mx-2"></i></span>
                                                        <?php else: ?>
                                                        <span class="badge badge-success">Romans étrangers <i class="fa-solid fa-earth-europe mx-2"></i></span>
                                                        <?php endif; ?>
                                                </ul>
                                                <a href="detail.php?ida=<?= $tableau["ida"] ?>" class="btn btn-inverse-warning">Voir</a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="text-center">Présentation de l'équipe</h2>

                                <div class="row portfolio-grid mt-3">

                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                                        <figure class="effect-text-in">
                                            <img src="../image/equipe/fred.jpg" alt="image" />
                                            <figcaption>
                                                <h4 class="text-dark">Fred</h4>
                                                <p style="background-color: #DFD019">Developpeur full-stack <br> Depuis 2022 pour JALE
                                                    <br>
                                                    Conctat : ablefonlin.f@yahoo.com</p>
                                            </figcaption>
                                        </figure>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                                        <figure class="effect-text-in">
                                            <img src="../image/equipe/hervy.jpeg" alt="image" />
                                            <figcaption>
                                                <h4 class="text-dark">Hervy</h4>
                                                <p style="background-color: #DFD019">Developpeur front-end <br>Depuis 2022 pour JALE
                                                    <br>
                                                    Conctat : hervymambou@gmail.com</p>
                                            </figcaption>
                                        </figure>
                                    </div>


                                </div>


                            </div>
                        </div>
                    </div>

                </div>


            </div>

            <?php include 'include/footer.php'; ?>

        </div>
    </div>
</div>

<?php include 'include/script.php'; ?>

</body>
</html>