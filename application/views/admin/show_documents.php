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
            <?php if ($userType == 'SEC') { ?>
            <div class="col-sm-10">
                <button id="addDivision" class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#addDocumentModal" data-backdrop="static" data-keyboard="false">
                    <i class="glyphicon glyphicon-plus-sign"></i> Add Document
                </button>
                <a class="btn btn-success btn-sm" href="<?php echo base_url(); ?>admin/document/sec">
                    <i class="glyphicon glyphicon-file"></i> My Documents
                </a>
                <button id="exportUncomplied" class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#exportUncompliedModal" data-backdrop="static" data-keyboard="false">
                    <i class="glyphicon glyphicon-share-alt"></i> Export Uncomplied
                </button>
                <!-- <a class="btn btn-danger btn-sm" href="<?php echo base_url(); ?>admin/document/archives">
                    <i class="glyphicon glyphicon-folder-open"></i> Open Archives
                </a> -->
            </div>
            <?php } ?>
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

<?php if ($userType == 'SEC') { ?>
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
                            <div class="input-group date">
                                <input id="dateReceived" type="text" class="form-control" name="dateReceived" date-format="YYYY/MM/DD" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Reference No.</label>
                        <div class="col-md-8">
                            <!--<input type="text" class="form-control" name="referenceNumber" value="<?php echo $refNo; ?>" readonly="readonly"/>-->
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
                        <label class="col-md-3 control-label">Description</label>
                        <div class="col-md-8">
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">From</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="from" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Document Due Date</label>
                        <div class="col-md-8">
                            <div class="input-group date">
                                <input id="dueDate" type="text" class="form-control" name="dueDate" date-format="YYYY/MM/DD" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            <span class="help-block">* Leave blank to set document due date to 15 days.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Attachment</label>
                        <div class="col-md-8">
                            <input type="file" name="attachment[]" multiple="" id="attachment" title="Browse for file..."/>
                            <span class="help-block">* Allowed file types (jpeg,png,gif,pdf,doc,docx).</span>
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