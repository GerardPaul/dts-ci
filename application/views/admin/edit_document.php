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
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <input id="dateReceived" type="text" class="form-control" name="dateReceived" date-date-format="YYYY/MM/DD" value="<?php echo date('m/j/Y', strtotime($document->getDateReceived())); ?>" />
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
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <input id="dateDue" type="text" class="form-control" name="dueDate" date-date-format="YYYY/MM/DD" value="<?php echo date('m/j/Y', strtotime($document->getDueDate())) ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Status</label>
                                <div class="col-md-8">
                                    <select name="status" class="form-control" id="documentStatus">
                                        <option value="">- Select -</option>
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
                                    $str = $document->getAttachment();
                                    $link = base_url() . strstr($str, 'upload');
                                    $download = '<a href="' . $link . '" class="btn btn-sm btn-success" title="View this File" target="_blank" data-toggle="tooltip">View File <i class="glyphicon glyphicon-new-window"></i></a>';
                                }
                                ?>
                                <input type="hidden" name="originalAttachment" value="<?php echo $document->getAttachment(); ?>">
                                <label class="col-md-3 control-label">Attachment</label>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="col-sm-2">
                                                <button type="button" class="btn btn-sm btn-primary" id="changeAttachmentButton"><span>Upload New</span><span style="display: none;">Cancel</span></button>
                                            </div>
                                            <div class="col-sm-10">
                                                <div class="attachments">
                                                    <?php echo $download; ?>
                                                    <span class="help-block">* Leave as is if you don't want to change the attached files.</span>
                                                </div>
                                                <div class="attachments" style="display: none;">
                                                    <input type="file" name="attachment" title="Browse" id="attachment" title="Browse for file..."/>
                                                    <span class="help-block">* Allowed file types (jpeg,png,gif,pdf).</span>
                                                </div>
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