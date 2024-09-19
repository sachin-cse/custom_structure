<?php
class Site
{
    private $_results = array();
    public $_connect;

    public function __construct() {
        $this->db();
        $this->ErrMsg = 'Error! Please check sql query.';
    }
    
    public function __set($var,$val) {
        $this->_results[$var] = $val;
    }

    public function __get($var) {
        if (isset($this->_results[$var])){
            return $this->_results[$var];
        }
        else{
            return null;
        }
    }
    
    function __destruct() {
        //$this->destroy();
    }
    
    function destroy() {
        if($this->_connect != false) {
            $thread_id = $this->_connect->thread_id;
            $this->_connect->kill($thread_id);
            $closeResults = $this->_connect->close();

            //make sure it closed
            if($closeResults === false) {
                //echo "Could not close DB connection.<br>";
            }
        }
    }
    
    protected function dbconnect() {
        
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if (mysqli_connect_errno()) {
            printf("Could not connect to DB server: %s\n", mysqli_connect_error());
            exit();
        }
        else
            return $mysqli;
    }
    
    protected function db() {
        
        if($this->_connect == false) {
            
            if($GLOBALS['siteObj']->_connect == false){
                $this->_connect = $this->dbconnect();
            }
            else {
                $this->_connect = $GLOBALS['siteObj']->_connect;
            }
        }
        
    }
    
    public function selectQuery($ENTITY, $params, $CLAUSE, $start, $limit) {
        $fieldList = is_array($params) ? implode(', ', $params) : $params;
        if($fieldList=='')
            $fieldList = '*';
        if(!$CLAUSE)
            $CLAUSE = 1;
        
        $sql        = "SELECT ".$fieldList." FROM ".$ENTITY." WHERE ".$CLAUSE." LIMIT ".$start.", ".$limit;
        
        $records    = array(); 

        $this->db();
        $res = $this->_connect->query($sql);
        
        if($res) {
        
            if($res->num_rows > 1){
                while ($row = $res->fetch_assoc()){
                    foreach ($row as $k=>$v){
                        $this->_results[$k] = $v;
                    }
                    $records[] = $this->_results;
                }
            }
            else
                $records = $res->fetch_assoc();

            $res->free();

            return $records;
        }
        else
            return $this->ErrMsg;
    }
    
    public function selectMulti($ENTITY, $params, $CLAUSE, $start=0, $limit=1, $debug = false) {
        $fieldList = is_array($params) ? implode(', ', $params) : $params;
        
        if($fieldList=='')
            $fieldList = '*';
        if(!$CLAUSE)
            $CLAUSE = 1;
        
        if($debug)
            echo '<br>'.$sql = "SELECT ".$fieldList." FROM ".$ENTITY." WHERE ".$CLAUSE." LIMIT ".$start.", ".$limit;
        else
            $sql = "SELECT ".$fieldList." FROM ".$ENTITY." WHERE ".$CLAUSE." LIMIT ".$start.", ".$limit;

        $records        = array(); 
        $this->_results = array();
        
        $this->db();
        $res = $this->_connect->query($sql);
        
        if($res) {
        
            while ($row = $res->fetch_assoc()){
                foreach ($row as $k=>$v){
                    $this->_results[$k] = $v;
                }
                $records[] = $this->_results;
            }

            $res->free();

            return $records;
        }
        else
            return $this->ErrMsg;
    }
    
    public function selectAll($ENTITY, $params, $CLAUSE, $debug = false) {
        $fieldList = is_array($params) ? implode(', ', $params) : $params;
        
        if($fieldList=='')
            $fieldList = '*';
        if(!$CLAUSE)
            $CLAUSE = 1;
        
        if($debug)
            echo '<br>'.$sql = "SELECT ".$fieldList." FROM ".$ENTITY." WHERE ".$CLAUSE;
        else
            $sql = "SELECT ".$fieldList." FROM ".$ENTITY." WHERE ".$CLAUSE;

        $records        = array(); 
        $this->_results = array();
        
        $this->db();
        $res = $this->_connect->query($sql);
        
        if($res) {
        
            while ($row = $res->fetch_assoc()){
                foreach ($row as $k=>$v){
                    $this->_results[$k] = $v;
                }
                $records[] = $this->_results;
            }

            $res->free();

            return $records;
        }
        else
            return $this->ErrMsg;
    }
    
    public function selectMultiOnLeft($ENTITY1, $ENTITY2, $params, $CLAUSE, $start=0, $limit=1, $debug = false) {
        $fieldList = is_array($params) ? implode(', ', $params) : $params;
        if($fieldList=='')
            $fieldList = '*';
        if(!$CLAUSE)
            $CLAUSE = 1;
        
        if($debug)
            echo '<br>'.$sql = "SELECT ".$fieldList." FROM ".$ENTITY1." LEFT JOIN ".$ENTITY2." ON ".$CLAUSE." LIMIT ".$start.", ".$limit;
        else
            $sql = "SELECT ".$fieldList." FROM ".$ENTITY1." LEFT JOIN ".$ENTITY2." ON ".$CLAUSE." LIMIT ".$start.", ".$limit;
        
        $records        = array(); 
        $this->_results = array();
        
        $this->db();
        $res = $this->_connect->query($sql);
        
        if($res) {
        
            while ($row = $res->fetch_assoc()){
                foreach ($row as $k=>$v){
                    $this->_results[$k] = $v;
                }
                $records[] = $this->_results;
            }

            $res->free();

            return $records;
        }
        else
            return $this->ErrMsg;
    }
    
