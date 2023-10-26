<?php error_reporting(0); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?= $this->renderSection('title') ?> </title>
    <!--bootstrap links-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!--google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&display=swap" rel="stylesheet">
    <!--css link-->
    <link rel="stylesheet" href="<?= base_url() ?>/admin_css/style.css">
    <!--owl slider link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.2.0/classic/ckeditor.js"></script>
    <?php $this->renderSection('style');?>
</head>
<body>
<div class="container-fluid">
    <header class="header_sc" style="border-radius:10px;">
        <div class="row">
            <div class="col">
                <nav class="navbar navbar-expand-sm bg-infos navbar-dark">
                    <a class="navbar-brand" href="#">
                        <img src="<?= base_url('assets/images/logo-rgph4.jpg'); ?>" alt="Logo RGPH4" style="height:40px; border-radius:10px;">
                    </a>                
                    <ul class="navbar-nav">
                        <li class="nav-item active"> <a class="nav-link" href="/tableau">Tableau de bord</a> </li>                        
                        <li class="nav-item active dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Postulants</a>
                            <div class="dropdown-menu">
                                <a href="<?= site_url('tableau') ?>" class="dropdown-item">Liste des Candidats</a>
                                <a href="<?= site_url('postulants/export') ?>" class="dropdown-item">Télécharger la liste en xslx</a>
                                <!-- <a href="<?= site_url('download') ?>" class="dropdown-item">Télécharger les images</a>                                 -->
                            </div>
                        </li>
                        <li class="nav-item active dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Trombinoscopes</a>
                            <div class="dropdown-menu">
                                <a href="<?= site_url('trombinoscope/') ?>" class="dropdown-item">Listes des candidats</a>
                                <a href="<?= site_url('trombinoscope/download') ?>" class="dropdown-item">Télécharger le fichier pdf</a>
                            </div>
                        </li>
                        <li class="nav-item active dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Profils</a>
                            <div class="dropdown-menu">
                                <a href="<?= route_to('admin')?>" class="dropdown-item">Deconnexion</a>
                            </div>
                        </li>
                        
                    </ul>               
                </nav>
            </div>
        </div>
    </header>

    <?php $this->renderSection('content');?>

    <footer class="defpooter_section">
        <div class="row">
            <div class="col-12">
                <p>Copyright © all rights reserved. developed by <b>INS GUINEE</b></p>
            </div>
        </div>
    </footer>
</div>
<!--bootstrap links-->

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<!--owl slider link-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">

<?php $this->renderSection('script');?>

<script>
    $(document).ready(function (){

    });
</script>

</body>

</html>