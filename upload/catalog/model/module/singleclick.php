<?php

class ModelModuleSingleclick extends Model
{

    function add() {

        $time = time();
        $sql = "INSERT INTO " . DB_PREFIX . "singleclick SET id='',name = '" . $this->request->post['name'] . "',phone = '" . $this->request->post['phone'] . "',message='".$this->request->post['message']."',date='".$time."'";
        $query = $this->db->query($sql);
    }


}