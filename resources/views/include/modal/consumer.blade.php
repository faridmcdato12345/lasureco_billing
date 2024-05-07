<div id="createConsumer" class="modal">
    <input type="hidden" id="user_id" value="{{Auth::user()->user_id}}">
	<div id = "fordesign" class="modal-content" style="height: auto;">
        <div class="modal-header">
            <h3>Create Consumer Form</h3>
            <span href="#createConsumer" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="row">
                <div class="col-md-3">
                    <img src="{{asset('img/placeholder-person.png')}}" width="315" height="280" alt="default" id="wizardPicturePreview" onclick="document.getElementById('wizard-picture').click();">
                    {{-- <button class="badge badge-secondary">Click to updload image</button> --}}
                    <input type="file" name="avatar" accept="image/png, image/jpeg" id="wizard-picture" hidden class="badge badge-secondary">
                    <div class="consumer-info-button">
                        <button class="btn btn-primary form-control">Fees</button>
                        <button class="btn btn-primary form-control">Technical Details</button>
                        <button class="btn btn-primary form-control">Meter</button>
                        <button class="btn btn-primary form-control">Transformer</button>
                        <button class="btn btn-primary form-control">Service Drop</button>
                        <button class="btn btn-primary form-control">Family Background Information</button>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="route">Route / Barangay:</label>
                                <input type="hidden" name="route" id="rc_hidden">
                                <input type="text" name="route" id="route" placeholder="click to choose route" readonly>
                            </div>
                            <div class="form-group">
                                <label for="route">Account No.:</label>
                                <input type="text" name="account_no" id="account_no" required readonly>
                            </div>
                            <div class="form-group">
                                <label for="sequence">Sequence No.:</label>
                                <input type="text" name="seq_no" id="seq_no" readonly>
                            </div>        
                            <div class="form-group">
                                <label for="route">Consumer Type:</label>
                                <input type="hidden" name="consumer_type" id="ct_hidden">
                                <input type="text" id="consumer_type" readonly placeholder="click to choose type">
                            </div>
                            <div class="form-group">
                                <label for="route">TIN:</label>
                                <input type="text" name="tin" id="tin">
                            </div>
                            <div class="form-group">
                                <label for="route">First Name:</label>
                                <input type="text" name="first_name" id="first_name" style="text-transform:uppercase">
                            </div>
                            <div class="form-group">
                                <label for="route">Middle Name:</label>
                                <input type="text" name="middle_name" id="middle_name" style="text-transform:uppercase">
                            </div>
                            <div class="form-group">
                                <label for="route">Family Name:</label>
                                <input type="text" name="family_name" id="family_name" style="text-transform:uppercase">
                            </div>
                            <div class="form-group">
                                <label for="extension">Extension:</label>
                                <input type="text" name="extension_name" id="extension_name" style="text-transform:uppercase">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="route">Consumer Fullname:</label>
                                <input type="text" name="cm_fullname" id="cm_fullname" readonly style="text-transform:uppercase">
                            </div>
                            <div class="form-group">
                                <label for="block">Block No.:</label>
                                <input type="text" name="block_no" id="block_no">
                            </div>
                            <div class="form-group">
                                <label for="purok">Purok No.:</label>
                                <input type="text" name="purok_no" id="purok_no">
                            </div>
                            <div class="form-group">
                                <label for="lot">Lot No.:</label>
                                <input type="text" name="lot_no" id="lot_no">
                            </div>
                            <div class="form-group">
                                <label for="sitio">Sitio:</label>
                                <input type="text" name="sitio" id="sitio">
                            </div>
                            <div class="form-group">
                                <label for="route">Consumer Address:</label>
                                <input type="text" name="cm_address" id="cm_address">
                            </div>
                            <div class="form-group">
                                <label for="route">Birthday:</label>
                                <input type="date" name="birthday" id="bdate">
                            </div>
                            <div class="form-group">
                                <label for="route">Member Type:</label>
                                <input type="text" name="memberType" id="member_type">
                            </div>
                            <div class="form-group">
                                <label for="route">Co-member / Spouse:</label>
                                <input type="text" name="spouse" id="spouse">
                            </div>
                            
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="display: inline-block;border-bottom:1px solid #f9f9f9">
                                <div style="display: inline-block">Employee?</div>
                                <input style="display: inline-block;" type="radio" name="employee_yes" id="employee_r" value="1">Yes
                                <input style="display: inline-block;" type="radio" name="employee_yes" id="employee_r" value="0" checked="true">No
                            </div>
                            <div class="form-group">
                                <div style="display: inline-block">Voting Address?</div>
                                <input style="display: inline-block;" type="radio" name="v_address" id="va_r" value="1">Yes
                                <input style="display: inline-block;" type="radio" name="v_address" id="va_r" value="0" checked="true">No
                            </div>
                            <div class="form-group">
                                <div style="display: inline-block">Temporary Connection?</div>
                                <input style="display: inline-block;" type="radio" name="t_connection" id="tc_r" value="1">Yes
                                <input style="display: inline-block;" type="radio" name="t_connection" id="tc_r" value="0" checked="true">No
                            </div>
                            <div class="form-group">
                                <div style="display: inline-block">Senior Citizen?</div>
                                <input style="display: inline-block;" type="radio" name="s_citizen" id="sc_r" value="1">Yes
                                <input style="display: inline-block;" type="radio" name="s_citizen" id="sc_r" value="0" checked="true">No
                            </div>
                            <div class="form-group">
                                <div style="display: inline-block">Institutional?</div>
                                <input style="display: inline-block;" type="radio" name="instit" id="ins_r" value="1">Yes
                                <input style="display: inline-block;" type="radio" name="instit" id="ins_r" value="0" checked="true">No
                            </div>
                            <div class="form-group" style="display: inline-block">
                                <div style="display: inline-block">Lgu?</div>
                            </div>
                            <div class="form-group" style="display: inline-block">
                                <div style="display: inline-block">
                                    <input type="checkbox" id="five_perc" name="five_perc" value="0">
                                    <label for="vehicle1"> 5%</label><br>
                                </div>
                            </div>
                            <div class="form-group" style="display: inline-block">
                                <div style="display: inline-block">
                                    <input type="checkbox" id="two_perc" name="two_perc" value="0">
                                    <label for="vehicle1"> 2%</label><br>
                                </div>
                            </div>
                            <div class="form-group">
                                <div style="display: inline-block">Metered?</div>
                                <input style="display: inline-block;" type="radio" name="metered" id="metered_yes" value="1" checked="true">Yes
                                <input style="display: inline-block;" type="radio" name="metered" id="metered_no" value="0">No
                            </div>
                            <div class="not_metered" style="display: block">
                                <div class="form-group">
                                    <label for="meter">Meter No:</label>
                                    <input type="hidden" name="meter_no" id="mm_hidden">
                                    <input type="text" readonly id="meter_no" placeholder="click to choose meter">
                                </div>
                            </div>
                            <div class="form-group">
                                <div style="display: inline-block">Main Account?</div>
                                <input style="display: inline-block;" type="radio" name="m_account" id="m_account_y" checked="true" value="1">Yes
                                <input style="display: inline-block;" type="radio" name="m_account" id="m_account_n" value="0">No
                            </div>
                            <div class="not_main_account" style="display: none">
                                <div class="form-group">
                                    <label for="route">Main Account No.:</label>
                                    <input type="hidden" name="main_accnt" id="mA_hidden">
                                    <input type="text" id="main_account" placeholder="click to choose main account" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="route">Nearest Consumer:</label>
                                    <input type="hidden" name="nearest_cons" id="nC_hidden">
                                    <input type="text" id="near_consumer" placeholder="click to choose nearest consumer" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="route">Occupant / Tenant:</label>
                                    <input type="text" name="occupant" id="occupant">
                                </div>
                                <div class="form-group">
                                    <label for="route">Board Res. No.:</label>
                                    <input type="text" name="board_red" id="board_reso">
                                </div>
                            </div>
                            <div class="form-group" style="display: inline-block">
                                <div style="display: inline-block">Government Account?</div>
                                <input style="display: inline-block;" type="radio" name="gov_account" id="govt_r" value="1">Yes
                                <input style="display: inline-block;" type="radio" name="gov_account" id="govt_r" value="0" checked="true">No
                            </div>
                            <div class="form-group" style="display: inline-block">
                                <div style="display: inline-block">Special Account Type?</div>
                                <input style="display: inline-block;" type="radio" name="spc_account" id="special_r" value="1">Yes
                                <input style="display: inline-block;" type="radio" name="spc_account" id="special_r" value="0" checked="true">No
                            </div>
                            <div class="form-group">
                                <div style="display: inline-block">Large load?</div>
                                <input style="display: inline-block;" type="radio" name="l_load" id="large_load_r" value="1">Yes
                                <input style="display: inline-block;" type="radio" name="l_load" id="large_load_r" value="0" checked="true">No
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary form-control" id="save_consumer">Save</button>
            <button class="btn btn-primary form-control" id="update_consumer" style="display: none;">Update</button>
        </div>
	</div>
