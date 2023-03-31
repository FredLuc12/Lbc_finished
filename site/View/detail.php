<?php include 'include/element.php';
//Récupération des informations de l'annonce dans la BDD
$ida=$_GET["ida"];
// on recupere l'id annonce par la methode get via le lien 
// on fais une requete pour recuperer toutes les informations d'une annonce grace à ida puis on enregistre ces informations
// dans des variables portant leur noms afin de les réutiliser tout au long de la page
$req = $pdo->prepare("SELECT * FROM annonce WHERE ida = :ida");
$req->execute(array("ida" => $ida));
$req = $req->fetch();
$titre = $req["titre"];
$detail = $req["detail"];
$prix = $req["prix"];
$photo = $req["photo"];
$vendeur=$req["vendeur"];
$date=$req["date"];
$categorie=$req["categorie"];
$favoris=$req["favoris"];
$etat=$req["etat"];
$livraison=$req["livraison"];
$ville=$req["ville"];
$marque=$req["marque"];
$vue=$req["vue"];
$timeAnnonce = $req["time"];

//on fait un compteur pour comptabiliser les vue pour une annonce. Pour une vue dans l'annonce ida, on ajoute +1 dans la BDD dans la colonne vue  
$req = $pdo->prepare("UPDATE annonce SET vue = :vue WHERE ida = :ida");
$req->execute(array("vue" => $vue+1, "ida" => $ida));
$req2 = $pdo->query("SELECT * FROM edition WHERE ide = ".$marque."");
$req2=$req2->fetch();
$nomEdition=$req2["nomEdition"];

$req3 = $pdo->query("SELECT * FROM user WHERE idu = ".$vendeur."");
$req3=$req3->fetch();
$nom=$req3["nom"];
$prenom=$req3["prenom"];
$nomVille=$req3["nomVille"];
// on selectionne l'édition et le vendeur selon son ide et idu des tables
if(isset($_POST["send"])){
    $message = $_POST["message"];
    $req = $pdo->prepare("INSERT INTO conversation (idc, idan, idu, idv, time) VALUES (null, :idan, :idu, :idv, :time)");
    $req->execute(array("idan" => $ida, "idu" => $uid, "idv" => $vendeur, "time" => date("U")));
    $lastId = $pdo->lastInsertId();
    $req = $pdo->prepare("INSERT INTO message (idm,idu_q, idu_r, idc, contenu, time) VALUES (null, :idu_q, :idu_r, :idc, :contenu, :time)");
    $req->execute(array("idu_q" => $uid, "idu_r" => $vendeur, "idc" => $lastId, "contenu" => $message, "time" => date("U")));
    header("location:message.php?idc=$lastId");
}
// on insert dans la table conversation, les id necessaire et le temps
// egalement dans la table message ou on enregistre les id, le contenu et le temps

