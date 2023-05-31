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

    public function sefLink($value)
    {
        $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#','?','*','!','.','(',')');
        $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp','','','','','','');
        $string = strtolower(str_replace($find, $replace, $value));
        $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
        $string = trim(preg_replace('/\s+/', ' ', $string));
        $string = str_replace(' ', '-', $string);
        return $string;
    }

    public function addModule()
    {
        if (!empty($_POST["title"]))
        {
            $title = $_POST["title"];
            if (!empty($_POST["status"]))
            {
                $status = 1;
            }
            else
            {
                $status = 2;
            }
            $tablo = str_replace("-", "", $this->sefLink($title));

            $control = $this->getData("modules", "WHERE tablo=?", array($tablo), "ORDER BY id ASC", 1);
            if ($control != false)
            {
                return false;
            }
            else
            {
                $createTable = $this->runQuery('CREATE TABLE `'.$tablo.'` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `seflink` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` int(5) DEFAULT NULL,
  `order_no` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;
');
                $addModule = $this->runQuery("INSERT INTO modules", "SET title=?, tablo=?, status=?, date=?", array($title, $tablo, $status, date("Y-m-d")));
                
                if ($addModule != false)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        else
        {
            return false;
        }
    }
}
?>