</div>
<div id="createConsumerRoute" class="modal">
    <div  class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Routes / Barangays</h3>
            <span href="#createConsumerRoute" class="closes">×</span>
        </div>
        <div id = "fordesign" class="modal-body" style="color: black;">
            <div class="row">
                <div class="col-md-12 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table" id="route_datatable">
                                <thead>
                                    <tr>
                                        <th>Area</th>
                                        <th>Town</th>
                                        <th>Route / Barangay</th>
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
<div id="consumerType" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Type</h3>
            <span href="#consumerType" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="row">
                <div class="col-md-12 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table" id="c_type_datatable">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
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
<div id="mainConsumer" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Main Account</h3>
            <span href="#mainConsumer" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="row">
                <div class="col-md-12 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table" id="main_consumer">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Account #</th>
                                        <th>Name</th>
                                        <th>Address</th>
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
<div id="nearestConsumer" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Nearest Consumer</h3>
            <span href="#nearestConsumer" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="row">
                <div class="col-md-12 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table" id="nearest_consumer">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Account #</th>
                                        <th>Name</th>
                                        <th>Address</th>
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
<div id="metered" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Meter & Serial Number</h3>
            <span href="#metered" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div style="margin-bottom: 5px;">
                <button class="btn btn-success create_meter">Create</button>
            </div>
            <div class="row">
                <div class="col-md-12 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table" id="metered_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Meter Serial No</th>
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
<div id="create_meter" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Create Meter</h3>
            <span href="#create_meter" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="brand">Brand:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="hidden" name="brand_id" id="brand_id">
                            <input type="text" id="brand" class="brand form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="brand">Serial:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id="serial" class="serial form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="brand">Class:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id="class" class="class form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="brand">Owner:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id="owner" class="owner form-control">
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="brand">Side Seal:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id="side_seal" class="side_seal form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="brand">Logo Seal:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id="logo_seal" class="logo_seal form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="brand">Terminal Seal:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id="terminal_seal" class="terminal_seal form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="brand">ERC Seal:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id="erc_seal" class="erc_seal form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="brand">Catalog #:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id="cataglog_no" class="catalog_no form-control">
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row bottom-row">
                <div class="col-md-4">
                    <label style="text-align: center;width:100%" for="KWH" class="multi-title">KWH</label>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="accuracy">Accuracy %:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="accuracy" id="accuracy" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="accuracy">As Found:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="as_found" id="as_found" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="accuracy">As left:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="as_left" id="as_left" class="form-control">
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="accuracy">Multiplier:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="multiplier_kwh" id="multiplier_kwh" class="form-control" placeholder="1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="accuracy">Prev Energy Reading:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="prev_energy_reading_kwh" id="prev_energy_reading_kwh" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="accuracy">Min. Consumption:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="min_cons_kwh" id="min_cons_kwh" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label style="text-align: center;width:100%" for="KW" class="multi-title">KW</label>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="accuracy">Demand type:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="demand_type" id="demand_type" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="accuracy">Multiplier:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="multiplier_kw" id="multiplier_kw" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="accuracy">Prev Dem Reading:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="prev_dem_reading_kw" id="prev_dem_reading_kw" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="accuracy">Min Demand:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="min_demand_kwh" id="min_demand_kwh" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="accuracy">Max Demand:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="max_demand_kwh" id="max_demand_kwh" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label style="text-align: center;width:100%" for="KW" class="multi-title">KVARH</label>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="multi">Multi:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="multi_kvar" id="multi_kvar" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="multi">Prev KVARH Reading:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="prev_kvarh" id="prev_kvarh" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="multi">Meter CO No.:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="meter_co_no" id="meter_co_no" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="multi">Meter CO Date:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="date" name="meter_co_date" id="meter_co_date" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary save_meter">Save</button>
        </div>
	</div>
