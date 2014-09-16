<div class="container">
<div class="row">
	<div class="col-xs-12">
		<button class="btn btn-primary btn-xs" type="button" data-toggle="modal" data-target="#addUserModal">
		  Add User
		</button>
	</div>
</div>
<div class="space-10"></div>
<div class="row">
	<div class="col-xs-12">
<?php 
if ($users !== FALSE) {

		//Create the HTML table header
		echo <<<HTML

		<table class="table table-condensed table-striped table-responsive table-hover display" id="userTable">
			<thead>
				<tr>
					<th>ID #</th>
					<th>Username</th>
					<th>Password</th>
					<th>Full Name</th>
					<th>User Type</th>
					<th>Salt</th>
					<th>Division</th>
					<th>Division Name</th>
					<th>Division Desciription</th>
				</tr>
			</thead>
			<tbody>
HTML;
		//Do we have an array of users or just a single user object?
		if (is_array($users) && count($users)) {
			//Loop through all the users and create a row for each within the table
			foreach ($users as $user) {
				echo <<<HTML

					<tr>
						<td>{$user->getId()}</td>
						<td>{$user->getUsername()}</td>
						<td>{$user->getPassword()}</td>
						<td>{$user->getLastName()}, {$user->getFirstName()}</td>
						<td>{$user->getUserType()}</td>
						<td>{$user->getSalt()}</td>
						<td>{$user->getDivision()}</td>
						<td>{$user->getDivisionName()}</td>
						<td>{$user->getDivisionDescription()}</td>
					</tr>

HTML;
			}

		} else {
			//Only a single user object so just create one row within the table
			echo <<<HTML

					<tr>
						<td>{$users->getId()}</td>
						<td>{$users->getUsername()}</td>
						<td>{$users->getPassword()}</td>
						<td>{$users->getLastName()}, {$users->getFirstName()}</td>
						<td>{$users->getUserType()}</td>
						<td>{$users->getSalt()}</td>
						<td>{$users->getDivision()}</td>
						<td>{$users->getDivisionName()}</td>
						<td>{$users->getDivisionDescription()}</td>
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

			<p>A user could not be found with the specified user ID#, please try again.</p>	

HTML;
	}
?>
	</div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="addUserModalLabel">Add User</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>