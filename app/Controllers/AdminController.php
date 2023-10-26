<?php

namespace App\Controllers;
use \Mpdf\Mpdf;
use Dompdf\Dompdf;

use App\Controllers\BaseController;
use App\Models\AdminModel;
// use App\Models\AdminModel;
//se App\Models\CategoryModel;
use App\Models\PersonneRecrut;
//use App\Models\RecrutExperience;
use App\Models\RecrutEthnie;
 \Config\Services::validation();
 use PhpOffice\PhpSpreadsheet\Spreadsheet;
 use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminController extends BaseController
{
    public function index()
    {
        helper(['form']);
        return view('admin/login');
    }

    public function signin()
    {
        helper(['form']);
        $session = session();
        $userModel = new AdminModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        
        $data = $userModel->where('email', $email)->first();
        
        if($data){
            $pass = $data['password'];
            $authenticatePassword = password_verify($password, $pass);
            if(!$authenticatePassword){
                $ses_data = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);
                // return redirect()->to('admin/dashboard');
                return redirect()->to('/tableau');
            
            }
            else{
                $session->setFlashdata('msg', 'Password is incorrect.');
               // return redirect()->to('admin');
               return redirect()->to('/tableau');
            }

        }
        else{
            $session->setFlashdata('msg', 'Email does not exist.');
            return redirect()->to('admin');
        }
    }

    public function dashboardNew(){
        helper(['form']);
        $personnerecru_model = new PersonneRecrut();
       // $experience_model = new RecrutExperience();
        $recrutEthnie_model = new RecrutEthnie();
        $data = [];
        $data['count']  = $personnerecru_model->countAllResults();
        $data['homme']  = $personnerecru_model->countHommes();
        $data['femme']  = $personnerecru_model->countFemmes();
       // $data['person'] = $personnerecru_model->findAll();
        $this->UpdateNoteAgent();

        $sql = 'SELECT DISTINCT recrut_personne_recrut.contact1,recrut_personne_recrut.matricule, 
        recrut_personne_recrut.name,
        recrut_personne_recrut.note,
        recrut_personne_recrut.exp_intitule_poste,
        recrut_personne_recrut.last_name,
        CASE recrut_personne_recrut.niveau_etude
            WHEN "11" THEN "DEUG/BAC +2/LICENCE 2/BTS"
            WHEN "12" THEN "LICENCE 3 / BAC+3"
            WHEN "13" THEN "MAITRISE / MASTER 1 / BAC+4"
            WHEN "14" THEN "MASTER 2 / DEA / BAC+5"
            WHEN "15" THEN "DOCTORAT"
            ELSE "Autre"
        END AS niveau_etude,
        CASE recrut_personne_recrut.sexe
            WHEN "1" THEN "homme"
            ELSE "femme"
        END AS sexe,
        recrut_personne_recrut.statut_recrut,
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.first_langue_nat) AS langue1, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.second_langue_nat) AS langue2, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.third_langue_nat) AS langue3
       
        FROM recrut_personne_recrut,recrut_departement
        WHERE recrut_personne_recrut.departement3 = recrut_departement.CodDep
        ORDER BY recrut_personne_recrut.note DESC;';
     
        $data['person'] = $personnerecru_model->db->query($sql)->getResult();
        $result = $recrutEthnie_model->getValueByParameter($param);
        //var_dump($data);

        $data['admin_content'] = 'admin/dashboard';    
        
        
        
        // $logger = service('logger');
        // $logger->error($data);

        return view('tpl/index', $data);
        // return view('admin/includes/template', $data);
    }

    public function dashboard(){
        helper(['form']);
        $personnerecru_model = new PersonneRecrut();
       // $experience_model = new RecrutExperience();
        $recrutEthnie_model = new RecrutEthnie();
        $data = [];
        $data['count']  = $personnerecru_model->countAllResults();
        $data['homme']  = $personnerecru_model->countHommes();
        $data['femme']  = $personnerecru_model->countFemmes();
       // $data['person'] = $personnerecru_model->findAll();
        $this->UpdateNoteAgent();

        $sql = 'SELECT DISTINCT recrut_personne_recrut.contact1,recrut_personne_recrut.matricule, 
        recrut_personne_recrut.name,
        recrut_personne_recrut.note,
        recrut_personne_recrut.exp_intitule_poste,
        recrut_personne_recrut.last_name,
        CASE recrut_personne_recrut.niveau_etude
            WHEN "11" THEN "DEUG/BAC +2/LICENCE 2/BTS"
            WHEN "12" THEN "LICENCE 3 / BAC+3"
            WHEN "13" THEN "MAITRISE / MASTER 1 / BAC+4"
            WHEN "14" THEN "MASTER 2 / DEA / BAC+5"
            WHEN "15" THEN "DOCTORAT"
            ELSE "Autre"
        END AS niveau_etude,
        CASE recrut_personne_recrut.sexe
            WHEN "1" THEN "homme"
            ELSE "femme"
        END AS sexe,
        recrut_personne_recrut.statut_recrut,
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.first_langue_nat) AS langue1, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.second_langue_nat) AS langue2, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.third_langue_nat) AS langue3
       
        FROM recrut_personne_recrut,recrut_departement
        WHERE recrut_personne_recrut.departement3 = recrut_departement.CodDep
        ORDER BY recrut_personne_recrut.note DESC;';
     
        $data['person'] = $personnerecru_model->db->query($sql)->getResult();
        $result = $recrutEthnie_model->getValueByParameter($param);
        //var_dump($data);

        $data['admin_content'] = 'admin/dashboard';
        return view('admin/includes/template', $data);
    }

    public function logout()
    {
        session()->session_destroy();      
        redirect()->to(base_url('/admin'));
    }

    public function UpdateNoteAgent()
    {
        $personnerecru_model = new PersonneRecrut();
        $sql ='SELECT recrut_personne_recrut.matricule, recrut_personne_recrut.id,
        recrut_personne_recrut.exp_intitule_poste,
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.first_langue_nat) AS langue1, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.second_langue_nat) AS langue2, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.third_langue_nat) AS langue3,
        CASE recrut_personne_recrut.niveau_etude
            WHEN "11" THEN "DEUG/BAC +2/LICENCE 2/BTS"
            WHEN "12" THEN "LICENCE 3 / BAC+3"
            WHEN "13" THEN "MAITRISE / MASTER 1 / BAC+4"
            WHEN "14" THEN "MASTER 2 / DEA / BAC+5"
            WHEN "15" THEN "DOCTORAT"
            ELSE "Autre"
        END AS niveau_etude
                
        FROM recrut_personne_recrut,recrut_departement
        WHERE recrut_personne_recrut.departement3 = recrut_departement.CodDep';
        $data = $personnerecru_model->db->query($sql)->getResult();
    
        foreach ($data as $datas) {
            $pointsLangueLocale = $this->calculatePointsByLangues($datas->langue1, $datas->langue2, $datas->langue3);    
            $pointsTotal = $this->calculatePointsByNiveauEtude($datas->niveau_etude) + $this->calculatePointsByExperience($datas->exp_intitule_poste) + $pointsLangueLocale;
    
            $d = ['note' => $pointsTotal ,];
            
            // Corrected the variable name here, it should be `$personnerecru_model`
            $result = $personnerecru_model->update($datas->id, $d);    
        }
    }  

    public function category()
    {
        $data = [];
        $model = new CategoryModel();
        if($this->request->getMethod() == 'post'){
            $tableData = [
                'cat_name' => $this->request->getVar('category'),
            ];
            
            if($model->save($tableData)){
                $data['cat_data'] = true;
            }
        }
       
        $data['admin_content'] = 'admin/category';
        return view('admin/includes/template', $data);
    }

    public function export()
    {
        //$this->UpdateNoteAgent();
        $personnerecru_model = new PersonneRecrut(); 
        // $experience_model = new RecrutExperience();

        $sql = 'SELECT recrut_personne_recrut.matricule,
        recrut_personne_recrut.name,
        recrut_personne_recrut.note,
        recrut_personne_recrut.last_name,
        recrut_personne_recrut.departement3,recrut_departement.NomDep,
        recrut_personne_recrut.exp_intitule_poste,
        recrut_personne_recrut.exp_intitule_projet,
        recrut_personne_recrut.exp_structure,
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.first_langue_nat) AS langue1, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.second_langue_nat) AS langue2, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.third_langue_nat) AS langue3,
        CASE recrut_personne_recrut.niveau_etude
            WHEN "11" THEN "DEUG/BAC +2/LICENCE 2/BTS"
            WHEN "12" THEN "LICENCE 3 / BAC+3"
            WHEN "13" THEN "MAITRISE / MASTER 1 / BAC+4"
            WHEN "14" THEN "MASTER 2 / DEA / BAC+5"
            WHEN "15" THEN "DOCTORAT"
            ELSE "Autre"
        END AS niveau_etude,
        CASE recrut_personne_recrut.sexe
            WHEN "1" THEN "homme"
            ELSE "femme"
        END AS sexe,
        recrut_personne_recrut.NomProjet,
        recrut_personne_recrut.contact1,
        recrut_departement.NomDep
        FROM recrut_personne_recrut,recrut_departement
        WHERE recrut_personne_recrut.departement3 = recrut_departement.CodDep ORDER BY recrut_personne_recrut.note DESC;';
        
        $data = $personnerecru_model->db->query($sql)->getResult();
        $fileName = 'postulants'.uniqid().'.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // En-têtes des colonnes
        $sheet->setCellValue('A1', 'Matricule');
        $sheet->setCellValue('B1', 'NOM');
        $sheet->setCellValue('C1', 'PRENOMS');
        $sheet->setCellValue('D1', 'FONCTION');
        $sheet->setCellValue('E1', 'CONTACT');
        $sheet->setCellValue('F1', 'ZONE ACTIVITE');
        $sheet->setCellValue('G1', 'LANGUES');
        $sheet->setCellValue('H1', 'NIVEAU');
        $sheet->setCellValue('I1', 'EXPERIENCE');
        $sheet->setCellValue('J1', 'NOTE');

        $rows = 2;
        foreach ($data as $datas) {        
            $sheet->setCellValue('A' . $rows, $datas->matricule);
            $sheet->setCellValue('B' . $rows, $datas->name);
            $sheet->setCellValue('C' . $rows, $datas->last_name);
            $sheet->setCellValue('D' . $rows, $datas->NomProjet);
            $sheet->setCellValue('E' . $rows, $datas->contact1 );
            $sheet->setCellValue('F' . $rows, $datas->NomDep);
            //$sheet->setCellValue('G' . $rows, $datas->NomDep);
            $sheet->setCellValue('G' . $rows, $datas->langue1.'/'.$datas->langue2.'/'.$datas->langue3);
            $sheet->setCellValue('H' . $rows, $datas->niveau_etude);    
            $sheet->setCellValue('I' . $rows,  $datas->exp_intitule_poste);
            $sheet->setCellValue('j' . $rows,  $datas->note);
            $rows++;
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    } 

    public function calculatePointsByNiveauEtude($niveauEtude) {
        switch ($niveauEtude) {
            case "DEUG/BAC +2/LICENCE 2/BTS":
                return 20;
            case "LICENCE 3 / BAC+3":
                return 25;
            case "MAITRISE / MASTER 1 / BAC+4":
            case "MASTER 2 / DEA / BAC+5":
            case "DOCTORAT":
                return 30;               
            default:
                return 0; // Retourne 0 si le niveau d'étude n'est pas reconnu
        }
    }

    function calculatePointsByExperience($experience) {
        $experiences = preg_replace('/\s+/', '', $experience);
        if (isset($experiences) && !empty($experiences)) {
            return 50; // Retourne 50 points si la variable d'expérience contient au moins un élément
        } else {
            return 40; // Retourne 40 points si la variable d'expérience ne contient rien
        }
    }
    public function calculatePointsByExperiences($experience) {
        if (empty($experience)) {
            return 40; // Retourne 40 si l'expérience est vide
        } else {
            return 50; // Retourne 50 si le critère est  reconnu
        }
    
    }

    public function calculatePointsByLangueLocale($langueLocale1, $langueLocale2, $langueLocale3) {
        $languesDifferentes = $this->verifierLanguesDifferentes($langueLocale1, $langueLocale2, $langueLocale3);
    
        if (!$languesDifferentes) {
            return 0; // Retourne 0 si les langues ne sont pas différentes
        }
    
        switch ($langueLocale1) {
            case "Trois langues différentes":
                return 20;
            case "Deux langues":
                return 15;
            case "Une langue":
                return 10;
            default:
                return 0; // Retourne 0 si le critère n'est pas reconnu
        }
    }
    
    private function verifierLanguesDifferentes($langueLocale1, $langueLocale2, $langueLocale3) {
        $langues = array($langueLocale1, $langueLocale2, $langueLocale3);
        $languesDifferentes = count(array_unique($langues)) === count($langues);
        return $languesDifferentes;
    }

    function calculatePointsByLangues($langue1, $langue2, $langue3) {
        // Compter le nombre de langues différentes non vides
        $countLangues = count(array_unique(array_filter([$langue1, $langue2, $langue3])));    
        // Attribuer les points en fonction du nombre de langues différentes
        switch ($countLangues) {
            case 3:
                return 20;
            case 2:
                return 15;
            case 1:
                return 10;
            default:
                return 0; // Retourne 0 si aucune langue ou moins de 3 langues différentes
        }
    } 

    public function tableau(){
        helper(['form']);
        $personnerecru_model = new PersonneRecrut();
       // $experience_model = new RecrutExperience();
        $recrutEthnie_model = new RecrutEthnie();
        $data = [];
        $data['count']  = $personnerecru_model->countAllResults();
        $data['homme']  = $personnerecru_model->countHommes();
        $data['femme']  = $personnerecru_model->countFemmes();
       // $data['person'] = $personnerecru_model->findAll();
        $this->UpdateNoteAgent();

        $sql = 'SELECT DISTINCT recrut_personne_recrut.contact1,recrut_personne_recrut.matricule, 
        recrut_personne_recrut.name,
        recrut_personne_recrut.note,
        recrut_personne_recrut.exp_intitule_poste,
        recrut_personne_recrut.last_name,
        CASE recrut_personne_recrut.niveau_etude
            WHEN "11" THEN "DEUG/BAC +2/LICENCE 2/BTS"
            WHEN "12" THEN "LICENCE 3 / BAC+3"
            WHEN "13" THEN "MAITRISE / MASTER 1 / BAC+4"
            WHEN "14" THEN "MASTER 2 / DEA / BAC+5"
            WHEN "15" THEN "DOCTORAT"
            ELSE "Autre"
        END AS niveau_etude,
        CASE recrut_personne_recrut.sexe
            WHEN "1" THEN "homme"
            ELSE "femme"
        END AS sexe,
        recrut_personne_recrut.statut_recrut,
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.first_langue_nat) AS langue1, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.second_langue_nat) AS langue2, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.third_langue_nat) AS langue3
       
        FROM recrut_personne_recrut,recrut_departement
        WHERE recrut_personne_recrut.departement3 = recrut_departement.CodDep
        ORDER BY recrut_personne_recrut.note DESC;';
     
        $data['person'] = $personnerecru_model->db->query($sql)->getResult();
        // $result = $recrutEthnie_model->getValueByParameter($param);
        //var_dump($data);

        return view('admin/includes/postulants', $data);
        // return view('admin/includes/base', $data);
    }

    public function trombinoscope(){
        helper(['form']);
        $personnerecru_model = new PersonneRecrut();
       // $experience_model = new RecrutExperience();
        $recrutEthnie_model = new RecrutEthnie();
        $data = [];
        $data['count']  = $personnerecru_model->countAllResults();
        $data['homme']  = $personnerecru_model->countHommes();
        $data['femme']  = $personnerecru_model->countFemmes();
       // $data['person'] = $personnerecru_model->findAll();
        $this->UpdateNoteAgent();

        $sql = '
        SELECT DISTINCT recrut_personne_recrut.contact1,recrut_personne_recrut.matricule, 
        recrut_personne_recrut.name,
        recrut_personne_recrut.note,
        recrut_personne_recrut.exp_intitule_poste,
        recrut_personne_recrut.last_name,
        CASE recrut_personne_recrut.niveau_etude
            WHEN "11" THEN "DEUG/BAC +2/LICENCE 2/BTS"
            WHEN "12" THEN "LICENCE 3 / BAC+3"
            WHEN "13" THEN "MAITRISE / MASTER 1 / BAC+4"
            WHEN "14" THEN "MASTER 2 / DEA / BAC+5"
            WHEN "15" THEN "DOCTORAT"
            ELSE "Autre"
        END AS niveau_etude,
        CASE recrut_personne_recrut.sexe
            WHEN "1" THEN "homme"
            ELSE "femme"
        END AS sexe,
        recrut_personne_recrut.statut_recrut,
        recrut_personne_recrut.email,
        recrut_personne_recrut.photo,
        recrut_personne_recrut.date_naiss,
        recrut_personne_recrut.lieu_naiss,
        recrut_personne_recrut.last_diplome,
        recrut_personne_recrut.doc_last_diplome,
        recrut_personne_recrut.nomtuteurlegal,
        recrut_personne_recrut.contact2,
        recrut_personne_recrut.nomtuteurlegal2,
        recrut_personne_recrut.contact3,
        recrut_personne_recrut.cv,
        recrut_personne_recrut.cni,
        recrut_personne_recrut.codeProjet,
        recrut_personne_recrut.status,
        recrut_personne_recrut.dateheureinscrip,
        recrut_personne_recrut.region,
        recrut_personne_recrut.sousprefecture,
        recrut_personne_recrut.departement2,
        recrut_personne_recrut.sousprefecture2,
        recrut_personne_recrut.departement4,
        recrut_personne_recrut.sousprefecture4,
        recrut_personne_recrut.sousprefecture3,
        recrut_personne_recrut.possedenumtel,
        recrut_personne_recrut.isDisponible,
        recrut_personne_recrut.hasAcceptDisponible,
        recrut_personne_recrut.cnituteurlegal,
        recrut_personne_recrut.declarahonneur,
        recrut_personne_recrut.codebonneconduite,
        recrut_personne_recrut.contrat,
        recrut_personne_recrut.type_piece,
        recrut_personne_recrut.numero_cni,
        recrut_personne_recrut.namepere,
        recrut_personne_recrut.namemere,
        recrut_personne_recrut.hasexpcollecte,
        recrut_personne_recrut.exp_structure,
        recrut_personne_recrut.exp_annee,
        recrut_personne_recrut.exp_intitule_poste,
        recrut_personne_recrut.exp_intitule_projet,
        recrut_personne_recrut.date_jour_decla,
        recrut_personne_recrut.confirmlieuaffectation,
        recrut_personne_recrut.certifresidence,
        recrut_personne_recrut.cand_retenu,
        recrut_personne_recrut.is_confirm,
        recrut_personne_recrut.NomProjet,
        recrut_personne_recrut.titre_poste,
        recrut_personne_recrut.choix_projet,
        recrut_personne_recrut.casier,
        recrut_personne_recrut.certifmedical,
        recrut_personne_recrut.attestcollecte,
        recrut_personne_recrut.note2,
        recrut_personne_recrut.rank,
        recrut_personne_recrut.fonction_id,
        recrut_personne_recrut.is_admited,
        recrut_personne_recrut.admited_at,
        recrut_personne_recrut.nbrinsert,

        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.first_langue_nat) AS langue1, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.second_langue_nat) AS langue2, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.third_langue_nat) AS langue3
       
        FROM recrut_personne_recrut,recrut_departement
        WHERE recrut_personne_recrut.departement3 = recrut_departement.CodDep
        ORDER BY recrut_personne_recrut.note DESC';
     
        $data['person'] = $personnerecru_model->db->query($sql)->getResult();
        // $result = $recrutEthnie_model->getValueByParameter($param);
        //var_dump($data);

        return view('admin/includes/trombinoscope', $data);
    }


    public function generatePdf1() {
        $autoload['helper'] = array('url');
        $helper = service('html');

        $mpdf = new Mpdf();

        $data = array(
            'title' => 'Mon Document PDF',
            'content' => 'Contenu de votre document PDF ici.'
        );

        // Chargez la vue HTML en tant que contenu du PDF
        // $html = $this->load->view('pdf_template', $data, true);
        $html =  view('pdf_template', $data);

        // Générez le PDF
        $mpdf->WriteHTML($html);
        $mpdf->Output('nom_du_fichier.pdf', 'D'); // Téléchargez le PDF directement
    }

    public function generatePdf()
    {
        $dompdf = new Dompdf();
        $data = [
            'imageSrc'    => $this->imageToBase64(ROOTPATH.'/public/assets/images/logo-rgph4.jpg'),
            'name'         => 'John Doe',
            'address'      => 'USA',
            'mobileNumber' => '000000000',
            'email'        => 'john.doe@email.com'
        ];

        $html = view('resume', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); 
        $dompdf->render();
        $dompdf->stream('resume.pdf', [ 'Attachment' => false ]);
    }

    private function imageToBase64($path) {

        $logger = service('logger');


        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $logger->error($path);
        // $logger->error($type);
        // $logger->error($data);
        $logger->error($base64);

        return $base64;
    }

    public function trombinoscopeDownload1(){        
        $personnerecru_model = new PersonneRecrut();
        $recrutEthnie_model = new RecrutEthnie();
        $data = [];
        $this->UpdateNoteAgent();

        $sql = '
        SELECT DISTINCT recrut_personne_recrut.contact1,recrut_personne_recrut.matricule, 
        recrut_personne_recrut.name,
        recrut_personne_recrut.note,
        recrut_personne_recrut.exp_intitule_poste,
        recrut_personne_recrut.last_name,
        CASE recrut_personne_recrut.niveau_etude
            WHEN "11" THEN "DEUG/BAC +2/LICENCE 2/BTS"
            WHEN "12" THEN "LICENCE 3 / BAC+3"
            WHEN "13" THEN "MAITRISE / MASTER 1 / BAC+4"
            WHEN "14" THEN "MASTER 2 / DEA / BAC+5"
            WHEN "15" THEN "DOCTORAT"
            ELSE "Autre"
        END AS niveau_etude,
        CASE recrut_personne_recrut.sexe
            WHEN "1" THEN "homme"
            ELSE "femme"
        END AS sexe,
        recrut_personne_recrut.statut_recrut,
        recrut_personne_recrut.email,
        recrut_personne_recrut.photo,
        recrut_personne_recrut.date_naiss,
        recrut_personne_recrut.lieu_naiss,
        recrut_personne_recrut.last_diplome,
        recrut_personne_recrut.doc_last_diplome,
        recrut_personne_recrut.nomtuteurlegal,
        recrut_personne_recrut.contact2,
        recrut_personne_recrut.nomtuteurlegal2,
        recrut_personne_recrut.contact3,
        recrut_personne_recrut.cv,
        recrut_personne_recrut.cni,
        recrut_personne_recrut.codeProjet,
        recrut_personne_recrut.status,
        recrut_personne_recrut.dateheureinscrip,
        recrut_personne_recrut.region,
        recrut_personne_recrut.sousprefecture,
        recrut_personne_recrut.departement2,
        recrut_personne_recrut.sousprefecture2,
        recrut_personne_recrut.departement4,
        recrut_personne_recrut.sousprefecture4,
        recrut_personne_recrut.sousprefecture3,
        recrut_personne_recrut.possedenumtel,
        recrut_personne_recrut.isDisponible,
        recrut_personne_recrut.hasAcceptDisponible,
        recrut_personne_recrut.cnituteurlegal,
        recrut_personne_recrut.declarahonneur,
        recrut_personne_recrut.codebonneconduite,
        recrut_personne_recrut.contrat,
        recrut_personne_recrut.type_piece,
        recrut_personne_recrut.numero_cni,
        recrut_personne_recrut.namepere,
        recrut_personne_recrut.namemere,
        recrut_personne_recrut.hasexpcollecte,
        recrut_personne_recrut.exp_structure,
        recrut_personne_recrut.exp_annee,
        recrut_personne_recrut.exp_intitule_poste,
        recrut_personne_recrut.exp_intitule_projet,
        recrut_personne_recrut.date_jour_decla,
        recrut_personne_recrut.confirmlieuaffectation,
        recrut_personne_recrut.certifresidence,
        recrut_personne_recrut.cand_retenu,
        recrut_personne_recrut.is_confirm,
        recrut_personne_recrut.NomProjet,
        recrut_personne_recrut.titre_poste,
        recrut_personne_recrut.choix_projet,
        recrut_personne_recrut.casier,
        recrut_personne_recrut.certifmedical,
        recrut_personne_recrut.note2,
        recrut_personne_recrut.rank,
        recrut_personne_recrut.fonction_id,
        recrut_personne_recrut.is_admited,
        recrut_personne_recrut.admited_at,
        recrut_personne_recrut.nbrinsert,

        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.first_langue_nat) AS langue1, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.second_langue_nat) AS langue2, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.third_langue_nat) AS langue3
       
        FROM recrut_personne_recrut,recrut_departement
        WHERE recrut_personne_recrut.departement3 = recrut_departement.CodDep
        ORDER BY recrut_personne_recrut.note DESC LIMIT 2';
     
        $data['person'] = $personnerecru_model->db->query($sql)->getResult();
        // $result = $recrutEthnie_model->getValueByParameter($param);       

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L',  // 'P' pour Portrait, 'L' pour Paysage
        ]);

        $logger = service('logger');
        $logger->error($data);

        $fileName = 'trombinoscope'.uniqid().'.pdf';

        $html = view('admin/includes/trombinoscope-download', $data);
        // $html = view('pdf_template', $data);
        $mpdf->WriteHTML($html);
        $mpdf->Output($fileName, 'D');  
    }



    public function trombinoscopeDownload()
    {
        helper(['form']);
        $personnerecru_model = new PersonneRecrut();
       // $experience_model = new RecrutExperience();
        $recrutEthnie_model = new RecrutEthnie();
        $data = [];
        $data['count']  = $personnerecru_model->countAllResults();
        $data['homme']  = $personnerecru_model->countHommes();
        $data['femme']  = $personnerecru_model->countFemmes();
       // $data['person'] = $personnerecru_model->findAll();
        $this->UpdateNoteAgent();

        $sql = '
        SELECT DISTINCT recrut_personne_recrut.contact1,recrut_personne_recrut.matricule, 
        recrut_personne_recrut.name,
        recrut_personne_recrut.note,
        recrut_personne_recrut.exp_intitule_poste,
        recrut_personne_recrut.last_name,
        CASE recrut_personne_recrut.niveau_etude
            WHEN "11" THEN "DEUG/BAC +2/LICENCE 2/BTS"
            WHEN "12" THEN "LICENCE 3 / BAC+3"
            WHEN "13" THEN "MAITRISE / MASTER 1 / BAC+4"
            WHEN "14" THEN "MASTER 2 / DEA / BAC+5"
            WHEN "15" THEN "DOCTORAT"
            ELSE "Autre"
        END AS niveau_etude,
        CASE recrut_personne_recrut.sexe
            WHEN "1" THEN "homme"
            ELSE "femme"
        END AS sexe,
        recrut_personne_recrut.statut_recrut,
        recrut_personne_recrut.email,
        recrut_personne_recrut.photo,
        recrut_personne_recrut.date_naiss,
        recrut_personne_recrut.lieu_naiss,
        recrut_personne_recrut.last_diplome,
        recrut_personne_recrut.doc_last_diplome,
        recrut_personne_recrut.nomtuteurlegal,
        recrut_personne_recrut.contact2,
        recrut_personne_recrut.nomtuteurlegal2,
        recrut_personne_recrut.contact3,
        recrut_personne_recrut.cv,
        recrut_personne_recrut.cni,
        recrut_personne_recrut.codeProjet,
        recrut_personne_recrut.status,
        recrut_personne_recrut.dateheureinscrip,
        recrut_personne_recrut.region,
        recrut_personne_recrut.sousprefecture,
        recrut_personne_recrut.departement2,
        recrut_personne_recrut.sousprefecture2,
        recrut_personne_recrut.departement4,
        recrut_personne_recrut.sousprefecture4,
        recrut_personne_recrut.sousprefecture3,
        recrut_personne_recrut.possedenumtel,
        recrut_personne_recrut.isDisponible,
        recrut_personne_recrut.hasAcceptDisponible,
        recrut_personne_recrut.cnituteurlegal,
        recrut_personne_recrut.declarahonneur,
        recrut_personne_recrut.codebonneconduite,
        recrut_personne_recrut.contrat,
        recrut_personne_recrut.type_piece,
        recrut_personne_recrut.numero_cni,
        recrut_personne_recrut.namepere,
        recrut_personne_recrut.namemere,
        recrut_personne_recrut.hasexpcollecte,
        recrut_personne_recrut.exp_structure,
        recrut_personne_recrut.exp_annee,
        recrut_personne_recrut.exp_intitule_poste,
        recrut_personne_recrut.exp_intitule_projet,
        recrut_personne_recrut.date_jour_decla,
        recrut_personne_recrut.confirmlieuaffectation,
        recrut_personne_recrut.certifresidence,
        recrut_personne_recrut.cand_retenu,
        recrut_personne_recrut.is_confirm,
        recrut_personne_recrut.NomProjet,
        recrut_personne_recrut.titre_poste,
        recrut_personne_recrut.choix_projet,
        recrut_personne_recrut.casier,
        recrut_personne_recrut.certifmedical,
        recrut_personne_recrut.attestcollecte,
        recrut_personne_recrut.note2,
        recrut_personne_recrut.rank,
        recrut_personne_recrut.fonction_id,
        recrut_personne_recrut.is_admited,
        recrut_personne_recrut.admited_at,
        recrut_personne_recrut.nbrinsert,

        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.first_langue_nat) AS langue1, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.second_langue_nat) AS langue2, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.third_langue_nat) AS langue3
       
        FROM recrut_personne_recrut,recrut_departement
        WHERE recrut_personne_recrut.departement3 = recrut_departement.CodDep
        ORDER BY recrut_personne_recrut.note DESC';
     
        $data['person'] = $personnerecru_model->db->query($sql)->getResult();
        // $result = $recrutEthnie_model->getValueByParameter($param);           

        return view('admin/includes/trombinoscope-download1', $data);
    }

    public function trombinoscopeDownload2()
    {
        $personnerecru_model = new PersonneRecrut();
        $recrutEthnie_model = new RecrutEthnie();
        $data = [];
        $this->UpdateNoteAgent();

        $sql = '
        SELECT DISTINCT recrut_personne_recrut.contact1,recrut_personne_recrut.matricule, 
        recrut_personne_recrut.name,
        recrut_personne_recrut.note,
        recrut_personne_recrut.exp_intitule_poste,
        recrut_personne_recrut.last_name,
        CASE recrut_personne_recrut.niveau_etude
            WHEN "11" THEN "DEUG/BAC +2/LICENCE 2/BTS"
            WHEN "12" THEN "LICENCE 3 / BAC+3"
            WHEN "13" THEN "MAITRISE / MASTER 1 / BAC+4"
            WHEN "14" THEN "MASTER 2 / DEA / BAC+5"
            WHEN "15" THEN "DOCTORAT"
            ELSE "Autre"
        END AS niveau_etude,
        CASE recrut_personne_recrut.sexe
            WHEN "1" THEN "homme"
            ELSE "femme"
        END AS sexe,
        recrut_personne_recrut.statut_recrut,
        recrut_personne_recrut.email,
        recrut_personne_recrut.photo,
        recrut_personne_recrut.date_naiss,
        recrut_personne_recrut.lieu_naiss,
        recrut_personne_recrut.last_diplome,
        recrut_personne_recrut.doc_last_diplome,
        recrut_personne_recrut.nomtuteurlegal,
        recrut_personne_recrut.contact2,
        recrut_personne_recrut.nomtuteurlegal2,
        recrut_personne_recrut.contact3,
        recrut_personne_recrut.cv,
        recrut_personne_recrut.cni,
        recrut_personne_recrut.codeProjet,
        recrut_personne_recrut.status,
        recrut_personne_recrut.dateheureinscrip,
        recrut_personne_recrut.region,
        recrut_personne_recrut.sousprefecture,
        recrut_personne_recrut.departement2,
        recrut_personne_recrut.sousprefecture2,
        recrut_personne_recrut.departement4,
        recrut_personne_recrut.sousprefecture4,
        recrut_personne_recrut.sousprefecture3,
        recrut_personne_recrut.possedenumtel,
        recrut_personne_recrut.isDisponible,
        recrut_personne_recrut.hasAcceptDisponible,
        recrut_personne_recrut.cnituteurlegal,
        recrut_personne_recrut.declarahonneur,
        recrut_personne_recrut.codebonneconduite,
        recrut_personne_recrut.contrat,
        recrut_personne_recrut.type_piece,
        recrut_personne_recrut.numero_cni,
        recrut_personne_recrut.namepere,
        recrut_personne_recrut.namemere,
        recrut_personne_recrut.hasexpcollecte,
        recrut_personne_recrut.exp_structure,
        recrut_personne_recrut.exp_annee,
        recrut_personne_recrut.exp_intitule_poste,
        recrut_personne_recrut.exp_intitule_projet,
        recrut_personne_recrut.date_jour_decla,
        recrut_personne_recrut.confirmlieuaffectation,
        recrut_personne_recrut.certifresidence,
        recrut_personne_recrut.cand_retenu,
        recrut_personne_recrut.is_confirm,
        recrut_personne_recrut.NomProjet,
        recrut_personne_recrut.titre_poste,
        recrut_personne_recrut.choix_projet,
        recrut_personne_recrut.casier,
        recrut_personne_recrut.certifmedical,
        recrut_personne_recrut.note2,
        recrut_personne_recrut.rank,
        recrut_personne_recrut.fonction_id,
        recrut_personne_recrut.is_admited,
        recrut_personne_recrut.admited_at,
        recrut_personne_recrut.nbrinsert,

        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.first_langue_nat) AS langue1, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.second_langue_nat) AS langue2, 
        (SELECT libelle FROM recrut_ethnie WHERE id = recrut_personne_recrut.third_langue_nat) AS langue3
       
        FROM recrut_personne_recrut,recrut_departement
        WHERE recrut_personne_recrut.departement3 = recrut_departement.CodDep
        ORDER BY recrut_personne_recrut.note DESC LIMIT 2';
     
        $data['person'] = $personnerecru_model->db->query($sql)->getResult();
        // $result = $recrutEthnie_model->getValueByParameter($param);       

        $fileName = 'trombinoscope'.uniqid().'.pdf';

        $dompdf = new Dompdf();
        // $html = view('resume', $data);
        $html = view('admin/includes/trombinoscope', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); 
        $dompdf->render();
        $dompdf->stream($fileName, [ 'Attachment' => false ]);
    }

}
