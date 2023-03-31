<?php
include 'include/connexion_bdd.php';
// élément php présent sur tout les pages (vérification si sessiion ouvert, connexion bdd etc...)
if(isset($_POST['submit']))
{
    if(!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']) && !empty($_POST['mdp']) && !empty($_POST['mdp2']) && !empty($_POST['numRue']) && !empty($_POST['nomRue']) && !empty($_POST['nomVille']) && !empty($_POST['cp']) && !empty($_POST['tel'])) //vérifie si le formulaire n'est pas vide et récupère toutes les informations saisies par l'utilisateur dans le formulaire
    {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];
        $mdp2 = $_POST['mdp2'];
        $numRue = $_POST['numRue'];
        $nomRue = $_POST['nomRue'];
        $nomVille = $_POST['nomVille'];
        $cp = $_POST['cp'];
        $tel = $_POST['tel'];
            if($mdp == $mdp2){ //vérifie que le mdp saisi ets le même que celui de confirmation
                if(strlen($mdp) >= 10){ //vérifie la longueur du mdp
                    $mdp = md5($mdp); //crypte le mdp
                    $statement = $pdo->prepare("SELECT * FROM user WHERE mail = :email"); //récupère les infos de l'utiisateur qui a créée un compte avec cette adresse mail pour vérifier si elle est dejà utilisée ou non
                    $statement->bindValue(':email', $email, PDO::PARAM_STR);
                    $statement->execute();
                    $result = $statement->fetch(PDO::FETCH_ASSOC);
                    if(!$result){
                          $statement = $pdo->prepare("INSERT INTO user VALUES (null,:nom,:prenom,:email,:tel,:mdp,:numRue,:nomRue,:nomVille,:cp,null,0)"); // si l'adresse mail n'est pas utilisé, requêt qui permet d'insérer les données saisies dans la bdd
                          $statement->execute([
                              ':nom' => $nom,
                              ':prenom' => $prenom,
                              ':email' => $email,
                              ':tel' => $tel,
                              ':mdp' => $mdp,
                              ':numRue' => $numRue,
                              ':nomRue' => $nomRue,
                              ':nomVille' => $nomVille,
                              ':cp' => $cp
                          ]);
                        session_start();
                        $_SESSION['inscription'] = "ok";
                       header("location:connexion.php");
                    }else{
                        $error = "Cet email est déjà utilisé";
                    }
                }else{
                    $error = "Le mot de passe doit contenir au moins 10 caractères";
                }

            }else{
                $error = "Les mots de passe ne correspondent pas";
            }
        }else{
            $error = "Tous les champs doivent être remplis";
        }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Inscription</title>
    <link rel="stylesheet" href="../asset/template/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../asset/template/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../asset/template/assets/css/demo_3/style.css">
    <link rel="shortcut icon" href="../image/jale.ico" />
    <script src="https://kit.fontawesome.com/13086b36a6.js" crossorigin="anonymous"></script>
</head>
<body>

<style>
    @keyframes imagesize {
        from {
            max-height: 50%;
            max-width: 50%;
        }
        to {
            max-height: 100%;
            max-width: 100%;
        }
    }
    * {
        margin: 0;
        padding: 0;
        overflow: auto;
        outline: none;
    }
    .img {
        animation-duration: 1s;
        animation-name: imagesize;
        transition: height 1s, width 1s;
    }
    .divider:after,
    .divider:before {
        content: "";
        flex: 1;
        height: 1px;
        background: #eee;
    }
    .h-custom {
        height: calc(100% - 73px);
    }
    .btn-color {
        background-color: #000078;
        border-color: #000078;
    }
    .alert-error.new {
        background: #f3f8f3;
        border: none;
        border-left: 4px solid #ec1212;
        border-radius: 0;
        box-shadow: 1px 1px 4px rgba(0, 0, 0, .2);
    }
    .alert-error.new.p-4 {
        padding: 0.75rem 0.75rem !important;
    }
    .alert-error.new .fa {
        color: #ec1212;
        display:table-cell;
        text-align: center;
        vertical-align: middle;
        font-size: 40px;
    }
    .alert-error .alert-body {
        padding-left: 0.75rem;
        display: table-cell;
        color: rgba(0,0,0,0.5);
    }

    .alert-error .alert-header {
        font-weight: 700;
        color: #ec1212;
        padding: 0;
        margin: 0;
    }
    @media (max-width: 450px) {
        .h-custom {
            height: 100%;
        }
    }
</style>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
            <div class="row flex-grow">
                <div class="col-lg-6 d-flex align-items-center justify-content-center">
                    <div class="auth-form-transparent text-left p-3">
                        <div class="brand-logo text-center">
                            <img src="../image/jalegreatedealnav.png" alt="logo">
                        </div>
                        <h4>Nouveau ici ?</h4>
                        <h6 class="font-weight-light">Rejoignez-nous aujourd'hui ! Cela ne prend que quelques étapes</h6>
                        <?php if(isset($error)): ?>
                            <div class="alert alert-error new p-4 mt-2">
                                <i class="fa fa-circle-exclamation" aria-hidden="true"></i>
                                <span class="alert-body">
                                    <h6 class="alert-header overflow-hidden">Erreur</h6>
                                    <p class="mb-0"><?= $error ?></p>
                                </span>
                            </div>
                        <?php endif; ?>
                        <div class="pt-3 table-responsive">
                            <form class="" action="" method="post" style="height: 500px"> <!-- formulaire d'inscription-->
                                    <div class="form-group">
                                        <label>Nom</label>
                                        <input type="text" name="nom" class="form-control form-control-lg border-left-0" placeholder="Nom">
                                    </div>
                                    <div class="form-group">
                                        <label>Prénom</label>
                                        <input type="text" name="prenom" class="form-control form-control-lg border-left-0" placeholder="Prénom">
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" name="email" class="form-control form-control-lg border-left-0" placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <label>Téléphone</label>
                                        <input type="text" name="tel" class="form-control form-control-lg border-left-0" placeholder="Téléphone">
                                    </div>
                                    <div class="form-group">
                                        <label>Numéro de voie</label>
                                        <input type="text" name="numRue" class="form-control form-control-lg border-left-0" placeholder="Numéro de voie">
                                    </div>
                                    <div class="form-group">
                                        <label>Adresse</label>
                                        <input type="text" name="nomRue" class="form-control form-control-lg border-left-0" placeholder="Adresse">
                                    </div>
                                    <div class="form-group">
                                        <label>Code postal</label>
                                        <input type="text" name="cp" class="form-control form-control-lg border-left-0" placeholder="Code postal">
                                    </div>
                                    <div class="form-group">
                                        <label>Ville</label>
                                        <input type="text" name="nomVille" class="form-control form-control-lg border-left-0" placeholder="Ville">
                                    </div>
                                    <div class="form-group">
                                        <label>Mot de passe</label>
                                        <input type="password" name="mdp" class="form-control form-control-lg border-left-0" placeholder="Mot de passe">
                                    </div>
                                    <div class="form-group">
                                        <label>Confirmer le mot de passe</label>
                                        <input type="password" name="mdp2" class="form-control form-control-lg border-left-0" placeholder="Confirmer le mot de passe">
                                    </div>
                                <div class="mt-3 text-center">
                                    <input type="submit" name="submit" class="btn  btn-warning " value="S'inscrire">
                                </div>

                            </form>
                        </div>
                    <div class="text-center mt-4 font-weight-light"> Vous avez déjà un compte ? <a href="connexion.php" class="text-danger">Connexion</a>
                    </div>
                    </div>
                </div>
                <div class="col-lg-6 login-half-bg d-flex flex-row">
                    <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy; 2021 All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
    
</div>
<script src="../asset/template/assets/vendors/js/vendor.bundle.base.js"></script>
<script src="../asset/template/assets/js/off-canvas.js"></script>
<script src="../asset/template/assets/js/hoverable-collapse.js"></script>
<script src="../asset/template/assets/js/misc.js"></script>
<script src="../asset/template/assets/js/settings.js"></script>
<script src="../asset/template/assets/js/todolist.js"></script>
<script src="../asset/template/assets/js/jquery.cookie.js"></script>
</body>
</html>