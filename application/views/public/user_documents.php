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
        </div>
    </div>
    <div class="space-10"></div>
    <input type="hidden" name="userId" id="userId" value="<?php echo $userId; ?>" >
    <div class="row">
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