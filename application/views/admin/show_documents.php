<input type="hidden" name="load" id="load" value="<?php echo $load; ?>">
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="col-sm-2">
                <select class="form-control" id="changeView">
                    <option value="1">All</option>
                    <option value="2">Compiled</option>
                    <option value="3">On-Going</option>
                    <option value="4">Cancelled</option>
                    <option value="5">Archived</option>
                </select>
                <div class="space-10"></div>
            </div>
            <div class="col-sm-10">
                <?php $this->load->view('admin/add_document_button'); ?>
            <?php if ($userType == 'SEC') { ?>
                <a class="btn btn-success btn-sm" href="<?php echo base_url(); ?>admin/document/sec">
                    <i class="glyphicon glyphicon-file"></i> My Documents
                </a>
                <button id="exportUncomplied" class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#exportUncompliedModal" data-backdrop="static" data-keyboard="false">
                    <i class="glyphicon glyphicon-share-alt"></i> Export Uncomplied
                </button>
                <!-- <a class="btn btn-danger btn-sm" href="<?php echo base_url(); ?>admin/document/archives">
                    <i class="glyphicon glyphicon-folder-open"></i> Open Archives
                </a> -->
            <?php } ?>
            </div>
        </div>
    </div>
    <div class="space-10"></div>
    <div class="row">
        <input type="hidden" name="userId" id="userId" value="<?php echo $userId; ?>" >
        <div class="col-xs-12">
            <table class="table table-condensed table-striped table-responsive table-hover display" id="documentsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ref #</th>
                        <th>From</th>
                        <th>Subject</th>
                        <th>Date Received</th>
                        <th>Deadline</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('admin/add_document_modal'); ?>
<?php if ($userType == 'SEC') { ?>
<div class="modal fade" id="exportUncompliedModal" tabindex="-1" role="dialog" aria-labelledby="exportUncompliedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cancelDocumentForm" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="exportUncompliedModalLabel">Export Uncomplied</h4>
            </div>
            <form id="exportUncompliedModalForm" method="post" class="form-horizontal" action="<?php echo base_url(); ?>admin/document/export">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">From</label>
                        <div class="col-md-8">
                            <div class="input-group date">
                                <input id="fromDate" type="text" class="form-control" name="fromDate" date-format="YYYY/MM/DD" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">To</label>
                        <div class="col-md-8">
                            <div class="input-group date">
                                <input id="toDate" type="text" class="form-control" name="toDate" date-format="YYYY/MM/DD" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default cancelExportUncompliedModalForm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary submitExportUncompliedModalForm">Generate Excel File</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>