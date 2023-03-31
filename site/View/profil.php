<?php include 'include/element.php'; //élément php présent sur tout les pages (vérification si la session est ouverte, connexion bdd etc...) -->



if(isset($_POST["bout_info"])){  // si le bouton est cliqué, alors les informations renseignées dans le formulaire de modification sont récupérées
    $nomm= $_POST["nom"];
    $prenomm=$_POST["prenom"];
    $mailm = $_POST["mail"];
    $telm = $_POST["tel"];
    $numRuem= $_POST["numRue"];
    $nomRuem=$_POST["nomRue"];
    $nomVillem= $_POST["nomVille"];
    $cpm= $_POST["cp"];
    $req2 = $pdo->prepare("UPDATE user SET nom = :nom, prenom = :prenom , mail = :mail, tel = :tel, numRue = :numRue, nomRue = :nomRue, nomVille = :nomVille, cp = :cp WHERE idu = :id"); //requête qui permet de modifier dans la bdd les informations saisies par l'utilisateur
    $req2->bindValue(':id', $uid, PDO::PARAM_INT);
    $req2->bindValue(':nom', $nomm, PDO::PARAM_STR);
    $req2->bindValue(':prenom', $prenomm, PDO::PARAM_STR);
    $req2->bindValue(':mail', $mailm, PDO::PARAM_STR);
    $req2->bindValue(':tel', $telm, PDO::PARAM_STR);
    $req2->bindValue(':numRue', $numRuem, PDO::PARAM_STR);
    $req2->bindValue(':nomRue', $nomRuem, PDO::PARAM_STR);
    $req2->bindValue(':nomVille', $nomVillem, PDO::PARAM_STR);
    $req2->bindValue(':cp', $cpm, PDO::PARAM_STR);
    $req2->execute();  
}



if(isset($_POST["bout_avatar"])){ // si le bouton d'ajout de l'avatar est cliqué, alors, la photo est récupérée

    $extensions = array('jpg', 'png', 'gif', 'jpeg', 'PNG');
    if (isset($_FILES['photo']) && !$_FILES['photo']['error']) { //vérifie si le "champ" photo n'est pas vide
        $fileInfo = pathinfo($_FILES['photo']['name']);
        if ($_FILES['photo']['size'] <= 2000000) { //vérifie la taille de la photo
            if (in_array($fileInfo['extension'], $extensions)) {
                $chemin = "image/avatar/"."$uid".".png"; //indique le chemin vers lequel envoyer l'image
                move_uploaded_file($_FILES['photo']['tmp_name'], '../image/avatar/'."$uid.png");
                $time = time();
                $req= $pdo->prepare("UPDATE user set avatar = :chemin WHERE idu = :id"); //requête qui sert à modifier l'avatar dans la bdd
                $req->bindValue(':id', $uid, PDO::PARAM_INT);
                $req->bindValue(':chemin', $chemin, PDO::PARAM_STR);
                $req->execute();
                header("location:profil.php");

            } else {
                echo 'Ce type de fichier est interdit';
            }
        } else {
            echo 'Le fichier dépasse la taille autorisée';
        }
    } else {
        echo 'Une erreur est survenue lors de l\'envoi du fichier';
    }
}

$req = $pdo->prepare("SELECT * FROM user WHERE idu = ".$uid); //requête qui récupère depuis la bdd les informations de l'utilisateur connecté
$req->execute();
$req = $req->fetch();
$prenom = $req["prenom"];
$nom = $req["nom"];
$mail = $req["mail"];
$tel = $req["tel"];
$numRue = $req["numRue"];
$nomRue = $req["nomRue"];
$nomVille = $req["nomVille"];
$cp = $req["cp"];
$avatar = $req["avatar"];


if(isset($_POST["bout_mdp"])) // si le bouton est cliqué, les informations saisies par l'utilisateur dans le formulaire de modification du mdp sont récupérées
{
    $mdpm= $_POST["mdpm"]; //récupère le mdp modifié
    $mdpm= md5($mdpm); //crypte le mdp
    $mdp2= $_POST["mdp2"]; //récupère le mot de passe modifié saisie une 2e fois
    $mdptest = $_POST["mdptest"]; //récupère le mot de passe saisie pour vérifier l'identité de l'utilisateur
    $mdptest= md5($mdptest); //hash le mot de passe saisie dans le formulaire de modification du mdp
    $mdpa= $req["mdp"]; //récupère l'ancien mot de passe

    if ($mdptest != $mdpa) // envoi un message si le mdp saisi est différent de l'ancien mdp qui aurait du être saisi
    {
        echo "<div class='alert alert-danger text-center mt-1'>
                                        <h3 class='alert-heading'>Votre ancien mot de passe est incorrect! </h3>
                                        </div>";

    }else
    {
        if($mdpm!=$mdp2) //envoi un message si le mdp modifié et le mdp de confirmation sont différents
        {
            echo "<div class='alert alert-danger text-center mt-1'>
                                        <h3 class='alert-heading'>Votre nouveau mot de passe et la confirmation de votre nouveau mot de passe sont différents !</h3>
                                        </div>";
        }elseif (strlen($mdpm)<10) // vérifie la taille du mdp
        {
            echo "<div class='alert alert-danger text-center mt-1'>
                                        <h3 class='alert-heading'>Votre nouveau mot de passe doit contenir au moins 10 caractères !</h3>
                                        </div>";
        }else{
            $req= $pdo->prepare("UPDATE user set mdp = :mdp WHERE idu = :id"); //modifie le mdp dans la bdd
            $req->bindValue(':id', $uid, PDO::PARAM_INT);
            $req->bindValue(':mdp', $mdpm, PDO::PARAM_STR);
            $req->execute();
        }
    }
}



