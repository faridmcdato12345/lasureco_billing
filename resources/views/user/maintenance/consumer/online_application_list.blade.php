@extends('layout.master')

@section('stylesheet')
@include('include.style.consumer')
@section('title', 'Online Application')
@endsection
@section('content')
<p class="contentheader">Pending Online Application List</p>

<div class="container consumer-container" style="background: #f9f9f9;overflow:scroll;padding-top:1%">
    <div class="row">
        @if(session('info'))
        <div class="alert alert-success">
            {{ session('info') }}
        </div>
        @endif
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table" id="onlineApp">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Account #</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($map as $items)
                                <tr>
                                    <td>{{$items['num']}}</td>
                                    <td>{{$items['account']}}</td>
                                    <td>{{ $items['name']}}</td>
                                    <td>{{ $items['address']}}</td>
                                    <td>{{ $items['created']}}</td>
                                    <td>
                                      <div class="d-inline-block ">
                                        <button type="button" class="btn btn-primary btn-sm detail-btn" data-toggle="modal" data-target="#viewApp" data-id="{{$items['id']}}" data-backdrop="false">
                                          View
                                        </button>
                                      </div>
                                      <div class="d-inline-block">
                                        <form action="{{ route('gen.inspection', ['id' => $items['id']]) }}" method="get">
                                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                          <input type="submit" class="btn btn-secondary btn-sm" value="Gen IR" />
                                        </form>
                                      </div>
                                      <div class="d-inline-block">
                                        <form action="{{ route('gen.verification', ['id' => $items['id']]) }}" method="get">
                                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                          <input type="submit" class="btn btn-secondary btn-sm" value="Gen VSLip" />
                                        </form>
                                      </div>
                                      <div class="d-inline-block">
                                        <form action="{{ route('reject.online', ['id' => $items['id']]) }}" method="post">
                                          @method('DELETE')
                                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                          <input type="submit" class="btn btn-danger btn-sm" value="Reject" />
                                        </form>
                                      </div>
                                    </td>
                                </tr>
                            @endForeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 
<!-- View Modal -->

 <!-- Modal -->
 <!-- <div class="modal fade" data-backdrop="false" id="viewApp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewAppTitle"> Application Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-dark bg-light">
        <table class="w-100" id="tblempinfo">
          <tbody>
            <div class="row">
              <div class="col-md-6">
                <p><strong>Account: </strong><span id="account"></span></p>
                <p><strong>Name: </strong><span id="name"></span></p>
                <p><strong>Address: </strong><span id="address"></span></p>
                <p><strong>Contact Number: </strong><span id="contact"> TBD </span></p>
              </div>
              <div class="col-md-6">
                <p><strong>Birth Date: </strong><span id="bdate"></span></p>
                <p><strong>Email Address: </strong><span id="eadd"></span></p>
                <p><strong>TBA: </strong><span id=""></span></p>
                <p><strong>TBA: </strong><span id=""></span></p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12" style="text-align: center;">
                <p>- - - - - - -&nbsp&nbsp&nbsp&nbsp<strong>Attachments</strong>&nbsp&nbsp&nbsp&nbsp- - - - - - -
                <br><br>
              </div>
            </div>
            <div class="row" style="text-align: center;">
              <div class="col-md-3">
                <p><strong>Fire Permit:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block d-block" id="fire" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>
            
              <div class="col-md-3">
                <p><strong>Government ID:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="gov" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

              <div class="col-md-3">
                <p><strong>Building Permit:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="build" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

              <div class="col-md-3">
                <p><strong>Electrical Plan:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="elec" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

            </div>

            <div class="row" style="text-align: center;">
              <div class="col-md-3">
                <p><strong>Brgy Certificate:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="brgy" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>
            
              <div class="col-md-3">
                <p><strong>Perspective View of Structure:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="pvs" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

              <div class="col-md-3">
                <p><strong>Affidavit of Ownership:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="aoo" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

              <div class="col-md-3">
                <p><strong>Picture:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="pic" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

            </div>
            
            <div class="row" style="text-align: center;">
              <div id="caInspect" style="display:none" class="col-md-3">
                <p><strong>Inspector Report:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="caInspectorPic" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

              <div id="caVerif" style="display:none" class="col-md-3">
                <p><strong>Verification Slip:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="caVerificationPic" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

              <div id="displayOR" style="display:none" class="col-md-3">
                <p><strong>Official Receipt:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="caORPic" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

            </div>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <div id="butts"></div>
      </div>
    </div>
  </div>
