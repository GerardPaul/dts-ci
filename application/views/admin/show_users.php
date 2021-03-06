<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#addUserModal" data-backdrop="static" data-keyboard="false">
                <i class="glyphicon glyphicon-plus-sign"></i> Add User
            </button>
        </div>
    </div>
    <input type="hidden" name="userId" id="userId" value="<?php echo $userId; ?>" >
    <div class="space-10"></div>
    <div class="row">
        <div class="col-xs-12">
            <?php
            if ($users !== FALSE) {

                //Create the HTML table header
                echo <<<HTML

		<table class="table table-condensed table-striped table-responsive table-hover display" id="usersTable">
			<thead>
				<tr>
					<th>Full Name</th>
					<th>E-mail Address</th>
					<th>User Type</th>
					<th>Division</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
HTML;
                //Do we have an array of users or just a single user object?
                if (is_array($users) && count($users)) {
                    //Loop through all the users and create a row for each within the table
                    foreach ($users as $user) {
                        $status = '';
                        if ($user->getStatus()) {
                            $status = '<span class="text-success status"><i class="glyphicon glyphicon-eye-open" title="Active" data-toggle="tooltip"></i></span>';
                        } else {
                            $status = '<span class="text-danger status"><i class="glyphicon glyphicon-eye-close" title="Inactive" data-toggle="tooltip"></i></span>';
                        }

                        $editLink = base_url('admin/user/edit/' . $user->getId());

                        echo <<<HTML

					<tr>
						<td>{$status}&nbsp;{$user->getLastName()}, {$user->getFirstName()}</td>
						<td>{$user->getEmail()}</td>
						<td>{$user->getUserType()}</td>
						<td>{$user->getDivisionName()}</td>
						<td>
							<a href="{$editLink}" class="btn btn-success btn-xs" title="Edit" data-toggle="tooltip"><i class="glyphicon glyphicon-pencil"></i></a>
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

			<div class="alert alert-warning">There are no <strong>Users</strong> to display.</div>	

HTML;
            }
            ?>
        </div>
    </div>
</div>
<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cancelAddForm" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="addUserModalLabel">Add User</h4>
            </div>
            <form id="addUserForm" method="post" class="form-horizontal" action="<?php echo base_url(); ?>admin/user/add">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Full Name</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="firstname" placeholder="First Name" />
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="lastname" placeholder="Last Name" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">E-mail</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="email" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Username</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="username" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Password</label>
                        <div class="col-md-4">
                            <input type="password" class="form-control" name="password" placeholder="Password"/>
                        </div>
                        <div class="col-md-4">
                            <input type="password" class="form-control" name="cpassword" placeholder="Confirm Password" />
                        </div>
                        <span class="btn btn-primary hide"><i class="glyphicon glyphicon-refresh"></i></span>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">User Type</label>
                        <div class="col-md-8">
                            <select class="form-control" name="userType">
                                <option value="">- Select -</option>
                                <option value="RD">RD</option>
                                <option value="SEC">SEC</option>
                                <option value="ARD">ARD</option>
                                <option value="EMP">EMP</option>
                                <option value="ADMIN">ADMIN</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Division</label>
                        <div class="col-md-8">
                            <select class="form-control" name="division">
                                <option value="">- Select -</option>
                                <?php
                                foreach ($divisions as $division) {
                                    echo <<<HTML
									<option value="{$division->getId()}">{$division->getDivisionName()}</option>
HTML;
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default cancelUserForm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary saveUserForm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>