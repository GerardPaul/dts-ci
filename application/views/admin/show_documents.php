<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <button id="addDivision" class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#addDocumentModal" data-backdrop="static" data-keyboard="false">
                <i class="glyphicon glyphicon-plus-sign"></i> Add Document
            </button>
            <a class="btn btn-danger btn-sm" href=""><i class="glyphicon glyphicon-folder-open"></i>&nbsp;&nbsp;Open Archives</a>
        </div>
    </div>
    <div class="space-10"></div>
    <div class="row">
        <div class="col-xs-12">
            <?php
            if ($documents !== FALSE) {

                //Create the HTML table header
                echo <<<HTML

		<table class="table table-condensed table-striped table-responsive table-hover display" id="documentsTable">
			<thead>
				<tr>
					<th>From</th>
                                        <th>Status</th>
					<th>Subject</th>
					<th>Date Received</th>
					<th>Due Date</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
HTML;
                //Do we have an array of users or just a single user object?
                if (is_array($documents) && count($documents)) {
                    //Loop through all the users and create a row for each within the table
                    foreach ($documents as $document) {
                        $status = '';
                        $stat = $document->getStatus();
                        if ($stat == 'Cancelled')
                            $status = '<span class="text-danger status"><i class="glyphicon glyphicon-remove-sign" title="' . $stat . '" data-toggle="tooltip"></i> ' . $stat . '</span>';
                        else if ($stat == 'On-Going')
                            $status = '<span class="text-warning status"><i class="glyphicon glyphicon-info-sign" title="' . $stat . '" data-toggle="tooltip"></i> ' . $stat . '</span>';
                        else if ($stat == 'Compiled')
                            $status = '<span class="text-success status"><i class="glyphicon glyphicon-ok-sign" title="' . $stat . '" data-toggle="tooltip"></i> ' . $stat . '</span>';

                        $viewLink = base_url('admin/document/view/' . $document->getId());
                        $editLink = base_url('admin/document/edit/' . $document->getId());
                        $archiveLink = base_url('admin/document/archive/' . $document->getId());
						$dateReceived = date('M j, Y', strtotime($document->getDateReceived()));
						$dueDate = date('M j, Y', strtotime($document->getDueDate()));
						
                        echo <<<HTML

					<tr>
						<td>{$document->getFrom()}</td>
                                                <td>{$status}</td>
						<td>{$document->getSubject()}</td>
						<td>{$dateReceived}</td>
						<td>{$dueDate}</td>
						<td>
							<a href="{$viewLink}" class="btn btn-primary btn-xs" title="View Details" data-toggle="tooltip"><i class="glyphicon glyphicon-search"></i></a>
							<a href="{$editLink}" class="btn btn-success btn-xs" title="Edit Document" data-toggle="tooltip"><i class="glyphicon glyphicon-pencil"></i></a>
							<a href="{$archiveLink}" class="btn btn-danger btn-xs" title="Archive Document" data-toggle="tooltip"><i class="glyphicon glyphicon-folder-open"></i></a>
						</td>
					</tr>

HTML;
                    }
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
            <form enctype="multipart/form-data" id="addDocumentForm" method="post" class="form-horizontal" action="<?php echo base_url(); ?>admin/document/add">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Date Received</label>
                        <div class="col-md-8">
                            <div class="input-group date" id="dateReceived">
                                <input id="dateReceived" type="text" class="form-control" name="dateReceived" date-date-format="YYYY/MM/DD" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
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
                                <input id="dateDue" type="text" class="form-control" name="dueDate" date-date-format="YYYY/MM/DD" />
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
                            <input type="file" name="attachment" id="attachment" title="Browse for file..."/>
                            <span class="help-block">* Allowed file types (jpeg,png,gif,pdf).</span>
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