</div> -->
<div id="viewApp" class="modal">
  <div class="modal-content" style="margin-top: 10px; width: 50%; height: auto;">
      <div class="modal-header" style="width: 100%; height: 60px;">
          <h3>Application Details</h3>
          <span href = "#viewApp" class="closes" id="close">&times;</span>
      </div>
      <div class="modal-body text-dark bg-light">
        <table class="w-100" id="tblempinfo">
          <tbody>
            <div class="row">
              <div class="col-md-6">
                <p><strong>Account: </strong><span id="account"></span></p>
                <p><strong>Name: </strong><span id="name"></span></p>
                <p><strong>Address: </strong><span id="address"></span></p>
                <p><strong>Contact Number: </strong><span id="contact"> TBD </span></p>
              </div>
              <div class="col-md-6">
                <p><strong>Birth Date: </strong><span id="bdate"></span></p>
                <p><strong>Email Address: </strong><span id="eadd"></span></p>
                <p><strong>TBA: </strong><span id=""></span></p>
                <p><strong>TBA: </strong><span id=""></span></p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12" style="text-align: center;">
                <p>- - - - - - -&nbsp&nbsp&nbsp&nbsp<strong>Attachments</strong>&nbsp&nbsp&nbsp&nbsp- - - - - - -
                <br><br>
              </div>
            </div>
            <div class="row" style="text-align: center;">
              <div class="col-md-3">
                <p><strong>Fire Permit:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block d-block" id="fire" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>
            
              <div class="col-md-3">
                <p><strong>Government ID:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="gov" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

              <div class="col-md-3">
                <p><strong>Building Permit:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="build" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

              <div class="col-md-3">
                <p><strong>Electrical Plan:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="elec" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

            </div>

            <div class="row" style="text-align: center;">
              <div class="col-md-3">
                <p><strong>Brgy Certificate:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="brgy" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>
            
              <div class="col-md-3">
                <p><strong>Perspective View of Structure:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="pvs" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

              <div class="col-md-3">
                <p><strong>Affidavit of Ownership:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="aoo" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

              <div class="col-md-3">
                <p><strong>Picture:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="pic" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

            </div>
            
            <div class="row" style="text-align: center;">
              <div id="caInspect" style="display:none" class="col-md-3">
                <p><strong>Inspector Report:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="caInspectorPic" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

              <div id="caVerif" style="display:none" class="col-md-3">
                <p><strong>Verification Slip:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="caVerificationPic" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

              <div id="displayOR" style="display:none" class="col-md-3">
                <p><strong>Official Receipt:</strong>
                  <div class="image-container">
                    <div class="w-25 p-1 mx-auto" id="">
                      <img class="img-thumbnail d-block" id="caORPic" src="">
                    </div>
            
                    <div class="preview-container" id="preview">
                      <div class="preview-image">
                        <img src="" alt="Preview Image">
                      </div>
                    </div>
                  </div>
                </p>
              </div>

            </div>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <div id="butts"></div>
      </div>
  </div>
</div>

<div id="showAccount" class="modal">
  <div class="modal-content" style="margin-top: 10px; width: 50%; height: auto;">
      <div class="modal-header" style="width: 100%; height: 60px;">
          <h3>Set Consumer Account</h3>
          <span href = "#showAccount" class="closes" id="close">&times;</span>
      </div>
      <div class="modal-body">
        <label for="consumer" style="color: black; font-size: 19px;"> Consumer Name </label>
        <input type="text" id="consumer" readonly>
        <br><br>
        <label for="accountRoute" style="color: black; font-size: 19px;"> Set Route </label>
        <input type="text" id="accountRoute" placeholder="Select Route" readonly>
        <br><br>
        <label for="accCons" style="color: black; font-size: 19px;"> Consumer Account </label>
        <input type="text" id="accCons" placeholder="e.g.(43 4343 0001)" readonly>
        <input type="text" id="cmid" hidden>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" id='gigi'> Set Account </button>
      </div>
  </div>
</div>

