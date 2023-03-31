<?php include 'include/element.php'; ?> <!-- élément php présent sur tout les pages (vérification si sessiion ouvert, connexion bdd etc...) -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Favoris - Great Deal</title>
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

                    <div class=" grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title">Mes favoris</h2>
                                <div class="table-responsive mt-3" style="height: 550px;">
                                    <table class="table text-center">
                                        <thead>
                                        <tr>
                                            <th>Photo</th>
                                            <th>Titre</th>
                                            <th>Prix</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($pdo->query("SELECT * FROM favoris WHERE idu = ".$uid."") as $favoris) { //requête qui récupère depuis la bdd les favoris de l'utilisateur connecté
                                            $req = $pdo->prepare("SELECT * FROM annonce WHERE ida = :ida "); //récupère les annonces ajoutées en favoris
                                            $req->execute(array("ida" => $favoris["ida"]));
                                            $req = $req->fetch(); ?>
                                            <tr>
                                                <td><img src="../<?= $req["photo"] ?>" alt="image" style="max-height: 100px; max-width: 100px;"></td>
                                                <td><?= $req["titre"] ?></td>
                                                <td><?= $req["prix"] ?> €</td>
                                                <td>
                                                    <a class="btn btn-danger" href="action_get.php?action=ajoutFavori&ida=<?= $favoris["ida"] ?>&idu=<?= $uid ?>&route=location:favoris.php">
                                                        <i  class="fa-solid fa-heart"></i>
                                                    </a>
                                                    <a href="detail.php?ida=<?= $favoris["ida"] ?>" class="btn btn-inverse-warning mx-2">Voir</a>
                                                </td>
                                            </tr>

                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
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