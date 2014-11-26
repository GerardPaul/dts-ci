<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DivisionFactory {

    private $_ci;

    function __construct() {
        $this->_ci = & get_instance();
        $this->_ci->load->model("division_model");
    }

    public function getDivision($id = 0) {
        if ($id > 0) {
            $sql = "SELECT * FROM dts_division WHERE id = ?";
            $query = $this->_ci->db->query($sql, array($id));
            if ($query->num_rows() > 0) {
                return $this->createObjectFromData($query->row());
            }
            return false;
        } else {
            $sql = "SELECT * FROM dts_division";
            $query = $this->_ci->db->query($sql);
            if ($query->num_rows() > 0) {
                $division = array();
                foreach ($query->result() as $row) {
                    $division[] = $this->createObjectFromData($row);
                }
                return $division;
            }
            return false;
        }
    }

    public function addDivision($name, $description) {
        $division = new Division_Model();
        $division->setDivisionName($name);
        $division->setDivisionDescription($description);

        return $division->commit();
    }

    public function createObjectFromData($row) {
        $division = new Division_Model();

        $division->setId($row->id);
        $division->setDivisionName($row->name);
        $division->setDivisionDescription($row->description);

        return $division;
    }

}
