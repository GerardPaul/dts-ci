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
                    if ($documents->getArdDateReceived() == '0') {
                        $received = '<a class="btn btn-primary btn-xs" href="" id="received" data-toggle="modal" data-target="#ardMarkReceived" data-backdrop="static" data-keyboard="false">Mark as Received</a> <small>*Mark as received to view chat messages.</small>';
                    } else {
                        $received = $documents->getArdDateReceived() . ' <a class="btn btn-success btn-xs disabled" href="#"><i class="glyphicon glyphicon-ok"></i> Received</a> ';
                        if ($documents->getEmp() == '0') {
                            echo '<a class="btn btn-primary" href="" id="btn_forward" data-toggle="modal" data-target="#forward" data-backdrop="static" data-keyboard="false">Forward To</a>';
                        } else {
                            $empName = $documents->getEmpName();
                            $division = $documents->getDivision();
                            echo '<a class="btn btn-primary disabled" href="" id="btn_forward">Forwarded To (<strong>' . $division . '</strong>) ' . $empName . '</a>';
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
                    if ($documents->getEmp() != '0') {
                        echo '<button class="btn btn-sm btn-primary" id="change" data-toggle="modal" data-target="#changeStatus" data-backdrop="static" data-keyboard="false">Change Status</button> <small><strong>*Change status after taking appropriate action.</strong></small>';
                    }
                    ?>
                </div>
            </div>
        </div>
<?php if ($documents->getArdDateReceived() != '0') {
    $forwarded = TRUE; ?>
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

<div class="modal fade" id="ardMarkReceived" tabindex="-1" role="dialog" aria-labelledby="markReceivedLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="markReceivedLabel">Mark as Received</h4>
            </div>
            <div class="modal-body">
                <p>By clicking <strong>YES</strong> you confirm that you have read and received this document.</p>
            </div>
            <form id="mardReceivedForm" method="post" action="<?php echo base_url(); ?>admin/document/ardReceive/<?php echo $documents->getId(); ?>">
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
            <form id="ardMarkReceivedForm" class="form-horizontal" method="post" action="<?php echo base_url(); ?>admin/document/forwardToEmp/<?php echo $documents->getId(); ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">To</label>
                        <div class="col-md-8" id="users_emp">
                            <select class="form-control" name="emp" id="emp">
                                <option value="">- Select -</option>
                                <?php
                                if (is_array($users) && count($users)) {
                                    foreach ($users as $user) {
                                        if ($user->getUserType() == 'EMP') {
                                            echo '<option value="' . $user->getId() . '">' . $user->getLastName() . ', ' . $user->getFirstName() . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
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