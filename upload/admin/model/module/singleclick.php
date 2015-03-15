<?php

class ModelModuleSIngleclick extends Model
{

    public function gethistory()
    {

        $sql = "SELECT * FROM " . DB_PREFIX . "singleclick";

        $sql .= " ORDER BY date";


        $sql .= " DESC";


        $query = $this->db->query($sql);

        return $query->rows;

    }


    public function getTotalhistory()
    {
        $query = $this->db->query(
            "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "singleclick"
        );

        return $query->row['total'];
    }

    public function createTable()
    {

        $sql = "CREATE TABLE IF NOT EXISTS " . DB_PREFIX
            . "singleclick (`id` INT( 18 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,name  VARCHAR(255),phone  VARCHAR(255),message  TEXT(500),date VARCHAR(255) ) ENGINE = MYISAM ;";
        $query = $this->db->query($sql);

        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteTable()
    {

        $sql = "DROP TABLE IF EXISTS " . DB_PREFIX . "singleclick ;";
        $query = $this->db->query($sql);

        if ($query) {
            return true;
        } else {
            return false;
        }
    }
}

?>