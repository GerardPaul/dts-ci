<div class="container">
<div class="row">
	<div class="col-xs-12">
		<button id="addDivision" class="btn btn-primary btn-xs" type="button" data-toggle="modal" data-target="#addDocumentModal" data-backdrop="static" data-keyboard="false">
			Add Document
		</button>
	</div>
</div>
<div class="space-10"></div>
<div class="row">
	<div class="col-xs-12">
<?php 
if ($documents !== FALSE) {

		//Create the HTML table header
		echo <<<HTML

		<table class="table table-condensed table-striped table-responsive table-hover display" id="dataTable">
			<thead>
				<tr>
					<th>Ref #</th>
					<th>Subject</th>
					<th>From</th>
					<th>Due Date</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
HTML;
		//Do we have an array of users or just a single user object?
		if (is_array($documents) && count($documents)) {
			//Loop through all the users and create a row for each within the table
			foreach ($documents as $document) {
				echo <<<HTML

					<tr>
						<td>{$document->getReferenceNumber()}</td>
						<td>{$document->getSubject()}</td>
						<td>{$document->getFrom()}</td>
						<td>{$document->getDueDate()}</td>
						<td>{$document->getStatus()}</td>
					</tr>

HTML;
			}

		} else {
			//Only a single user object so just create one row within the table
			echo <<<HTML

					<tr>
						<td>{$documents->getReferenceNumber()}</td>
						<td>{$documents->getSubject()}</td>
						<td>{$documents->getFrom()}</td>
						<td>{$documents->getDueDate()}</td>
						<td>{$documents->getStatus()}</td>
					</tr>

HTML;
		}
		//Close the table HTML
		echo <<<HTML
			</tbody>
		</table>
HTML;

	} else {
		//Now user could be found so display an error messsage to the user
		echo <<<HTML

			<div class="alert alert-warning">There are no <strong>Documents</strong> to display.</div>

HTML;
	}
?>
	</div>
</div>
</div>

<div class="modal fade" id="addDocumentModal" tabindex="-1" role="dialog" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close cancelDocumentForm" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="addDocumentModalLabel">Add Document</h4>
			</div>
			<form id="addDocumentForm" method="post" class="form-horizontal" action="<?php echo base_url(); ?>document/add">
				<div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Reference No.</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="referenceNumber" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Subject</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="subject" />
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-md-3 control-label">From</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="from" />
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-md-3 control-label">Due Date</label>
                        <div class="col-md-8">
							<div class="input-group date" id="dueDate">
								<input type="text" class="form-control" name="dueDate" date-date-format="YYYY/MM/DD" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-md-3 control-label">Status</label>
                        <div class="col-md-8">
                            <select class="form-control" name="status">
								<option value="">- Select -</option>
								<option value="On-Going">On-Going</option>
								<option value="Compiled">Compiled</option>
								<option value="Cancelled">Cancelled</option>
							</select>
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-md-3 control-label">Attachment</label>
                        <div class="col-md-8">
                            <input type="file" data-filename-placement="inside" name="attachment" title="Browse"/>
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default cancelDocumentForm" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary saveDocumentForm">Confirm Add</button>
				</div>
			</form>
		</div>
	</div>
</div>