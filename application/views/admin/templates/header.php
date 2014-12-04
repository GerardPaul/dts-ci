<?php
$current_controller = $this->router->fetch_class();
$active[$current_controller] = 'active';
$current_method = $this->router->fetch_method();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo $title; ?> | Document Tracking System</title>
        <link rel="shortcut icon" href="<?php echo base_url("application/assets/images/favicon.ico"); ?>" type="image/x-icon"/>
        <link rel="stylesheet" href="<?php echo base_url("application/assets/css/bootstrap-datetimepicker.min.css"); ?>">
        <link rel="stylesheet" href="<?php echo base_url("application/assets/css/bootstrapValidator.min.css"); ?>">
        <link rel="stylesheet" href="<?php echo base_url("application/assets/css/jquery.dataTables.min.css"); ?>">
        <link rel="stylesheet" href="<?php echo base_url("application/assets/css/bootstrap.css"); ?>">
        <link rel="stylesheet" href="<?php echo base_url("application/assets/css/custom.css"); ?>">

        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

        <script type="text/javascript" src="<?php echo base_url("application/assets/js/jquery-2.1.1.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("application/assets/js/moment.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("application/assets/js/bootstrap.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("application/assets/js/functions.js"); ?>"></script>

        <script type="text/javascript">
            var userType = "<?php echo $userType; ?>";
            var method = "<?php echo $current_method; ?>";
        </script>

    </head>
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button class="navbar-toggle collapsed" data-target=".navbar-collapse" data-toggle="collapse" type="button">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo base_url("admin/home"); ?>">Document Tracking System</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="<?php if (isset($active['document'])) echo $active['document']; ?>"><a href="<?php echo base_url("admin/document"); ?>">Documents</a></li>
                        <?php if ($userType == 'ADMIN') { ?>
                            <li class="<?php if (isset($active['user'])) echo $active['user']; ?>"><a href="<?php echo base_url("admin/user"); ?>">Users</a></li>
                            <li class="<?php if (isset($active['division'])) echo $active['division']; ?>"><a href="<?php echo base_url("admin/division"); ?>">Divisions</a></li>
                            <li class="<?php if (isset($active['log'])) echo $active['log']; ?>"><a href="<?php echo base_url("admin/log"); ?>">Logs</a></li>
                        <?php } ?>
                        <li class="dropdown <?php if (isset($active['profile'])) echo $active['profile']; ?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $username; ?><span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo base_url("admin/profile/edit"); ?>">Profile</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo base_url("admin/home/logout"); ?>">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container">
            <h1><?php echo "$header"; ?></h1>
        </div>