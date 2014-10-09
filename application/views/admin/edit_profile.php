<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="well">
                <form id="addUserForm" method="post" class="form-horizontal" action="<?php echo base_url('admin/profile/update'); ?>">							
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
                    <hr>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <button type="submit" class="btn btn-success saveUserForm">Confirm Update</button>
                            <a class="btn btn-default" href="<?php echo base_url(); ?>admin/home">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>