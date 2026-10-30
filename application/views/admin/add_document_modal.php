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
