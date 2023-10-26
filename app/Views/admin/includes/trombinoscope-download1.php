
<?= $this->extend('admin/includes/base') ?>

<?= $this->section('title') ?>
    Online recruitment INS
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="dasbord_section">
    <div class="row">
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
    window.print();
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








































