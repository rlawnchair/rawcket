<?php

namespace Rawcket;

class Model {

    static $connections = array(); //Tableau des connexions
    public $conf = 'default'; //Type de configutation PDO
    public $table = false; //Table sur laquelle on effectue des requetes
    public $db; //Connexion a la base de donnée
    public $primaryKey = 'id'; //Clé primaire - par défaut id
    public $id; //Dernier ID inséré
    protected $validates = array(); //Permet de tester les valeurs avant insertion
    private $errors = array();//Tableau des erreurs
    public $Form; //Permet la gestion des erreurs sur les formulaires

    /*
     * Connexion a la base de données + gestion des erreurs et encodage
     */
    function __construct() {

        //Si la table n'est pas définie on la récupere
        if($this->table===false){
            $this->table=strtolower(get_class($this)).'s';
        }

        //Récupération de la configuration pour se connecter a mysql avec PDO
        $conf = Config::$database[$this->conf];

        //Options de PDO - Ici affichage des erreurs et encodage en utf8
        $pdo_options=array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',);

        //S'il existe une connexion on la stock dans $this->db et on s'arrete ici
        if(isset(Model::$connections[$this->conf])){
            $this->db = Model::$connections[$this->conf];
            return true;
        }
        //Sinon on se connecte et on cré une nouvelle connexion
        try{
            $pdo = new PDO('mysql:host='.$conf['host'].';dbname='.$conf['database'].';',$conf['login'],$conf['password'],$pdo_options);
            Model::$connections[$this->conf] = $pdo;
            $this->db=$pdo;
        }
        catch(PDOException $e){
            Config::$debug==1?die($e->getMessage()):die('La connexion à la base de donnée a échouée.');
        }
    }
    /*
     * Requete de selection
     * @param $req tableau contenant les conditions, les champs, et la limite
     * @return un objet php contenant les données récupérées
     */
    public function find($req = null) {
        $sql = 'SELECT ';

        //Permet de préciser des champs
        $sql .= isset($req['fields'])?(is_array($req['fields'])?implode(',',$req['fields']):$req['fields']):'*';

        $sql.=' FROM '.$this->table.' as '.get_class($this).' ';

        if(isset($req['conditions'])){
            $sql .= 'WHERE ';
            if(!is_array($req['conditions'])){
                $sql.=$req['conditions'];
            }
            else{
                $cond = array();
                foreach ($req['conditions'] as $key => $value) {
                    if(!is_numeric($value)){
                        $value = '"'.mysql_escape_string($value).'"';
                    }
                    $cond[] = $key.'='.$value;
                }
                $sql .= implode(' AND ',$cond);
            }
        }

        if(isset($req['orderby'])){
            $sql .= ' ORDER BY '.$req['orderby'];
        }

        if(isset($req['limit'])){
            $sql .= ' LIMIT '.$req['limit'];
        }
        $pre = $this->db->prepare($sql);
        $pre->execute();
        return $pre->fetchAll(PDO::FETCH_OBJ);
    }

    /*
     * Permet de récupérer un seul élément
     * @param $req conditions,champs,limite
     * @return un objet contenant les informations demandées
     */
    public function findFirst($req) {
        return current($this->find($req));
    }

    /*
     * Permet de compter un nombre d'enregistrements
     * @param $cond conditions de la requete
     * @return le nombre d'enregistrements
     */
    public function findCount($cond = null) {
        $res = $this->findFirst(array(
            'fields'=>array('count('.$this->primaryKey.') as count'),
            'conditions'=>$cond,
        ));
        return $res->count;
    }

    /*
     * Permet de supprimer un enregistrement en base
     * @param $id clé primaire
     */
    public function delete($id) {
        $sql = 'DELETE FROM '.$this->table.' WHERE '.$this->primaryKey.' = '.$id;
        $this->db->query($sql);
    }

    /*
     * Permet d'inserer ou mettre a jour des données en base.
     * @param $data objet contenant les données postées depuis un formulaire
     */
    public function save($data) {
        $key = $this->primaryKey;
        $fields = array();
        $d = array();

        //On parcourt l'objet
        foreach($data as $k=>$v){
            //S'il n'a pas de clé primaire on récupere seulement les champs et et on fait une insertion et on construit un tableau qui associe un champs a sa valeur pour la requete préparée
            if($k!=$this->primaryKey){
                $fields []= " $k=:$k";
                $d[":$k"]=$v;
            }
            //Sinon on construit seulement le tableau
            else if(!empty($v)){
                $d[":$k"]=$v;
            }
        }
        
        //S'il y a une clé primaire on met a jour
        if(isset($data->$key) && !empty($data->$key)){
            $sql = 'UPDATE '.$this->table.' SET '.implode(',',$fields). ' WHERE '.$key. ' =:'.$key;
            $this->id = $data->$key;
            $action = 'update';
        }
        //Sinon on fait une insertion
        else{
            $sql = 'INSERT INTO '.$this->table.' SET '.implode(',',$fields);
            $action = 'insert';
        }
        
        $pre = $this->db->prepare($sql);
        $pre->execute($d);
        if($action == 'insert'){
            $this->id = $this->db->lastInsertId();
            //Dans le cas d'une insertion on récupere le nouvel identifiant
        }
    }

    /*
     * Permet de valider des informations passées par un formulaire
     * @param $data objet contenant les informations a valider
     * @return un booléen indiquant si les données sont valides ou non
     */
    public function validates($data) {
        $errors = array();
        foreach($this->validates as $k=>$v){
            if(!isset($data->$k)){
                $errors[$k] = $v['message'];
            }
            else{
                if($v['rule']=='notEmpty') {
                    if(empty($data->$k)){
                        $errors[$k]=$v['message'];
                    }
                }
                else if(!preg_match('/^'.$v['rule'].'$/',$data->$k)){
                    $errors[$k]=$v['message'];
                }
            }
        }
        $this->errors = $errors;
        if(isset($this->Form)){
            $this->Form->errors = $errors;
        }
        if(empty($this->errors)){
            return true;
        }
        return false;
    }

}
?>