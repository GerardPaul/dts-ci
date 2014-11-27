<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class LogFactory {

    private $_ci;

    function __construct() {
        $this->_ci = & get_instance();
        $this->_ci->load->model("log_model");
    }

    public function getLogs() {
        $sql = 'SELECT * FROM dts_logs ORDER BY timeOccured DESC';
        $query = $this->_ci->db->query($sql);
        if ($query->num_rows() > 0) {
            $logs = array();
            foreach ($query->result() as $row) {
                $logs[] = $this->createObjectFromData($row);
            }
            return $logs;
        }
        return false;
    }

    public function createObjectFromData($row) {
        $log = new Log_Model();

        $log->setId($row->id);
        $log->setTimeOccured($this->formatDate($row->timeOccured));
        $log->setActionTaken($row->actionTaken);

        return $log;
    }

    private function formatDate($date) {
        return date('j-m-Y @ H:i:s', strtotime($date));
    }
}
