<div id="create_consumer_type" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Create Consumer Type</h3>
            <span href="#create_consumer_type" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="form-group">
                <label for="ct_code">Code:</label>
                <input type="text" name="ct_code" id="ct_code" class="form-control">
            </div>
            <div class="form-group">
                <label for="ct_description">Description:</label>
                <input type="text" name="ct_description" id="ct_description" class="form-control">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary save-consumer-type">Save</button>
        </div>
	</div>
</div>
<div id="edit_consumer_type" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Edit Consumer Type</h3>
            <span href="#edit_consumer_type" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <input type="hidden" name="" id="ct_hidden_edit">
            <div class="form-group">
                <label for="ct_code">Code:</label>
                <input type="text" name="ct_code" id="ct_code_edit" class="form-control">
            </div>
            <div class="form-group">
                <label for="ct_description">Description:</label>
                <input type="text" name="ct_description" id="ct_description_edit" class="form-control">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary save-edit-consumer-type">Save</button>
        </div>
	</div>
</div>