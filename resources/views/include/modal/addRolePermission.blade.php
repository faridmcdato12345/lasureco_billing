<div id="addRole" class="modal" style="display:none;">
	<div class="modal-content" style="height: 650px;">
        <div class="modal-header">
            <h3>Role</h3>
            <span href="#addRole" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div>
                <label for="role">Role:</label>
                <input type="text" name="role" id="role" placeholder="Enter role here..." required="true">
            </div>
            <hr>
            <div class="search_permission">
                <label for="permissions">Permissions</label>
                <table class="table table-striped" id="permission-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                            <th><input type="checkbox" class="check-all"></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-primary form-control add-role-permission">Add</button>
        </div>
	</div>
</div>
<!---view role modal-->
<div id="showRole" class="modal" style="display:none;">
	<div class="modal-content" style="height: 650px;">
        <div class="modal-header">
            <h3>Role</h3>
            <span href="#showRole" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="role_name">
                <label for="role">Role:</label>
                <p></p>
            </div>
            <hr>
            <div class="search_permission">
                <label for="permissions">Permissions</label>
                <ul>
                </ul>
            </div>
        </div>
	</div>
</div>
<!--- edit role modal--->
<div id="editRole" class="modal" style="display:none;">
	<div class="modal-content" style="height: 650px;">
        <div class="modal-header">
            <h3>Role</h3>
            <span href="#editRole" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="role_name">
                <label for="role">Role:</label>
                <p></p>
            </div>
            <hr>
            <div class="search_permission">
                <label for="permissions">Permissions</label>
                <ul>

                </ul>
            </div>
        </div>
        <div class="modal-footer">
            <button class="update-role-permission-button form-control btn btn-primary">Update</button>
        </div>
	</div>
</div>