</div>
<div id="change_meter" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Change Meter</h3>
            <span href="#change_meter" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div style="margin-bottom: 5px;">
                <button class="btn btn-success create_change_meter">Create</button>
            </div>
            <div class="row">
                <div class="col-md-12 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table" id="change_meter_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Meter Serial No</th>
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
<div id="add_meter" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Add Meter</h3>
            <span href="#add_meter" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div style="margin-bottom: 5px;">
                <button class="btn btn-success create_change_meter">Create</button>
            </div>
            <div class="row">
                <div class="col-md-12 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table" id="add_meter_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Meter Serial No</th>
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
<div id="change_meter_remarks" class="modal">
    <div class="modal-content" style="height: auto">
        <div class="modal-header">
            <h3>Change Meter</h3>
            <span href="#change_meter_remarks" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div>
                <h5>OLD METER</h5>
            </div>
            <hr>
            <div class="form-group">
                <label for="changerMeterRemarks">Old Meter Previous Reading:</label>
                <input type="number" class="form-control" id="old_prev_reading" readonly>
            </div>
            <div class="form-group">
                <label for="changerMeterRemarks">Old Meter Present Reading:</label>
                <input type="number" class="form-control" id="old_pres_reading" placeholder="0.00">
            </div>
            <div class="form-group">
                <label for="changerMeterRemarks">Old Final Reading:</label>
                <input type="number" class="form-control" id="old_final_reading" readonly>
            </div>
            <hr>
            <div>
                <h5>NEW METER</h5>
            </div>
            <hr>
            <div class="form-group">
                <label for="changerMeterRemarks">New Initial Reading:</label>
                <input type="number" class="form-control" id="new_initial_reading" placeholder="0.00">
            </div>
            <hr>
            <div class="form-group">
                <label for="changerMeterRemarks">Effective On:</label>
                <input type="month" class="form-control" id="effective_date">
            </div>
            <div class="form-group">
                <label for="changerMeterRemarks">Remarks:</label>
                <textarea class="form-control" id="changerMeterRemarks" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary submit_meter_change">Save</button>
        </div>
	</div>
    
