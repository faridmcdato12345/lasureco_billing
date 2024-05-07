@extends('layout.master')
@section('title', 'Real Time Collection and Billing Inquiry')
@section('content')

<p class="contentheader">Real Time Collection and Billing Inquiry</p>
<div class="main">
    <table class="content-table">
        <tr> 
            <td style="width: 18%;"> 
                Date Transacted:
            </td>
            <td> 
                <input type="date" name="dateTransacted">
            </td>
            <td> 
                &nbsp; Friday, June 04, 2021    
            </td>
        </tr>
        <tr style="height: 70px;"> 
            <td colspan=3> 
                <table style="font-size: 20px; font-weight: 100px;"> 
                    <tr> 
                        <td class="RTI-button" id="billedbtn" href="#billed"> 
                            &nbsp; Billed &nbsp;
                        </td>
                        <td class="RTI-button" id="unpostedbtn" href="#unposted"> 
                            &nbsp; Unposted Collection &nbsp;
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr> 
            <td> 
                TOTAL BILLED:
            </td>
            <td> 
                <input type="number" name="totalBilled" placeholder="0.00">
            </td>
        </tr>
        <tr style="height: 60px;"> 
            <td colspan=2> 
                <select name="sortPer" style="width: 50%;"> 
                    <option value="PerArea"> Per Area </option>
                    <option value="PerTown"> Per Town </option>
                    <option value="PerRoute"> Per Route </option>
                </select>
            </td>
        </tr>
        <tr> 
            <td colspan=3> 
                <div id="billed" href="#billbtn">
                    <div style="width: 100%; height: 40px; background-color: #5B9BD5;"> 
                        <table style="width: 100%; height: 40px; color: white;">
                            <tr> 
                                <td> 
                                    &nbsp; Area
                                </td>
                                <td> 
                                    Description
                                </td>
                                <td> 
                                    Count 
                                </td>
                                <td> 
                                    Total Billed Amount
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style="width: 100%; background-color: white; height: 200px;"> 
                        <table class="RTITable"> 
                            <tr>
                                <td> 
                                    &nbsp; Area
                                </td>
                                <td> 
                                    Description
                                </td>
                                <td> 
                                    Count 
                                </td>
                                <td> 
                                    Total Billed Amount
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="unposted" style="display: none;" href="#unpostedbtn"> 
                    <div style="width: 100%; height: 40px; background-color: #5B9BD5;"> 
                        <table style="width: 100%; height: 40px; color: white;">
                            <tr> 
                                <td> 
                                    &nbsp; Area
                                </td>
                                <td> 
                                    Description  
                                </td>
                                <td> 
                                    Total Collected
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style="width: 100%; background-color: white; height: 200px;"> 
                        <table class="RTITable"> 
                            <tr>
                                <td> 
                                    &nbsp; Area
                                </td>
                                <td> 
                                    Description  
                                </td>
                                <td> 
                                    Total Collected
                                </td>
                            </tr>
                        </table>
                    </div> 
                </div>
            </td>
        </tr>
    </table>
</div>
<script>
    var button = document.querySelectorAll('td.RTI-button');
    var oldDiv = document.getElementById('billed');
    var oldBtn = document.getElementById('billedbtn')

    for(var i=0; i<button.length; i++){
        button[i].onclick = function(e) {
            oldDiv.style.display="none";
            oldBtn.style.background="none";

            modal = document.querySelector(e.target.getAttribute("href"));
            modal.style.display="block";
            btnName = modal.id + "btn";
            btn = document.getElementById(btnName);
            btn.style.background="#5B9BD5";
            btn.style.color="white";

            oldBtn = btn;
            oldDiv = modal;
        }
    }

    window.onload=function(){
        var b = document.querySelector('#drpbtn4');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container2').childNodes;
        c[16].style.color="blue";
     }
</script>
@endsection
