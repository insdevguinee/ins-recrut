<?php

namespace App\Controllers;
use App\Models\ProjetModel;
use App\Models\EhnieModel;
use App\Models\DepartementModel;
use App\Models\SPrefectureModel;
use App\Models\PersoRecrutModel;

use App\Models\AdminModel;
use App\Models\PersonneRecrut;
use App\Models\RecrutEthnie;
\Config\Services::validation();
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Home extends BaseController
{
    public function __construct()
    {
        helper(['form']);
        $this->session = \Config\Services::session();
    }   

    public function dashboard(){
        helper(['form']);
        $personnerecru_model = new PersonneRecrut();
        $recrutEthnie_model = new RecrutEthnie();
        $data = [];
        $data['count']  = $personnerecru_model->countAllResults();
        $data['homme']  = $personnerecru_model->countHommes();
        $data['femme']  = $personnerecru_model->countFemmes();
        
        $sql = 'SELECT DISTINCT r.matricule, r.note, r.codeProjet, r.NomProjet, r.titre_poste, r.departement, 
        r.departement2, r.departement3, r.departement4, r.sousprefecture, r.sousprefecture2, r.sousprefecture3, 
        r.sousprefecture4, r.possedeNumTel, r.nomtuteurlegal, r.nomtuteurlegal2, r.namepere, 
        r.namemere, r.isDisponible, r.hasAcceptDisponible, r.hasexpcollecte, r.exp_structure, 
        r.exp_annee, r.exp_intitule_poste, r.exp_intitule_projet, r.date_jour_decla, r.declarahonneur, 
        r.confirmlieuaffectation, r.codebonneconduite, r.contrat, r.numero_cni, r.type_piece, 
        r.name, r.last_name, r.choix_projet, r.date_naiss, r.lieu_naiss, 
        r.last_diplome, r.status, r.email,         
        r.contact1, r.contact2, r.contact3, r.first_langue_nat AS langue1, r.second_langue_nat AS langue2, 
        r.third_langue_nat AS langue3, r.photo, r.cv, r.doc_last_diplome, 
        r.cni, r.id_projet, r.name, r.last_name, r.date_naiss, r.lieu_naiss,r.statut_recrut,

        CASE r.niveau_etude
            WHEN "11" THEN "DEUG/BAC +2/LICENCE 2/BTS"
            WHEN "12" THEN "LICENCE 3 / BAC+3"
            WHEN "13" THEN "MAITRISE / MASTER 1 / BAC+4"
            WHEN "14" THEN "MASTER 2 / DEA / BAC+5"
            WHEN "15" THEN "DOCTORAT"
            ELSE "Autre"
        END AS niveau_etude,
        CASE r.sexe
            WHEN "1" THEN "Homme"
            ELSE "Femme"
        END AS sexe,        

        CASE r.hasexpcollecte
            WHEN "1" THEN "OUI"
            ELSE "NON"
        END AS ExpCollecte,        

        (SELECT libelle FROM recrut_ethnie WHERE id = r.first_langue_nat) AS first_langue_nat, 
        (SELECT libelle FROM recrut_ethnie WHERE id = r.second_langue_nat) AS second_langue_nat, 
        (SELECT libelle FROM recrut_ethnie WHERE id = r.third_langue_nat) AS third_langue_nat
       
        FROM recrut_personne_recrut r, recrut_departement d
        WHERE r.departement3 = d.CodDep
        ORDER BY r.note DESC';
     
        $data['person'] = $personnerecru_model->db->query($sql)->getResult();
        $result = $recrutEthnie_model->getValueByParameter($param);      
        
        $data['json_person'] = json_encode($data['person']);
        
        // $logger = service('logger');
        // $logger->error($data);

        return view('tpl/index', $data);
    }    

    public function trombinoscope(){
        helper(['form']);
        $personnerecru_model = new PersonneRecrut();
        $recrutEthnie_model = new RecrutEthnie();
        $data = [];
        $data['count']  = $personnerecru_model->countAllResults();
        $data['homme']  = $personnerecru_model->countHommes();
        $data['femme']  = $personnerecru_model->countFemmes();
        
        $sql = 'SELECT DISTINCT r.matricule, r.note, r.codeProjet, r.NomProjet, r.titre_poste, r.departement, 
        r.departement2, r.departement3, r.departement4, r.sousprefecture, r.sousprefecture2, r.sousprefecture3, 
        r.sousprefecture4, r.possedeNumTel, r.nomtuteurlegal, r.nomtuteurlegal2, r.namepere, 
        r.namemere, r.isDisponible, r.hasAcceptDisponible, r.hasexpcollecte, r.exp_structure, 
        r.exp_annee, r.exp_intitule_poste, r.exp_intitule_projet, r.date_jour_decla, r.declarahonneur, 
        r.confirmlieuaffectation, r.codebonneconduite, r.contrat, r.numero_cni, r.type_piece, 
        r.name, r.last_name, r.choix_projet, r.date_naiss, r.lieu_naiss, 
        r.last_diplome, r.status, r.email,         
        r.contact1, r.contact2, r.contact3, r.first_langue_nat AS langue1, r.second_langue_nat AS langue2, 
        r.third_langue_nat AS langue3, r.photo, r.cv, r.doc_last_diplome, 
        r.cni, r.id_projet, r.name, r.last_name, r.date_naiss, r.lieu_naiss,r.statut_recrut,

        CASE r.niveau_etude
            WHEN "11" THEN "DEUG/BAC +2/LICENCE 2/BTS"
            WHEN "12" THEN "LICENCE 3 / BAC+3"
            WHEN "13" THEN "MAITRISE / MASTER 1 / BAC+4"
            WHEN "14" THEN "MASTER 2 / DEA / BAC+5"
            WHEN "15" THEN "DOCTORAT"
            ELSE "Autre"
        END AS niveau_etude,
        CASE r.sexe
            WHEN "1" THEN "Homme"
            ELSE "Femme"
        END AS sexe,        

        CASE r.hasexpcollecte
            WHEN "1" THEN "OUI"
            ELSE "NON"
        END AS ExpCollecte,        

        (SELECT libelle FROM recrut_ethnie WHERE id = r.first_langue_nat) AS first_langue_nat, 
        (SELECT libelle FROM recrut_ethnie WHERE id = r.second_langue_nat) AS second_langue_nat, 
        (SELECT libelle FROM recrut_ethnie WHERE id = r.third_langue_nat) AS third_langue_nat
       
        FROM recrut_personne_recrut r, recrut_departement d
        WHERE r.departement3 = d.CodDep
        ORDER BY r.note DESC';
     
        $data['person'] = $personnerecru_model->db->query($sql)->getResult();
        $result = $recrutEthnie_model->getValueByParameter($param);      
        
        $data['json_person'] = json_encode($data['person']);
        
        // $logger = service('logger');
        // $logger->error($data);

        return view('tpl/trombinoscope', $data);
    }    

    public function index(): string
    {
        $data = [];
        $model = new ProjetModel();        
        $data['lists'] = $model->where('active', '1')->orderBy('NomProjet')->findAll();
        $data['postulants'] = $this->postulants(0);
		
		$data['main_content'] = 'welcome';
        return view('template/template', $data);
    }

    public function testindex(): string
    {
        $data = [];
		// $data['main_content'] = 'testindex';
        return view('tpl/testindex', $data);
    }

    public function welcome(): string
    {
        return view('welcome_test');
    }

    public function profils(): string
    {
        $data = [];
        $model = new ProjetModel();        
        $data['lists'] = $model->where('active', '1')->orderBy('NomProjet')->findAll();
        $data['postulants'] = $this->postulants(0);
		$data['main_content'] = 'welcome_message';
        return view('template/template', $data);
    }

    public function recepisse(): string
    {
		$data = [];
        $model = new ProjetModel();        
        $data['lists'] = $model->where('active', '1')->orderBy('NomProjet')->findAll();
        $data['postulants'] = $this->postulants(0);       
        
		$data['main_content'] = 'vues/recepisse_view';
        return view('template/template', $data);
    }

	public function inscription_copy(){
        $data = [];
		$data['main_content'] = 'welcome';
        return view('template/template', $data);
    }

	public function inscription(){
		$data = [];
        $projets = new ProjetModel();
        $data['lists'] = $projets->where('active', '1')->findAll();

        $request = service('request');
        $projet = $request->getPost('projet');
        // $toutesLesVariables = $request->getPost();

        $data['projet'] = $projets->where('id', $projet)->first();

		// $data->projet = $this->projet_m->get_by(array('id'=>$projet));
        
        $ethnies = new EhnieModel();
        // $data['ethnie'] = $ethnies->findAll();
        $data['ethnie'] = array("0"=>"Selectionner une Ethnie");
        $data['ethnie'] += $ethnies->dropdown_orderby('id','libelle');

        // $data->ethnie = array(''=>'Selectionner une Ethnie');
		// $data->ethnie += $this->ethnie_m->dropdown_orderby('id','libelle');

        $departements = new DepartementModel();
        // $data['departementsAll'] = $departements->findAll();
        $data['departementsAll'] = array("0"=>"Selectionner une Préfecture");
        $data['departementsAll'] += $departements->dropdown_orderby('CodDep','NomDep');

		// $data->departementsAll  = array("0"=>"sélectionner une préfecture");
		// $data->departementsAll += $this->departement_m->dropdown_orderby('CodDep','NomDep');


        $sPrefecture = new SPrefectureModel();
        // $data['sousprefecturesAll'] = $sPrefecture->findAll();

        $data['sousprefecturesAll'] = array("0"=>"Selectionner une Sous-Préfecture");
        $data['sousprefecturesAll'] += $sPrefecture->dropdown_orderby('CodSp','NomSp');
        
        $data['listDep'] = $departements->findAll();
        $data['listSP'] = $sPrefecture->findAll();
        $data['listEthnie'] = (new EhnieModel())->findAll();
        
        // $data['sousprefecturesAll'] = array();

		// $this->load->model('projet_m');
		// $this->load->model('ethnie_m');
		// $this->load->model('departement_m');

		// $projet = $request->getPost('projet');
		// $data->ethnie = array(''=>'Selectionner une Ethnie');
		// $data->ethnie += $this->ethnie_m->dropdown_orderby('id','libelle');

		// $data->departementsAll  = array("0"=>"sélectionner une préfecture");
		// $data->departementsAll += $this->departement_m->dropdown_orderby('CodDep','NomDep');

		// $sousprefecturesAll = array();
		// $data->sousprefecturesAll = $sousprefecturesAll;

		// $data->projet = $this->projet_m->get_by(array('id'=>$projet));

        $data['main_content'] = 'vues/contener_formulaire_view.php';
        return view('template/template', $data);
	}

	public function inscriptionView($projet){
		$data = [];
        $projets = new ProjetModel();
        $data['lists'] = $projets->where('active', '1')->findAll();

        // $request = service('request');
        // $projet = $request->getGet('projet');

        $data['projet'] = $projets->where('id', $projet)->first();

        // $logger = service('logger');
        // $logger->error("--------------------- projet_id ----------------------------------");
        // $logger->error( $data['projet']['NomProjet']);

		// $data->projet = $this->projet_m->get_by(array('id'=>$projet));
        
        $ethnies = new EhnieModel();
        // $data['ethnie'] = $ethnies->findAll();
        $data['ethnie'] = array("0"=>"Selectionner une Ethnie");
        $data['ethnie'] += $ethnies->dropdown_orderby('id','libelle');

        // $data->ethnie = array(''=>'Selectionner une Ethnie');
		// $data->ethnie += $this->ethnie_m->dropdown_orderby('id','libelle');

        $departements = new DepartementModel();
        // $data['departementsAll'] = $departements->findAll();
        $data['departementsAll'] = array("0"=>"Selectionner une Préfecture");
        $data['departementsAll'] += $departements->dropdown_orderby('CodDep','NomDep');

		// $data->departementsAll  = array("0"=>"sélectionner une préfecture");
		// $data->departementsAll += $this->departement_m->dropdown_orderby('CodDep','NomDep');

        $sPrefecture = new SPrefectureModel();
        // $data['sousprefecturesAll'] = $sPrefecture->findAll();

        $data['sousprefecturesAll'] = array("0"=>"Selectionner une Sous-Préfecture");
        $data['sousprefecturesAll'] += $sPrefecture->dropdown_orderby('CodSp','NomSp');
        
        $data['listDep'] = $departements->findAll();
        $data['listSP'] = $sPrefecture->findAll();
        $data['listEthnie'] = (new EhnieModel())->findAll();
        
        // $data['sousprefecturesAll'] = array();

		// $this->load->model('projet_m');
		// $this->load->model('ethnie_m');
		// $this->load->model('departement_m');

		// $projet = $request->getPost('projet');
		// $data->ethnie = array(''=>'Selectionner une Ethnie');
		// $data->ethnie += $this->ethnie_m->dropdown_orderby('id','libelle');

		// $data->departementsAll  = array("0"=>"sélectionner une préfecture");
		// $data->departementsAll += $this->departement_m->dropdown_orderby('CodDep','NomDep');

		// $sousprefecturesAll = array();
		// $data->sousprefecturesAll = $sousprefecturesAll;

		// $data->projet = $this->projet_m->get_by(array('id'=>$projet));

        $data['main_content'] = 'vues/contener_formulaire_view.php';
        return view('template/template', $data);
	}


    public function register(){
        $request = service('request');        
        $logger = service('logger');

		if(isset($_POST['formSubmit'])){
            $perso_recrut = new PersoRecrutModel();
            $projet = new ProjetModel();
            $projets = new ProjetModel();
            $departements = new DepartementModel();
            $sPrefecture = new SPrefectureModel();

            $projet_id = $request->getPost('choix_projet');
            // $projet_id = 14;
            // $logger->error("projet_id : $projet_id");
            // $logger->error("projet_id- getPost : ".$request->getPost('choix_projet'));

            
            $postulants = $this->postulants($projet_id);
            $projets = $projets->where('id', $projet_id)->first();
            $nbDisponible = intval($projets['nbr_limit']);
            // $logger->error("-------------------------- projets 2 ---------------------------------");
            // $logger->error($postulants);
            if($postulants){
                $nbDisponible = intval($projets['nbr_limit']) - intval($postulants['total']);
            }
            // $nbDisponible = 100;

            // $logger->error($projets);
            // $logger->error($postulants);
            // $logger->error("nbDisponible : $nbDisponible");
            // $logger->error("-------------------------------- -------------- ----------------------------------");

            if($nbDisponible <= 0){
                return redirect()->to('/');        
            }

            // $this->load->library('form_validation');gg
            // $this->form_validation->set_error_delimiters('<p class="fg-color-red">erreur dans le formualire</p>');

            $validation_rules = array(
                array(
                    'field' => 'codeProjet',
                    'label' => 'codeProjet',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'NomProjet',
                    'label' => 'NomProjet',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'titre_poste',
                    'label' => 'titre_poste',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'departement',
                    'label' => 'departement',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'departement2',
                    'label' => 'departement2',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'departement3',
                    'label' => 'departement3',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'departement4',
                    'label' => 'departement4',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'sousprefecture',
                    'label' => 'sousprefecture',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'sousprefecture2',
                    'label' => 'sousprefecture2',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'sousprefecture3',
                    'label' => 'sousprefecture3',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'sousprefecture4',
                    'label' => 'sousprefecture4',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'sousprefecture4',
                    'label' => 'sousprefecture4',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'possedeNumTel',
                    'label' => 'possedeNumTel',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'nametuteurlegal',
                    'label' => 'nametuteurlegal',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'nametuteurlegal2',
                    'label' => 'nametuteurlegal2',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'namepere',
                    'label' => 'namepere',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'namemere',
                    'label' => 'namemere',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'pdispo',
                    'label' => 'pdispo',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'confirmdispo',
                    'label' => 'confirmdispo',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'hasexpcollecte',
                    'label' => 'hasexpcollecte',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'employeur_1',
                    'label' => 'employeur_1',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'year_deb_1',
                    'label' => 'year_deb_1',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'fontion_1',
                    'label' => 'fontion_1',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'projet_name_1',
                    'label' => 'projet_name_1',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'date_jour_decla',
                    'label' => 'date_jour_decla',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'ludeclaration',
                    'label' => 'ludeclaration',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'confirmlieuaffect',
                    'label' => 'confirmlieuaffect',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'lubonneconduite',
                    'label' => 'lubonneconduite',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'lucontrat',
                    'label' => 'lucontrat',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'numero_cni',
                    'label' => 'numero_cni',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'type_piece',
                    'label' => 'type_piece',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'name_postulant',
                    'label' => 'name_postulant',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'surname_postulant',
                    'label' => 'surname_postulant',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'date_naiss',
                    'label' => 'date_naiss',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'lieu_naissance',
                    'label' => 'lieu_naissance',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'status',
                    'label' => 'status',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'trim|valid_email'
                ),
                array(
                    'field' => 'sex',
                    'label' => 'sex',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'niveau_etude',
                    'label' => 'niveau_etude',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'last_diplome',
                    'label' => 'last_diplome',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'contact_1',
                    'label' => 'contact_1',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'contact_2',
                    'label' => 'contact_2',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'contact_22',
                    'label' => 'contact_22',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'first_langue',
                    'label' => 'first_langue',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'second_langue',
                    'label' => 'second_langue',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'third_langue',
                    'label' => 'third_langue',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'choix_projet',
                    'label' => 'choix_projet',
                    'rules' => 'trim'
                ),

            );

            $nb_email = count($perso_recrut->matricule($request->getPost('matricule')));
            $nb_contact1 = count($perso_recrut->contact1($request->getPost('contact_1')));
            $nb_piece = count($perso_recrut->piece($request->getPost('type_piece'), $request->getPost('numero_cni')));

            if($nb_email == 0 && $nb_contact1 == 0 && $nb_piece == 0){
                // $this->form_validation->set_rules($validation_rules);
                /* if ($this->form_validation->run()==TRUE)*/ {
                    // helper(['security']);                
                    $datainsert = array(
                        'codeProjet' => $request->getPost('codeProjet'),
                        'NomProjet' => $request->getPost('NomProjet'),
                        'titre_poste' => $request->getPost('titre_poste'),
                        'departement' => $request->getPost('departement'),
                        'departement2' => $request->getPost('departement2'),
                        'departement3' => $request->getPost('departement3'),
                        'departement4' => $request->getPost('departement4'),
                        'sousprefecture' => $request->getPost('sousprefecture'),
                        'sousprefecture2' => $request->getPost('sousprefecture2'),
                        'sousprefecture3' => $request->getPost('sousprefecture3'),
                        'sousprefecture4' => $request->getPost('sousprefecture4'),
                        'possedeNumTel' => $request->getPost('possedeNumTel'),
                        'nomtuteurlegal' => $request->getPost('nametuteurlegal'),
                        'nomtuteurlegal2' => $request->getPost('nametuteurlegal2'),
                        'namepere' => $request->getPost('namepere'),
                        'namemere' => $request->getPost('namemere'),
                        'isDisponible' => $request->getPost('pdispo'),
                        'hasAcceptDisponible' => $request->getPost('confirmdispo'),
                        'hasexpcollecte' => $request->getPost('hasexpcollecte'),
                        'exp_structure' => $request->getPost('employeur_1'),
                        'exp_annee' => $request->getPost('year_deb_1'),
                        'exp_intitule_poste' => $request->getPost('fontion_1'),
                        'exp_intitule_projet' => $request->getPost('projet_name_1'),
                        'date_jour_decla' => $request->getPost('date_jour_decla'),
                        'declarahonneur' => $request->getPost('ludeclaration'),
                        'confirmlieuaffectation' => $request->getPost('confirmlieuaffect'),
                        'codebonneconduite' => $request->getPost('lubonneconduite'),
                        'contrat' => $request->getPost('lucontrat'),
                        'numero_cni' => $request->getPost('numero_cni'),
                        'type_piece' => $request->getPost('type_piece'),
                        'name' => strtoupper($request->getPost('name_postulant')),
                        'last_name' => strtoupper($request->getPost('surname_postulant')),
                        'choix_projet' => $request->getPost('choix_projet'),
                        'date_naiss' => $request->getPost('date_naiss'),
                        'lieu_naiss' => strtoupper($request->getPost('lieu_naissance')),
                        'sexe' => $request->getPost('sex'),
                        'niveau_etude' => $request->getPost('niveau_etude'),
                        'last_diplome' => $request->getPost('last_diplome'),
                        'status' => $request->getPost('status'),
                        'email' => $request->getPost('email'),
                        'contact1' => $request->getPost('contact_1'),
                        'contact2' => $request->getPost('contact_2'),
                        'contact3' => $request->getPost('contact_22'),
                        'first_langue_nat' => $request->getPost('first_langue'),
                        'second_langue_nat' => $request->getPost('second_langue'),
                        'third_langue_nat' => $request->getPost('third_langue'),
                    );





                    // $datainsert = $this->security->xss_clean($datainsert);
                    $codeDept = $datainsert['departement'];
                    $codeSp = $datainsert['sousprefecture'];

                    $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567891011121314151617181920212223242526';
                    $shuffled = str_shuffle($str);
                    $shuffled = substr($shuffled, 1, 5);        
                    $matricule = $codeDept.$codeSp.$shuffled;
                    // $verifmatricule = $perso_recrut->where('matricule', $matricule)->findAll();
                    $verifmatricule = $perso_recrut->matricule($matricule);
                    while(count($verifmatricule) > 0){                     
                        $shuffled = str_shuffle($str);
                        $shuffled = substr($shuffled, 1, 5);
                        $matricule = $codeDept.$codeSp.$shuffled;
                        // $verifmatricule = $perso_recrut->where('matricule', $matricule)->findAll();
                        $verifmatricule = $perso_recrut->matricule($matricule);
                    }

                    // $logger = service('logger');
                    // $logger->error($matricule);

                    //creation folder upload
                    $this->createFolderUpload($codeDept, $codeSp);

                    $name = strtoupper($datainsert['name']);
                    $sur_name  = strtoupper($datainsert['last_name']);
            
                    $base_name_file = $this->concat_full_name($name, $sur_name);
                    $date_jr = strtotime(date('Y-m-d H:i:s'));

                    $photo = $this->uploadFile("userFiles1", $base_name_file."_photo_".$date_jr, $codeDept, $codeSp);
                    $cv = $this->uploadFile("userFiles2", $base_name_file."_cv_".$date_jr,$codeDept,$codeSp);
                    $doc_last_diplome =  $this->uploadFile("userFiles3", $base_name_file."_dip_".$date_jr,$codeDept,$codeSp);
                    $cni = $this->uploadFile("userFiles4", $base_name_file."_cni_".$date_jr,$codeDept,$codeSp);
                    $cnituteur = $this->uploadFile("userFiles5", $base_name_file."_cnituteur_".$date_jr,$codeDept,$codeSp);
                                
                    $certifresidence =  $this->uploadFile("userFiles6", $base_name_file."_certifresidence_".$date_jr, $codeDept, $codeSp);
                    $certifmedical = $this->uploadFile("userFiles7", $base_name_file."_certifmedical_".$date_jr,$codeDept,$codeSp);
                    $attestcollecte =  $this->uploadFile("userFiles8", $base_name_file."_attestcollecte_".$date_jr, $codeDept, $codeSp);
                    $casier = $this->uploadFile("userFiles9", $base_name_file."_casierjudiciaire_".$date_jr,$codeDept,$codeSp);

                    if($photo && $cv && $doc_last_diplome && $cni /* && $cnituteur*/){
                        //insert in bd
                        $id_projet = $datainsert['choix_projet'];
                        $photo = $photo;
                        $doc_last_diplome = $doc_last_diplome;
                        $cv = $cv;
                        $cni = $cni;
                        //$cnituteur = $cnituteur;
                        //$casier = $casier;
                        //$certifmedical = $certifmedical;

                        $datainsert['photo'] = $photo;
                        $datainsert['cv'] = $cv;
                        $datainsert['cni'] = $cni;
                        $datainsert['doc_last_diplome'] = $doc_last_diplome;
                        $datainsert['cnituteurlegal'] = $cnituteur;
                        $datainsert['certifresidence'] = $certifresidence;
                        $datainsert['casier'] = $casier;
                        $datainsert['attestcollecte'] = $attestcollecte;
                        $datainsert['certifmedical'] = $certifmedical;
                        $datainsert['matricule'] = $matricule;
                        $datainsert['dateheureinscrip'] = date("Y-m-d H:i:s");;
                        $datainsert['id_projet'] = $id_projet;

                        // if($id_pers_recrut = $this->perso_recrut_m->insert($datainsert)){
                        if($perso_recrut->insert($datainsert, false)){
                            $reps = $projet->where('id', $id_projet)->first(); 
                            // $data = new stdClass();
                            // $reps = $this->projet_m->get_by(array("id"=>$id_projet));

                            $data = array(
                                'id_projet' => $id_projet,
                                'projet' => $reps['NomProjet'],
                                'photo' => $photo,
                                'matricule' => $matricule,
                                'nom' => html_entity_decode($name),
                                'prenoms' =>html_entity_decode($datainsert['last_name']),
                                'date_naiss'=>$datainsert['date_naiss'],
                                'lieu_naiss'=>html_entity_decode($datainsert['lieu_naiss']),
                                'sexe'=>$datainsert['sexe'],
                                'contact'=>$datainsert['contact1'],
                                'nivo_etude'=>$datainsert['niveau_etude'],
                                'specialite'=> $datainsert['last_diplome'],
                                'statut'=>  $datainsert['status'],
                                'email'=>  $datainsert['email'],
                                'lang_parlee'=>$datainsert['first_langue_nat'],
                                'lang_parlee_2'=>$datainsert['second_langue_nat'],
                                'experiences' => array(),
                                'formations' => array(),
                                'nom_projet' =>  $datainsert['NomProjet'],
                                'titre_poste' =>  $datainsert['titre_poste'],
                                'departement' => $departements->where('CodDep', $codeDept)->first(),
                                'sousprefecture' => $sPrefecture->where('CodSp', $codeSp)->first(),                            
                                
                            );                    

                            $this->session->set('data', $data);

                            // $_SESSION['data'] = $data; 
                            return redirect()->route('recap_view');
                        }
                        else{
                            Header("Location: formulaire_view.php"); 
                        }
                    }
                    else{
                        if(!$photo){
                            $_SESSION["statusMsg"] = "La Photo n'a pas pu êre téléchargée, veuillez respecter le format de fichier et la taille de fichier(1MO)";
                        }
                        if(!$cv){
                            $_SESSION["statusMsg"] = "Le CV n'a pas pu êre téléchargé, veuillez respecter le format de fichier et la taille de fichier(1MO)";
                        }
                        if(!$last_diplome){
                            $_SESSION["statusMsg"] = "Le dernier diplôme n'a pas pu êre téléchargé, veuillez respecter le format de fichier et la taille de fichier(1MO)";
                        }
                        if(!$cni){
                            $_SESSION["statusMsg"] = "La CNI n'a pas pu êre téléchargé, veuillez respecter le format de fichier et la taille de fichier(1MO)";
                        }            
                        Header("Location: formulaire_view.php");
                    }
                }
                // else{
                //     //redirect("welcome/ccdcdcdf");
                //     echo validation_errors(); 
                // }
            }else{
                $_SESSION["statusMsg"] = "Désolé! vous êtes déjà inscrit";    
                Header("Location: formulaire_view.php");
            }
        }
        else{           
			//echo $codeProjet;
        }         
	}
	
	public function recap_view(){
		// if(isset($_SESSION['data'])){
		if($this->session->get('data')){
			$data = [];
            $data['main_content'] = 'recap_view';
            return view('template/template', $data);
        }
		else{
			return redirect()->to('/');
		}
	}

    //function permettant de creer les dossiers d'upload si cela n'existe pas
    function createFolderUpload($codeDept, $codeSp){
        if(!file_exists('uploads/files/'.$codeDept)) {
            mkdir('uploads/files/'.$codeDept, 0777, true);
        }

        if(!file_exists('uploads/files/'.$codeDept."/".$codeSp)) {
            mkdir('uploads/files/'.$codeDept."/".$codeSp, 0777, true);
        }
    }

    //fonction permettant d'upload un fichier
    function uploadFile($elt, $file_name, $codeDept, $codeSp){
        $target_dir = "uploads/files/".$codeDept."/".$codeSp."/";
        $extentions = array('png','pdf','jpg','jpeg','JPG','JPEG','PDF','PNG');
        $ext = substr(strrchr($_FILES[$elt]['name'],'.'),1);
        if ($extentions !== FALSE AND !in_array($ext, $extentions)) return FALSE;

        $target_dir = $target_dir .basename( $file_name.".".$ext);
        if (move_uploaded_file($_FILES[$elt]["tmp_name"], $target_dir)) {
            return $file_name.".".$ext;
        }else {
            return FALSE;
        }
    }

    //methode permettant de concatener le name et le second name
    function concat_full_name($first_name,$last_name){
        //enlever les ' dans les differents string
        $first_name = str_replace("\'", "", $first_name);
        $last_name = str_replace("\'", "", $last_name);
        
        //enlever les " dans les differents string
        $first_name = str_replace("\"", "", $first_name);
        $last_name = str_replace("\"", "", $last_name);

        // enlever les espaces vides
        $first_name = str_replace(" ","", $first_name);
        $last_name = str_replace(" ","", $last_name);

        $full_name = $first_name."".$last_name;

        return $full_name;
    }




	//methode permettant d'obtenir les recepisse
	public function getRecepisse(){
        $request = service('request');

		// $this->load->library('form_validation');
        // $this->form_validation->set_error_delimiters('<p class="fg-color-red">', '</p>');

		$validation_rules = array(
            array(
                'field' => 'contact_1x',
                'label' => 'contact_1x',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'id_projet',
                'label' => 'id_projet',
                'rules' => 'trim|required'
            ),

        );

        // $this->form_validation->set_rules($validation_rules);

        /*if ($this->form_validation->run()==TRUE)*/ {
            // helper(['security']);                

			$id_projet = $request->getPost('id_projet');  
			$contact1 = $request->getPost('contact_1x'); 
            
            $perso_recrut = new PersoRecrutModel();
            $projets = new ProjetModel();
            $departements = new DepartementModel();
            $sPrefecture = new SPrefectureModel();

			// $info_persons = $perso_recrut->where('contact1', $contact1)->where('id_projet', $id_projet)->findAll();

            // 
			// $info_persons = $perso_recrut->where('contact1', $contact1)->where('id_projet', $id_projet)->first();
			$info_persons = $perso_recrut->where('id_projet', $id_projet)->where('email', $contact1)->orWhere('matricule', $contact1)
            ->orWhere('contact1', $contact1)->first();
			$reps = $projets->where('id', $id_projet)->first();

            // $nb = count($info_persons);
            $logger = service('logger');
            // Enregistrement d'un message d'information
            // $logger->info('Données : ' . print_r($data, true));

			if($info_persons){        
                //$regs = $info_persons['region'];
                $depts = $info_persons['departement'];
                $sps = $info_persons['sousprefecture'];
                $data = array(
                    'id_projet' => $id_projet,
                    'projet' => $reps['NomProjet'],
                    //'nom_projet' => $reps['Nom_Projet'],
                    'photo' => $info_persons['photo'],
                    'matricule' => $info_persons['matricule'],
                    'nom' => $info_persons['name'],
                    'prenoms' =>$info_persons['last_name'],
                    'date_naiss'=>$info_persons['date_naiss'],
                    'lieu_naiss'=>$info_persons['lieu_naiss'],
                    'sexe'=>$info_persons['sexe'],
                    'contact'=>$contact1,
                    'nivo_etude'=>$info_persons['niveau_etude'],
                    'specialite'=> $info_persons['last_diplome'],
                    'statut'=> $info_persons['statut_recrut'],
                    'email'=> $info_persons['email'],
                    'lang_parlee'=>$info_persons['first_langue_nat'],
                    'lang_parlee_2'=>$info_persons['second_langue_nat'],
                    'experiences' => '',
                    'formations' => '',
                    'nom_projet' => '',
                    'titre_poste' => '',
                    // 'region' =>  get_by('recrut_region',"CodReg = '$regs'"),
                    'departement' => $departements->where('CodDep', $depts)->first(),
                    'sousprefecture' => $sPrefecture->where('CodSp', $sps)->first(),                               

                // 'localite1' =>  $info_persons['localite1'],
                // 'localite2' =>  $info_persons['localite2'],
                // 'localite3' =>  $info_persons['localite3'],
                );
                
                $this->session->set('data', $data);

                // $logger->error(count($info_persons));
                // $logger->error($info_persons['name']);
                // $logger->error($this->session->get('data'));

                return redirect()->route('recap_view');
            }
			else{
               return redirect()->route('recepisse');
			}
		}
		/*else{
			//echo validation_errors(); 
            return redirect()->route('recepisse');
		}*/
	}

	//methode permettant d'obtenir les recepisse

    public function checkapp(): string
    {
		$data = [];
        $projet = new ProjetModel();
		$data['lists'] = $projet->findAll();
		$data['main_content'] = 'vues/checkapp';
        return view('template/template', $data);
    }


    public function getCheckApp(): string
    {
        $data = [];
        $data['hasExiste'] = false;

        $request = service('request');
        $searchapp = $request->getPost('searchapp');   
        
        $perso_recrut = new PersoRecrutModel();
        $projets = new ProjetModel();
        $departements = new DepartementModel();
        $sPrefecture = new SPrefectureModel();
        
        $info_persons = $perso_recrut->where('email', $searchapp)->orWhere('matricule', $searchapp)->orWhere('contact1', $searchapp)->first();

        if(isset($info_persons['id']) && $info_persons['id'] > 0)
        {
            $data['hasExiste'] = true;
            $reps = $projets->where('id', $info_persons['choix_projet'])->first();    
            // $logger = service('logger');    
            // $logger->error($info_persons);
            $data['data'] = $info_persons;    
        }

		$data['main_content'] = 'vues/resultat_app';
        return view('template/template', $data);           
    }


	public function getCheckApp1(){
        $request = service('request');

		// $this->load->library('form_validation');
        // $this->form_validation->set_error_delimiters('<p class="fg-color-red">', '</p>');

		$validation_rules = array(
            array(
                'field' => 'searchapp',
                'label' => 'searchapp',
                'rules' => 'trim|required'
            )
        );

        $id_projet = 14;
        
        // $this->form_validation->set_rules($validation_rules);

        /*if ($this->form_validation->run()==TRUE)*/ {
            // helper(['security']);               
			$searchapp = $request->getPost('searchapp');             
            $perso_recrut = new PersoRecrutModel();
            $projets = new ProjetModel();
            $departements = new DepartementModel();
            $sPrefecture = new SPrefectureModel();

			// $info_persons = $perso_recrut->where('email', $searchapp)->where('contact1', $searchapp)->where('matricule', $searchapp)->first();
			$info_persons = $perso_recrut->where('email', $searchapp)->first();
			$reps = $projets->where('id', $id_projet)->first();

            $logger = service('logger');
            $logger->error(count($info_persons));

            // Enregistrement d'un message d'information
            // $logger->info('Données : ' . print_r($data, true));

			if($info_persons){        
                //$regs = $info_persons['region'];
                $depts = $info_persons['departement'];
                $sps = $info_persons['sousprefecture'];
                $data = [];

                $data = array(
                    'id_projet' => $id_projet,
                    'projet' => $reps['NomProjet'],
                    //'nom_projet' => $reps['Nom_Projet'],
                    'photo' => $info_persons['photo'],
                    'matricule' => $info_persons['matricule'],
                    'nom' => $info_persons['name'],
                    'prenoms' =>$info_persons['last_name'],
                    'date_naiss'=>$info_persons['date_naiss'],
                    'lieu_naiss'=>$info_persons['lieu_naiss'],
                    'sexe'=>$info_persons['sexe'],
                    'contact'=>$contact1,
                    'nivo_etude'=>$info_persons['niveau_etude'],
                    'specialite'=> $info_persons['last_diplome'],
                    'statut'=> $info_persons['statut_recrut'],
                    'email'=> $info_persons['email'],
                    'lang_parlee'=>$info_persons['first_langue_nat'],
                    'lang_parlee_2'=>$info_persons['second_langue_nat'],
                    'experiences' => '',
                    'formations' => '',
                    'nom_projet' =>  '',
                    'titre_poste' =>  '',
                    // 'region' =>  get_by('recrut_region',"CodReg = '$regs'"),
                    'departement' => $departements->where('CodDep', $depts)->first(),
                    'sousprefecture' => $sPrefecture->where('CodSp', $sps)->first(), 
                );
                
                // $this->session->set('data', $data);

                $data['main_content'] = 'resultat_app';
                return view('template/template', $data);   
                
                // return redirect()->route('recap_view');
            }
			else{
               return redirect()->route('index');
			}
		}
		/*else{
			//echo validation_errors(); 
            return redirect()->route('recepisse');
		}*/
	}

	public function postulantsByProject(){
        $logger = service('logger');
        // $projet_id = 14;
        // $recrut = new PersoRecrutModel();
        // $recrut = $recrut->where('status', 0)->where('id_projet', $projet_id)->findAll();  
        // $logger->error($recrut);

        $recrut = $this->postulants(14);
        // if($projet_id > 0){
        //     $recrut = $recrut->where('status', 0)->where('id_projet', $projet_id)->select('id_projet, COUNT(id) as total')->groupBy('id_projet')->findAll();  
        // }else{
        //     $recrut = $recrut->where('status', 0)->select('id_projet, COUNT(id) as total')->groupBy('id_projet')->findAll();  
        //     // $recrut = $recrut->where('status', 0)->select('id_projet, COUNT(id) as total')->groupBy('id_projet')->get()->getResult();  
        // }
        // $logger->error("-------------------------------- recrut----------------------------------");
        // $logger->error($recrut);

        return redirect()->to('/');        
	}
    
	public function postulants($projet_id){
        $recrut = new PersoRecrutModel();
        if($projet_id > 0){
            $recrut = $recrut->where('status', 0)->where('id_projet', $projet_id)->select('id_projet, COUNT(id) as total')->groupBy('id_projet')->first();  
        }else{
            $recrut = $recrut->where('status', 0)->select('id_projet, COUNT(id) as total')->groupBy('id_projet')->findAll();  
            // $recrut = $recrut->where('status', 0)->select('id_projet, COUNT(id) as total')->groupBy('id_projet')->get()->getResult();  
        }
        return $recrut;        
	}
    

	public function postulantsByProject1(){
        helper(['form']);
        
        // helper('string');
        // helper('inflector');

        $request = service('request');
        $logger = service('logger');

        $projet_id = $request->getPost('projet');
        $projet_id = 14;

        // $model = new ProjetModel();
        // // $projet = $model->where('active', '1')->findAll();
        // $projet = $model->where('active', '1')->where('id', $projet_id)->asObject()->first();       
        // // $projet = $model->where('active', '1')->where('id', $projet_id)->first();       
        // // $projet = $model->where('active', '1')->first();   

        // // $logger->error($projet->postulants);
        // $logger->error($projet->postulants);
        // // $logger->error($projet['id']);
        // $logger->error($projet->id);



        // $recrut = new PersoRecrutModel();
        // $recrut = $recrut->where('status', 0)->where('id_projet', $projet_id)->findAll();  
        // $logger->error($recrut);


        $recrut = new PersoRecrutModel();
        $recrut = $recrut->where('status', 0)->select('id_projet, COUNT(id) as total')->groupBy('id_projet')->findAll();  
        // $recrut = $recrut->where('status', 0)->select('id_projet, COUNT(id) as total')->groupBy('id_projet')->get()->getResult();  
        $logger->error($recrut);


        // Charger la classe Query Builder
        // $builder = $db->table('votre_table');

        // // Spécifier les colonnes à sélectionner
        // $builder->select('colonne1, colonne2, COUNT(colonne3) as total');

        // // Ajouter la clause GROUP BY
        // $builder->groupBy('colonne1');

        // // Exécuter la requête
        // $query = $builder->get();

        // // Récupérer les résultats
        // $result = $query->getResult();


        return redirect()->to('/');        
	}


	public function profilDemande($projet_id){
        $data = [];
        $model = new ProjetModel();        
        $data['lists'] = $model->where('active', '1')->where('id', $projet_id)->orderBy('NomProjet')->findAll();
        $data['postulants'] = $this->postulants(0);
		$data['main_content'] = 'welcome_message';
        return view('template/template', $data);
	}
    
    public function mProfils()
    {
        $data = [];
        $model = new ProjetModel();        
        $data['lists'] = $model->where('active', '1')->orderBy('NomProjet')->findAll();
        $data['postulants'] = $this->postulants(0);
        return view('mobile/mprofils', $data);        
    }


	public function inscriptionMobile(){  
		$data = [];
        $projets = new ProjetModel();
        $data['lists'] = $projets->where('active', '1')->findAll();

        $request = service('request');
        $projet_id = $request->getPost('choix_projet');

        $data['id_nivo_minimum'] = $request->getPost('nivo_projet');
        $data['id_projet'] = $request->getPost('choix_projet');
        $data['codeProjet'] = $request->getPost('codeProjet');
        $data['NomProjet'] = $request->getPost('NomProjet');
        $data['titre_poste'] = $request->getPost('titre_poste');

        $data['projet'] = $projets->where('id', $projet_id)->first();
        
        $departements = new DepartementModel();
        $sPrefecture = new SPrefectureModel();
        $data['listDep'] = $departements->findAll();
        $data['listSP'] = $sPrefecture->findAll();
        $data['listEthnie'] = (new EhnieModel())->findAll();

        return view('mobile/index', $data);        
	}

	public function inscriptionMobile1(){  
		$data = [];
        $projets = new ProjetModel();
        $data['lists'] = $projets->where('active', '1')->findAll();

        $request = service('request');
        $projet = $request->getPost('projet');
        $data['projet'] = $projets->where('id', $projet)->first();
        
        $departements = new DepartementModel();
        $sPrefecture = new SPrefectureModel();
        $data['listDep'] = $departements->findAll();
        $data['listSP'] = $sPrefecture->findAll();
        $data['listEthnie'] = (new EhnieModel())->findAll();

        return view('mobile/index', $data);        
	}

    public function registerMobile(){
        $request = service('request');        
        $logger = service('logger');

		if(isset($_POST['formSubmit'])){
            $perso_recrut = new PersoRecrutModel();
            $projet = new ProjetModel();
            $projets = new ProjetModel();
            $departements = new DepartementModel();
            $sPrefecture = new SPrefectureModel();

            $projet_id = $request->getPost('choix_projet');            
            $postulants = $this->postulants($projet_id);
            $projets = $projets->where('id', $projet_id)->first();
            $nbDisponible = intval($projets['nbr_limit']);

            if($postulants){
                $nbDisponible = intval($projets['nbr_limit']) - intval($postulants['total']);
            }

            // $logger->error("-------------------------------- -------------- ----------------------------------");

            if($nbDisponible <= 0){
                return redirect()->to('/');        
            }

            // $this->load->library('form_validation');
            // $this->form_validation->set_error_delimiters('<p class="fg-color-red">erreur dans le formualire</p>');

            $validation_rules = array(
                array(
                    'field' => 'codeProjet',
                    'label' => 'codeProjet',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'NomProjet',
                    'label' => 'NomProjet',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'titre_poste',
                    'label' => 'titre_poste',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'departement',
                    'label' => 'departement',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'departement2',
                    'label' => 'departement2',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'departement3',
                    'label' => 'departement3',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'departement4',
                    'label' => 'departement4',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'sousprefecture',
                    'label' => 'sousprefecture',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'sousprefecture2',
                    'label' => 'sousprefecture2',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'sousprefecture3',
                    'label' => 'sousprefecture3',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'sousprefecture4',
                    'label' => 'sousprefecture4',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'sousprefecture4',
                    'label' => 'sousprefecture4',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'possedeNumTel',
                    'label' => 'possedeNumTel',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'nametuteurlegal',
                    'label' => 'nametuteurlegal',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'nametuteurlegal2',
                    'label' => 'nametuteurlegal2',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'namepere',
                    'label' => 'namepere',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'namemere',
                    'label' => 'namemere',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'pdispo',
                    'label' => 'pdispo',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'confirmdispo',
                    'label' => 'confirmdispo',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'hasexpcollecte',
                    'label' => 'hasexpcollecte',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'employeur_1',
                    'label' => 'employeur_1',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'year_deb_1',
                    'label' => 'year_deb_1',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'fontion_1',
                    'label' => 'fontion_1',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'projet_name_1',
                    'label' => 'projet_name_1',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'date_jour_decla',
                    'label' => 'date_jour_decla',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'ludeclaration',
                    'label' => 'ludeclaration',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'confirmlieuaffect',
                    'label' => 'confirmlieuaffect',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'lubonneconduite',
                    'label' => 'lubonneconduite',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'lucontrat',
                    'label' => 'lucontrat',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'numero_cni',
                    'label' => 'numero_cni',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'type_piece',
                    'label' => 'type_piece',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'name_postulant',
                    'label' => 'name_postulant',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'surname_postulant',
                    'label' => 'surname_postulant',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'date_naiss',
                    'label' => 'date_naiss',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'lieu_naissance',
                    'label' => 'lieu_naissance',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'status',
                    'label' => 'status',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'trim|valid_email'
                ),
                array(
                    'field' => 'sex',
                    'label' => 'sex',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'niveau_etude',
                    'label' => 'niveau_etude',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'last_diplome',
                    'label' => 'last_diplome',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'contact_1',
                    'label' => 'contact_1',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'contact_2',
                    'label' => 'contact_2',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'contact_22',
                    'label' => 'contact_22',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'first_langue',
                    'label' => 'first_langue',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'second_langue',
                    'label' => 'second_langue',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'third_langue',
                    'label' => 'third_langue',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'choix_projet',
                    'label' => 'choix_projet',
                    'rules' => 'trim'
                ),

            );

            $nb_email = count($perso_recrut->matricule($request->getPost('matricule')));
            $nb_contact1 = count($perso_recrut->contact1($request->getPost('contact_1')));
            $nb_piece = count($perso_recrut->piece($request->getPost('type_piece'), $request->getPost('numero_cni')));

            if($nb_email == 0 && $nb_contact1 == 0 && $nb_piece == 0){
                // $this->form_validation->set_rules($validation_rules);
                /* if ($this->form_validation->run()==TRUE)*/ {
                    // helper(['security']);                
                    $datainsert = array(
                        'codeProjet' => $request->getPost('codeProjet'),
                        'NomProjet' => $request->getPost('NomProjet'),
                        'titre_poste' => $request->getPost('titre_poste'),
                        'departement' => $request->getPost('departement'),
                        'departement2' => $request->getPost('departement2'),
                        'departement3' => $request->getPost('departement3'),
                        'departement4' => $request->getPost('departement4'),
                        'sousprefecture' => $request->getPost('sousprefecture'),
                        'sousprefecture2' => $request->getPost('sousprefecture2'),
                        'sousprefecture3' => $request->getPost('sousprefecture3'),
                        'sousprefecture4' => $request->getPost('sousprefecture4'),
                        'possedeNumTel' => $request->getPost('possedeNumTel'),
                        'nomtuteurlegal' => $request->getPost('nametuteurlegal'),
                        'nomtuteurlegal2' => $request->getPost('nametuteurlegal2'),
                        'namepere' => $request->getPost('namepere'),
                        'namemere' => $request->getPost('namemere'),
                        'isDisponible' => $request->getPost('pdispo'),
                        'hasAcceptDisponible' => $request->getPost('confirmdispo'),
                        'hasexpcollecte' => $request->getPost('hasexpcollecte'),
                        'exp_structure' => $request->getPost('employeur_1'),
                        'exp_annee' => $request->getPost('year_deb_1'),
                        'exp_intitule_poste' => $request->getPost('fontion_1'),
                        'exp_intitule_projet' => $request->getPost('projet_name_1'),
                        'date_jour_decla' => $request->getPost('date_jour_decla'),
                        'declarahonneur' => $request->getPost('ludeclaration'),
                        'confirmlieuaffectation' => $request->getPost('confirmlieuaffect'),
                        'codebonneconduite' => $request->getPost('lubonneconduite'),
                        'contrat' => $request->getPost('lucontrat'),
                        'numero_cni' => $request->getPost('numero_cni'),
                        'type_piece' => $request->getPost('type_piece'),
                        'name' => strtoupper($request->getPost('name_postulant')),
                        'last_name' => strtoupper($request->getPost('surname_postulant')),
                        'choix_projet' => $request->getPost('choix_projet'),
                        'date_naiss' => $request->getPost('date_naiss'),
                        'lieu_naiss' => strtoupper($request->getPost('lieu_naissance')),
                        'sexe' => $request->getPost('sex'),
                        'niveau_etude' => $request->getPost('niveau_etude'),
                        'last_diplome' => $request->getPost('last_diplome'),
                        'status' => $request->getPost('status'),
                        'email' => $request->getPost('email'),
                        'contact1' => $request->getPost('contact_1'),
                        'contact2' => $request->getPost('contact_2'),
                        'contact3' => $request->getPost('contact_22'),
                        'first_langue_nat' => $request->getPost('first_langue'),
                        'second_langue_nat' => $request->getPost('second_langue'),
                        'third_langue_nat' => $request->getPost('third_langue'),
                    );

                    $codeDept = $datainsert['departement'];
                    $codeSp = $datainsert['sousprefecture'];

                    $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567891011121314151617181920212223242526';
                    $shuffled = str_shuffle($str);
                    $shuffled = substr($shuffled, 1, 5);        
                    $matricule = $codeDept.$codeSp.$shuffled;
                    $verifmatricule = $perso_recrut->matricule($matricule);
                    while(count($verifmatricule) > 0){                     
                        $shuffled = str_shuffle($str);
                        $shuffled = substr($shuffled, 1, 5);
                        $matricule = $codeDept.$codeSp.$shuffled;
                        $verifmatricule = $perso_recrut->matricule($matricule);
                    }

                    //creation folder upload
                    $this->createFolderUpload($codeDept, $codeSp);

                    $name = strtoupper($datainsert['name']);
                    $sur_name  = strtoupper($datainsert['last_name']);
            
                    $base_name_file = $this->concat_full_name($name, $sur_name);
                    $date_jr = strtotime(date('Y-m-d H:i:s'));

                    $photo = $this->uploadFile("userFiles1", $base_name_file."_photo_".$date_jr, $codeDept, $codeSp);
                    $cv = $this->uploadFile("userFiles2", $base_name_file."_cv_".$date_jr,$codeDept,$codeSp);
                    $doc_last_diplome =  $this->uploadFile("userFiles3", $base_name_file."_dip_".$date_jr,$codeDept,$codeSp);
                    $cni = $this->uploadFile("userFiles4", $base_name_file."_cni_".$date_jr,$codeDept,$codeSp);
                    $cnituteur = $this->uploadFile("userFiles5", $base_name_file."_cnituteur_".$date_jr,$codeDept,$codeSp);
                                
                    $certifresidence =  $this->uploadFile("userFiles6", $base_name_file."_certifresidence_".$date_jr, $codeDept, $codeSp);
                    $certifmedical = $this->uploadFile("userFiles7", $base_name_file."_certifmedical_".$date_jr,$codeDept,$codeSp);
                    $attestcollecte =  $this->uploadFile("userFiles8", $base_name_file."_attestcollecte_".$date_jr, $codeDept, $codeSp);
                    $casier = $this->uploadFile("userFiles9", $base_name_file."_casierjudiciaire_".$date_jr,$codeDept,$codeSp);

                    if($photo && $cv && $doc_last_diplome && $cni /* && $cnituteur*/){
                        //insert in bd
                        $id_projet = $datainsert['choix_projet'];
                        $photo = $photo;
                        $doc_last_diplome = $doc_last_diplome;
                        $cv = $cv;
                        $cni = $cni;
                        //$cnituteur = $cnituteur;
                        //$casier = $casier;
                        //$certifmedical = $certifmedical;

                        $datainsert['photo'] = $photo;
                        $datainsert['cv'] = $cv;
                        $datainsert['cni'] = $cni;
                        $datainsert['doc_last_diplome'] = $doc_last_diplome;
                        $datainsert['cnituteurlegal'] = $cnituteur;
                        $datainsert['certifresidence'] = $certifresidence;
                        $datainsert['casier'] = $casier;
                        $datainsert['attestcollecte'] = $attestcollecte;
                        $datainsert['certifmedical'] = $certifmedical;
                        $datainsert['matricule'] = $matricule;
                        $datainsert['dateheureinscrip'] = date("Y-m-d H:i:s");;
                        $datainsert['id_projet'] = $id_projet;

                        // if($id_pers_recrut = $this->perso_recrut_m->insert($datainsert)){
                        if($perso_recrut->insert($datainsert, false)){
                            $reps = $projet->where('id', $id_projet)->first(); 
                            // $data = new stdClass();
                            // $reps = $this->projet_m->get_by(array("id"=>$id_projet));

                            $data = array(
                                'id_projet' => $id_projet,
                                'projet' => $reps['NomProjet'],
                                'photo' => $photo,
                                'matricule' => $matricule,
                                'nom' => html_entity_decode($name),
                                'prenoms' =>html_entity_decode($datainsert['last_name']),
                                'date_naiss'=>$datainsert['date_naiss'],
                                'lieu_naiss'=>html_entity_decode($datainsert['lieu_naiss']),
                                'sexe'=>$datainsert['sexe'],
                                'contact'=>$datainsert['contact1'],
                                'nivo_etude'=>$datainsert['niveau_etude'],
                                'specialite'=> $datainsert['last_diplome'],
                                'statut'=>  $datainsert['status'],
                                'email'=>  $datainsert['email'],
                                'lang_parlee'=>$datainsert['first_langue_nat'],
                                'lang_parlee_2'=>$datainsert['second_langue_nat'],
                                'experiences' => array(),
                                'formations' => array(),
                                'nom_projet' =>  $datainsert['NomProjet'],
                                'titre_poste' =>  $datainsert['titre_poste'],
                                'departement' => $departements->where('CodDep', $codeDept)->first(),
                                'sousprefecture' => $sPrefecture->where('CodSp', $codeSp)->first(), 
                            );                    

                            $this->session->set('data', $data);
                            // $_SESSION['data'] = $data; 
                            // return redirect()->route('recap_view');
                            return redirect()->to('/index.php/mrecap');
                        }
                        else{
                            Header("Location: formulaire_view.php"); 
                        }
                    }
                    else{
                        if(!$photo){
                            $_SESSION["statusMsg"] = "La Photo n'a pas pu êre téléchargée, veuillez respecter le format de fichier et la taille de fichier(1MO)";
                        }
                        if(!$cv){
                            $_SESSION["statusMsg"] = "Le CV n'a pas pu êre téléchargé, veuillez respecter le format de fichier et la taille de fichier(1MO)";
                        }
                        if(!$last_diplome){
                            $_SESSION["statusMsg"] = "Le dernier diplôme n'a pas pu êre téléchargé, veuillez respecter le format de fichier et la taille de fichier(1MO)";
                        }
                        if(!$cni){
                            $_SESSION["statusMsg"] = "La CNI n'a pas pu êre téléchargé, veuillez respecter le format de fichier et la taille de fichier(1MO)";
                        }            
                        Header("Location: formulaire_view.php");
                    }
                }
                // else{
                //     //redirect("welcome/ccdcdcdf");
                //     echo validation_errors(); 
                // }
            }else{
                $_SESSION["statusMsg"] = "Désolé! vous êtes déjà inscrit";    
                Header("Location: formulaire_view.php");
            }
        }
        else{           
			//echo $codeProjet;
        }         
	}

    public function mRecepisse()
    {
		$data = [];
        $model = new ProjetModel();        
        $data['lists'] = $model->where('active', '1')->orderBy('NomProjet')->findAll();
        $data['postulants'] = $this->postulants(0);       
        return view('mobile/recepisse', $data);        
    }
    
    public function mCheckApp()
    {
		$data = [];
        return view('mobile/checkapp', $data);        
    }

    public function mGetCheckApp(): string
    {
        $data = [];
        $data['hasExiste'] = false;

        $request = service('request');
        $searchapp = $request->getPost('searchapp');   
        
        $perso_recrut = new PersoRecrutModel();
        $projets = new ProjetModel();
        $departements = new DepartementModel();
        $sPrefecture = new SPrefectureModel();
        
        $info_persons = $perso_recrut->where('email', $searchapp)->orWhere('matricule', $searchapp)->orWhere('contact1', $searchapp)->first();

        if(isset($info_persons['id']) && $info_persons['id'] > 0)
        {
            $data['hasExiste'] = true;
            $reps = $projets->where('id', $info_persons['choix_projet'])->first();    
            // $logger = service('logger');    
            // $logger->error($info_persons);
            $data['data'] = $info_persons;    
        }

        return view('mobile/resultatApp', $data);        
    }

	public function mGetRecepisse(){
        $request = service('request');

		// $this->load->library('form_validation');
        // $this->form_validation->set_error_delimiters('<p class="fg-color-red">', '</p>');

		$validation_rules = array(
            array(
                'field' => 'contact_1x',
                'label' => 'contact_1x',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'id_projet',
                'label' => 'id_projet',
                'rules' => 'trim|required'
            ),

        );

        // $this->form_validation->set_rules($validation_rules);

        /*if ($this->form_validation->run()==TRUE)*/ {
            // helper(['security']);                

			$id_projet = $request->getPost('id_projet');  
			$contact1 = $request->getPost('contact_1x'); 
            
            $perso_recrut = new PersoRecrutModel();
            $projets = new ProjetModel();
            $departements = new DepartementModel();
            $sPrefecture = new SPrefectureModel();

			// $info_persons = $perso_recrut->where('contact1', $contact1)->where('id_projet', $id_projet)->findAll();

			// $info_persons = $perso_recrut->where('contact1', $contact1)->where('id_projet', $id_projet)->first();
			$info_persons = $perso_recrut->where('id_projet', $id_projet)->where('email', $contact1)->orWhere('matricule', $contact1)
            ->orWhere('contact1', $contact1)->first();
			$reps = $projets->where('id', $id_projet)->first();

            // $nb = count($info_persons);
            $logger = service('logger');
            // Enregistrement d'un message d'information
            // $logger->info('Données : ' . print_r($data, true));

			if($info_persons){        
                //$regs = $info_persons['region'];
                $depts = $info_persons['departement'];
                $sps = $info_persons['sousprefecture'];
                $data = array(
                    'id_projet' => $id_projet,
                    'projet' => $reps['NomProjet'],
                    //'nom_projet' => $reps['Nom_Projet'],
                    'photo' => $info_persons['photo'],
                    'matricule' => $info_persons['matricule'],
                    'nom' => $info_persons['name'],
                    'prenoms' => $info_persons['last_name'],
                    'date_naiss'=> $info_persons['date_naiss'],
                    'lieu_naiss'=> $info_persons['lieu_naiss'],
                    'sexe'=> $info_persons['sexe'],
                    'contact'=> $info_persons['contact1'],
                    'nivo_etude'=> $info_persons['niveau_etude'],
                    'specialite'=> $info_persons['last_diplome'],
                    'statut'=> $info_persons['statut_recrut'],
                    'email'=> $info_persons['email'],
                    'lang_parlee'=> $info_persons['first_langue_nat'],
                    'lang_parlee_2'=> $info_persons['second_langue_nat'],
                    'experiences' => '',
                    'formations' => '',
                    'nom_projet' => '',
                    'titre_poste' => '',
                    // 'region' =>  get_by('recrut_region',"CodReg = '$regs'"),
                    'departement' => $departements->where('CodDep', $depts)->first(),
                    'sousprefecture' => $sPrefecture->where('CodSp', $sps)->first(),                               

                // 'localite1' =>  $info_persons['localite1'],
                // 'localite2' =>  $info_persons['localite2'],
                // 'localite3' =>  $info_persons['localite3'],
                );
                
                $this->session->set('data', $data);
                // $logger->error($this->session->get('data'));

                return redirect()->route('mrecap');
            }
			else{
               return redirect()->route('mrecepisse');
			}
		}
		/*else{
			//echo validation_errors(); 
            return redirect()->route('recepisse');
		}*/
	}

    public function mRecap(){
		if($this->session->get('data')){
			$data = [];
            return view('mobile/recap', $data);        
        }
		else{
			return redirect()->to('/');
		}
	}


}
