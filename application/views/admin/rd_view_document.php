<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="col-sm-6">
                <a class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>admin/document"><i class="glyphicon glyphicon-chevron-left"></i> Back</a>
                <div class="space-10"></div>
            </div>
            <div class="col-sm-6">
                <div class="pull-right">

                </div>
                <div class="space-10"></div>
            </div>
        </div>
        <div class="col-xs-12">
            <?php
            if ($document !== FALSE) {
                $download = '<button class="btn btn-sm btn-success disabled" >No Attachments</button>';
                if ($document->getAttachment() != 'No File.') {
                    //$download = '<form method="post" action="'. base_url() .'admin/document/download"><input type="hidden" name="document" value="'.$documents->getId().'"><button class="btn btn-sm btn-success" type="submit"><i class="glyphicon glyphicon-download"></i> Download Attachments</button></form>';
                    $str = $document->getAttachment();
                    $link = base_url() . strstr($str, 'upload');
                    $download = '<a href="' . $link . '" class="btn btn-sm btn-success" title="View File" target="_blank">View File <i class="glyphicon glyphicon-new-window"></i></a>';
                }
                $dateReceived = date('j-M-Y', strtotime($document->getDateReceived()));
                $dueDate = date('j-M-Y', strtotime($document->getDueDate()));
                $due15Days = date('j-M-Y', strtotime($document->getDue15Days()));

                $deadline = 'Not Set';
                if ($document->getDeadline() !== '0000-00-00')
                    $deadline = date('j-M-Y', strtotime($document->getDeadline()));

                echo <<<HTML
                    <table class="table table-condensed table-responsive" id="documentDetails">
                        <tr>
                                <th>Subject</th>
                                <td><strong>{$document->getSubject()}</strong></td>
                        </tr>
                        <tr>
                                <th>Description</th>
                                <td>{$document->getDescription()}</td>
                        </tr>
                        <tr>
                                <th>Status</th>
                                <td>{$status}</td>
                        </tr>
                        <tr>
                                <th>Ref. #</th>
                                <td>{$document->getReferenceNumber()}</td>
                        </tr>
                        <tr>
                                <th>From</th>
                                <td>{$document->getFrom()}</td>
                        </tr>
                        <tr>
                                <th>Date Received</th>
                                <td>{$dateReceived}</td>
                        </tr>
                        <tr>
                                <th>Document Deadline</th>
                                <td>{$dueDate}</td>
                        </tr>
                        <tr>
                                <th>RD Deadline</th>
                                <td>{$deadline}</td>
                        </tr>
                        <tr>
                                <th>15-Day Deadline</th>
                                <td>{$due15Days}</td>
                        </tr>
                        <tr>
                                <th>Attachment</th>
                                <td>{$download}</td>
                        </tr>
                    </table>
HTML;
            } else {
                echo <<<HTML
                        <div class="alert alert-warning">There are no <strong>Documents</strong> to display.</div>
HTML;
            }
            ?>
        </div>
        <!--        <div class="col-xs-12">
                    <div class="row">
                        <div class="col-sm-2">
                            <button class="btn btn-sm btn-primary" type="button">
                                Assign To Person
                            </button>
                            <div class="space-10"></div>
                            <div class="well well-sm">
        
                            </div>
                        </div>
                        <div class="col-sm-10">
                            Chat Goes Here
                        </div>
                    </div>
                </div>-->
        <!-- 
        <div class="chatBox">
            <div class="chatContainer">
                <div class="chatHeading">
                    <div class="pull-right">
                        <span id="toggleChat">
                            <i class="glyphicon glyphicon-chevron-down" id="open"></i>
                            <i class="glyphicon glyphicon-chevron-up" id="close" style="display: none"></i>
                        </span>
                    </div>
                    <h3 class="chatTitle">Chat</h3>
                </div>
                <div id="chatContents">
                    <div class="chatBody" id="chatBody">

                    </div>
                    <div class="chatFooter">
                        <input type="hidden" name="document" id="document" value="<?php echo $document->getDocument(); ?>">
                        <input type="hidden" name="chat" id="chat" value="1">
                        <input class="form-control" type="text" name="message" id="message">
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</div>