<div id="updateCaInspector" class="modal">
  <div class="modal-content" style="margin-top: 10px; width: 50%; height: auto;">
      <div class="modal-header" style="width: 100%; height: 60px;">
          <h3>Set Inspector Report</h3>
          <span href = "#updateCaInspector" class="closes" id="close">&times;</span>
      </div>
      <div class="modal-body">
        <form id="updateCA">
        <label for="inspectorCa" style="color: black; font-size: 19px;"> Inspector Report </label>
        <input type="file" id="caInspector" required>
        <br><br>
        <label for="verification" style="color: black; font-size: 19px;"> Verification Slip </label>
        <input type="file" id="ca_verification" required>
        <br><br>
        <input type="number" id="caInspectorID" hidden>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-secondary" id='saveInspector'> save </button>
      </div>
      </form>
  </div>
</div>

<div id="updateOR" class="modal">
  <div class="modal-content" style="margin-top: 10px; width: 50%; height: auto;">
      <div class="modal-header" style="width: 100%; height: 60px;">
          <h3>Upload OR</h3>
          <span href = "#updateOR" class="closes" id="close">&times;</span>
      </div>
      <div class="modal-body">
        <form id="sendOR">
        <label for="inspectorCa" style="color: black; font-size: 19px;"> OR picture </label>
        <input type="file" id="caOR" required>
        <br><br>
        <input type="number" id="caORID" hidden>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-secondary" id='saveOR'> save </button>
      </div>
      </form>
  </div>
</div>
@include('include.modal.routemodal')



