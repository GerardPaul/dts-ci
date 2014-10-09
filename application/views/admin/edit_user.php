<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>admin/user">
                <i class="glyphicon glyphicon-chevron-left"></i> Back
            </a>
        </div>
    </div>
    <div class="space-10"></div>
    <div class="row">
        <div class="col-xs-12">
            <div class="well">
                <form id="addUserForm" method="post" class="form-horizontal" action="<?php echo base_url('admin/user/update/' . $user->getId()); ?>">							
                    <input type="hidden" name="userId" value="<?php echo $user->getId(); ?>">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Account</label>
                        <div class="col-md-8">
                            <label class="radio-inline">
                                <input type="radio" name="status" id="account1" value="1" checked>
                                Activate
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="status" id="account1" value="0">
                                De-Activate
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Full Name</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="firstname" placeholder="First Name" value="<?php echo $user->getFirstName(); ?>" autofocus="" />
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="lastname" placeholder="Last Name" value="<?php echo $user->getLastName(); ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">E-mail</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="email" value="<?php echo $user->getEmail(); ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Username</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="username" value="<?php echo $user->getUsername(); ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Password</label>
                        <div class="col-md-4">
                            <input type="password" class="form-control" name="password" placeholder="Password" value="password"/>
                            <span class="help-block">* Leave as is if you don't want to change password.</span>
                        </div>
                        <div class="col-md-4">
                            <input type="password" class="form-control" name="cpassword" placeholder="Confirm Password" value="password" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">User Type</label>
                        <div class="col-md-3">
                            <select class="form-control" name="userType" id="userType">
                                <option value="RD">RD</option>
                                <option value="SEC">SEC</option>
                                <option value="ARD">ARD</option>
                                <option value="EMP">EMP</option>
                                <option value="ADMIN">ADMIN</option>
                            </select>
                        </div>
                        <label class="col-md-1 control-label">Division</label>
                        <div class="col-md-3">
                            <select class="form-control" name="division" id="division">
                                <?php
                                foreach ($divisions as $division) {
                                    echo <<<HTML
					<option value="{$division->getId()}">{$division->getDivisionName()}</option>
HTML;
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" id="currentUserType" value="<?php echo $user->getUserType(); ?>">
                        <input type="hidden" id="currentDivision" value="<?php echo $user->getDivision(); ?>">
                    </div>
                    <hr>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <button type="submit" class="btn btn-success saveUserForm">Confirm Update</button>
                            <a class="btn btn-default" href="<?php echo base_url(); ?>admin/user">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>