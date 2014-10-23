<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="col-sm-6">
                <a class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>admin/document"><i class="glyphicon glyphicon-chevron-left"></i> Back</a>
                <div class="space-10"></div>
            </div>
            <div class="col-sm-6">
                
            </div>
            <?php
            if ($documents !== FALSE) {
                $download = '<button class="btn btn-sm btn-success disabled" >No Attachments</button>';
                if ($documents->getAttachment() != 'No File.') {
                    $download = '<form method="post" action="'. base_url() .'admin/document/download"><input type="hidden" name="document" value="'.$documents->getId().'"><button class="btn btn-sm btn-success" type="submit"><i class="glyphicon glyphicon-download"></i> Download Attachments</button></form>';
                }
				$dateReceived = date('M j, Y', strtotime($documents->getDateReceived()));
				$dueDate = date('M j, Y', strtotime($documents->getDueDate()));
				
                echo <<<HTML
				<table class="table table-condensed table-responsive" id="documentDetails">
					<tr>
						<th>Subject</th>
						<td><strong>{$documents->getSubject()}</strong></td>
					</tr>
					<tr>
						<th>Status</th>
						<td>{$status}</td>
					</tr>
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
						<td>{$dateReceived}</td>
					</tr>
					<tr>
						<th>Due</th>
						<td>{$dueDate}</td>
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
            <hr>
        </div>
    </div>
</div>