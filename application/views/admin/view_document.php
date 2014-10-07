<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <?php
            if ($documents !== FALSE) {
				$download = '';
				if($documents->getAttachment() != 'No File.'){
					$download = '<button class="btn btn-sm btn-success" id="download"><i class="glyphicon glyphicon-download"></i> Download Attachment</button>';
					echo '<input type="hidden" id="path" value="'.$documents->getAttachment().'">';
				}else{
					$download = '<button class="btn btn-sm btn-success" ><i class="glyphicon glyphicon-download"></i> Download Attachment</button>';
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
						<td>{$documents->getDateReceived()}</td>
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
    </div>
</div>