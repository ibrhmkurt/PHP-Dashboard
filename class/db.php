<?php
class DB
{
    var $server = "localhost";
    var $user = "root";
    var $password = "1994420";
    var $dbname = "panel";

    var $connection;

    function  __construct()
    {
        try {
            $this->connection = new PDO("mysql:host=".$this->server.";dbname=".$this->dbname.";charset=utf8;",$this->user, $this->password);
        }catch(PDOException $error) {
            echo $error->getMessage();
            exit;
        }
    }

    /* SELECT * FROM settings WHERE id=1 ORDER BY id ASC LIMIT 1 */

    public function getData($table, $wherefields="", $wherearrayvalue="", $orderby="ORDER BY id ASC", $limit="")
    {
        $this->connection->query("SET CHARACTER SET utf8");
        $sql="SELECT * FROM ".$table;
        if(!empty($wherefields) && !empty($wherearrayvalue))
        {
            $sql.=" ".$wherefields;
            if(!empty($orderby))
            {
                $sql.=" ".$orderby;
            }
            if (!empty($limit))
            {
                $sql.=" LIMIT ".$limit;
            }
            $run = $this->connection->prepare($sql);
            $result = $run->execute($wherearrayvalue);
            $data = $run->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            if(!empty($orderby))
            {
                $sql.=" ".$orderby;
            }
            if (!empty($limit))
            {
                $sql.=" LIMIT ".$limit;
            }
            $data = $this->connection->query($sql, PDO::FETCH_ASSOC);
        }
        if ($data != false && !empty($data))
        {
            $datas = array();
            foreach ($data as $infos)
            {
                $datas[] = $infos;
            }
            return $datas;
        }
        else
        {
            return false;
        }
    }

    public function runQuery($tablo, $fields = "", $valuearray = "", $limit = "")
    {
        $this->connection->query("SET CHARACTER SET utf8");
        if (!empty($fields) && !empty($valuearray))
        {
            $sql = $tablo." ".$fields;
            if (!empty($limit))
            {
                $sql.=" LIMIT ".$limit;
            }
            $run = $this->connection->prepare($sql);
            $result = $run->execute($valuearray);
        }
        else
        {
            $sql = $tablo;
            if (!empty($limit))
            {
                $sql.=" LIMIT ".$limit;
            }
            $result = $this->connection->exec($sql);
        }
        if ($result != false)
        {
            return true;
        }
        else
        {
            return false;
        }
        $this->connection->query("SET CHARACTER SET utf8"); // character sorunu yaşanmaması için
    }
}
?>