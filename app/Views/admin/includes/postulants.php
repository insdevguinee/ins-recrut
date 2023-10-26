
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

        <div class="col-xl-12 col-md-12 col-12">   
            <div class="table_box">                   
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Matricules</th>
                            <th>Noms</th>
                            <th>Prenoms</th>
                            <th>Niveaux d'Etudes</th>
                            <th>Sexes</th>
                            <th>Contacts</th>
                            <th>Langues Parlées</th>
                            <th>Experiences</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($person as $key => $value):?>
                        <tr>
                            <td><?= $value->matricule ;?></td>
                            <td><?= $value->name;?></td>
                            <td><?= $value->last_name;?></td>
                            <td><?= $value->niveau_etude;?></td>
                            <td><?= $value->sexe; ?></td>
                            <td><?= $value->contact1;?></td>
                            <td><?= $value->langue1.'/'.$value->langue2.'/'.$value->langue3;?></td>
                            <td><?= $value->exp_intitule_poste;?></td>
                            <td><?= $value->note;?></td>
                        </tr>                            
                        <?php endforeach;?>                        
                    </tbody>                    
                </table>
            </div>
        </div>            
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>
<?= $this->endSection() ?>








































