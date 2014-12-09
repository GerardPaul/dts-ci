<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <?php
            if ($logs !== FALSE) {

                //Create the HTML table header
                echo <<<HTML

		<table class="table table-condensed table-striped table-responsive table-hover display" id="logsTable">
                    <thead>
                        <tr>
                            <th>ID #</th>
                            <th>Action Taken</th>
                            <th>Event Occured</th>
                        </tr>
                    </thead>
                    <tbody>
HTML;
                //Do we have an array of users or just a single user object?
                if (is_array($logs) && count($logs)) {
                    //Loop through all the users and create a row for each within the table
                    foreach ($logs as $log) {
                        echo <<<HTML
                            <tr>
                                <td>{$log->getId()}</td>
                                <td>{$log->getActionTaken()}</td>
                                <td>{$log->getTimeOccured()}</td>
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
			<div class="alert alert-warning">There are no <strong>Logs</strong> to display.</div>
HTML;
            }
            ?>
        </div>
    </div>
</div>