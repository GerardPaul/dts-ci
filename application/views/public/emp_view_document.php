<div class="container">
    <div class="row">
        <div class="col-xs-12">
			<div class="col-sm-6">
				<a class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>document"><i class="glyphicon glyphicon-chevron-left"></i> Back</a>
			</div>
			<div class="col-sm-6">
				<div class="pull-right">
					<?php
					$received = '';
					if ($documents->getEmpDateReceived() == '0') {
						$received = '<a class="btn btn-primary btn-xs" href="" id="received" data-toggle="modal" data-target="#empMarkReceived" data-backdrop="static" data-keyboard="false">Mark as Received</a> <small>*Mark as received to view chat messages.</small>';
					}else {
						$received = $documents->getArdDateReceived() . ' <a class="btn btn-success btn-xs disabled" href="#"><i class="glyphicon glyphicon-ok"></i> Received</a> ';
						$ardName = $documents->getArdName();
						$division = $documents->getDivision();
						echo '<a class="btn btn-primary disabled" href="" id="btn_forward">Forwarded From (<strong>' . $division . '</strong>) ' . $ardName . '</a>';
					}
					?>
					<div class="space-10"></div>
				</div>
			</div>
            <?php
            if ($documents !== FALSE) {
				$download = '';
				if($documents->getAttachment() != 'No File.'){
					$download = '<button class="btn btn-sm btn-success" id="download"><i class="glyphicon glyphicon-download"></i> Download Attachment</button>';
					echo '<input type="hidden" id="path" value="'.$documents->getAttachment().'">';
				}else{
					$download = '<button class="btn btn-sm btn-success"><i class="glyphicon glyphicon-download"></i> Download Attachment</button>';
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
						<?php if ($documents->getEmpDateReceived() != '0') {
							echo '<button class="btn btn-sm btn-primary" id="change" data-toggle="modal" data-target="#changeStatus" data-backdrop="static" data-keyboard="false">Change Status</button> <small><strong>*Change status after taking appropriate action.</strong></small>';
						} ?>
                    </div>
                </div>
            </div>
		 <?php if ($documents->getEmpDateReceived() != '0') { $forwarded = TRUE; ?>
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

<div class="modal fade" id="empMarkReceived" tabindex="-1" role="dialog" aria-labelledby="markReceivedLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="markReceivedLabel">Mark as Received</h4>
            </div>
            <div class="modal-body">
                <p>By clicking <strong>YES</strong> you confirm that you have read and received this document.</p>
            </div>
            <form id="mardReceivedForm" method="post" action="<?php echo base_url(); ?>document/receive/<?php echo $documents->getId(); ?>">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">&nbsp;&nbsp;No&nbsp;&nbsp;</button>
                    <button type="submit" class="btn btn-primary">&nbsp;&nbsp;Yes&nbsp;&nbsp;</button>
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
			<form id="changeStatusForm" class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>document/statusChange/<?php echo $documents->getId(); ?>">
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
    if(isset($current_method) && $current_method == 'details' && isset($forwarded)  && $forwarded){ ?>
        <script type="text/javascript" src="<?php echo base_url("application/assets/js/application/chat.js"); ?>"></script>
<?php }?>