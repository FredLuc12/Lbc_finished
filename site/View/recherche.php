<?php include 'include/element.php';
if(isset($_SESSION["search"])){
    $result2=$_SESSION["search"];
}



?> <!-- élément php présent sur tout les pages (vérification si sessiion ouvert, connexion bdd etc...) -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Recherche - Great Deal</title>
    <?php include 'include/header.php'; ?>  <!-- header présent sur toutes les pages (connexion avec bootstrap) -->
</head>
<body style="background-color: #f2edf3">
<div class="container-scroller">

    <?php include 'include/navigation.php'; ?> <!-- bar de navigation présent sur toute les pages-->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper container">


                <div class="row">

                    <div class="grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="text-center">Recherche</h2>
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
                                                            <?php if ($ligne["poche"]==1): ?>
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
                                                        <p class='card-title'><?=$ligne['detail']?></p> <br>
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

            <?php include 'include/footer.php'; ?> <!-- footer présent sur toute les pages -->

        </div>
    </div>
</div>

<?php include 'include/script.php'; ?> <!-- script présent sur toute les pages (connexion avec bootstrap) -->

</body>
</html>