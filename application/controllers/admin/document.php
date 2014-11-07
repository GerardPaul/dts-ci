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
        }
        $data = array(
            "title" => 'Error ' . $er,
            "header" => 'Error ' . $er . ' ' . $header,
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
                $data = array(
                    "title" => $this->title,
                    "header" => 'All Documents',
                    "userType" => $this->userType,
                    "username" => $this->username,
                    "userId" => $this->userId,
                    "load" => 'documents'
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

    public function getAllDocuments() {
        $userType = $_POST['userType'];
        $userId = $_POST['userId'];
        if ($userType == 'ADMIN' || $userType == 'SEC') {
            $this->load->library("DocumentFactory");
            echo json_encode($this->documentfactory->ajaxGetDocument($_POST['change']));
        } else {
            $this->load->library("TrackFactory");
            echo json_encode($this->trackfactory->ajaxGetDocument($_POST['change'], $userId));
        }
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
                        $status = $this->status($documents->getStatus());
                        $data = array(
                            "documents" => $documents,
                            "title" => 'Document Details',
                            "header" => 'Document Details',
                            "userType" => $this->userType,
                            "username" => $this->username,
                            "status" => $status
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
            if ($this->userType != 'SEC') {
                $this->error(403);
            } else {
                $attachment_path = 'No File.';
                $filename = date('ymds') . '-' . $_POST['referenceNumber'] . '-' . $_FILES['attachment']['name'];
                $config = array(
                    'upload_path' => './upload/',
                    'file_name' => $filename,
                    'allowed_types' => 'gif|jpg|jpeg|png|pdf|docx|doc',
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
                $this->load->library("DocumentFactory");

                $subject = $this->cleanString($_POST['subject']);
                $description = $this->cleanString($_POST['description']);
                $from = $this->cleanString($_POST['from']);
                $refNo = $this->cleanString($_POST['referenceNumber']);

                $due = $this->cleanString($_POST['dueDate']);
                $received = $this->cleanString($_POST['dateReceived']);

                $dueDate = date('Y-m-d', strtotime(str_replace('-', '/', $due)));
                $dateReceived = date('Y-m-d', strtotime(str_replace('-', '/', $received)));

                if ($this->documentfactory->addDocument($subject, $description, $from, $dueDate, $attachment_path, $refNo, $dateReceived)) {
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
            if ($userType == 'EMP' || $userType == 'ADMIN' || $userType == 'SEC') {
                $this->error(403);
            } else {
                $track = (int) $track;
                $this->load->library("TrackFactory");
                $document = $this->trackfactory->getUserDocument($track);
                if ($track > 0 && $document) {
                    $this->receive($this->userId, $track);//automatically receive upon viewing document

                    $status = $this->status($document->getStatus());
                    if ($userType == 'RD') {
                        $this->rdDetails($document, $status);
                    } else {
                        $this->ardDetails($document, $status);
                    }
                } else {
                    $this->error(403);
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    private function rdDetails($document, $status) {
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
            "load" => 'rddetails'
        );
        $this->load->admin_template('rd_view_document', $data);
    }

    private function ardDetails($document, $status) {
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
            "load" => 'arddetails'
        );
        $this->load->admin_template('ard_view_document', $data);
    }

    private function status($status) {
        if ($status == 'Cancelled') {
            return '<span class="text-danger status"><i class="glyphicon glyphicon-remove-sign" title="' . $status . '" data-toggle="tooltip"></i>&nbsp;' . $status . '</span>';
        } else if ($status == 'On-Going') {
            return '<span class="text-warning status"><i class="glyphicon glyphicon-info-sign" title="' . $status . '" data-toggle="tooltip"></i>&nbsp;' . $status . '</span>';
        } else if ($status == 'Compiled') {
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

    public function ardReceive($documentId = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'EMP') {
                $this->error(403);
            } else {
                if ($this->userType == 'ADMIN' || $this->userType == 'SEC' || $this->userType == 'RD') {
                    $this->error(403);
                } else {
                    $documentId = (int) $documentId;
                    $this->load->library("RDDocumentFactory");
                    if ($this->rddocumentfactory->updateArdReceived($documentId)) {
                        redirect('admin/document/details/' . $documentId);
                    } else {
                        echo "Failed!";
                    }
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function statusChange($document = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType != 'RD') {
                $this->error(403);
            } else {
                $document = (int) $document;
                $status = $this->cleanString($_POST['status']);
                $track = $this->cleanString($_POST['trackId']);
                $this->load->library("TrackFactory");
                if ($this->trackfactory->updateStatus($document, $status)) {
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
            if ($userType == 'EMP' || $userType == 'ADMIN' || $userType == 'SEC') {
                $this->error(403);
            } else {
                $document = (int) $document;
                $notes = $this->cleanString($_POST['note']);
                $action = $this->cleanString($_POST['action']);
				
                $deadline = $this->cleanString($_POST['deadline']);
				$deadline = date('Y-m-d', strtotime(str_replace('-', '/', $deadline)));
				
				$users = array();
				foreach($_POST['selectedList'] as $user){
					$users[] = $user;
				}
				
				$track = $this->cleanString($_POST['trackId']);
				
                $this->load->library("TrackFactory");
                if ($this->trackfactory->forward($document, $notes, $action, $deadline, $users)) {
                    redirect('admin/document/details/' . $track);
                } else {
                    echo "Failed!";
                }
            }
        } else {
            redirect('login', 'refresh');
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
        } else {
            redirect('login', 'refresh');
        }
    }

    public function forwardToEmp($id = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'EMP') {
                $this->error(403);
            } else {
                if ($this->userType == 'ADMIN' || $this->userType == 'SEC' || $this->userType == 'RD') {
                    $this->error(403);
                } else {
                    $id = (int) $id;
                    $empId = $this->cleanString($_POST['emp']);

                    $this->load->library("RDDocumentFactory");
                    if ($this->rddocumentfactory->forwardToEmp($id, $empId)) {
                        redirect('admin/document/details/' . $id);
                    } else {
                        echo "Failed!";
                    }
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
                $attachment_path = 'No File.';
                $filename = date('ymds') . '-' . $_POST['referenceNumber'] . '-' . $_FILES['attachment']['name'];
                $config = array(
                    'upload_path' => './upload/',
                    'file_name' => $filename,
                    'allowed_types' => 'gif|jpg|jpeg|png|pdf|docx|doc',
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
                $this->load->library("DocumentFactory");

                $subject = $this->cleanString($_POST['subject']);
                $description = $this->cleanString($_POST['description']);
                $from = $this->cleanString($_POST['from']);
                $refNo = $this->cleanString($_POST['referenceNumber']);
                $status = $this->cleanString($_POST['status']);
				
                $due = $this->cleanString($_POST['dueDate']);
                $received = $this->cleanString($_POST['dateReceived']);

                $dueDate = date('Y-m-d', strtotime(str_replace('-', '/', $due)));
                $dateReceived = date('Y-m-d', strtotime(str_replace('-', '/', $received)));

				if($attachment_path == 'No File.'){
					$attachment_path = $_POST['originalAttachment'];
				}
				
                if ($this->documentfactory->updateDocument($id, $subject, $status, $description, $from, $dueDate, $attachment_path, $refNo, $dateReceived)) {
                    redirect('admin/document');
                } else {
                    echo "Failed!";
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

}
