
<?= $this->extend('admin/includes/base') ?>

<?= $this->section('title') ?>
    Online recruitment INS
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="dasbord_section">
    <div class="row">
        <div class="col-xl-12 col-md-12 col-12 dasbord_subbox">   
            <div class="right_amountbox">
                <div class="right_txtbox">
                    <div class="user_box">
                        <h4>Total Inscrit</h4>
                    </div>
                    <h5><?= $count;?></h5>
                </div>
                <div class="right_txtbox1">
                    <div class="user_box">
                        <h4>Total Homme</h4>
                    </div>
                    <h5><?= $homme;?></h5>
                </div>
                <div class="right_txtbox2">
                    <div class="user_box">
                        <h4>Total Femme</h4>
                    </div>
                    <h5><?= $femme;?></h5>
                </div>
                <div class="right_txtbox3">
                    <div class="user_box">
                        <h4>Quotas</h4>
                    </div>
                    <h6>150</h6>
                </div>
            </div>
        </div>

        <div class="col-12 mt-3">  

            <?php foreach ($person as $key => $value):?>
                <div class="row border-bottom">
                    <div class="col-2 image"> 
                    <?php
                        $image_path = 'uploads/files/'.$value->photo;
                        if(file_exists($image_path)) {
                            echo '<img src="' .base_url($image_path).'" alt="PHOTO ..." class="rounded">';
                        }else {
                            echo'<img src="'.base_url('assets/images/logo-rgph4.jpg').'" alt="PHOTO ..." class="img-flui" class="rounded" />';
                        }
                    ?>                         
                    </div>
                    <div class="col-4">
                        <div class="row mb-1"> <div class="col font-weight-bold"> Matricule </div> <div class="col"> <?= $value->matricule ?> </div> </div>
                        <div class="row mb-1"> <div class="col font-weight-bold"> Prénoms </div> <div class="col"> <?= $value->name ?> </div> </div>
                        <div class="row mb-1"> <div class="col font-weight-bold"> Nom </div> <div class="col"> <?= $value->last_name ?> </div> </div>
                        <div class="row mb-1"> <div class="col font-weight-bold"> Sexe </div> <div class="col"> <?= $value->sexe ?> </div> </div>
                        <div class="row mb-1"> <div class="col font-weight-bold"> Phone </div> <div class="col"> <?= $value->contact1 ?> </div> </div>
                        <div class="row mb-1"> <div class="col font-weight-bold"> Email </div> <div class="col"> <?= $value->email ?> </div> </div>
                        <div class="row mb-1"> <div class="col font-weight-bold"> Niveaux d'Etudes </div> <div class="col"> <?= $value->niveau_etude ?> </div> </div>
                    </div>
                    <div class="col-4">
                        <div class="row mb-1"> <div class="col font-weight-bold"> Langues Parlées </div> <div class="col"> <?= $value->langue1.'/'.$value->langue2.'/'.$value->langue3 ?> </div> </div>
                        <div class="row mb-1"> <div class="col font-weight-bold"> Experiences </div> <div class="col"> <?= $value->exp_intitule_poste ?> </div> </div>
                    </div>
                    <div class="col-2">
                        <div class="row mb-1"> 
                            <div class="col"> 
                                <div class="font-weight-bold text-center">
                                    <h4 class="display-6">NOTE</h4> 
                                    <h2 class="display-6 font-weight-bold text-primary"><?= $value->note ?></h2> 
                                </div>
                                <div class="">
                                    <a href="<?= base_url("uploads/files/".$value->cv) ?>" target="_blank">CV</a> , 
                                    <a href="<?= base_url("uploads/files/".$value->cni) ?>" target="_blank">Pièce d'Identité</a> , 
                                    <a href="<?= base_url("uploads/files/".$value->certifmedical) ?>" target="_blank">Certificat Médical</a> , 
                                    <a href="<?= base_url("uploads/files/".$value->certifresidence) ?>" target="_blank">Certificat de Residence</a>,
                                    <a href="<?= base_url("uploads/files/".$value->casier) ?>" target="_blank">Casier Judiciaire</a>,
                                    <a href="<?= base_url("uploads/files/".$value->attestcollecte) ?>" target="_blank">Attestation d'Expérience</a>,
                                    <a href="<?= base_url("uploads/files/".$value->doc_last_diplome) ?>" target="_blank">Diplome d'Etude</a>,
                                </div>
                            </div> 
                        </div>                              
                    </div>
                </div>
            <?php endforeach;?>     
        </div>            
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    // $(document).ready(function () {
    //     $('#example').DataTable();
    // });
</script>
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    .image img{
        max-width:150px;
        max-height:150px;
    }
</style>
<?= $this->endSection() ?>








































