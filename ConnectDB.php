<?php
namespace EstBase;
use PDO;

class ConnectDB  {
    
    /**
     * dados do host
     */
    private   $host,
              $user,
              $pass,
              $database;
    /**
     * objeto que armazena consulta 
     */
    protected $obj;
            

    
    public function __construct() {
        
        $this->host     = Config::DB['HOST'];
        $this->user     = Config::DB['USER'];
        $this->pass     = Config::DB['PASS'];
        $this->database = Config::DB['DATABASE'];
        $this->prefix   = Config::DB['PREFIX'];
        
              
        try {
           
              if($this->connect() == null):
                  
                    $this->connect();
              
              endif;
             
            
          } catch (\Exception $e) {

             echo ' <div class="cl-mcont">
                      <div class="block-flat"> <h2>OPS... Erro no banco de dados, tente novamente mais tarde</h2>'.$e->getMessage().'</div> </div>';
             exit();
          }
    }
   /**
    * 
    * @return \PDO link  com dados da conexao
    */
   private function connect(){
        
        $options = array(
                          \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                          \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING
                         );
        
        $link =  new \PDO("mysql:host={$this->host};dbname={$this->database}",
                $this->user, $this->pass, $options);              
       
        return $link;
    }
    /**
     * 
     * @param type string é a SQL passada
     * @param array são parametros da sql
     * @return PDO uma consulta
     */
    public function executeSQL($query,array $params =NULL){
        
        $this->obj = $this->connect()->prepare($query);               
       
           // contagem dos elementos do array params
           if( $params!=NULL && count($params) > 0):
               
               // varrendo o array e pagendo os dados
               foreach ($params as $key => $value):

                $this->obj->bindValue($key, $value);
           
               endforeach;
                            
           endif;
               
             return $this->obj->execute();
         
                                  
    }
    /**
     * 
     * @return array com dados da SQL
     */
    public function listData(){
        
        return $this->obj->fetch(PDO::FETCH_ASSOC);
    }
    /**
     * 
     * @return int com total de resgistros
     */
    public function totalData(){
        
        return $this->obj->rowCount();
    }
     
        
    
}