<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <button id="addDivision" class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#addDivisionModal" data-backdrop="static" data-keyboard="false">
                <i class="glyphicon glyphicon-plus-sign"></i> Add Division
            </button>
        </div>
    </div>
    <div class="space-10"></div>
    <div class="row">
        <div class="col-xs-12">
            <?php
            if ($divisions !== FALSE) {

                //Create the HTML table header
                echo <<<HTML

		<table class="table table-condensed table-striped table-responsive table-hover display" id="divisionsTable">
			<thead>
				<tr>
					<th>ID #</th>
					<th>Division Name</th>
					<th>Division Desciription</th>
				</tr>
			</thead>
			<tbody>
HTML;
                //Do we have an array of users or just a single user object?
                if (is_array($divisions) && count($divisions)) {
                    //Loop through all the users and create a row for each within the table
                    foreach ($divisions as $division) {
                        echo <<<HTML

					<tr>
						<td>{$division->getId()}</td>
						<td>{$division->getDivisionName()}</td>
						<td>{$division->getDivisionDescription()}</td>
					</tr>

HTML;
                    }
                } else {
                    //Only a single user object so just create one row within the table
                    echo <<<HTML

					<tr>
						<td>{$divisions->getId()}</td>
						<td>{$divisions->getDivisionName()}</td>
						<td>{$divisions->getDivisionDescription()}</td>
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

			<div class="alert alert-warning">There are no <strong>Divisions</strong> to display.</div>

HTML;
            }
            ?>
        </div>
    </div>
</div>

<div class="modal fade" id="addDivisionModal" tabindex="-1" role="dialog" aria-labelledby="addDivisionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cancelDivisionForm" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="addDivisionModalLabel">Add Division</h4>
            </div>
            <form id="addDivisionForm" method="post" class="form-horizontal" action="<?php echo base_url(); ?>admin/division/add">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Division Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="divisionName" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Description</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="description" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default cancelDivisionForm" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary saveDivisionForm">Confirm Add</button>
                </div>
            </form>
        </div>
    </div>
</div>