?> <!-- élément php présent sur toutes les pages (vérification si session ouverte, connexion bdd, etc...) -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Detail annonce - Great Deal</title>
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
                    <div class="col-lg-4 grid-margin stretch-card">
                        <div class="card">
                            <?php
                            $time = time();
                            $diff = $time - $timeAnnonce;
                            if($diff < 604800): ?>
                                <div class="action-holder">
                                    <div class="sale-badge bg-gradient-success text-center">Nouveauté</div>
                                </div>
                            <?php endif; ?>
                            <!-- si l'annonce est posté il y a moins d'une semaine soit 604800 secondes, on ajoute 'nouveauté à l'annonce'
                            ce badge disparaitra lorsque l'annonce est présente depuis plus d'une semaine  -->
                            <div class="card-body">
                                <div class='card rounded hover-shadow'>
                                    <div class="card">
                                        <img src='../<?=$photo?>' width='350'>
                                        <br>
                                        <span class="favorite-button"><a class="btn btn-danger" href="<?php if(isset($uid)): ?> action_get.php?action=ajoutFavori&ida=<?= $ida ?>&idu=<?= $uid ?>&route=location:detail.php?ida=<?=$ida?><?php else: ?>connexion.php<?php endif; ?>">
                                    <!-- sur ce bouton la possibilté d'ajouter au favoris, il renvoie à la page action_get : où la requete est effectué -->
                                    <?php
                                    if(isset($uid)){
                                        $verif = $pdo->prepare("SELECT * from favoris where ida = ? and idu = ?");
                                        $verif->execute(array($ida, $uid));
                                        if($verif->rowCount() == 0){
                                            echo '<i style="color: #ff0000" class="fa-regular fa-heart"></i>';
                                        }else{
                                            echo '<i style="color: #ff0000" class="fa-solid fa-heart"></i>';
                                        }
                                    }else{  ?>
                                        <i style="color: #ff0000" class="fa-regular fa-heart"></i>
                                    <?php } ?>
                                                <?=$favoris?></a></span>
                                    </div>
                                    <!-- $uid : on stocke l'id de la personne qui est actuellement connecté
                                    on demande à la bdd l'etat du compteur pour l'id annonce et l'id annonce.

                                    Si il est egale à 0 alors le coeur est vide sinon le coeur est rempli et cela signifie que l'id l'a deja ajoute dans ces favoris 
                                    puis on affiche favoris : 1 ou 0 
                                    -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class='card rounded hover-shadow'>
                                    <div class="card">
                                        <h2><?=$titre?><p class="float-end h3"><?= number_format($prix, 0, ',', ' ')  ?> €</p></h2>
                                        <ul class="product-variation">
                                            <?php if ($categorie==1): ?>
                                                <a class="badge badge-pill badge-primary" href="categorie.php?idcategorie=<?=$categorie?>">All<i class="fa-solid fa-heart mx-2"></i></a>
                                            <?php elseif ($categorie==2): ?>
                                                <a class="badge badge-pill badge-warning">Childen<i class="fa-solid fa-user-secret mx-2"></i></a>
                                            <?php elseif ($categorie==3): ?>
                                                <a class="badge badge-pill badge-info">Teenager<i class="fa-solid fa-rocket mx-2"></i></a>
                                            <?php elseif ($categorie==4): ?>
                                                <a class="badge badge-pill badge-danger">Parents<i class="fa-solid fa-feather-pointed mx-2"></i></a>
                                            <?php else: ?>
                                                <a class="badge badge-pill badge-success">Professionals<i class="fa-solid fa-earth-europe mx-2"></i></a>
                                            <?php endif; ?>
                                            <!-- selon la catégorie, un different badge va s'afficher  -->


                                            <span class="badge badge-pill badge-dark"><?=$nomEdition?> <i class="fa-solid fa-book"></i></span>

                                            <span class="badge badge-pill badge-info"><?=$etat?> &nbsp<i class="fa-solid fa-thumbs-up"></i></span>
                                            <!-- les informations récuperer son afficher dans un badge -->

                                            <?php if ($ville==1): ?>
                                                <span class="badge badge-pill badge-danger">Format ville &nbsp<i class="fa-solid fa-pen-nib"></i></span>
                                            <?php else: ?>
                                                <span class="badge badge-pill badge-danger">Format standard &nbsp<i class="fa-solid fa-pen-nib"></i></span>
                                            <?php endif; ?>
                                            <!--  si l'annonce est en format poche alors affichage d'un badge sinon different badge  -->

                                            <?php if ($livraison==1): ?>
                                                <span class="badge badge-pill badge-success">Livraison &nbsp<i class="fa-solid fa-truck"></i></span>
                                            <?php else: ?>
                                                <span class="badge badge-pill badge-sucess">Main propre &nbsp<i class="fa-solid fa-handshake"></i></span>
                                            <?php endif; ?>
                                            <!--  si l'annonce est en livraison alors affichage d'un badge sinon different badge pour en main propre -->

                                        </ul>
                                        <p><?=$detail?></p>
                                        <p><span class="badge badge-gradient-dark" ><i class="fa-solid fa-eye"></i> <?= $vue ?></span></p>
                                        <p class="card-text"><small class="text-muted float-start"><?=$nom?> <?=$prenom?></small><small class="text-muted"><br><?=$nomVille?><br><?=$date?></small></p>
                                        <p>
                                        <?php if(isset($uid)): ?>
                                            <?php
                                            $verif = $pdo->prepare("SELECT * from conversation where idan = ? and idu = ?");
                                            $verif->execute(array($ida, $uid));
                                            if($verif->rowCount() == 0){  ?>
                                            <button type="button" class="btn btn-inverse-warning" data-bs-toggle="modal" data-bs-target="#exampleModal-4" data-whatever="@fat">Contacter le vendeur</button>
                                            <?php }else{
                                                $idc = $verif->fetch();
                                                ?>
                                            <a href="message.php?idc=<?= $idc["idc"]?>" class="btn btn-inverse-warning">Contacter le vendeur</a>
                                            <?php } ?>
                                        <?php else: ?>
                                            <a href="connexion.php" class="btn btn-inverse-warning">Contacter le vendeur</a>
                                        <?php endif; ?>
                                        <!-- si la personne est connecté alors on demande a la BDD si il y a une conversation en cours sur l'id de l'annonce et avec l'user :  possibilité de contacter le vendeur en cliquant sur le bouton
                                        sinon la personne n'est pas connecté alors on renvoie à la page connexion pour contacter ensuite le vendeur
                                    -->
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="exampleModal-4" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <h5 class="modal-title" id="ModalLabel">Nouveau message</h5>
                                    <form action="" method="post">
                                        <div class="form-group">
                                            <label for="message-text" class="col-form-label">Message:</label>
                                            <textarea class="form-control" name="message" id="message-text"></textarea>
                                        </div>

                                </div>
                                <div class="modal-footer">
                                    <input type="submit" class="btn btn-success" name="send" value="Envoyer message">
                                    </form>
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- formulaire renvoyant à la methode post afin de faire requete dans le if(isset) -->

                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?> <!-- footer présent sur toute les pages -->
<?php include 'include/script.php'; ?> <!-- script présent sur toute les pages (connexion avec bootstrap) -->

</body>
</html>