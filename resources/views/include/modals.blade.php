<style>
    .pages1 input{
        height:25px;
        text-align:center;
    }
    .pages2 input{
        height:25px;
        text-align:center;
    }
    .pages3 input{
        height:25px;
        text-align:center;
    }
    .modal-body{
        font-family: calibri;
        font-size: 12px;
    }
    /* .modal-content{
        min-height: 470px;
    } */
</style>
<div id="accNo" class="modal">
        <div class="modal-content" style="min-height:470px;width: 50%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Account Lookup</h3>
            <span href="#accNo" class="closes">&times;</span>
        </div>
        <div class="modal-body">
                <div style ="height:320px;overflow-y:scroll">
                <div class="filter"><input type="text" class="mb-2" id="dearch" onchange="tearch();" placeholder="Search for names..">
                <div class="modaldiv2" style="height:230px">
                </div>
                </div></div>
                <div class= "pages2"></div>
        </div>
        </div>
</div>
<!-- <div id="town" class="modal">
    <div class="modal-content" style="width: 50%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Town Lookup</h3>
            <span href = "#town" class="closes">&times;</span>
        </div>
        <div class="modal-body">
                <div class="modaldiv">
                    <table class="modal-table" style="color: black;">
                        <tr>
                            <th>
                                Code
                            </th>
                            <th>
                                Description
                            </th>
                            <th> </th>
                        </tr>
                        <tr>
                            <td>
                                03
                            </td>
                            <td>
                                Marawi City Wide and MSU
                            </td>
                            <td>
                                <button class="modalBtn"> Select </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                03
                            </td>
                            <td>
                                Marawi City Wide and MSU
                            </td>
                            <td>
                                <button class="modalBtn"> Select </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                03
                            </td>
                            <td>
                                Marawi City Wide and MSU
                            </td>
                            <td>
                                <button class="modalBtn"> Select </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                03
                            </td>
                            <td>
                                Marawi City Wide and MSU
                            </td>
                            <td>
                                <button class="modalBtn"> Select </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                03
                            </td>
                            <td>
                                Marawi City Wide and MSU
                            </td>
                            <td>
                                <button class="modalBtn"> Select </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                03
                            </td>
                            <td>
                                Marawi City Wide and MSU
                            </td>
                            <td>
                                <button class="modalBtn"> Select </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                03
                            </td>
                            <td>
                                Marawi City Wide and MSU
                            </td>
                            <td>
                                <button class="modalBtn"> Select </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                03
                            </td>
                            <td>
                                Marawi City Wide and MSU
                            </td>
                            <td>
                                <button class="modalBtn"> Select </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                03
                            </td>
                            <td>
                                Marawi City Wide and MSU
                            </td>
                            <td>
                                <button class="modalBtn"> Select </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                03
                            </td>
                            <td>
                                Marawi City Wide and MSU
                            </td>
                            <td>
                                <button class="modalBtn"> Select </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                03
                            </td>
                            <td>
                                Marawi City Wide and MSU
                            </td>
                            <td>
                                <button class="modalBtn"> Select </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                03
                            </td>
                            <td>
                                Marawi City Wide and MSU
                            </td>
                            <td>
                                <button class="modalBtn"> Select </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                03
                            </td>
                            <td>
                                Marawi City Wide and MSU
                            </td>
                            <td>
                                <button class="modalBtn"> Select </button>
                            </td>
                        </tr>
                    </table>
                </div>
        </div>
    </div>
</div> -->
<div id="accNo2" class="modal">
        <div class="modal-content" style="width: 50%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Account Lookup</h3>
            <span href="#accNo2" class="closes">&times;</span>
        </div>
        <div class="modal-body">
                <div style ="height:320px;overflow-y:scroll">
                <div class="filter"><input type="text" class="mb-2" id="rearch" onchange="pearch();" placeholder="Search for names..">
                <div class="modaldiv3" style="height:230px">
                </div>
                </div></div>
                <div style ="width:100%;" class= "pages3"></div>
        </div>
        </div>
</div>
<div id="meterreader" class="modal">
    <div class="modal-content" style="width: 50%;">
    <div class="modal-header" style="width: 100%;">
        <h3>Meter Reader Lookup</h3>
        <span href = "#meterreader" class="closes" id="close">&times;</span>
    </div>
    <div class="modal-body">
        <div style ="height:320px;overflow-y:scroll">
        <div class="filter"><input type="text" id="mreaderID" class="mb-2" onchange="myFunction();" placeholder="Search for names..">
            <div class="modaldiv4" style="height:230px">
            </div>
        </div></div>
    </div>
    </div>
</div>
<div id="route" class="modal">
    <div class="modal-content" style="width: 50%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Route Lookup</h3>
            <span href = "#route" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="filter"><input id="myID" class="mb-2" type="text" onchange="setTimeout(search(), 3000);" placeholder="Search for names..">
                <div class="modaldiv1" style="height:230px">

                </div>
                <div class= "pages1"></div>
            </div>
        </div>
    </div>
</div>

<!-- new modal -->
<div id="area" class="modal">
    <div class="modal-content" style="width: 50%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Area Lookup</h3>
            <span href = "#area" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <!-- <div class="filter"><input id="myID" type="text" onkeyup="setTimeout(search(), 3000);" placeholder="Search for names.."> -->
                <div class="modaldivArea">

                </div>
                <!-- <div class= "pages1"></div> -->
            </div>
        </div>
    </div>
</div>
<!-- end of new modal  -->

<div id="user" class="modal">
    <div class="modal-content" style="height:22%;">
        <div class="modal-body" style="padding:0%!important">
            <div style="border-bottom: 1px solid gray">
                <p class="block px-4 py-2 text-sm leading-5 text-gray-700 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{Auth::user()->user_full_name}}</p>
            </div>
            <div>
                <p style="margin-bottom:0px; cursor:pointer;" class="block px-4 py-2 text-sm leading-5 text-gray-700 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out" id="change_pass">Change password</p>
            </div>
            <div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" style="text-decoration: none;">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </div>
            {{-- <table border=0 id="userTable">
                <tr>
                    <td style="height: 30px; font-size: 20px; font-weight: 10px;">
                        
                    </td>
                </tr>
                <tr>
                    <td class="changePassword">
                        &nbsp;
                        <img class="settingsIcon" src="/img/gear.png">
                        Change Password
                    </td>
                </tr>
                <tr>
                    <td class="logout">
                        &nbsp;
                        <img class="logoutIco" src="/img/logout.ico">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();  
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </td>
                </tr>
            </table> --}}
        </div>
    </div>
</div>
<!--- change pass --->
<div id="change_password" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Change Password</h3>
            <span href="#change_password" class="closes">×</span>
        </div>
        <div class="modal-body">
            <form action="#">
                <div class="form-group">
                    <label for="current_pass">Current Password:</label>
                    <input type="password" name="current_password" id="c_pass" class="form-control">
                </div>
                <div class="form-group">
                    <label for="current_pass">New Password:</label>
                    <input type="password" name="new_password" id="n_pass" class="form-control">
                </div>
                <div class="form-group">
                    <label for="current_pass">Confirm Password:</label>
                    <input type="password" name="confirm_password" id="c_n_pass" class="form-control">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="form-control btn btn-primary change-password-user">Save</button>
        </div>
    </div>
</div>
