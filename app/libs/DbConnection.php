<?php
/**
 *  Class nay ket noi den Database
 *  Author: Dat Lq
 *  Date: 22.08.21
 */
class app_libs_DbConnection{
    protected $username = "root";
    protected $password ="123";

    protected $host = "localhost";
    protected $dbname = "newspaper";

    protected $tablename;
    protected static $connectionInstance = null;

    protected $queryParams = [];

    public function __construct()
    {
        $this->connect();
    }

    /**
     *  tao ket noi den DB
     */
    public function connect(){
        if (self::$connectionInstance === NULL){
            try{
                self::$connectionInstance = new PDO ('mysql:host='.$this->host.';database='.$this->dbname, $this->username, $this->password);
                self::$connectionInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                //var_dump(self::$connectionInstance); die();
            } catch(Exception $ex){
                echo "ERROR".$ex->getMessage();
                die();
            }
        }
        return self::$connectionInstance;
    }
    /**
     * 
     */
    public function query($sql, $param = []){
        $q = self::$connectionInstance->prepare($sql);
        //var_dump($param); //die();
        // $q->bindParam($param);
        // $q->execute();
        if (is_array($param) && $param){
           // $q->execute($param);
           foreach($param as $key=> $value){
               $q->bindParam("$key", $value);
               
           }
           $q->execute();
        }
        else{
            $q->execute();
        }
         return $q;
    }

    public function buildQueryParam($param){
        $default = [
            "select" => "*",
            "where" => "",
            "other" => "",
            "params" => [],
            "field" =>"",
            "value" => []
        ];
        $this->queryParams = array_merge($default, $param);
        
        return $this;
    }
    public function buildCondition($condition){
        if(trim($condition)){
            return "where ".$condition." ";
        }
        return "";
    }
    public function select(){
        $sql = "select ".$this->queryParams["select"]." from ".$this->tablename." ".$this->buildCondition($this->queryParams["where"]).$this->queryParams["other"];
        $query = $this->query($sql, $this->queryParams["params"]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function selectOne(){
        $this->queryParams["other"]= "limit 1";
        $data  = $this->select();
        if($data){
            return $data[0];
        }
        return [];
    }

    public function insert(){
        $sql = "insert into ".$this->tablename." ".$this->queryParams["field"];
        $result = $this->query($sql,$this->queryParams["value"]);
        if($result){
            return self::$connectionInstance->lastInsertId();
        }else{
            return FALSE;
        }
    }

    public function registerAccount($username, $password){
        $sql = "insert into newspaper.author (username, password) value (?,?)";
        $stmt = self::$connectionInstance->prepare($sql);
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $password);
        $result =$stmt->execute();
        if($result){
            return self::$connectionInstance->lastInsertId();
        }else{
            return FALSE;
        }

    }
    public function update(){
        $sql = "update ".$this->tablename." set ".$this->queryParams["value"]." ".$this->buildCondition($this->queryParams["where"])." ".$this->queryParams["other"];
        return $this->query($sql);
    }

    public function delete(){
        $sql = "delete from ".$this->tablename." ".$this->buildCondition($this->queryParams["where"])." ".$this->queryParams["other"];
        return $this->query($sql);
    }
}