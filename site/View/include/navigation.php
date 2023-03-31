<?php
if(isset($_GET["idcategorie"])){
    if($_GET["idcategorie"] == 1){
        $all = "active";
    }else{
        $all = null;
    }
    if($_GET["idcategorie"] == 2){
        $children = "active";
    }else{
        $children = null;
    }
    if($_GET["idcategorie"] == 3){
        $teenager = "active";
    }else{
        $teenager = null;
    }
    if($_GET["idcategorie"] == 4){
        $parents = "active";
    }else{
        $parents = null;
    }
    if($_GET["idcategorie"] == 5){
        $professionals = "active";
    }else{
        $professionals = null;
    }
}else{
    $all = null;
    $children = null;
    $teenager = null;
    $parents = null;
    $professionals = null;
}
if(isset($_POST["search"])){
    $search = $_POST["search"];
    $statement = $pdo -> prepare("SELECT * from annonce where titre like :search");
    $statement -> bindValue(':search', "%$search%", PDO::PARAM_STR);
    $statement -> execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION["search"] = $result;
    header("Location: recherche.php");
}

?>

<div class="horizontal-menu">
    <nav class="navbar top-navbar col-lg-12 col-12 p-0">
        <div class="container">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="index.php"><img style="height: 60px; width: 110px" src="../image/jalegreatedealnav.png" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="index.php"><img style="height: 60px; width: 120px" src="../image/jalegreatedealnavmini.png" alt="logo" /></a>
                <!-- Logo jale great deal responsive -->
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <ul class="navbar-nav mr-lg-2">
                    <li class="nav-item d-none d-lg-block">
                        <a class="btn btn-warning" style="background-color: #DFD019" href="<?php if(isset($uid)): ?>nvAnnonces.php <?php else: ?> connexion.php <?php endif; ?>">
                            <i class="fa-regular fa-square-plus "></i><span class="menu-title "> Nouvelle Annonce</span>
                        </a>
                    </li>
                    <li class="nav-item nav-search d-none d-lg-block">
                        <div class="input-group">
                            <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                      <span class="input-group-text" id="search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                      </span>
                            </div>
                            <form action="" method="post">
                                <input type="text" class="form-control" id="navbar-search-input" name="search"  placeholder="Chercher une annonce" placeholder="Rechercher" aria-label="search" aria-describedby="search">
                            </form>
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav navbar-nav-right">


                    <li class="nav-item nav-logout ">
                        <a class="nav-link text-center text-dark mt-lg-4" href="<?php if(isset($uid)): ?>mesAnnonces.php <?php else: ?>connexion.php <?php endif; ?>">
                            <i class="fa-solid fa-list"><p  style="font-family: 'Courier New', Courier, monospace" class="d-lg-flex d-none note-icon">Mes annonces</p></i>
                        </a>
                    </li>

                    <li class="nav-item nav-logout ">
                        <a class="nav-link text-center text-dark mt-lg-4 count-indicator" href="<?php if(isset($uid)): ?>favoris.php <?php else: ?>connexion.php <?php endif; ?>">
                            <i class="fa-regular fa-heart fa-4x"><p style="font-family: 'Courier New', Courier, monospace" class="fw-bold d-none d-lg-flex note-icon">Favoris</p></i>
                            <?php
                            if(isset($uid)){
                                $reqNav = $pdo->prepare("SELECT * FROM favoris WHERE idu = ?");
                                $reqNav->execute(array($uid));
                                $count = $reqNav->rowCount();
                                if($count > 0){
                                    echo "<span class='count-symbol bg-danger'></span>";
                                }
                            } ?>
                        </a>
                    </li>

                    <li class="nav-item nav-settings ">
                        <a class="nav-link text-center text-dark mt-lg-4" href="<?php if(isset($uid)): ?>message.php <?php else: ?> connexion.php <?php endif; ?>">
                            <i class="fa-regular fa-comments"><p style="font-family: 'Courier New', Courier, monospace" class="fw-bold d-none d-lg-flex note-icon">Messages</p></i>
                        </a>
                    </li>
                    <?php if(isset($uid)): ?>
                        <li class="nav-item nav-profile dropdown">

                            <a class="nav-link " id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="nav-profile-img">
                                    <img src="<?php if($infoUser["avatar"] == null){ echo "../image/avatarbasique.png";}else{ ?>../<?= $infoUser["avatar"] ?><?php } ?>" alt="image">
                                    <span class="availability-status online"></span>
                                </div>
                                <div class="nav-profile-text">
                                    <p class="text-black"><?= $infoUser["prenom"] ?> <?= $infoUser["nom"] ?></p>
                                </div>
                                <i class="fa-solid fa-chevron-down mx-1"></i>
                            </a>
                            <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                                <a class="dropdown-item" href="profil.php">
                                    <i class="fa-solid fa-user"></i> Profil</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="deconnexion.php">
                                    <i class="fa-solid fa-right-from-bracket"></i> Déconnexion </a>
                            </div>
                        </li>

                    <?php else: ?>

                        <li class="nav-item nav-logout ">
                            <a class="nav-link text-center text-dark mt-lg-4" href="connexion.php">
                                <i class="fa-regular fa-user"><br><p style="font-family: 'Courier New', Courier, monospace" class="fw-bold d-none d-lg-flex note-icon">Connexion</p></i>

                            </a>
                        </li>

                    <?php endif; ?>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center mx-2" type="button" data-toggle="horizontal-menu-toggle">
                    <i class="fa-solid fa-bars text-dark"></i>
                </button>
            </div>
        </div>
    </nav>
    <nav class="bottom-navbar" style="background-color: #DFD019">
        <div class="container">
            <ul class="nav page-navigation">
                <li class="nav-item <?= $all ?>">
                    <a class="nav-link" href="categorie.php?idcategorie=1">
                        <i class="fa-solid fa-heart mx-2"></i>
                        <span class="menu-title">All</span>
                    </a>
                </li>
                <li class="nav-item <?= $children ?>">
                    <a class="nav-link" href="categorie.php?idcategorie=2">
                        <i class="fa-solid fa-user-secret mx-2"></i>
                        <span class="menu-title">Children</span>
                    </a>
                </li>
                <li class="nav-item <?= $teenager ?>">
                    <a class="nav-link" href="categorie.php?idcategorie=3">
                        <i class="fa-solid fa-rocket mx-2"></i>
                        <span class="menu-title">Teenager</span>
                    </a>
                </li>
                <li class="nav-item <?= $parents ?>">
                    <a class="nav-link" href="categorie.php?idcategorie=4">
                        <i class="fa-solid fa-feather-pointed mx-2"></i>
                        <span class="menu-title">Parents</span>
                    </a>
                </li>
                <li class="nav-item <?= $professionals ?>">
                    <a class="nav-link" href="categorie.php?idcategorie=5">
                        <i class="fa-solid fa-earth-europe mx-2"></i>
                        <span class="menu-title">Professionals</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>