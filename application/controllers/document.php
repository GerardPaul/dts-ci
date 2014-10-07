<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Document extends CI_Controller {

	var $title = 'Documents';
    var $login = FALSE;
    var $userType = '';
    var $userId = '';
	
	private function checkLogin() {
        if ($this->session->userdata('logged_in')) {
            $this->login = TRUE;
            $session_data = $this->session->userdata('logged_in');
            $this->userType = $session_data['userType'];
            $this->userId = $session_data['id'];
        } else {
            $this->login = FALSE;
            $this->userType = 'EMP';
        }
    }

    private function cleanString($string) {
        $detagged = strip_tags($string);
        if (get_magic_quotes_gpc()) {
            $stripped = stripslashes($detagged);
            $escaped = mysql_real_escape_string($stripped);
        } else {
            $escaped = mysql_real_escape_string($detagged);
        }
		
        return addslashes($escaped);
    }

    private function error($er) {
        $header = '';
        $content = '';
        if ($er == 403) {
            $header = 'Forbidden';
            $content = 'You have no permission to view this page.';
        } else if ($er == 404) {
            $header = 'Not Found';
            $content = 'We cannot find the page you are looking for.';
        }
        $data = array(
            "title" => 'Error ' . $er,
            "header" => 'Error ' . $er . ' ' . $header,
            "content" => $content,
            "userType" => $this->userType
        );
        $this->load->template('error_view', $data);
    }
	
	public function index() {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'ADMIN' || $this->userType == 'SEC' || $this->userType == 'RD' || $this->userType == 'ARD') {
                $this->error(403);
            } else {
                $data = array(
                    "title" => $this->title,
                    "header" => 'All Documents',
                    "userType" => $this->userType
                );
                $this->load->library("RDDocumentFactory");
				$data['documents'] = $this->rddocumentfactory->getEMPDocument($this->userId);

				$this->load->template('emp_documents', $data);
            }
        } else {
            redirect('login', 'refresh');
        }
    }
	
	public function details($documentId = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'ADMIN' || $this->userType == 'SEC' || $this->userType == 'RD' || $this->userType == 'ARD') {
                $this->error(403);
            } else {
                $documentId = (int) $documentId;
                $this->load->library("RDDocumentFactory");
				
                $documents = $this->rddocumentfactory->getRDDocument($documentId);
				if ($documentId > 0 && $documents) {
					$status = $this->status($documents->getStatus());
					
					$data = array(
						"documents" => $documents,
						"title" => 'Document Details',
						"header" => $status . $documents->getSubject(),
						"userType" => $this->userType
					);
					$this->load->template('emp_view_document', $data);
				}else{
					$this->error(404);
				}
            }
        } else {
            redirect('login', 'refresh');
        }
    }
	
	private function status($status){
        if ($status == 'Cancelled'){
            return '<small class="text-danger status"><i class="glyphicon glyphicon-remove-sign" title="' . $status . '" data-toggle="tooltip"></i></small>&nbsp;';
        }else if ($status == 'On-Going'){
            return '<small class="text-warning status"><i class="glyphicon glyphicon-info-sign" title="' . $status . '" data-toggle="tooltip"></i></small>&nbsp;';
        }else if ($status == 'Compiled'){
            return '<small class="text-success status"><i class="glyphicon glyphicon-ok-sign" title="' . $status . '" data-toggle="tooltip"></i></small>&nbsp;';
        }
    }
	
	public function receive($documentId = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'ADMIN' || $this->userType == 'SEC' || $this->userType == 'RD' || $this->userType == 'ARD') {
                $this->error(403);
            } else {
                $documentId = (int) $documentId;
                $this->load->library("RDDocumentFactory");
                if ($this->rddocumentfactory->updateEmpReceived($documentId)) {
                    redirect('document/details/' . $documentId);
                } else {
                    echo "Failed!";
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }
	
	public function statusChange($documentId = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'ADMIN' || $this->userType == 'SEC' || $this->userType == 'RD' || $this->userType == 'ARD') {
                $this->error(403);
            } else {
                $documentId = (int) $documentId;
				$status = $this->cleanString($_POST['status']);
                $this->load->library("RDDocumentFactory");
                if ($this->rddocumentfactory->updateStatus($documentId,$status)) {
                    redirect('document/details/' . $documentId);
                } else {
                    echo "Failed!";
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }
}