?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Profil - Great Deal</title>
    <?php include 'include/header.php'; ?>  <!-- header présent sur toutes les pages (connexion avec bootstrap) -->
</head>
<body style="background-color: #f2edf3">
<div class="container-scroller">

    <?php include 'include/navigation.php'; ?> <!-- barre de navigation présente sur toute les pages-->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper container">


                <div class="row">

                    <div class="col-lg-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h2> Modifier mon avatar </h2>
                                <div class="text-center my-2">
                                    <img 
                                            src="<?php if($avatar == null){ echo "../image/avatarbasique.png";}else{ ?>../<?= $avatar ?><?php } ?>" 
                                            alt="image"
                                            style="width: 300px; height: 300px; border-radius: 50%; border: 1px solid #000000"
                                    /> <!-- affiche l'avatar actuel de l'utilisateur -->
                                </div>
                                <form enctype='multipart/form-data' action='' method='post'> <!-- formumlaire de modification de l'avatar -->

                                <div class='mb-2'>

                                    <input type='file' name='photo' class='form-control form-control-lg' value="" />
                                    
                                </div>            
                                <input class='btn btn-warning px-5' name='bout_avatar' type='submit' value='Modifier avatar'>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                        <h2 class='fw-bold mb-2'>Modifier mes informations:</h2>
                                            
                                            <form enctype='multipart/form-data' action='' method='post'> <!-- formulaire de modification des infos de l'utilisateur -->
                                            <div class='mb-4'>

                                                    <input type='text' name='nom' class='form-control form-control-lg' value='<?= $nom ?>' /> <!-- affiche les informations actuels de l'utilisateur -->
                                                    
                                                </div>
                                                <div class='mb-4'>

                                                    <input type='text' name='prenom' class='form-control form-control-lg' value='<?= $prenom ?>' />
                                                    
                                                </div>
                                                <div class='mb-4'>

                                                    <input type='text' name='mail' class='form-control form-control-lg' value='<?= $mail ?>'/>
                                                    
                                                </div>
                                                <div class='mb-4'>

                                                    <input type='text' name='tel' class='form-control form-control-lg' value='<?= $tel ?>'/>
                                                    
                                                </div>
                                                <div class='mb-4'>

                                                    <input type='text' name='numRue' class='form-control form-control-lg' value='<?= $numRue ?>'/>
                                                    
                                                </div>
                                                <div class='mb-4'>

                                                    <input type='text' name='nomRue' class='form-control form-control-lg' value='<?= $nomRue ?>'/>
                                                    
                                                </div>
                                                <div class='mb-4'>

                                                    <input type='text' name='cp' class='form-control form-control-lg' value='<?= $cp ?>'/>
                                                    
                                                </div>
                                                <div class='mb-4'>

                                                    <input type='text' name='nomVille' class='form-control form-control-lg' value='<?= $nomVille ?>'/>
                                                    
                                                </div>
                                                <input class='btn btn-warning px-5' name='bout_info' type='submit' value='Enregistrer mes informations'>

                                                </form>
                                            </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h2 class='fw-bold mb-2'>Modifier mon mot de passe:</h2>
                                    <form action='' method='post'> <!-- formulaire de modification du mdp -->

                                    <div class='mb-4'>

                                        <input type='password' name='mdptest' class='form-control form-control-lg' placeholder='Entrez votre ancien mot de passe'/>
                                        
                                    </div>
                                    <div class='mb-4'>

                                        <input type='password' name='mdpm' class='form-control form-control-lg' placeholder='Entrez votre nouveau mot de passe'/>
                                        
                                    </div>
                                    <div class='mb-4'>

                                        <input type='password' name='mdp2' class='form-control form-control-lg'placeholder='Confirmez votre nouveau mot de passe'/>
                                        
                                    </div>
                                    <input class='btn btn-warning px-5' name='bout_mdp' type='submit' value='Enregistrer le nouveau mot de passe'>

                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>

            </div>

            <?php include 'include/footer.php'; ?> <!-- footer présent sur toutes les pages -->

        </div>
    </div>
</div>

<?php include 'include/script.php'; ?> <!-- script présent sur toutes les pages (connexion avec bootstrap) -->

</body>
</html>