</div>
<div id="change_name" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Change name</h3>
            <span href="#change_name" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <label for="old-name">From:</label>
            <input type="text" readonly id="old-name" class="form-control">
        </div>
        <div class="modal-body" style="color: black;">
            <div class="form-group">
                <label for="new-name">Family name:</label>
                <input type="text"  id="family-name" class="form-control">
            </div>
            <div class="form-group">
                <label for="new-name">First name:</label>
                <input type="text"  id="first-name" class="form-control" style="text-transform:uppercase">
            </div>
            <div class="form-group">
                <label for="new-name">Middle name:</label>
                <input type="text"  id="middle-name" class="form-control" style="text-transform:uppercase">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary save_new_name">Save</button>
        </div>
	</div>
</div>
<div id="change_type" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Change type</h3>
            <span href="#change_type" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <label for="old-name">From:</label>
            <input type="text" readonly id="old-type" class="form-control">
        </div>
        <div class="modal-body" style="color: black;">
            <label for="new-name">To:</label>
            <select name="" id="consumer_type_select" class="form-control"></select>
        </div>
        <div class="modal-body" style="color: black;">
            <label for="new-name">Remarks:</label>
            <textarea name="" id="consumer_type_remark" class="form-control" rows="4" cols="50"></textarea>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary save_new_type">Save</button>
        </div>
	</div>
</div>
<div id="show_meter_brand" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Select Meter Brand</h3>
            <span href="#show_meter_brand" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div style="margin-bottom: 5px;">
                <button class="btn btn-success create_meter_brand">Create Brand</button>
            </div>
            <div class="row">
                <div class="col-md-12 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table" id="meter_brand">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Brand Code</th>
                                        <th>Brand Name</th>
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
<div id="create_brand" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;width:50%">
        <div class="modal-header">
            <h3>Create Meter Brand</h3>
            <span href="#create_brand" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="row">
                <div class="form-group">
                    <label for="code">Code:</label>
                    <input type="text" name="code" id="brand_code" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="brand_name" id="brand_name" class="form-control">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary save_meter_brand">Save</button>
        </div>
	</div>
</div>
<div id="change_status_remarks_modal" class="modal">
    <div class="modal-content" style="height: auto;width:50%">
        <div class="modal-header">
            <h3>Change Status Remarks</h3>
            <span href="#change_status_remarks_modal" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="row">
                <div class="form-group">
                    <label for="code">Remarks:</label>
                    <textarea name="changestatusremarks" id="changestatusremarks" rows="4" cols="50" class="form-control"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary changeStatusRemarks">Save</button>
        </div>
	</div>
</div>
<div id="add_remarks" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;width:50%">
        <div class="modal-header">
            <h3>ADD REMARKS</h3>
            <span href="#add_remarks" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="row">
                <div class="form-group">
                    <form onsubmit="subRemarks(event)">
                    <label for="remarks">Remarks:</label><br>
                    <textarea id="remarks" style="width:100%;height:150px" required></textarea>
                    
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary">Submit</button>
        </div>
        </form>
	</div>
</div>
<!--- change multiplier--->
<div id="change_multiplier" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Change Multiplier</h3>
            <span href="#change_multiplier" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="row">
                <div class="col-md-12 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="from">From:</label>
                                <input type="text" name="mult_from" id="mult_from" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="from">To:</label>
                                <input type="text" name="mult_to" id="mult_to" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <button class="form-control btn btn-primary updateChangeKwh">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
<!--- end change multiplier--->

<!--- start history log --->
<div id="history_log" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>History Log</h3>
            <span href="#history_log" class="closes" id="closeHistLog">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="row">
                <div class="col-md-12 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table" id="history_log_table">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Old Info</th>
                                        <th>New Info</th>
                                        <th>Date</th>
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
<!--- end history log --->