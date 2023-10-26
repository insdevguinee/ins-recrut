<style>
    .h3{ color:black; }
</style>

<!-- <div id="sectionAimprimer" class="container" style="margin:auto; padding-top:30px; padding-bottom:20px;background-color:#f7f7f9; margin-bottom:20px"> -->
<div id="sectionAimprimer" class="container">
    <div id="pimprime">       
        <?php if($hasExiste == true): ?>
            <div class="row">
                <div class="col-xs-12">
                    <?php $sexe_ac = ""; $title = "M."; if($data['sexe']==2){ $sexe_ac = "e"; $title = "Mme"; } ?>
                    <?php if($data['is_admited'] == 1): ?>
                        <div class="row">
                            <div class="col-xs-12 text-center text-success"> 
                                <h3 class="h1"> <strong>Féliciation</strong> 
                                <?= $title ?> <?= strtoupper($data['last_name']); ?>! <br> vous êtes retenu<?= $sexe_ac ?> pour participer à la formation des 
                                <strong><?= strtolower($data['NomProjet']); ?></strong> </h3> 
                            </div>    
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <div class="col-xs-12 text-center text-danger"> 
                                <h3 class="h1"> <strong>Desolé</strong> <?= $title ?> <?= strtoupper($data['last_name']); ?>! <br> votre candidatrue n'a pas été acceptée </h3> 
                            </div>    
                        </div>                           
                    <?php endif; ?>
                </div>
            </div>
            <div class="top-30 row">
                <div class="col-sm-7">
                    <div class="row">
                        <div class="col-xs-5"> <h3 class="h3">Matricule</h3> </div>
                        <div class="col-xs-7"> <h3 class="h3"> <?= strtoupper($data['matricule']); ?> </h3> </div>    
                    </div>

                    <div class="row">
                        <div class="col-xs-5"> <h3 class="h3">Prénom (s)</h3> </div>
                        <div class="col-xs-7"> <h3 class="h3"> <?= strtoupper($data['name']); ?> </h3> </div>    
                    </div>

                    <div class="row">
                        <div class="col-xs-5"> <h3 class="h3">Nom de Famille</h3> </div>
                        <div class="col-xs-7"> <h3 class="h3"> <?= strtoupper($data['last_name']); ?> </h3> </div>    
                    </div>

                    <div class="row">
                        <div class="col-xs-5"> <h3 class="h3">Téléphone</h3> </div>
                        <div class="col-xs-7"> <h3 class="h3"> <?= $data['contact1']; ?> </h3> </div>    
                    </div>

                    <div class="row">
                        <div class="col-xs-5"> <h3 class="h3">Email</h3> </div>
                        <div class="col-xs-7"> <h3 class="h3"> <?= strtolower($data['email']); ?> </h3> </div>    
                    </div>

                    <div class="row">
                        <div class="col-xs-5"> <h3 class="h3">N° Pièce</h3> </div>
                        <div class="col-xs-7"> <h3 class="h3"> <?= strtoupper($data['numero_cni']); ?> </h3> </div>    
                    </div>

                    <div class="row">
                        <div class="col-xs-12 text-center"> 
                        
                        <img src="<?= base_url();?>uploads/files/<?= $data['photo'];?>" alt="Loading..." height="100" width="100"> 
                        </div>    
                    </div>         
                        
                    <div class="form-group">
                        <canvas id="canvasTarget" width="150" height="150"></canvas> 
                    </div>
                </div>
                <div class="col-sm-5">
                    <h1 class="h1 text-center">
                    <?php if($data['is_admited']) {?>
                        <span>Veuillez prendre contact avec l'Unité des Ressources Humaines du Bureau Central du Recensement (BCR).</span>
                        <?php if($data['fonction_id'] == 0 && $data['is_admited'] == 1): ?>                        
                            <h1 class="text-danger text-center h2" style="padding:15px;"> <strong>Vous êtes sur la liste d'attente</strong> </h1>						
                        <?php endif; ?>
                        <?php } else{  ?>
                        <span class="rouge">Désolé, votre candidature n'a pas été acceptée veillez vous préparez pour la prochaine candidature</span>
                        <?php }   ?>
                        <br>
                        <?php if($data['fonction_id'] == 0 && $data['is_admited'] == 1): ?>                        
                            <h1 class="text-danger text-center h2" style="padding:15px;"> <strong>Vous êtes sur la liste d'attente</strong> </h1>						
                        <?php endif; ?>
                        <h3 class="text-center"> <a href="<?= url_to('index'); ?>" class=""> <i class="fa fa-reply"></i> Terminer </a> </h3> 
                    </h1>
                    <?php if($data['is_admited']) {?>
                        <div class="text-center m-5">
                            <a href="" class="btn btn-success btn-sm">
                                <i class="fa fa-file"></i>
                                Télécharger
                            </a>
                        </div>
                        <h1 class="text-success text-center h2" style="padding:15px;">Prière de venir avec ce récépissé</h1>
                    <?php }   ?>
                </div>

            </div>
        <?php else: ?>
            <div class="row"> 
                <div class="col-xs-12 text-center text-danger"> 
                    <h3 class="h2"> Votre candidature n'a pas été retrouver, veillez verifier votre coordonnée <br> (matricule, numéro de telephone ou email) </h3> 
                </div>              
            </div>              
            <div class="row"> 
                <div class="col-xs-12 text-center"> 
                    <h3> <a href="<?= url_to('checkapp'); ?>" class=""> <i class="fa fa-undo"></i> Reessayez </a> </h3> 
                </div>              
            </div>              
        <?php endif; ?>      
    </div>
</div>


<!--<script
	src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>-->
<script src="<?php echo base_url('assets/js/jspdf.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="<?php echo base_url('assets/js/jquery-barcode.js') ?>"></script>
<script>
	$(document).ready(function() {
		$('div#m_plateforme').css('display','none');
	});
	
    function imprimer(divName){
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

</script>

<script>
$(document).ready(function(){
	$("#imprimerRecepisse").click(function(){
		var htmlWidth = $("#pimprime").width();
		var htmlHeight = $("#pimprime").height();
		var pdfWidth = htmlWidth + (15 * 2);
		var pdfHeight = (pdfWidth * 1.5) + (15 * 2);
		
		var doc = new jsPDF('p', 'pt', [pdfWidth, pdfHeight]);	
		var pageCount = Math.ceil(htmlHeight / pdfHeight) - 1;	
	
		html2canvas($("#pimprime")[0], { allowTaint: true }).then(function(canvas) {
			canvas.getContext('2d');
	
			var image = canvas.toDataURL("image/png", 1.0);
			doc.addImage(image, 'PNG', 15, 15, htmlWidth, htmlHeight);	
	
			for (var i = 1; i <= pageCount; i++) {
				doc.addPage(pdfWidth, pdfHeight);
				doc.addImage(image, 'PNG', 15, -(pdfHeight * i)+15, htmlWidth, htmlHeight);
			}
			
		    doc.save("recepisse.pdf");
		});
	});
});
</script>