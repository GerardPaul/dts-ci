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

    function cleanString($string) {
        $detagged = strip_tags($string);
        if (get_magic_quotes_gpc()) {
            $stripped = stripslashes($detagged);
            $escaped = mysql_real_escape_string($stripped);
        } else {
            $escaped = mysql_real_escape_string($detagged);
        }
        return $escaped;
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
        $this->load->admin_template('error_view', $data);
    }

    public function index() {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'EMP') {
                $this->error(403);
            } else {
                $data = array(
                    "title" => $this->title,
                    "header" => 'All Documents',
                    "userType" => $this->userType
                );
                if ($this->userType == 'RD') {
                    $this->rdIndex($data);
                } else if ($this->userType == 'SEC') {
                    $this->adminIndex($data);
                } else if ($this->userType == 'ARD') {
                    $this->ardIndex($data);
                } else {
                    $this->adminIndex($data);
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    private function rdIndex($data) {
        $this->load->library("RDDocumentFactory");
        $data['documents'] = $this->rddocumentfactory->getRDDocument();

        $this->load->admin_template('rd_documents', $data);
    }

    private function adminIndex($data) {
        $this->load->library("DocumentFactory");
        $data['documents'] = $this->documentfactory->getDocument();

        $this->load->admin_template('show_documents', $data);
    }

    private function ardIndex($data) {
        $this->load->library("RDDocumentFactory");
        $data['documents'] = $this->rddocumentfactory->getARDDocument($this->userId);

        $this->load->admin_template('ard_documents', $data);
    }

    public function view($documentId = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'EMP') {
                $this->error(403);
            } else {
                if ($this->userType == 'RD' || $this->userType == 'ARD') {
                    $this->error(403);
                } else {
                    $documentId = (int) $documentId;
                    $this->load->library("DocumentFactory");
                    $documents = $this->documentfactory->getDocument($documentId);
                    if ($documentId > 0 && $documents) {
                        $status = '';
                        $stat = $documents->getStatus();
                        if ($stat == 'Cancelled') {
                            $status = '<small class="text-danger status"><i class="glyphicon glyphicon-remove-sign" title="' . $stat . '" data-toggle="tooltip"></i></small>&nbsp;';
                        } else if ($stat == 'On-Going') {
                            $status = '<small class="text-warning status"><i class="glyphicon glyphicon-info-sign" title="' . $stat . '" data-toggle="tooltip"></i></small>&nbsp;';
                        } else if ($stat == 'Compiled') {
                            $status = '<small class="text-success status"><i class="glyphicon glyphicon-ok-sign" title="' . $stat . '" data-toggle="tooltip"></i></small>&nbsp;';
                        }
                        $data = array(
                            "documents" => $documents,
                            "title" => 'Document Details',
                            "header" => $status . $documents->getSubject(),
                            "userType" => $this->userType
                        );
                        $this->load->admin_template('view_document', $data);
                    } else {
                        $this->error(404);
                    }
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function add() {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'EMP') {
                $this->error(403);
            } else {
                if ($this->userType == 'RD' || $this->userType == 'ARD') {
                    $this->error(403);
                } else {
                    $attachment_path = 'No File.';
                    $filename = date('ymds') . '-' . $_POST['referenceNumber'] . '-' . $_FILES['attachment']['name'];
                    $config = array(
                        'upload_path' => './upload/',
                        'file_name' => $filename,
                        'allowed_types' => 'gif|jpg|jpeg|png|pdf|txt',
                        'max_size' => 2048,
                    );
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('attachment')) {
                        $error = $this->upload->display_errors();
                        echo $error;
                    } else {
                        $upload_data = $this->upload->data();
                        if (isset($upload_data['full_path'])) {
                            $attachment_path = $upload_data['full_path'];
                        } else {
                            $attachment_path = "No File.";
                        }
                    }
                    if ($this->session->userdata('logged_in')) {
                        $this->load->library("DocumentFactory");

                        $subject = $this->cleanString($_POST['subject']);
                        $from = $this->cleanString($_POST['from']);
                        $dueDate = $this->cleanString($_POST['dueDate']);
                        $status = $this->cleanString($_POST['status']);
                        $refNo = $this->cleanString($_POST['referenceNumber']);
                        $dateReceived = $this->cleanString($_POST['dateReceived']);

                        if ($this->documentfactory->addDocument($subject, $from, $dueDate, $attachment_path, $status, $refNo, $dateReceived)) {
                            redirect('admin/document');
                        } else {
                            echo "Failed!";
                        }
                    } else {
                        redirect('login', 'refresh');
                    }
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    //edits
    public function details($documentId = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'EMP') {
                $this->error(403);
            } else {
                if ($this->userType == 'ADMIN' || $this->userType == 'SEC' ) {
                    $this->error(403);
                }else {
                    $documentId = (int) $documentId;
                    $this->load->library("RDDocumentFactory");
                    
                    if($this->userType == 'ARD'){ 
                        $this->ardDetails($documentId);
                    }else {
                        $this->rdDetails($documentId);
                    }
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }
    
    private function ardDetails($documentId){
        $documents = $this->rddocumentfactory->getRDDocument($documentId);
        if ($documentId > 0 && $documents) {
            $status = $this->status($documents->getStatus());
            
            $this->load->library("UserFactory");
            
            $division = $documents->getDivision();
            $users = 'users'.$division;
            
            $usersByDivision = '';
            if($division=='TSSD'){
                $usersByDivision = $this->userfactory->getUserByDivision('TSSD');
            }else if($division=='TSD'){
                $usersByDivision = $this->userfactory->getUserByDivision('TSD');
            }else if($division=='FASD'){
                $usersByDivision = $this->userfactory->getUserByDivision('FASD');
            }
                
            $data = array(
                "documents" => $documents,
                "title" => 'Document Details',
                "header" => $status . $documents->getSubject(),
                "userType" => $this->userType,
                $users => $usersByDivision
            );
            $this->load->admin_template('ard_view_document', $data);
        }else {
            $this->error(404);
        }
    }

    private function rdDetails($documentId) {
        $documents = $this->rddocumentfactory->getRDDocument($documentId);
        if ($documentId > 0 && $documents) {
            $status = $this->status($documents->getStatus());
            
            $this->load->library("UserFactory");
            $data = array(
                "documents" => $documents,
                "title" => 'Document Details',
                "header" => $status . $documents->getSubject(),
                "userType" => $this->userType,
                "usersTSD" => $this->userfactory->getUserByDivision('TSD'),
                "usersTSSD" => $this->userfactory->getUserByDivision('TSSD'),
                "usersFASD" => $this->userfactory->getUserByDivision('FASD')
            );
            $this->load->admin_template('rd_view_document', $data);
        }else {
            $this->error(404);
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
            if ($this->userType == 'EMP') {
                $this->error(403);
            } else {
                if ($this->userType == 'ADMIN' || $this->userType == 'SEC' || $this->userType == 'ARD') {
                    $this->error(403);
                } else {
                    $documentId = (int) $documentId;
                    $this->load->library("RDDocumentFactory");
                    if ($this->rddocumentfactory->updateReceived($documentId)) {
                        redirect('admin/document/details/' . $documentId);
                    } else {
                        echo "Failed!";
                    }
                }
            }
        }
    }

    public function forward($id = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'EMP') {
                $this->error(403);
            } else {
                if ($this->userType == 'ADMIN' || $this->userType == 'SEC' || $this->userType == 'ARD') {
                    $this->error(403);
                } else {
                    $id = (int) $id;
                    $notes = $this->cleanString($_POST['note']);
                    $action = $this->cleanString($_POST['action']);
                    $ardId = $this->cleanString($_POST['ardId']);
                    $empId = $this->cleanString($_POST['empId']);
                    if ($ardId == $empId)
                        $empId = '';

                    $this->load->library("RDDocumentFactory");
                    if ($this->rddocumentfactory->forwardTo($id, $ardId, $empId, $action, $notes)) {
                        redirect('admin/document/details/' . $id);
                    } else {
                        echo "Failed!";
                    }
                }
            }
        }
    }

}
