<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Document extends CI_Controller {

    var $title = 'Documents';
    var $login = FALSE;
    var $userType = '';
    var $userId = '';
    var $username = '';

    private function checkLogin() {
        if ($this->session->userdata('logged_in')) {
            $this->login = TRUE;
            $session_data = $this->session->userdata('logged_in');
            $this->userType = $session_data['userType'];
            $this->userId = $session_data['id'];
            $this->username = $session_data['username'];
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
        } else if ($er == 400) {
            $header = 'No Data Available';
            $content = 'There is no data available for export. Select another date range.';
            $er = 404;
        }
        $data = array(
            "title" => 'Error ' . $er,
            "header" => 'Error ' . $er . ' : ' . $header,
            "content" => $content,
            "userType" => $this->userType,
            "username" => $this->username
        );
        if ($this->userType == 'EMP') {
            $this->load->template('error_view', $data);
        } else {
            $this->load->admin_template('error_view', $data);
        }
    }

    public function index() {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'EMP') {
                $this->error(403);
            } else {
                $this->load->library('DocumentFactory');

                $data = array(
                    "title" => $this->title,
                    "header" => 'All Documents',
                    "userType" => $this->userType,
                    "username" => $this->username,
                    "userId" => $this->userId,
                    "load" => 'documents',
                    "refNo" => $this->documentfactory->getLastRefNo()
                );
                if ($this->userType == 'ADMIN' || $this->userType == 'SEC') {
                    $this->load->admin_template('show_documents', $data);
                } else {
                    $this->load->admin_template('user_documents', $data);
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function sec() {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType != 'SEC') {
                $this->error(403);
            } else {
                $data = array(
                    "title" => $this->title,
                    "header" => 'My Documents',
                    "userType" => $this->userType,
                    "username" => $this->username,
                    "userId" => $this->userId,
                    "load" => 'documents'
                );
                $this->load->admin_template('user_documents', $data);
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function getAllDocuments() {
        $userType = $_POST['userType'];
        $userId = $_POST['userId'];
        $method = $_POST['method'];

        if ($userType == 'ADMIN' || $userType == 'SEC') {
            if ($method == 'sec') {
                $this->load->library("TrackFactory");
                echo json_encode($this->trackfactory->ajaxGetDocument($_POST['change'], $userId));
            } else {
                $this->load->library("DocumentFactory");
                echo json_encode($this->documentfactory->ajaxGetDocument($_POST['change']));
            }
        } else {
            $this->load->library("TrackFactory");
            echo json_encode($this->trackfactory->ajaxGetDocument($_POST['change'], $userId));
        }
    }

    public function view($documentId = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'EMP' || $this->userType == 'RD' || $this->userType == 'ARD') {
                $this->error(403);
            } else {
                $documentId = (int) $documentId;
                $this->load->library("DocumentFactory");
                $documents = $this->documentfactory->getDocument($documentId);

                $seen = $this->documentfactory->getNamesReceived($documentId);
                
                if ($documentId > 0 && $documents) {
                    $status = $this->status($documents->getStatus());
                    $data = array(
                        "documents" => $documents,
                        "title" => 'Document Details',
                        "header" => 'Document Details',
                        "userType" => $this->userType,
                        "username" => $this->username,
                        "status" => $status,
                        "seen" => $seen
                    );
                    $this->load->admin_template('view_document', $data);
                } else {
                    $this->error(404);
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    private function set_upload_config($filename) {
        $config = array();
        $config['upload_path'] = './upload/';
        $config['file_name'] = $filename;
        $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf|docx|doc|xls|xlsx|csv|ppt|pptx|tar|tgz|zip|text';
        $config['max_size'] = 0;

        return $config;
    }

    public function add() {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType != 'SEC') {
                $this->error(403);
            } else {
                $this->load->library("DocumentFactory");
                
                $subject = $this->cleanString($_POST['subject']);
                $description = $this->cleanString($_POST['description']);
                $from = $this->cleanString($_POST['from']);
                $refNo = $this->cleanString($_POST['referenceNumber']);

                $dueDate = $this->cleanString($_POST['dueDate']);
                $received = $this->cleanString($_POST['dateReceived']);

                if ($dueDate == '')
                    $dueDate = '00/00/0000';

                $dateReceived = date('Y-m-d', strtotime(str_replace('-', '/', $received)));

                $files = $_FILES;
                $count = count($_FILES['attachment']['name']);
                $att = '';
                $full_path = '';
                if($count>0){
                    $att = "Has attachment.";
                }else{
                    $att = "No File.";
                }
                    
                $id = $this->documentfactory->addDocument($subject, $description, $from, $dueDate, $att, $refNo, $dateReceived);
                if ($id) {
                    $this->load->library("LogsFactory");
                    $user = $this->username;
                    $action = "$user has added a document, ref no. <a href='" . base_url() . "admin/document/view/$id' target='_blank'>$refNo</a>.";
                    $this->logsfactory->logAction($action);

                    //----- multiple file upload ------

                    $attachment_path = 'No File.';
                    $this->load->library('upload');
                    $this->load->library('FileFactory');
                    for ($i = 0; $i < $count; $i++) {
                        $_FILES['attachment']['name'] = $files['attachment']['name'][$i];
                        $_FILES['attachment']['type'] = $files['attachment']['type'][$i];
                        $_FILES['attachment']['tmp_name'] = $files['attachment']['tmp_name'][$i];
                        $_FILES['attachment']['error'] = $files['attachment']['error'][$i];
                        $_FILES['attachment']['size'] = $files['attachment']['size'][$i];

                        $filename = date('ymds') . '-' . $_POST['referenceNumber'] . '-' . $_FILES['attachment']['name'];

                        $this->upload->initialize($this->set_upload_config($filename));

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
                            $this->filefactory->addFile($id, $attachment_path);
                        }
                    }
                    if($count == 1){
                        $this->documentfactory->updateAttachment($id, $attachment_path);
                    }
                    
                    //----- multiple file upload ------

                    redirect('admin/document');
                } else {
                    echo "Failed!";
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function details($track = 0) {
        $this->checkLogin();
        if ($this->login) {
            $userType = $this->userType;
            if ($userType == 'EMP' || $userType == 'ADMIN') {
                $this->error(403);
            } else {
                $track = (int) $track;
                $this->load->library("TrackFactory");
                $document = $this->trackfactory->getUserDocument($track);
                if ($track > 0 && $document) {
                    $this->receive($this->userId, $track); //automatically receive upon viewing document
                    $status = $this->status($document->getStatus());
                    
                    $seen = $this->trackfactory->getNamesReceived($document->getDocument());
                    
                    if ($userType == 'RD') {
                        $this->rdDetails($document, $status, $track, $seen);
                    } else if ($userType == 'ARD') {
                        $this->ardDetails($document, $status, $track, $seen);
                    } else if ($userType == 'SEC') {
                        $this->secDetails($document, $status, $track, $seen);
                    }

                    if (isset($_GET['notification'])) {
                        $notification_id = $_GET['notification'];
                        $this->load->library('NotificationFactory');
                        $this->notificationfactory->seenNotification($notification_id);
                    }
                    
                    
                } else {
                    $this->error(403);
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    private function rdDetails($document, $status, $track, $seen) {
        $this->load->library("UserFactory");

        $data = array(
            "document" => $document,
            "title" => 'Document Details',
            "header" => 'Document Details',
            "userType" => $this->userType,
            "allUsers" => $this->userfactory->getAllUsers(),
            "username" => $this->username,
            "status" => $status,
            "users" => $this->trackfactory->getCountUserDocuments($document->getDocument()),
            "load" => 'rddetails',
            "track" => $track,
            "seen" => $seen
        );
        $this->load->admin_template('rd_view_document', $data);
    }

    private function ardDetails($document, $status, $track, $seen) {
        $this->load->library("UserFactory");
        $division = $this->userfactory->getDivision($this->userId);

        $usersByDivision = '';
        if ($division == 'TSSD') {
            $usersByDivision = $this->userfactory->getUserByDivision('TSSD');
        } else if ($division == 'TSD') {
            $usersByDivision = $this->userfactory->getUserByDivision('TSD');
        } else if ($division == 'FASD') {
            $usersByDivision = $this->userfactory->getUserByDivision('FASD');
        }

        $data = array(
            "document" => $document,
            "title" => 'Document Details',
            "header" => 'Document Details',
            "userType" => $this->userType,
            "allUsers" => $usersByDivision,
            "username" => $this->username,
            "status" => $status,
            "users" => $this->trackfactory->getCountUserDocuments($document->getDocument()),
            "load" => 'arddetails',
            "track" => $track,
            "seen" => $seen
        );
        $this->load->admin_template('ard_view_document', $data);
    }

    private function secDetails($document, $status, $track, $seen) {
        $this->load->library("UserFactory");
        $division = $this->userfactory->getDivision($this->userId);

        $data = array(
            "document" => $document,
            "title" => 'Document Details',
            "header" => 'Document Details',
            "userType" => $this->userType,
            "username" => $this->username,
            "status" => $status,
            "users" => $this->trackfactory->getCountUserDocuments($document->getDocument()),
            "load" => 'arddetails',
            "track" => $track,
            "seen" => $seen
        );
        $this->load->admin_template('sec_view_document', $data);
    }

    private function status($status) {
        if ($status == 'Cancelled') {
            return '<span class="text-danger status"><i class="glyphicon glyphicon-remove-sign" title="' . $status . '" data-toggle="tooltip"></i>&nbsp;' . $status . '</span>';
        } else if ($status == 'On-Going') {
            return '<span class="text-warning status"><i class="glyphicon glyphicon-info-sign" title="' . $status . '" data-toggle="tooltip"></i>&nbsp;' . $status . '</span>';
        } else if ($status == 'Complied') {
            return '<span class="text-success status"><i class="glyphicon glyphicon-ok-sign" title="' . $status . '" data-toggle="tooltip"></i>&nbsp;' . $status . '</span>';
        }
    }

    private function receive($user, $track) {
        $this->load->library("TrackFactory");
        if ($this->trackfactory->updateReceived($user, $track)) {
            return true;
        }
        return false;
    }

    public function statusChange($document = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'ADMIN' || $this->userType == 'EMP') {
                $this->error(403);
            } else {
                $document = (int) $document;
                $status = $this->cleanString($_POST['status']);
                $track = $this->cleanString($_POST['trackId']);
                $this->load->library("TrackFactory");
                if ($this->trackfactory->updateStatus($document, $status)) {
                    $this->load->library("LogsFactory");
                    $user = $this->username;

                    $this->load->library("DocumentFactory");
                    $refNo = $this->documentfactory->getRefNo($document);
                    $action = "$user has changed status of document, ref no. <a href='" . base_url() . "admin/document/view/$document' target='_blank'>$refNo</a>.";
                    $this->logsfactory->logAction($action);

                    redirect('admin/document/details/' . $track);
                } else {
                    echo "Failed!";
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function ardAssign($document = 0) {
        $this->checkLogin();
        if ($this->login) {
            $userType = $this->userType;
            if ($userType != 'ARD') {
                $this->error(403);
            } else {
                $document = (int) $document;

                $this->load->library("UserFactory");
                $usernames = "";

                $users = array();
                foreach ($_POST['selectedList'] as $user) {
                    $users[] = $user;
                    $usernames .= $this->userfactory->getUsername($user) . ", ";
                }

                $track = $this->cleanString($_POST['trackId']);

                $this->load->library("TrackFactory");
                if ($this->trackfactory->ardForward($this->userId, $document, $users)) {
                    $this->load->library("LogsFactory");
                    $assignedBy = $this->username;

                    $this->load->library("DocumentFactory");
                    $refNo = $this->documentfactory->getRefNo($document);
                    $action = "$assignedBy has assigned document ref no. <a href='" . base_url() . "admin/document/view/$document' target='_blank'>$refNo</a> to $usernames";
                    $this->logsfactory->logAction($action);

                    redirect('admin/document/details/' . $track);
                } else {
                    echo "Failed!";
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function assign($document = 0) {
        $this->checkLogin();
        if ($this->login) {
            $userType = $this->userType;
            if ($userType != 'RD') {
                $this->error(403);
            } else {
                $document = (int) $document;
                $notes = $this->cleanString($_POST['note']);
                $action = $this->cleanString($_POST['action']);

                $deadline = "";
                if(isset($_POST['deadline'])){
                    $deadline = $this->cleanString($_POST['deadline']);
                }else{
                    $deadline = "00/00/0000";
                }
                $deadline = date('Y-m-d', strtotime(str_replace('-', '/', $deadline)));

                $this->load->library("UserFactory");
                $usernames = "";

                $users = array();
                foreach ($_POST['selectedList'] as $user) {
                    $users[] = $user;
                    $usernames .= $this->userfactory->getUsername($user) . ", ";
                }

                $track = $this->cleanString($_POST['trackId']);

                $this->load->library("TrackFactory");
                if ($this->trackfactory->forward($document, $notes, $action, $deadline, $users, $this->userId)) {
                    if (isset($_POST['urgent'])) {
                        $this->trackfactory->setUrgent($document);
                    }

                    $this->load->library("LogsFactory");
                    $assignedBy = $this->username;

                    $this->load->library("DocumentFactory");
                    $refNo = $this->documentfactory->getRefNo($document);
                    $action = "$assignedBy has assigned document ref no. <a href='" . base_url() . "admin/document/view/$document' target='_blank'>$refNo</a> to $usernames";
                    $this->logsfactory->logAction($action);

                    redirect('admin/document/details/' . $track);
                } else {
                    echo "Failed!";
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function download() {
        $this->checkLogin();
        if ($this->login) {
            if (isset($_POST['document'])) {
                $this->load->library("DocumentFactory");
                $document = $this->documentfactory->getDocument($_POST['document']);

                $path = $document->getAttachment();
                $name = date('mdYHis');
                $this->_push_file($path, $name);
            } else {
                $this->error(404);
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function _push_file($path, $name) {
        // make sure it's a file before doing anything!
        if (is_file($path)) {
            // required for IE
            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }

            // get the file mime type using the file extension
            $this->load->helper('file');

            $mime = get_mime_by_extension($path);

            // Build the headers to push out the file properly.
            header('Pragma: public');     // required
            header('Expires: 0');         // no cache
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
            header('Cache-Control: private', false);
            header('Content-Type: ' . $mime);  // Add the mime type from Code igniter.
            header('Content-Disposition: attachment; filename="' . basename($name) . '"');  // Add the file name
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($path)); // provide file size
            header('Connection: close');
            readfile($path); // push it out
            exit();
        }
    }

    public function edit($id = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType != 'SEC') {
                $this->error(403);
            } else {
                $id = (int) $id;
                $this->load->library("DocumentFactory");
                $document = $this->documentfactory->getDocument($id);
                if ($document && $id > 0) {
                    $data = array(
                        "document" => $document,
                        "title" => $this->title,
                        "header" => 'Edit Document',
                        "userType" => $this->userType,
                        "username" => $this->username
                    );
                    $this->load->admin_template('edit_document', $data);
                } else {
                    $this->error(404);
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function update($id = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType != 'SEC') {
                $this->error(403);
            } else {
                $originalPath = $_POST['originalAttachment'];

                $attachment_path = 'No File.';
                $filename = date('ymds') . '-' . $_POST['referenceNumber'] . '-' . $_FILES['attachment']['name'];
                $config = array(
                    'upload_path' => './upload/',
                    'file_name' => $filename,
                    'allowed_types' => 'gif|jpg|jpeg|png|pdf|docx|doc|xls|xlsx|csv|ppt|pptx|tar|tgz|zip|text',
                    'max_size' => 0,
                );
                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('attachment')) {
                    $error = $this->upload->display_errors();
                    echo $error;
                } else {
                    $upload_data = $this->upload->data();
                    if (isset($upload_data['full_path'])) {
                        $attachment_path = $upload_data['full_path'];
                        //delete old file
                        if (is_file($originalPath)) {
                            unlink($originalPath);
                        }
                    } else {
                        $attachment_path = "No File.";
                    }
                }
                $this->load->library("DocumentFactory");

                $subject = $this->cleanString($_POST['subject']);
                $description = $this->cleanString($_POST['description']);
                $from = $this->cleanString($_POST['from']);
                $refNo = $this->cleanString($_POST['referenceNumber']);
                $status = $this->cleanString($_POST['status']);

                $due = $this->cleanString($_POST['dueDate']);
                $received = $this->cleanString($_POST['dateReceived']);

                if ($dueDate == '')
                    $dueDate = '00/00/0000';

                $dateReceived = date('Y-m-d', strtotime(str_replace('-', '/', $received)));

                if ($attachment_path == 'No File.') {
                    $attachment_path = $originalPath;
                }

                if ($this->documentfactory->updateDocument($id, $subject, $status, $description, $from, $dueDate, $attachment_path, $refNo, $dateReceived)) {
                    $this->load->library("LogsFactory");
                    $user = $this->username;
                    $action = "$user has updated document, ref no. <a href='" . base_url() . "admin/document/view/$id' target='_blank'>$refNo</a>.";
                    $this->logsfactory->logAction($action);

                    redirect('admin/document');
                } else {
                    echo "Failed!";
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function export() {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType != 'SEC') {
                $this->error(403);
            } else {
                $fromDate = date('Ymd', strtotime(str_replace('-', '/', $_POST['fromDate'])));
                $toDate = date('Ymd', strtotime(str_replace('-', '/', $_POST['toDate'])));


                $this->load->library("DocumentFactory");
                $documents = $this->documentfactory->getUncomplied($fromDate, $toDate);
                if ($documents) {
                    $heading = array('Document Subject', 'Document Description', 'From', 'Date Received', 'RD Deadline', 'Due Date', '15 Day', 'Chat Messages');
                    $this->load->library('PHPExcel');
                    //$this->load->library('PHPExcel/IOFactory');
                    $excel = new PHPExcel();
                    $excel->getActiveSheet()->setTitle('Export Uncomplied');
                    $excel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                    
                    $col = 0;
                    foreach ($heading as $h) {
                        $excel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $h);
                        $excel->getActiveSheet()->setCellValueByColumnAndRow($col, 2, "-------");
                        $col++;
                    }

                    $num = count($documents);
                    $row = 3;
                    foreach ($documents as $document) {
                        $excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $document->getSubject());
                        $excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $document->getDescription());
                        $excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $document->getFrom());
                        $excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $document->getDateReceived());
                        $excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $document->getDueRD());
                        $excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $document->getDueDate());
                        $excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $document->getDue15Days());
                        $excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $document->getChat());
                        
                        $excel->getActiveSheet()->getColumnDimensionByColumn(7)->setWidth(500);
                        $excel->getActiveSheet()->getStyleByColumnAndRow(7, $row)->getAlignment()->setWrapText(true);
                        $row++;
                    }
                    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="export.xls"');
                    header('Cache-Control: max-age=0');

                    $writer->save('php://output');

                    redirect('admin/document', 'refresh');
                } else {
                    $this->error(400);
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function getAttachment($id = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'EMP') {
                $this->error(403);
            } else {
                $id = (int) $id;
                $this->load->library("DocumentFactory");
                $attachments = $this->documentfactory->getAttachments($id);
                if ($attachments && $id > 0) {
                    if(sizeof($attachments)>1){
                        $this->load->library('zip');
                        for($i = 0; $i < sizeof($attachments); $i++){
                           $this->zip->read_file($attachments[$i]); 
                        }
                        $this->zip->download('attachments.zip');
                    }else{
                        
                    }
                } else {
                    $this->error(404);
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }
}
