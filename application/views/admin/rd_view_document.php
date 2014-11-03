<input type="hidden" name="load" id="load" value="<?php echo $load; ?>">
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="col-sm-6">
                <div class="visible-md visible-lg visible-sm visible-xs btn-group" id="actionButtons">
                    <a class="btn btn-sm btn-default" href="<?php echo base_url(); ?>admin/document" data-toggle="tooltip" title="Back to Documents"><i class="glyphicon glyphicon-chevron-left"></i> Back</a>
                    <button type="button" class="btn btn-sm btn-primary" title="Forward this document to person/s responsible." data-toggle="modal" data-target="#forwardModal" data-backdrop="static" data-keyboard="false"><i class="glyphicon glyphicon-share"></i> Forward To</button>
                    <button type="button" class="btn btn-sm btn-warning" title="Change status of this document." data-toggle="modal" data-target="#changeStatusModal" data-backdrop="static" data-keyboard="false"><i class="glyphicon glyphicon-refresh"></i> Change Status</button>
                </div>
                <div class="space-10"></div>
            </div>
            <div class="col-sm-6">
                <div class="pull-right">

                </div>
                <div class="space-10"></div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="space-10"></div>
            <?php
            if ($document !== FALSE) {
                $download = '<button class="btn btn-sm btn-success disabled" >No Attachments</button>';
                if ($document->getAttachment() != 'No File.') {
                    //$download = '<form method="post" action="'. base_url() .'admin/document/download"><input type="hidden" name="document" value="'.$documents->getId().'"><button class="btn btn-sm btn-success" type="submit"><i class="glyphicon glyphicon-download"></i> Download Attachments</button></form>';
                    $str = $document->getAttachment();
                    $link = base_url() . strstr($str, 'upload');
                    $download = '<a href="' . $link . '" class="btn btn-sm btn-success" title="View this File" target="_blank" data-toggle="tooltip">View File <i class="glyphicon glyphicon-new-window"></i></a>';
                }
                $dateReceived = date('j-M-Y', strtotime($document->getDateReceived()));
                $dueDate = date('j-M-Y', strtotime($document->getDueDate()));
                $due15Days = date('j-M-Y', strtotime($document->getDue15Days()));

                $deadline = '<strong>Not Set</strong>';
                if ($document->getDeadline() !== '0000-00-00') {
                    $deadline = date('j-M-Y', strtotime($document->getDeadline()));
                }

                echo '<input type="hidden" name="getUserCount" id="getUserCount" value="' . $users . '" >';

                echo <<<HTML
                    <table class="table table-condensed table-responsive table-striped table-hover" id="documentDetails">
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

<div class="modal fade" id="forwardModal" tabindex="-1" role="dialog" aria-labelledby="forwardLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="forwardLabel">Assign To Person/s Responsible</h4>
            </div>
            <form id="assignForm" class="form-horizontal" method="post" action="<?php echo base_url(); ?>admin/document/deadline/<?php echo $document->getDocument(); ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Assign To</label>
                        <div class="col-md-12">
                            <div class="col-xs-5">
                                <select name="list" id="list" class="selectList form-control" multiple>
                                    <?php foreach ($allUsers as $user) {
                                        if($user->getUserType() !== 'ADMIN' && $user->getUserType() !== 'RD'){
                                            echo '<option value="'.$user->getId().'">'.$user->getLastName().','.$user->getFirstName().'</option>';
                                        }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-xs-1" id="move">
                                <button class="btn btn-xs btn-primary" type="button" onclick="moveItem();"><i class="glyphicon glyphicon-chevron-right"></i></button>&nbsp;
                                <button class="btn btn-xs btn-primary" type="button" onclick="removeItem();"><i class="glyphicon glyphicon-chevron-left"></i></button>
                            </div>
                            <div class="col-xs-5">
                                <select name="selectedList" id="selectedList" class="selectList form-control" multiple>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Deadline</label>
                        <div class="col-md-8">
                            <div class="input-group date">
                                <input id="deadline" type="text" class="form-control" name="deadline" date-format="YYYY/MM/DD" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Action</label>
                        <div class="col-md-8">
                            <select class="form-control" name="status" id="status">
                                <option value="">- Select -</option>
                                <option value="Handle">Handle</option>
                                <option value="Comment">Comment</option>
                                <option value="Information">Information</option>
                                <option value="Prepare Draft">Prepare Draft</option>
                                <option value="Reply">Reply</option>
                                <option value="Discuss with Me">Discuss w/ Me</option>
                                <option value="Note and File">Note & File</option>
                                <option value="Note and Return">Note & Return</option>
                                <option value="Give Status">Give Status</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Note</label>
                        <div class="col-md-8">
                            <textarea class="form-control" name="note" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" aria-labelledby="changeStatusLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="changeStatusLabel">Change Status</h4>
            </div>
            <form id="changeStatusForm" class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>admin/document/statusChange/<?php echo $document->getDocument(); ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Status</label>
                        <div class="col-md-8">
                            <select class="form-control" name="status" id="status">
                                <option value="">- Select -</option>
                                <option value="On-Going">On-Going</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="trackId" id="trackId" value="<?php echo $document->getId(); ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-sm btn-primary" type="submit"><i class="glyphicon glyphicon-refresh"></i> Change</button>
                </div>
            </form>
        </div>
    </div>
</div>