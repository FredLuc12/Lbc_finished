<?php include 'include/element.php'; ?> <!-- élément php présent sur tout les pages (vérification si sessiion ouvert, connexion bdd etc...) -->
<?php $ida = $_GET['ida'];
if(isset($_POST["editImg"])){ // si le bouton d'ajout de l'avatar est cliqué, alors, la photo est récupérée

    $extensions = array('jpg', 'png', 'gif', 'jpeg', 'PNG');
    if (isset($_FILES['photo']) && !$_FILES['photo']['error']) { //vérifie si le "champ" photo n'est pas vide
        $fileInfo = pathinfo($_FILES['photo']['name']);
        if ($_FILES['photo']['size'] <= 2000000) { //vérifie la taille de la photo
            if (in_array($fileInfo['extension'], $extensions)) {
                $chemin = "image/annonce/"."$ida".".png"; //indique le chemin vers lequel envoyer l'image
                move_uploaded_file($_FILES['photo']['tmp_name'], '../image/annonce/'."$ida.png");
                $time = time();
                $req= $pdo->prepare("UPDATE annonce set photo = :chemin WHERE ida = :id"); //requête qui sert à modifier l'avatar dans la bdd
                $req->bindValue(':id', $ida, PDO::PARAM_INT);
                $req->bindValue(':chemin', $chemin, PDO::PARAM_STR);
                $req->execute();
                header("location:editAnnonce.php?ida=$ida");

            } else {
                echo 'Ce type de fichier est interdit';
                // l'image n'est pas en .png
            }
        } else {
            echo 'Le fichier dépasse la taille autorisée';
            // la taille de l'image est trop grande
        }
    } else {
        echo 'Une erreur est survenue lors de l\'envoi du fichier';// le "champ" photo est vide
    }
    
}
$req = $pdo->prepare('SELECT * FROM annonce WHERE ida = ?');
$req->execute([$ida]);
$annonce = $req->fetch(PDO::FETCH_ASSOC);
if(isset($_POST["edit"])){
    $titre = $_POST["titre"];
    $detail = $_POST["detail"];
    $prix = $_POST["prix"];
    $livraison = $_POST["livraison"];
    $poche = $_POST["poche"];
    echo $categorie = $_POST["categorie"];
    echo $edition = $_POST["edition"];
    $etat = $_POST["etat"];
    $poche = $_POST["poche"];
    $req = $pdo->prepare('UPDATE annonce SET titre = ?, detail = ?, prix = ?, livraison = ?, poche = ?, categorie = ?, edition = ?, etat = ? WHERE ida = ?');
    $req->execute([$titre, $detail, $prix, $livraison, $poche, $categorie, $edition, $etat, $ida]);
    header('Location: editAnnonce.php?ida='.$ida);
}
// recuperation des informations de l'annonce selon l'ida
// requete lorsque la methode post est utiliser pour modifier dans la table annonce les données
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Modification annonce - Great Deal</title>
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

                    <div class="col-lg-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Image</h4>
                                <img style="height: 300px; width: 600px" src="../<?= $annonce["photo"] ?>" alt="image" class="img-fluid">
                                <form class="mt-3" enctype='multipart/form-data' action='' method='post'> <!-- formumlaire de modification de l'avatar -->

                                    <div class='mb-2'>

                                        <input type='file' name='photo' class='form-control form-control-lg' value="" />

                                    </div>
                                    <input class='btn btn-warning px-5' name='editImg' type='submit' value='Modifier image'>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- formulaire pour modifier les informations d'une annonce :  -->
                    <div class="col-lg-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-center">Information annonce </h4>
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label for="titre" class="form-label">Titre</label>
                                        <input type="text" class="form-control" id="titre" name="titre" value="<?= $annonce["titre"] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="titre" class="form-label">Détail</label>
                                        <input type="text" class="form-control" id="titre" name="detail" value="<?= $annonce["detail"] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="titre" class="form-label">Prix</label>
                                        <input type="text" class="form-control" id="titre" name="prix" value="<?= $annonce["prix"] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="titre" class="form-label">Catégorie</label>
                                        <select class="form-control" name="categorie">
                                            <?php $req = $pdo->prepare('SELECT * FROM categorie');
                                            foreach ($pdo->query('SELECT * FROM categorie') as $row) {
                                                if ($row['idc'] == $annonce['categorie']) {
                                                    echo '<option value="' . $row['idc'] . '" selected>' . $row['nomCat'] . '</option>';
                                                } else {
                                                    echo '<option value="' . $row['idc'] . '">' . $row['nomCat'] . '</option>';
                                                }
                                            }
                                            //  possibilite de changer la categorie de son annonce  
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="titre" class="form-label">Edition</label>
                                        <select class="form-control" name="edition">
                                            <?php foreach ($pdo->query('SELECT * FROM edition') as $row) {
                                                if ($row['ide'] == $annonce['edition']) {
                                                    echo '<option value="' . $row['ide'] . '" selected>' . $row['nomEdition'] . '</option>';
                                                } else {
                                                    echo '<option value="' . $row['ide'] . '">' . $row['nomEdition'] . '</option>';
                                                }
                                            }
                                            // possibilité de changer l'edition
                                            ?>
                                        </select>
                                    </div>
                                
                                    <div class="form-group">
                                        <label for="titre" class="form-label">Poche</label>
                                        <select class="form-control" name="poche">
                                            <?php if ($annonce['poche'] == 1) {
                                                echo '<option value="1" selected>Oui</option>';
                                                echo '<option value="0">Non</option>';
                                            } else {
                                                echo '<option value="1">Oui</option>';
                                                echo '<option value="0" selected>Non</option>';
                                            }
                                            ?>
                                            <!-- possibilité de changer le format du livre  -->
                                        </select>

                                    </div>
                                    <div class="form-group">
                                        <label for="livraison">Livraison</label>
                                        <select class="form-control" name="livraison">
                                            <?php if ($annonce['livraison'] == 1) {
                                                echo '<option value="1" selected>Oui</option>';
                                                echo '<option value="0">Non</option>';
                                            } else {
                                                echo '<option value="1">Oui</option>';
                                                echo '<option value="0" selected>Non</option>';
                                            }
                                            ?>
                                            <!-- possibilité de changer la livraison de l'annonce  -->
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="livraison">Etat</label>
                                        <select class="form-control" name="etat">
                                            <?php if ($annonce['etat'] == "Neuf") {
                                                echo "<option value='Neuf' selected>Neuf</option>";
                                                echo "<option value='Très bon état'>Très bon état</option>";
                                                echo "<option value='Bon état'>Bon état</option>";
                                                echo "<option value='Satisfaisant'>Satisfaisant</option>";
                                            } else if ($annonce['etat'] == "Très bon état") {
                                                echo "<option value='Neuf'>Neuf</option>";
                                                echo "<option value='Très bon état' selected>Très bon état</option>";
                                                echo "<option value='Bon état'>Bon état</option>";
                                                echo "<option value='Satisfaisant'>Satisfaisant</option>";
                                            } else if ($annonce['etat'] == "Bon état") {
                                                echo "<option value='Neuf'>Neuf</option>";
                                                echo "<option value='Très bon état'>Très bon état</option>";
                                                echo "<option value='Bon état' selected>Bon état</option>";
                                                echo "<option value='Satisfaisant'>Satisfaisant</option>";
                                            } else if ($annonce['etat'] == "Satisfaisant") {
                                                echo "<option value='Neuf'>Neuf</option>";
                                                echo "<option value='Très bon état'>Très bon état</option>";
                                                echo "<option value='Bon état'>Bon état</option>";
                                                echo "<option value='Satisfaisant' selected>Satisfaisant</option>";
                                            }
                                            ?>
                                            <!-- possibilité de changer l'état du livre :  l'état actuel est pre selectionné   -->
                                        </select>
                                    </div>
                                    <input type="submit" name="edit" value="Modifier" class="btn btn-gradient-warning float-end">
                                </form>
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