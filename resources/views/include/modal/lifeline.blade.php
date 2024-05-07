<div id="edit_lifeline" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Edit Lifeline</h3>
            <span href="#edit_lifeline" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <input type="hidden" name="" id="ll_hidden_edit">
            <div class="form-group">
                <label for="ct_code">Minimum Kw/H:</label>
                <input type="text" name="min_kwh" id="min_kwh" class="form-control">
            </div>
            <div class="form-group">
                <label for="ct_description">Maximum Kw/H:</label>
                <input type="text" name="max_kwh" id="max_kwh" class="form-control">
            </div>
            <div class="form-group">
                <label for="ct_description">Discount:</label>
                <input type="text" name="discount" id="discount" class="form-control">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary update_lifeline">Save</button>
        </div>
	</div>
</div>
<div id="create_lifeline" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Create Lifeline</h3>
            <span href="#create_lifeline" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <input type="hidden" name="" id="ll_hidden_edit">
            <div class="form-group">
                <label for="ct_code">Minimum Kw/H:</label>
                <input type="text" name="min_kwh" id="create_min_kwh" class="form-control">
            </div>
            <div class="form-group">
                <label for="ct_description">Maximum Kw/H:</label>
                <input type="text" name="max_kwh" id="create_max_kwh" class="form-control">
            </div>
            <div class="form-group">
                <label for="ct_description">Discount:</label>
                <input type="text" name="discount" id="create_discount" class="form-control">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary create_lifeline_save">Save</button>
        </div>
	</div>
</div>