    public function selectSingle($ENTITY, $params, $CLAUSE, $debug = false) {
        $fieldList  = is_array($params) ? implode(', ', $params) : $params;
        if($fieldList=='')
            $fieldList = '*';
        if(!$CLAUSE)
            $CLAUSE = 1;
        
        if($debug)
            echo '<br>'.$sql = "SELECT ".$fieldList." FROM ".$ENTITY." WHERE ".$CLAUSE." LIMIT 0,1";
        else
            $sql = "SELECT ".$fieldList." FROM ".$ENTITY." WHERE ".$CLAUSE." LIMIT 0,1";
        
        $records        = array();
        
        $this->db();
        $res        = $this->_connect->query($sql);
        
        if($res) {
            $records = $res->fetch_assoc();
            $res->free();

            return $records;
        }
        else
            return $this->ErrMsg;
    }
    
    public function rowCount($ENTITY, $needle, $CLAUSE, $debug = false) {
        if(!$CLAUSE)
            $CLAUSE = 1;
        
        if($debug)
            echo '<br>'.$sql = "SELECT COUNT(".$needle.") AS count FROM ".$ENTITY." WHERE ".$CLAUSE;
        else
            $sql = "SELECT COUNT(".$needle.") AS count FROM ".$ENTITY." WHERE ".$CLAUSE;

        $this->db();
        $res = $this->_connect->query($sql);
        
        if($res) {
            $records = $res->fetch_assoc();
            $res->free();

            return $records['count'];
        }
        else
            return $this->ErrMsg;
    }
    
    public function selectDistinct($ENTITY, $needle, $CLAUSE, $debug = false){
        if(!$CLAUSE)
            $CLAUSE = 1;
        
        if($debug)
            echo '<br>'.$sql = "SELECT DISTINCT(".$needle.") AS ".$needle." FROM ".$ENTITY." WHERE ".$CLAUSE;
        else
            $sql = "SELECT DISTINCT(".$needle.") AS ".$needle." FROM ".$ENTITY." WHERE ".$CLAUSE;
        
        $this->db();
        $res        = $this->_connect->query($sql);
        
        if($res) {
            $records = $res->fetch_assoc();
            $res->free();

            return $records[$needle];
        }
        else
            return $this->ErrMsg;
    }
    
    public function insertQuery($ENTITY, $params, $debug = false) {
        $fields         = array();
        $fieldVals      = array();
        
        foreach($params as $param=>$pval){
            $fields[] = $param;
            $fieldVals[] = "'".addslashes($pval)."'";
        } 
        
  		if($debug)
            echo '<br>'.$sql = "INSERT INTO ".$ENTITY." (".implode(', ', $fields).") VALUES (".implode(', ', $fieldVals).")";
        else
  		    $sql = "INSERT INTO ".$ENTITY." (".implode(', ', $fields).") VALUES (".implode(', ', $fieldVals).")";
        
        $this->db();
        $this->_connect->query($sql);
        $return = ($this->_connect->affected_rows)? $this->_connect->insert_id:0;
        
        return $return;
    }
    
    public function updateQuery($ENTITY, $params, $CLAUSE, $debug = false) {
        $fields = array();
        foreach($params as $param=>$pval){
            $fields[] = $param."='".addslashes($pval)."'";
        }
        if(!$CLAUSE)
            $CLAUSE = 1;
        
        if($debug)
            echo '<br>'.$sql = "UPDATE ".$ENTITY." SET ".implode(', ', $fields)." WHERE ".$CLAUSE;
        else
            $sql = "UPDATE ".$ENTITY." SET ".implode(', ', $fields)." WHERE ".$CLAUSE;
        
        $this->db();
        $this->_connect->query($sql);
        $return = ($this->_connect->affected_rows)? 1:0;
    
        
        return $return;
    }
    
    public function executeQuery($sql) {
        
        $this->db();
        $res = $this->_connect->query($sql);
        $return = ($this->_connect->affected_rows)? 1:0;
        
        return $return;
    }
    
    function getSiteBysiteId($siteId) {
        return $this->selectSingle(TBL_SITE, "*", "siteId=".$siteId);
	}
    
	function getSiteBysiteURL($siteURL) {
        return $this->selectSingle(TBL_SITE, "*", "siteUrl='".$siteURL."'");
	}
    
    function redirectTo404($SITE_LOC_PATH) {
		header("HTTP/1.0 404 Not Found");
		echo '<center class="st_404" style="margin:20px;"><h1>404</h1><h2>Page Not Found</h2>';
		echo 'The URL that you have requested could not be found.</center>';
        //header('location:'.$SITE_LOC_PATH.'/404/');
        //exit();
    }
    
    function redirectToURL($URL) {
        header('location:'.$URL);
        exit();
    }
    
    function underConstruction() {
        include("underconstruction/index.php");
        exit();
    }
    
    function getCountries() {
        return $this->selectMulti(TBL_COUNTRIES, "*", 1, 0, 250); 
    }
    
    function settings($name) {
        $ExtraQryStr = "name = '".addslashes($name)."'";
		return $this->selectSingle(TBL_SETTINGS, "*", $ExtraQryStr);
    }
    
    function newSettings($params) {
        return $this->insertQuery(TBL_SETTINGS, $params);
    }

    function updateSetting($name, $params) {
        $CLAUSE = "name = '".addslashes($name)."'";
        return $this->updateQuery(TBL_SETTINGS, $params, $CLAUSE);
    }
}
?>