<script type="text/javascript" >
    $(document).ready(function () {
      
      toks = $('meta[name="csrf-token"]').attr('content');
        $('#onlineApp').DataTable();
    });
    
    // $('#viewApp').modal('hide');
    // $(document).ready(function(){
        // $('.detail-btn').click(function(){
          $(document).on('click', '.detail-btn', function() {
            document.querySelector('#accountRoute').value ="";
            document.querySelector('#accountRoute').placeholder = "Select Route"
            document.querySelector('#accCons').value ="";
            document.querySelector('#accCons').placeholder ="e.g.(43 4343 0001)";
            document.querySelector('#viewApp').style.display="block";
            console.log(1);
            const id = $(this).attr('data-id');
            // const csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: 'online_application_list/'+id,
                type: 'GET',
                data: {
                    "id": id
                },
                // headers: {
                //   "X-CSRF-TOKEN": csrfToken
                // },
                success:function(data){
                    // Access the image data
                    var images = data.images;
                    var brgyCrtImage = images.brgy_cert;
                    var firePermitImage = images.fire_permit;
                    var picImage = images.picture;
                    var pvsImage = images.pvs;
                    var eplanImage = images.eplan;
                    var affidavitImage = images.affidavit;
                    var buildImage = images.build_permit;
                    var govImage = images.gov;
                    var details = data.details;
                    // console.log(data);
                    document.querySelector('#displayOR').style.display="none";
                    $("#caORPic").attr("src", " ");
                    // console.log(images.or)
                    $('#account').html(details.cm_account_no);
                    $('#name').html(details.cm_full_name);
                    $('#address').html(details.cm_address);
                    $('#bdate').html(details.cm_birthdate);
                    $('#eadd').html(details.cm_email);
                    $('#contact').html(details.cm_contact_num);
                    $("#fire").attr("src", "data:image/jpeg;base64," + firePermitImage);
                    $("#gov").attr("src", "data:image/jpeg;base64," + govImage);
                    $("#build").attr("src", "data:image/jpeg;base64," + buildImage);
                    $("#elec").attr("src", "data:image/jpeg;base64," + eplanImage);
                    $("#brgy").attr("src", "data:image/jpeg;base64," + brgyCrtImage);
                    $("#pvs").attr("src", "data:image/jpeg;base64," + pvsImage);
                    $("#aoo").attr("src", "data:image/jpeg;base64," + affidavitImage);
                    $("#pic").attr("src", "data:image/jpeg;base64," + picImage);
                    console.log(images.verification)
                    var butbuts = "";
                    if(images.inspector == ''){
                      document.querySelector('#caInspect').style.display="none";
                      $("#caInspectorPic").attr("src", "");
                      document.querySelector('#caVerif').style.display="none";
                      $("#caVerificationPic").attr("src", " ");
                      butbuts += "<button class='btn btn-primary approve-btn updateCaInspector' data-id='" + data.details.cm_id + "'>";
                      butbuts += "Upload";
                      butbuts += "</buttons>";
                      $("#butts").html(butbuts);
                    }
                    else{
                      // console.log(images.inspector);
                      // caVerif
                      document.querySelector('#caInspect').style.display="block";
                      $("#caInspectorPic").attr("src", "data:image/jpeg;base64," + images.inspector);
                      document.querySelector('#caVerif').style.display="block";
                      $("#caVerificationPic").attr("src", "data:image/jpeg;base64," + images.verification);
                      
                        // document.querySelector('#caInspect').style.display="block";
                        // $("#caInspectorPic").attr("src", "data:image/jpeg;base64," + images.inspector);
                        if(data.details.cm_account_no == null) {
                          butbuts += "<button class='btn btn-primary approve-btn showAccountSet' consName='" + data.details.cm_full_name + "' data-id='" + data.details.cm_id + "'>";
                          butbuts += "Set Account";
                          butbuts += "</buttons>";
                          $("#butts").html(butbuts);
                        } else {
                          if(images.or == ''){
                            butbuts += "<button class='btn btn-primary approve-btn updateOR' consName='" + data.details.cm_full_name + "' data-id='" + data.details.cm_id + "'>";
                            butbuts += "upload OR";
                            butbuts += "</buttons>";
                            $("#butts").html(butbuts);
                          }
                          else{
                          document.querySelector('#displayOR').style.display="block";
                          $("#caORPic").attr("src", "data:image/jpeg;base64," + images.or);
                          butbuts += "<button class='btn btn-primary approve-btn approveAccount' data-id='" + data.details.cm_id + "'>";
                          butbuts += "Approve";
                          butbuts += "</buttons>";
                          $("#butts").html(butbuts);
                          }
                        }
                      

                    }
                },
                error: function(xhr, status, error) {
                    // Handle error
                    var errorMessage = xhr.responseJSON.error;
                    console.log(errorMessage);
                }
            })
        });
    

    $(document).on('click', '.showAccountSet', function() {
      document.querySelector("#showAccount").style.display = "block";
      const id = $(this).attr('data-id');
      console.log(id);
      const name = $(this).attr('consName');
      document.querySelector("#cmid").value = id;
      document.querySelector("#consumer").value = name;
    })

    $(document).on('click', '.updateCaInspector', function() {
      document.querySelector("#updateCaInspector").style.display = "block";
      const id = $(this).attr('data-id');
      document.querySelector("#caInspectorID").value = id;
    })

    $(document).on('click', '.updateOR', function() {
      document.querySelector("#updateOR").style.display = "block";
      const id = $(this).attr('data-id');
      document.querySelector("#caORID").value = id;
    })

    $(document).on('click', '.approveAccount', function() {
      Swal.fire({
        title: 'Approve  Application?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
      }).then((result) => {
        if (result.isConfirmed) {
          const id = $(this).attr('data-id');
          $.ajax({
                    url: 'approve_consumer/'+id,
                    type: 'GET',
                    data: {
                        "id": id
                    },
                    success:function(data){
                      Swal.fire({
                          title: 'Success!',
                          icon: 'success',
                          text: 'Consumer Approved'
                      }).then(function(){ 
                        location.reload();
                      });
                    }
                })
        } else {
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        }
      })
    })

    $(document).on('click', '#accountRoute', function() {
      // document.querySelector("#showAccount").style.display = "none";
      showRoutes();
    })

    $(document).on('click', '#gigi', function() {
      Swal.fire({
        title: 'Set Consumer Account?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
      }).then((result) => {
        if (result.isConfirmed) {
          var cmids = document.querySelector("#cmid").value;
          var consAccs = document.querySelector("#accCons").value;
          $.ajax({
                url: 'set_consumer_account',
                type: 'POST',
                data: {
                    "cm_id": cmids,
                    "cons_account": consAccs
                },
                success:function(data){
                  Swal.fire({
                      title: 'Success!',
                      icon: 'success',
                      text: 'Account number created for Consumer'
                  }).then(function(){ 
                    location.reload();
                  });
                }
            })
        } else {
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        }
      })
    })

    var testForm = document.querySelector('#updateCA');
    testForm.addEventListener('submit',
    function(event){
        event.preventDefault();
        var ex =  "{{route('set.inspector.report')}}";
        let id = document.querySelector('#caInspectorID').value;
        let verification = document.querySelector('#ca_verification')
        const file2 = verification.files[0];
        const uploadElement = document.querySelector('#caInspector')
        const file = uploadElement.files[0];
        // const com = document.querySelector('#comments').value;
        const payload = new FormData();
        payload.append('ca_inspector', file, '.jpg');
        payload.append('ca_verification', file2, '.jpg');
        payload.append('cm_id', id);

        console.log(id);
        console.log(file)
        fetch(ex,{
            method: "POST",
            body: payload,
            headers:{
                'X-CSRF-TOKEN': toks
            }
        })
        .then(res => res.json())
        .then(data => {
            alert('Successfully Uploaded');
            setTimeout(function(){
                location.reload();
            }, 1500)
            
        })
        .catch(err => console.log(err))
        
        
        return false;
    });

    var testForm2 = document.querySelector('#sendOR');
    testForm2.addEventListener('submit',
    function(event){
        event.preventDefault();
        var ex =  "{{route('set.or')}}";
        let id = document.querySelector('#caORID').value;
        const uploadElement = document.querySelector('#caOR')
        const file = uploadElement.files[0];
        // const com = document.querySelector('#comments').value;
        const payload = new FormData();
        payload.append('ca_or', file, '.jpg');
        payload.append('cm_id', id);

        console.log(id);
        console.log(file)
        fetch(ex,{
            method: "POST",
            body: payload,
            headers:{
                'X-CSRF-TOKEN': toks
            }
        })
        .then(res => res.json())
        .then(data => {
            alert('Successfully Uploaded');
            setTimeout(function(){
                location.reload();
            }, 1500)
            
        })
        .catch(err => console.log(err))
        
        
        return false;
    });

    function setRoute(x){
      var code = x.getAttribute('code');
      var name = x.getAttribute('name');
      $.ajax({
                url: 'get_last_consumer/'+x.id,
                type: 'GET',
                data: {
                    "id": x.id
                },
                success:function(data){
                  document.querySelector('#accountRoute').value = code + ' - ' + name;
                  document.querySelector('#accCons').value = parseInt(data)+1;
                  document.querySelector('#routeCodes').style.display = "none";
                  // document.querySelector("#showAccount").style.display = "block";
                }
            })
    }

    $(document).on('click', '#fire, #gov, #build, #elec, #pvs, #brgy, #aoo, #pic', function() {
    var src = $(this).attr('src');

    // Create the preview element
    var preview = $('<div>').css({
      'background': 'rgba(0,0,0,0.7) url('+src+') no-repeat center',
      'background-size': 'contain',
      'width':'100%',
      'height':'100%',
      'position':'fixed',
      'z-index':'99999',
      'top':'0',
      'left':'0'
    }).appendTo('body');
  
    // Add zooming functionality
    var zoomFactor = 1;
    $(document).on('wheel', function(e) {
      e.preventDefault();
      var delta = e.originalEvent.deltaY;
      var zoom = Math.max(1, Math.min(4, zoomFactor + delta * -0.01));

      zoomFactor = zoom;
      preview.css({
        transform: 'scale(' + zoom + ') translate3d(0px, 0px, 0px)',
        cursor: 'move'
      });
    });

    //Add mousedown event listener for dragging functionality
    var isDragging = false;
    var mouseX, mouseY, deltaX, deltaY;

    preview.on('mousedown', function(e) {
      if ($(this).css('cursor') === 'move'){
        isDragging = true;
        mouseX = e.clientX;
        mouseY = e.clientY;
        preview.addClass('grabbing');
      }
    });

    // Add mousemove event listener to entire document for dragging functionality
    $(document).on('mousemove', function(e) {
      if (isDragging) {
        deltaX = e.clientX - mouseX;
        deltaY = e.clientY - mouseY;
        preview.css({
          left: '+=' + deltaX,
          top: '+=' + deltaY,
        });
        mouseX = e.clientX;
        mouseY = e.clientY;
      }
    });

    //Add mouseup event listener to entire document to stop dragging functionality
    $(document).on('mouseup', function() {
      if (isDragging) {
        isDragging = false;
        preview.removeClass('grabbing');
      }
    });

    // Add close button
    var closeButton = $('<div>').text('X').css({
      'position': 'absolute',
      'top': '10px',
      'right': '10px',
      'color': '#fff',
      'cursor': 'pointer',
      'font-size': '24px',
      'font-weight': 'bold'
    }).appendTo(preview);

    closeButton.on('click', function() {
      preview.remove();
    });

    // Add click event listener to remove preview element when clicked outside of it
    $(document).on('click', function(event) {
      if ($(event.target).is(preview) || $(event.target).is(closeButton)) {
        return;
      } else {
        preview.remove();
      }
    });
  }); 
</script>
@endsection

