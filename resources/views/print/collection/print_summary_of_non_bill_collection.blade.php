<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <title> Print Summary of Non-bill Collection </title>
</head>
<style media="print">
    @page {
        size: A4;
        margin: 0mm;
    }
    table {
        font-size: 12.5px !important;
        margin: auto;
    }
    th {
        font-weight: 400 !important;
    }
    .delete {
        display: none;
    }
    .action {
        display: none;
    }
</style>
<style>
    .page-break {
        page-break-after: always;
    }
    body {
        font-family: Consolas;
    }
    table {
        width: 95%;
        font-size: 15px;
        margin: auto;
        border-right: 0.75px dashed;
    }
    /* #table td {
        height: 30px;
    } */
    .left{
        border-left: 0.75px dashed;
        text-align: center;
    }
    .right{
        border-right: 0.75px dashed;
        text-align: center;
    }
    .bot{
        border-bottom: 0.75px dashed;
        text-align: center;
    }
    .top{
        border-top: 0.75px dashed;
        text-align: center;
    }
    .delete {
        background-color: rgb(221, 51, 51);
        color: white;
        border: 0px;
        height: 25px;
        cursor: pointer;
        border-radius: 2px;
    }
    #numBar {
        display: block;
    }
    #logo {
		height: 70px;
		width: 70px;
        float: left;
        margin-top: 22px;
        margin-left: 25px;
	}
    #lasuText {
        font-size: 24px; 
        font-weight: bold; 
        margin-left: -90px;
    }
    #dateText {
        font-size: 18px; 
        margin-left: -100px;
    }
    #emailText {
        font-size: 15px; 
        margin-left: -100px;
    }
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
    <br>
    <p id="numBar"> </p>
</body>
</html>

