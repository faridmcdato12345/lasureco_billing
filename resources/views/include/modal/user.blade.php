<div id="addUser" class="modal">
	<div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Create User</h3>
            <span href="#addUser" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="form-group">
                <label for="full_name">Full name:</label>
                <input type="text" name="user_full_name" id="fname" class="form-control" placeholder="Enter full name here..." required="true">
            </div>
            <div class="form-group">
                <label for="user_name">Username:</label>
                <input type="text" name="user_name" id="uname" class="form-control" placeholder="Enter username here..." required="true">
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" id="role" class="form-control">
                    @forelse ($roles as $role)
                        <option value="{{$role->name}}">{{$role->name}}</option>
                    @empty
                        <option value="">No data found</option>
                    @endforelse
                </select>
            </div>
            <p style="color:red">Note: Default password is <b>Lasureco</b>. User must change their password.</p>
            <input type="button" class="btn btn-primary form-control create-user" value="Create">
        </div>
	</div>
</div>
<div id="add_user_role" class="modal" style="padding-top:1%;">
	<div class="modal-content" style="height: auto;max-height:650px;max-width:80%;overflow:scroll">
        <div class="modal-header">
            <h3>Add User Role</h3>
            <span href="#add_user_role" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="row">
                <div class="col-md-12 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table" id="user_user_role_table">
                                <thead>
                                    <tr>
                                        <th>Role</th>
                                        <th>Permissions</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>