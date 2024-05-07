<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <title> Print Vat Sales per Consumer type  </title>

</head>
<style media="print">
    @page {
        size: auto;
        margin-top: 0mm;
    }
    table {
        font-size: 13px !important;
        margin: auto;
    }
</style>
<style>
    #charges {
        height: 50px; 
        width: 24%;
    }
    .page-break {
        page-break-after: always;
    }
    body {
        font-family: Consolas;
    }
    table {
        font-size: 15px;
        margin: auto;
        width: 97%;
    }
    td {
        text-align: center;
    }
    .left {
        border-left: 0.75px dashed;
    }
    .right {
        border-right: 0.75px dashed;
    }
    .bot {
        border-bottom: 0.75px dashed;
    }
    .top {
        border-top: 0.75px dashed;
    }
    #breakline {
        display: none;
    }
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
</div>
</body>
</html>

<script>
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var date = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();

    var toSend = new Object();

    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    var param = JSON.parse(localStorage.getItem("data"));
   
    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.area_from = param.area_from; 
        toSend.area_to = param.area_to; 
        toSend.date_period = param.date_period; 

        var toSendJSONed = JSON.stringify(toSend);

        var token = document.querySelector('meta[name="csrf-token"]').content;
        var vatSalesConsType = "{{route('report.sales.vat.per.constype')}}";
        xhr.open('POST', vatSalesConsType, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var info = data.Summary_EVAT_Constype;
                var grandTotal = data.Grand_Total2;
                var output = "";
                var date = param.date_period;
                var year = date.slice(0,4);
                var month = date.slice(5,7);
                
                output += "<br>";
                output += '<center> <label id="lasuText"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label id="dateText"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label id="emailText"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;">  SUMMARY OF VAT PASSED ON TO CONSUMERS  </label> <br>';
                output += '<label style="font-size: 18px;">' + months[month - 1] + ', ' + year + '</label> <br> </center>';
                output += '<label style="font-size:16px; float: right; margin-right: 1.5%;"> Runtime: ' + date + " - " + time + '</label> <br><br>';
                output += '<table id="table">';
                
                for(var i in info){
                    output += "<tr><td>" + i + "</td></tr>";
                    output += '<tr><th class="left top bot"> Consumer Type </th>';
                    output += '<th class="left top bot"> No. of Cons. </th>';
                    output += '<th class="left top bot"> GenVAT </th>';
					output += '<th class="left top bot"> Power Act VAT </th>';
                    output += '<th class="left top bot"> TransVAT </th>';
                    output += '<th class="left top bot"> Sys. Loss VAT </th>';
					output += '<th class="left top bot"> Lifeline VAT </th>';
                    output += '<th class="left top bot"> DistVAT </th>';
                    output += '<th class="left top bot"> VAT Others </th>';
                    output += '<th class="left top bot"> Trans. Dem. </th>';
                    output += '<th class="left top bot"> Dist. Dem. </th>';
					output += '<th class="left top bot"> Supply Fix VAT </th>';
					output += '<th class="left top bot"> Supply Sys VAT </th>';
					output += '<th class="left top bot"> Meter Fix VAT </th>';
					output += '<th class="left top bot"> Meter Sys VAT </th>';
                    output += '<th class="left top bot"> Loan Cond. KWH </th>';
                    output += '<th class="left top bot"> Loan Cond. Fix </th>';
                    output += '<th class="left top bot"> Total Amount </th>';
                    output += '<th class="left top bot right"> KWH Used </th>';
                    output += '</tr>';
                    
                    var consCountTotal = 0;
                    var genVatTotal = 0;
                    var transVatTotal = 0;
                    var sysLossVatTotal = 0;
                    var disVatTotal = 0;
                    var othersTotal = 0;
                    var transDemVatTotal = 0;
                    var distDemVatTotal = 0;
                    var kwhVatTotal = 0;
                    var fixVatTotal = 0;
                    var totalTotal = 0;
                    var kwhUsedTotal = 0;
					var lifeLineVatTotal = 0;
					var meterFixVatTotal = 0;
					var meterSysVatTotal = 0;
					var powerActVatTotal = 0;
					var suppFixVatTotal = 0;
					var suppSysVatTotal = 0;
	
                    for(x in info[i]) {
                        const constTypeObj = info[i][x];
                        var consCount = Object.values(constTypeObj)[0].Consumer_Count;
                        consCountTotal += consCount;

                        var genVat = Object.values(constTypeObj)[0].Generation_Vat;
                        genVatTotal += genVat;

                        var transVat = Object.values(constTypeObj)[0].Trans_Vat;
                        transVatTotal += transVat;

                        var sysLossVat = Object.values(constTypeObj)[0].Line_Loss_Vat;
                        sysLossVatTotal += sysLossVat;
                        
                        var disVat = Object.values(constTypeObj)[0].Dist_Vat;
                        disVatTotal += disVat;

                        var others = Object.values(constTypeObj)[0].Others_Vat;
                        othersTotal += others;

                        var transDemVat = Object.values(constTypeObj)[0].Trans_Dem_Vat;
                        transDemVatTotal += transDemVat; 
                        
                        var distDemVat = Object.values(constTypeObj)[0].Dist_Dem_Vat;
                        distDemVatTotal += distDemVat;

                        var kwhVat = Object.values(constTypeObj)[0].LCondo_Kwh_Vat;
                        kwhVatTotal += kwhVat;

                        var fixVat = Object.values(constTypeObj)[0].LCondo_Fix_Vat;
                        fixVatTotal += fixVat;

                        var total = Object.values(constTypeObj)[0].Total;
                        totalTotal += total; 

                        var kwhUsed = Object.values(constTypeObj)[0].KWH_Used;
                        kwhUsedTotal += kwhUsed;
						
						var lifeLineVat = Object.values(constTypeObj)[0].LifeLine_Vat;
						lifeLineVatTotal += lifeLineVat;
						
						var meterFixVat = Object.values(constTypeObj)[0].Meter_Fix_Vat;
						meterFixVatTotal += lifeLineVat;
						
						var meterSysVat = Object.values(constTypeObj)[0].Meter_Sys_Vat;
						meterSysVatTotal += meterSysVat;
						
						var powerActVat = Object.values(constTypeObj)[0].Power_Act_Vat;
						powerActVatTotal += powerActVat;
						
						var suppFixVat = Object.values(constTypeObj)[0].Supply_Fix_Vat;
						suppFixVatTotal += suppFixVat;
						
						var suppSysVat = Object.values(constTypeObj)[0].Supply_Sys_Vat;
						suppSysVatTotal += suppSysVat;

                        output += "<tr><td class='left'>" + x + "</td>";
                        output += "<td class='left'>" + consCount.toLocaleString("en-US") + "</td>";
                        output += "<td class='left'>" + genVat.toLocaleString("en-US") + "</td>";
						output += "<td class='left'>" + powerActVat.toLocaleString("en-US") + "</td>";
                        output += "<td class='left'>" + transVat.toLocaleString("en-US") + "</td>";
                        output += "<td class='left'>" + sysLossVat.toLocaleString("en-US") + "</td>";
                        output += "<td class='left'>" + lifeLineVat.toLocaleString("en-US") + "</td>";
						output += "<td class='left'>" + disVat.toLocaleString("en-US") + "</td>";
                        output += "<td class='left'>" + others.toLocaleString("en-US") + "</td>";
                        output += "<td class='left'>" + transDemVat.toLocaleString("en-US") + "</td>";
                        output += "<td class='left'>" + distDemVat.toLocaleString("en-US") + "</td>";
						output += "<td class='left'>" + suppFixVat.toLocaleString("en-US") + "</td>";
						output += "<td class='left'>" + suppSysVat.toLocaleString("en-US") + "</td>";
						output += "<td class='left'>" + meterFixVat.toLocaleString("en-US") + "</td>";
						output += "<td class='left'>" + meterSysVat.toLocaleString("en-US") + "</td>";
                        output += "<td class='left'>" + kwhVat.toLocaleString("en-US") + "</td>";
                        output += "<td class='left'>" + fixVat.toLocaleString("en-US") + "</td>";
                        output += "<td class='left'>" + total.toLocaleString("en-US") + "</td>";
                        output += "<td class='left right'>" + kwhUsed.toLocaleString("en-US") + "</td> </tr>";
                       
                        
                    }
                    output += "<tr> <td class='left top'> Sub-Total </td>";
                    output += "<td class='left top'>" + consCountTotal.toLocaleString("en-US") + "</td>";
                    output += "<td class='left top'>" + genVatTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
					output += "<td class='left top'>" + powerActVatTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top'>" + transVatTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top'>" + sysLossVatTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
					output += "<td class='left top'>" + lifeLineVatTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top'>" + disVatTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top'>" + othersTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top'>" + transDemVatTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top'>" + distDemVatTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
					output += "<td class='left top'>" + suppFixVatTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
					output += "<td class='left top'>" + suppSysVatTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
					output += "<td class='left top'>" + meterFixVatTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
					output += "<td class='left top'>" + meterSysVatTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top'>" + kwhVatTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top'>" + fixVatTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top'>" + totalTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top right'>" + kwhUsedTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "</tr> <tr><td class='top'> &nbsp; </td>";
                    output += "<td class='top'> &nbsp; </td><td class='top'> </td><td class='top'> </td><td class='top'> </td><td class='top'> </td><td class='top'> </td>";
                    output += "<td class='top'> </td><td class='top'> </td><td class='top'> </td> <td class='top'> </td><td class='top'> </td><td class='top'> </td><td class='top'> </td>";
					output += "<td class='top'> </td><td class='top'> </td><td class='top'> </td> <td class='top'> </td> <td class='top'> </td> </tr>";
                }
                
                output += "<tr> <td class='left top bot'> <b> Grand Total </b> </td>";
                output += "<td class='left top bot'>" + grandTotal.Consumer_Count_Grand_Total.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + grandTotal.Generation_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
				output += "<td class='left top bot'>" + grandTotal.Power_Act_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
				output += "<td class='left top bot'>" + grandTotal.Trans_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top bot'>" + grandTotal.Line_Loss_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
				output += "<td class='left top bot'>" + grandTotal.LifeLine_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top bot'>" + grandTotal.Dist_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top bot'>" + grandTotal.Others_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top bot'>" + grandTotal.Trans_Dem_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top bot'>" + grandTotal.Dist_Dem_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
				output += "<td class='left top bot'>" + grandTotal.Supply_Fix_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
				output += "<td class='left top bot'>" + grandTotal.Supply_Sys_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
				output += "<td class='left top bot'>" + grandTotal.Meter_Fix_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
				output += "<td class='left top bot'>" + grandTotal.Meter_Sys_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top bot'>" + grandTotal.LCondo_Kwh_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top bot'>" + grandTotal.LCondo_Fix_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top bot'>" + grandTotal.Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top bot right'>" + grandTotal.KWH_Used_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr> </tale>";
                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                Swal.fire({
                    title: 'Notice!',
                    icon: 'error',
                    text: 'No Report found!'
                }).then(function(){ 
                    window.close();
                });
            }
        }
    }
</script>