<script>
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var date = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();

    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.from = param.from;
        toSend.to = param.to;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;

        var summNBColl = "{{route('collection.withNonbill.perdate')}}";
        xhr.open('POST', summNBColl, true);

        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);

        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var info = data.info;
    
                var output = "";
                output += '<img id="logo" src="/img/logo.png">';
                output += "<br>";
                output += '<center> <label id="lasuText"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label id="dateText"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label id="emailText"> teamlasureco@gmail.com </label> </center> <br><br>';
                output += '<center> <label style="font-size: 20px;"> <b> SUMMARY OF NON-BILL COLLECTION </b> </label> <br>';
                output += '<label>' + param.from + ' - ' + param.to + '</label> </center> <br>';
                output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br>';
                output += '<table id="table"><tr>';
                output += '<th class="left top bot"> Teller </th> ';
                output += '<th class="left top bot"> Date </th>';
                output += '<th class="left top bot"> AR Number </th>';
                output += '<th class="left top bot"> Total Collection </th>';
                output += '<th class="left top bot"> PB Collection </th>';
                output += '<th class="left top bot"> Membership Fee  </th>';
                output += '<th class="left top bot"> App Fee  </th>';
                output += '<th class="left top bot"> Install <br> Fee <br> (Trans) </th>';
                output += '<th class="left top bot"> Install <br> Fee <br> (New Cons)  </th>';
                output += '<th class="left top bot"> Recon Fee  </th>';
                output += '<th class="left top bot"> Penalty Fee  </th>';
                output += '<th class="left top bot"> Others Fee </th> </tr>';

                var totalTotalMemberFee = 0;
                var totalTotalAppFee = 0;
                var totalTotalInstallFeeTrans = 0;
                var totalTotalInstallFeeCons = 0;
                var totalTotalReconFee = 0;
                var totalTotalPenaltyFee = 0;
                var totalTotalOtherFee = 0;
                var totalCollection = 0;
                var pBillAmountTotal=0;

                for(var i in info){

                    if(i > 0 && i % 49 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<img id="logo" src="/img/logo.png">';
                        output += "<br>";
                        output += '<center> <label id="lasuText"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label id="dateText"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label id="emailText"> teamlasureco@gmail.com </label> </center> <br><br>';
                        output += '<center> <label style="font-size: 20px;"> <b> SUMMARY OF NON-BILL COLLECTION </b> </label> <br>';
                        output += '<label>' + param.from + ' - ' + param.to + '</label> </center> <br>';
                        output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br>';output += '<table id="table"><tr>';
                        output += '<table id="table"><tr>';
                        output += '<th class="left top bot"> Teller </th> ';
                        output += '<th class="left top bot"> Date </th>';
                        output += '<th class="left top bot"> AR Number </th>';
                        output += '<th class="left top bot"> Total Collection </th>';
                        output += '<th class="left top bot"> PB Collection </th>';
                        output += '<th class="left top bot"> Membership Fee  </th>';
                        output += '<th class="left top bot"> App Fee  </th>';
                        output += '<th class="left top bot"> Install Fee(Transformer) </th>';
                        output += '<th class="left top bot"> Install Fee(New Cons)  </th>';
                        output += '<th class="left top bot"> Recon Fee  </th>';
                        output += '<th class="left top bot"> Penalty Fee  </th>';
                        output += '<th class="left top bot"> Others Fee </th> </tr>';
                    }
                    else {
                        var rowspan = Object.keys(info[i]).length;
                        output += '<tr> <td class="left bot" rowspan="' + rowspan + '">' + i + '</td>';
                        
                        for(var x in info[i]){
                            var totalMemberFee = 0;
                            var totalAppFee = 0;
                            var totalInstallFeeTrans = 0;
                            var totalInstallFeeCons = 0;
                            var totalReconFee = 0;
                            var totalPenaltyFee = 0;
                            var totalOtherFee = 0;
                            var feeTotal=0;
                            var pBillAmount=0;
                            
                            output += '<td class="left bot">' + x + '</td>';
                            if(info[i][x][0].ar_no !== null){
                                output += '<td class="left bot">' + info[i][x][0].ar_no + '</td>';
                            } else {
                                output += '<td class="left bot">0</td>';
                            }
                            output += '<td class="left bot">' + info[i][x][0].amount.toLocaleString("en-US") + '</td>';
                            var memberFee = 0;
                            var appFee = 0;
                            var installFeeTrans = 0;
                            var installFeeCons = 0;
                            var reconFee = 0;
                            var penaltyFee = 0;
                            var otherFee = 0;

                            for(var j in info[i][x]){
                                memberFee += info[i][x][j].MEMBERSHIP_FEE;
                                appFee += info[i][x][j].APPLICATION_FEE;
                                installFeeTrans += info[i][x][j].INSTALLATION_FEE_Transformer;
                                installFeeCons += info[i][x][j].INSTALLATION_FEE_New_Consumer;
                                reconFee += info[i][x][j].RECONNECTION_FEE;
                                penaltyFee += info[i][x][j].PENALTY_FEE;
                                otherFee += info[i][x][j].OTHERS_FEE;
                            }
                            
                            totalCollection += info[i][x][0].amount;
                            totalMemberFee += memberFee;
                            totalAppFee += appFee;
                            totalInstallFeeTrans += installFeeTrans;
                            totalInstallFeeCons += installFeeCons;
                            totalReconFee += reconFee;
                            totalPenaltyFee += penaltyFee;
                            totalOtherFee += otherFee;

                            feeTotal = totalMemberFee+totalAppFee+totalInstallFeeTrans+totalInstallFeeCons+totalReconFee+totalPenaltyFee+totalOtherFee;
                            pBillAmount = info[i][x][0].amount-feeTotal;

                            pBillAmountTotal += pBillAmount;
                            output += '<td class="left bot">' + pBillAmount.toLocaleString("en-US") + '</td>';
                            output += '<td class="left bot">' + memberFee.toLocaleString("en-US") + '</td>';
                            output += '<td class="left bot">' + appFee.toLocaleString("en-US") + '</td>';
                            output += '<td class="left bot">' + installFeeTrans.toLocaleString("en-US") + '</td>';
                            output += '<td class="left bot">' + installFeeCons.toLocaleString("en-US") + '</td>';
                            output += '<td class="left bot">' + reconFee.toLocaleString("en-US") + '</td>';
                            output += '<td class="left bot">' + penaltyFee.toLocaleString("en-US") + '</td>';
                            output += '<td class="left bot">' + otherFee.toLocaleString("en-US") + '</td>';
                            output += '</tr>';

                            console.log(pBillAmount);

                            totalTotalMemberFee += totalMemberFee;
                            totalTotalAppFee += totalAppFee;
                            totalTotalInstallFeeTrans += totalInstallFeeTrans;
                            totalTotalInstallFeeCons += totalInstallFeeCons;
                            totalTotalReconFee += totalReconFee;
                            totalTotalPenaltyFee += totalPenaltyFee;
                            totalTotalOtherFee += totalOtherFee;
                        }
                        output += '</tr>';
                    }
                }

                output += '<tr>'
                output += '<td class="left bot" colspan=3 style="text-align: right;"> <b> Total </b> &nbsp;&nbsp; </td>';
                output += '<td class="left bot">' + totalCollection.toLocaleString("en-US") + '</td>';
                output += '<td class="left bot">' + pBillAmountTotal.toLocaleString("en-US") + '</td>';
                output += '<td class="left bot">' + totalTotalMemberFee.toLocaleString("en-US") + '</td>';
                output += '<td class="left bot">' + totalTotalAppFee.toLocaleString("en-US") + '</td>';
                output += '<td class="left bot">' + totalTotalInstallFeeTrans.toLocaleString("en-US") + '</td>';
                output += '<td class="left bot">' + totalTotalInstallFeeCons.toLocaleString("en-US") + '</td>';
                output += '<td class="left bot">' + totalTotalReconFee.toLocaleString("en-US") + '</td>';
                output += '<td class="left bot">' + totalTotalPenaltyFee.toLocaleString("en-US") + '</td>';
                output += '<td class="left bot">' + totalTotalOtherFee.toLocaleString("en-US") + '</td>';
                output += '</tr>';

                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                Swal.fire({
                    title: 'Notice!',
                    icon: 'error',
                    text: 'No Report found!'
                })
                .then(function(){ 
                    // window.close();
                });
            }
        }
    }
</script>