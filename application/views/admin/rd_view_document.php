<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="col-sm-6">
                <a class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>admin/document"><i class="glyphicon glyphicon-chevron-left"></i> Back</a>
            </div>
            <div class="col-sm-6">
                <div class="pull-right">
                    <?php
                    $received = '';
                    if ($documents->getMarkReceived() == '0') {
                        $received = '<a class="btn btn-primary btn-xs" href="" id="received" data-toggle="modal" data-target="#markReceived" data-backdrop="static" data-keyboard="false">Mark as Received</a> <small>*Mark as received to forward this document to a user.</small>';
                    } else {
                        $received = $documents->getMarkReceived() . ' <a class="btn btn-success btn-xs disabled" href="#"><i class="glyphicon glyphicon-ok"></i> Received</a> ';
                        if ($documents->getArd() == '0') {
                            echo '<a class="btn btn-primary" href="" id="btn_forward" data-toggle="modal" data-target="#forward" data-backdrop="static" data-keyboard="false">Forward To</a>';
                        } else {
                            $ardName = $documents->getArdName();
                            $empName = $documents->getEmpName();
                            $division = $documents->getDivision();
                            echo '<a class="btn btn-primary disabled" href="" id="btn_forward">Forwarded To (<strong>' . $division . '</strong>) ' . $ardName . '/' . $empName . '</a>';
                        }
                    }
                    ?>
                    <div class="space-10"></div>
                </div>
            </div>
            <?php
            if ($documents !== FALSE) {
                $download = '<button class="btn btn-sm btn-success disabled" >No Attachments</button>';
                if ($documents->getAttachment() != 'No File.') {
                    $download = '<form method="post" action="'. base_url() .'admin/document/download"><input type="hidden" name="document" value="'.$documents->getDocument().'"><button class="btn btn-sm btn-success" type="submit"><i class="glyphicon glyphicon-download"></i> Download Attachments</button></form>';
                }
                echo <<<HTML
				<table class="table table-condensed table-responsive" id="documentDetails">
					<tr>
						<th>Ref. #</th>
						<td>{$documents->getReferenceNumber()}</td>
					</tr>
					<tr>
						<th>From</th>
						<td>{$documents->getFrom()}</td>
					</tr>
					<tr>
						<th>Received</th>
						<td>{$received}</td>
					</tr>
					<tr>
						<th>Due</th>
						<td>{$documents->getDueDate()}</td>
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
        <?php if ($documents->getArd() != '0') {
            $forwarded = TRUE; ?>
			<hr />
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-sm-2">
                        <button class="btn btn-sm btn-success" type="button">
                            <strong><?php echo $documents->getAction(); ?></strong>
                        </button>
                        <div class="space-10"></div>
                        <div class="well well-sm">
                            <p><?php echo $documents->getNotes(); ?></p>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <?php
                        if ($documents->getArd() != '0') {
                            echo '<button class="btn btn-sm btn-primary" id="change" data-toggle="modal" data-target="#changeStatus" data-backdrop="static" data-keyboard="false">Change Status</button> <small><strong>*Change status after taking appropriate action.</strong></small>';
                        }
                        ?>
                    </div>
                </div>
            </div>
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
                            <input type="hidden" name="document" id="document" value="<?php echo $documents->getDocument(); ?>">
                            <input type="hidden" name="chat" id="chat" value="1">
                            <input class="form-control" type="text" name="message" id="message">
                        </div>
                    </div>
                </div>
            </div>
<?php }
?>
    </div>
</div>

<div class="modal fade" id="markReceived" tabindex="-1" role="dialog" aria-labelledby="markReceivedLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="markReceivedLabel">Mark as Received</h4>
            </div>
            <div class="modal-body">
                <p>By clicking <strong>YES</strong> you confirm that you have read and received this document.</p>
            </div>
            <form id="mardReceivedForm" method="post" action="<?php echo base_url(); ?>admin/document/receive/<?php echo $documents->getId(); ?>">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">&nbsp;&nbsp;No&nbsp;&nbsp;</button>
                    <button type="submit" class="btn btn-primary">&nbsp;&nbsp;Yes&nbsp;&nbsp;</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="forward" tabindex="-1" role="dialog" aria-labelledby="forwardLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="markReceivedLabel">Forward To</h4>
            </div>
            <form id="markReceivedForm" class="form-horizontal" method="post" action="<?php echo base_url(); ?>admin/document/forward/<?php echo $documents->getId(); ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">To</label>
                        <div class="col-md-4">
                            <select class="form-control" name="ard" id="ard">
                                <option value="">- Select -</option>
                                <option value="1">TSD</option>
                                <option value="2">TSSD</option>
                                <option value="3">FASD</option>
                            </select>
                        </div>
                        <?php
                        $ardTSSD = ''; //This is the ID of the ARD.
                        $ardTSD = ''; //This is the ID of the ARD.
                        $ardFASD = ''; //This is the ID of the ARD.
                        ?>
                        <div class="col-md-4 hide" id="tsd_emp">
                            <select class="form-control" name="empTSD" id="empTSD">
                                <?php
                                if (is_array($usersTSD) && count($usersTSD)) {
                                    foreach ($usersTSD as $userTSD) {
                                        if ($userTSD->getUserType() != 'ADMIN') {
                                            if ($userTSD->getUserType() == 'ARD') {
                                                $ardTSD = $userTSD->getId();
                                            }
                                            echo '<option value="' . $userTSD->getId() . '">' . $userTSD->getLastName() . ', ' . $userTSD->getFirstName() . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 hide" id="tssd_emp">
                            <select class="form-control" name="empTSSD" id="empTSSD">
                                <?php
                                if (is_array($usersTSSD) && count($usersTSSD)) {
                                    foreach ($usersTSSD as $userTSSD) {
                                        if ($userTSSD->getUserType() != 'ADMIN') {
                                            if ($userTSSD->getUserType() == 'ARD') {
                                                $ardTSSD = $userTSSD->getId();
                                            }
                                            echo '<option value="' . $userTSSD->getId() . '">' . $userTSSD->getLastName() . ', ' . $userTSSD->getFirstName() . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 hide" id="fasd_emp">
                            <select class="form-control" name="empFASD" id="empFASD">
                                <?php
                                if (is_array($usersFASD) && count($usersFASD)) {
                                    foreach ($usersFASD as $userFASD) {
                                        if ($userFASD->getUserType() != 'ADMIN') {
                                            if ($userFASD->getUserType() == 'ARD') {
                                                $ardFASD = $userFASD->getId();
                                            }
                                            echo '<option value="' . $userFASD->getId() . '">' . $userFASD->getLastName() . ', ' . $userFASD->getFirstName() . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" value="<?php echo $ardTSD; ?>" id="ardTSD">
                        <input type="hidden" value="<?php echo $ardTSSD; ?>" id="ardTSSD">
                        <input type="hidden" value="<?php echo $ardFASD; ?>" id="ardFASD">
                        <input type="hidden" name="ardId" value="" id="ardId">
                        <input type="hidden" name="empId" value="" id="empId">
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Action</label>
                        <div class="col-md-8">
                            <select class="form-control" name="action">
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
                            <textarea class="form-control" name="note" rows="4"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="changeStatus" tabindex="-1" role="dialog" aria-labelledby="changeStatusLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="changeStatusLabel">Change Status</h4>
            </div>
            <form id="changeStatusForm" class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>admin/document/statusChange/<?php echo $documents->getId(); ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Status</label>
                        <div class="col-md-8">
                            <select class="form-control" name="status" id="status">
                                <option value="">- Select -</option>
                                <option value="On-Going">On-Going</option>
                                <option value="Compiled">Compiled</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                            <span class="help-block">*Change Status only after taking appropriate action.</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-sm btn-primary" type="submit"><i class="glyphicon glyphicon-refresh"></i> Change</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$current_method = $this->router->fetch_method();
if (isset($current_method) && $current_method == 'details' && isset($forwarded) && $forwarded) {
    ?>
    <script type="text/javascript" src="<?php echo base_url("application/assets/js/application/chat.js"); ?>"></script>
<?php
}?>