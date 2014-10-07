<div class="container">
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
                            $status = '<span class="text-danger status"><i class="glyphicon glyphicon-remove-sign" title="' . $stat . '" data-toggle="tooltip"></i></span>';
                        else if ($stat == 'On-Going')
                            $status = '<span class="text-warning status"><i class="glyphicon glyphicon-info-sign" title="' . $stat . '" data-toggle="tooltip"></i></span>';
                        else if ($stat == 'Compiled')
                            $status = '<span class="text-success status"><i class="glyphicon glyphicon-ok-sign" title="' . $stat . '" data-toggle="tooltip"></i></span>';

                        $viewLink = base_url('document/details/' . $document->getId());

                        $subject = $document->getSubject();
                        $received = $document->getEmpDateReceived();
                        if ($document->getEmpDateReceived() == '0') {
                            $subject = '<strong>' . $document->getSubject() . '</strong>';
                            $received = $document->getDateReceived();
                        }

                        echo <<<HTML

					<tr>
						<td>{$document->getFrom()}</td>
						<td>{$status}&nbsp;{$subject}</td>
						<td>{$received}</td>
						<td>{$document->getDueDate()}</td>
						<td>
							<a href="{$viewLink}" class="btn btn-primary btn-xs" title="View" data-toggle="tooltip"><i class="glyphicon glyphicon-search"></i></a>
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
