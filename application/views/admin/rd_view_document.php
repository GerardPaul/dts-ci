<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="pull-right">
                <?php
                $received = '';
                if ($documents->getMarkReceived() == '0') {
                    $received = '<a class="btn btn-primary btn-xs" href="" id="received" data-toggle="modal" data-target="#markReceived" data-backdrop="static" data-keyboard="false">Mark as Received</a>';
                } else {
                    $received = $documents->getMarkReceived() . ' <a class="btn btn-success btn-xs disabled" href="#"><i class="glyphicon glyphicon-ok"></i> Received</a> ';
                    echo '<a class="btn btn-primary" href="" id="btn_forward" data-toggle="modal" data-target="#forward" data-backdrop="static" data-keyboard="false">Forward To</a>';
                }
                ?>
                <div class="space-10"></div>
            </div>
            <?php
            if ($documents !== FALSE) {
                echo <<<HTML
				<table class="table table-condensed table-responsive">
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
						<td>{$documents->getAttachment()}</td>
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
            <form id="mardReceivedForm" class="form-horizontal" method="post" action="<?php echo base_url(); ?>admin/document/forward/<?php echo $documents->getId(); ?>">
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
                        <div class="col-md-4 hide" id="tsd_emp">
                            <select class="form-control" name="emp">
                                <option value="">- Select -</option>
                                <?php
                                if (is_array($usersTSD) && count($usersTSD)) {
                                    foreach ($usersTSD as $userTSD) {
                                        if ($userTSD->getUserType() != 'ADMIN')
                                            echo '<option value="' . $userTSD->getId() . '">' . $userTSD->getLastName() . ', ' . $userTSD->getFirstName() . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 hide" id="tssd_emp">
                            <select class="form-control" name="emp">
                                <option value="">- Select -</option>
                                <?php
                                if (is_array($usersTSSD) && count($usersTSSD)) {
                                    foreach ($usersTSSD as $userTSSD) {
                                        if ($userTSSD->getUserType() != 'ADMIN')
                                            echo '<option value="' . $userTSSD->getId() . '">' . $userTSSD->getLastName() . ', ' . $userTSSD->getFirstName() . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 hide" id="fasd_emp">
                            <select class="form-control" name="emp">
                                <option value="">- Select -</option>
                                <?php
                                if (is_array($usersFASD) && count($usersFASD)) {
                                    foreach ($usersFASD as $userFASD) {
                                        if ($userFASD->getUserType() != 'ADMIN')
                                            echo '<option value="' . $userFASD->getId() . '">' . $userFASD->getLastName() . ', ' . $userFASD->getFirstName() . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
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