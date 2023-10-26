<div style="display: block;text-align: center;background: #2b542c;padding: 5px;color:white;font-weight: bold;  font-family: 'Calibri', sans-serif; ">TERMES DE REFERENCE (TDR)</div>

<div class="top-10" style="word-wrap: break-word!important; font-family: 'Calibri', sans-serif; font-size:17px;">
<?php  
    if($projet['id']==12){ 
        // TECHNICIEN LABORATOIRE CARTOGRAPHIE
        echo $this->include('avis/avis1.php'); 
    }
    else if($projet['id']==17){ 
        // AGENT DE COLLECTE DE LA CARTOGRAPHIE CENSITAIRE
        echo $this->include('avis/avis2.php'); 
    }
    else if($projet['id']==3){ 
        echo $this->include('avis/avis3.php'); 
    }
    else if($projet['id']==4){ 
        echo $this->include('avis/avis4.php'); 
    }
    else if($projet['id']==5){ 
        echo $this->include('avis/avis5.php'); 
    }
    else echo $projet['contrat'];
?>

</div>

<div id="<?php echo $projet['id'];?>" style="margin:0; margin-top:20px;padding-bottom:10px; font-weight:bold;  font-family: 'Calibri', sans-serif; font-size:17px;">
    <input type="checkbox" name="lucontrat" value="1" required>J'ai lu le contrat et j'approuve<br>
</div>
