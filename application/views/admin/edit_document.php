<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-sm-6">
                    <a class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>admin/document"><i class="glyphicon glyphicon-chevron-left"></i> Back</a>
                    <div class="space-10"></div>
                </div>
                <div class="col-sm-6">
                    <div class="space-10"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="well">
                        <form enctype="multipart/form-data" id="editDocumentForm" method="post" class="form-horizontal" action="<?php echo base_url(); ?>admin/document/update/<?php echo $document->getId(); ?>">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Date Received</label>
                                <div class="col-md-8">
                                    <div class="input-group date" id="dateReceived">
                                        <input id="dateReceived" type="text" class="form-control" name="dateReceived" date-date-format="YYYY/MM/DD" value="<?php echo date('m/j/Y', strtotime($document->getDateReceived())); ?>" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Reference No.</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="referenceNumber" value="<?php echo $document->getReferenceNumber(); ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Subject</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="subject" value="<?php echo $document->getSubject(); ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Description</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="description"><?php echo $document->getDescription(); ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">From</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="from" value="<?php echo $document->getFrom(); ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Document Due Date</label>
                                <div class="col-md-8">
                                    <div class="input-group date" id="dueDate">
                                        <input id="dateDue" type="text" class="form-control" name="dueDate" date-date-format="YYYY/MM/DD" value="<?php echo date('m/j/Y', strtotime($document->getDueDate())) ?>" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Status</label>
                                <div class="col-md-8">
                                    <select name="status" class="form-control" id="documentStatus">
                                        <option value="On-Going">On-Going</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <input type="hidden" id="currentStatus" value="<?php echo $document->getStatus(); ?>">
                            </div>
                            <div class="form-group">
                                <?php
                                $download = '<button class="btn btn-sm btn-success disabled" >No Attachments</button>';
                                if ($document->getAttachment() != 'No File.') {
                                    $download = '<form method="post" action="' . base_url() . 'admin/document/download"><input type="hidden" name="document" value="' . $document->getId() . '"><button class="btn btn-sm btn-success" type="submit"><i class="glyphicon glyphicon-download"></i> Download Attached Document</button></form>';
                                }
                                ?>
                                <label class="col-md-3 control-label">Attachment</label>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <!--
                                                    <div id="downloadAttachment" >
                                            <?php echo $download; ?>
                                                            <button type="button" class="btn btn-sm btn-primary" id="changeAttachmentButton"><i class="glyphicon glyphicon-upload"></i> Upload New Attachment</button>
                                                    </div>
                                            -->
                                            <div id="changeAttachment" >
                                                <input type="file" name="attachment" title="Browse" id="attachment" title="Browse for file..."/>
                                                <span class="help-block">* Allowed file types (jpeg,png,gif,pdf).</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="col-md-offset-3 col-md-8">
                                    <button type="submit" class="btn btn-success saveDocumentForm">Confirm Update</button>
                                    <a href="<?php echo base_url(); ?>admin/document" class="btn btn-default">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>	