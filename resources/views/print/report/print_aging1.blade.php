<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    body{
        font-family: Consolas;
    }
    #aging-table{
        width: 100%;
    }
    .age-tr td:not(:first-child) {
        text-align: right;
    }
    table, td, th {
        border: 1px solid black;
        font-size: 0.83em;
    }
    .cont{
        margin-left: 5px;
    }
    .my-row .col-md-4{
        flex: 0 0 auto;
        width: 33.33333333%;
    }
    .my-row .col-md-4:nth-child(2){
        text-align: center;
    }
    .my-row{
        display: flex;
        margin-top: 50px;
    }
    h5{
        margin: 10px 0px;
    }
    .overall-total{text-align: right;}
    hr{width: 70%;}
    @media print {
        .page-1 {
            page-break-after: always;
        }
        @page{
            size: landscape;
        }
        table{
            font-size: 12px;
        }
        h5,.areas,.runtimes{
            font-size: 14px;
        }
        .lo{
            text-align: center;
        }
        .runtimes{
            text-align: right;
            margin-bottom: 0px;
        }
        .prepared{
            display:inline-table;
            width: 33.33%;
        }
        
    }
</style>
<body>
    <div class="cont">
        <div class="my-row">
            <div class="col-md-4">
                <img src="" alt="">
            </div>
            <div class="col-md-4">
                <h5 class="lo">LANAO DEL SUR ELECTRIC COOPERATIVE, INC.</h5>
                <h5 class="lo">INVENTORY OF <span class="aging__status"></span> BILLS PER CONSUMER</h5>
                <h4 class="lo aging__date"></h4>
            </div>
            <div class="col-md-4 runtimes">
                <h5>RUNDATE: <span class="rundate"></span></h5>
                <h5>RUTIME: <span class="runtime"></span></h5>
            </div>
        </div>
        <div class="row areas">
            <h5 for="area_code">AREA CODE: <span class="area"></span></h5>
            <h5 for="town_code">TOWN CODE: <span class="town"></span></h5>
            <h5 for="route_code">ROUTE CODE: <span class="route"></span></h5>
        </div>
        <div class="row table-responsive">
            <table class="table table-bordered" id="aging-table">
                <thead>
                    <tr>
                        <th class="account_no">ACCOUNT NO.</th>
                        <th class="account">ACCOUNT</th>
                        <th class="date1"></th>
                        <th class="date2"></th>
                        <th class="date3"></th>
                        <th class="date4"></th>
                        <th class="date5"></th>
                        <th class="total">TOTAL</th>
                    </tr>
                </thead>
                <tbody class="aging-tbody">
    
                </tbody>
            </table>
        </div>
        <div class="my-row">
            <div class="col-md-4 prepared" style="text-align: center;">
                <p style="margin-bottom: 0px;">{{Auth::user()->user_full_name}}</p>
                <p style="margin-bottom: 0px;margin-top:0px;"><hr></p>
                <p><strong>Prepared by</strong></p>
            </div>
            <div class="col-md-4 prepared" style="text-align: center;">
                <p style="margin-bottom: 0px;">&nbsp;</p>
                <p style="margin-bottom: 0px;margin-top:0px;"><hr></p>
                <p><strong>Checked by</strong></p>
            </div>
            <div class="col-md-4 prepared" style="text-align: center;">
                <p style="margin-bottom: 0px;">&nbsp;</p>
                <p style="margin-bottom: 0px;margin-top:0px;"><hr></p>
                <p><strong>Approved by</strong></p>
            </div>
        </div>
    </div>
</body>
<script>
window.onload = (event) => {
    let data = JSON.parse(localStorage.getItem('aging'))
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();
    let aging_status = data.status
    let runDateElement,runTimeElement,areaElement,townElement,routeElement,agingDate,agingStatus
    runDateElement = document.querySelector('.rundate')
    runDateElement.innerText =today.toLocaleDateString()
    agingDate = document.querySelector('.aging__date')
    agingDate.innerText = convertDate(data.dates[0])
    runTimeElement = document.querySelector('.runtime')
    runTimeElement.innerText = new Date().toLocaleTimeString()
    areaElement = document.querySelector('.area')
    areaElement.innerText = data.Area
    townElement = document.querySelector('.town')
    townElement.innerText = data.Town
    routeElement = document.querySelector('.route')
    routeElement.innerText = data.Route
    agingStatus = document.querySelector('.aging__status')
    agingStatus.innerText = aging_status.toUpperCase()
    let d = data.dates
    let consumers = data.Consumers
    let billOne = data.Bill_Period_1
    let billTwo = data.Bill_Period_2
    let billThree = data.Bill_Period_3
    let billFour = data.Bill_Period_4
    let billFive = data.Bill_Period_5
    for(var i = 0; i < d.length; i++){
        let lastIndex = d.length - 1
        let element = document.querySelector('#aging-table thead th.date'+(i+1)+'');
        if(i == lastIndex){
            element.innerText = "121 Days & Over"
        }else{
            element.innerText = convertDate(d[i])
        }
        
        
    }
    let c = 0;
    for(let i in consumers){
        let accntNumber = consumers[i].Account_No
        let converNumber = accntNumber.toString()
        let tbody = '<tr class="aging-tr-'+[i]+' age-tr">'
                    +'<td>'+converNumber.substring(converNumber.length - 4)+'</td>'
                    +'<td style="text-align:left;">'+consumers[i].name+'</td>'
                    +'<td class="for_billone_'+[i]+' first_bill"></td>'
                    +'<td class="for_billtwo_'+[i]+' second_bill"></td>'
                    +'<td class="for_billthree_'+[i]+' third_bill"></td>'
                    +'<td class="for_billfour_'+[i]+' fourth_bill"></td>'
                    +'<td class="for_billfive_'+[i]+' five_bill"></td>'
                    +'<td class="for_total_'+[i]+' tr_total"></td>'
                    +'</tr>'
        // $('.aging-tbody').append(tbody)
        document.querySelector('.aging-tbody').innerHTML += tbody
    }

    let overAll = '<tr>'
                    +'<td colspan="2" style="text-align:center"><strong>OVERALL TOTAL</strong></td>'
                    +'<td style="text-align:right"><strong><span class="overall_total total-first"></span></strong></td>'
                    +'<td style="text-align:right"><strong><span class="overall_total total-second"></span></strong></td>'
                    +'<td style="text-align:right"><strong><span class="overall_total total-third"></span></strong></td>'
                    +'<td style="text-align:right"><strong><span class="overall_total total-fourth"></span></strong></td>'
                    +'<td style="text-align:right"><strong><span class="overall_total total-fifth"></span></strong></td>'
                    +'<td style="text-align:right"><strong><span class="overall_total total-six"></span></strong></td>'
                    +'</tr>'
    // $('.aging-tbody').append(overAll)
    document.querySelector('.aging-tbody').innerHTML += overAll
    // comparing for billing period one
    for(let q = 0; q < consumers.length; q++){
        if(billOne.length != 0){
            
            for(let x = 0; x < billOne.length; x++){
                if(consumers[q].Account_No == billOne[x].Account){
                    document.querySelector('.for_billone_'+[q]+'').innerText = billOne[x].Amount
                    break
                }
                else{
                    document.querySelector('.for_billone_'+[q]+'').innerText = '0.00'
                }
            }
            
        }else{
            document.querySelector('.for_billone_'+[q]+'').innerText = '0.00'
        }
    }
    
    for(let q = 0; q < consumers.length; q++){
        if(billTwo.length != 0){
            for(let x = 0; x < billTwo.length; x++){
                if(consumers[q].Account_No == billTwo[x].Account){
                    document.querySelector('.for_billtwo_'+[q]+'').innerText = billTwo[x].Amount
                    break
                }
                else{
                    document.querySelector('.for_billtwo_'+[q]+'').innerText = '0.00'
                }
            }
        }else{
            document.querySelector('.for_billtwo_'+[q]+'').innerText = '0.00'
        }
        
    }
    for(let q = 0; q < consumers.length; q++){
        if(billThree.length != 0){
            for(let x = 0; x < billThree.length; x++){
                if(consumers[q].Account_No == billThree[x].Account){
                    document.querySelector('.for_billthree_'+[q]+'').innerText = billThree[x].Amount
                    break
                }
                else{
                    document.querySelector('.for_billthree_'+[q]+'').innerText = '0.00'
                }
            }
        }else{
            document.querySelector('.for_billthree_'+[q]+'').innerText = '0.00'
        }
        
    }
    for(let q = 0; q < consumers.length; q++){
        if(billFour.length != 0){
            for(let x = 0; x < billFour.length; x++){
                if(consumers[q].Account_No == billFour[x].Account){
                    document.querySelector('.for_billfour_'+[q]+'').innerText = billFour[x].Amount
                    break
                }
                else{
                    document.querySelector('.for_billfour_'+[q]+'').innerText = '0.00'
                }
            }
        }else{
            document.querySelector('.for_billfour_'+[q]+'').innerText = '0.00'
        }
        
    }
    for(let q = 0; q < consumers.length; q++){
        if(billFive.length != 0){
            for(let x = 0; x < billFive.length; x++){
                if(consumers[q].Account_No === billFive[x].Account_no){
                    document.querySelector('.for_billfive_'+[q]+'').innerText = billFive[x].Amount
                    break;
                }
                else{
                    document.querySelector('.for_billfive_'+[q]+'').innerText = '0.00'
                }
            }
        }else{
            document.querySelector('.for_billfive_'+[q]+'').innerText = '0.00'
        }
        
    }
    let trLength = document.getElementsByClassName('age-tr').length
    let oneT = 0;
    let twoT = 0;
    let threeT = 0;
    let fourT = 0;
    let fiveT = 0;
    let sixT = 0;
    for(let i = 0; i < trLength; i++){
        let one = parseFloat(document.querySelector('.aging-tr-'+i+' .first_bill').innerText)
        oneT += parseFloat(document.querySelector('.aging-tr-'+i+' .first_bill').innerText)
        let two = parseFloat(document.querySelector('.aging-tr-'+i+' .second_bill').innerText)
        twoT += parseFloat(document.querySelector('.aging-tr-'+i+' .second_bill').innerText)
        let three = parseFloat(document.querySelector('.aging-tr-'+i+' .third_bill').innerText)
        threeT += parseFloat(document.querySelector('.aging-tr-'+i+' .third_bill').innerText)
        let four = parseFloat(document.querySelector('.aging-tr-'+i+' .fourth_bill').innerText)
        fourT += parseFloat(document.querySelector('.aging-tr-'+i+' .fourth_bill').innerText)
        let five = parseFloat(document.querySelector('.aging-tr-'+i+' .five_bill').innerText)
        fiveT += parseFloat(document.querySelector('.aging-tr-'+i+' .five_bill').innerText)
        let six = parseFloat(document.querySelector('.aging-tr-'+i+' .tr_total').innerText)
        sixT += parseFloat(document.querySelector('.aging-tr-'+i+' .tr_total').innerText)
        let total = one + two + three + four + five
        document.querySelector('.aging-tr-'+i+' .for_total_'+i+'').innerText = total.toFixed(2)
    }

    let trTotal = document.getElementsByClassName('age-tr').length
    let overallTotal = 0
    for(let i = 0; i < trTotal; i++){
        overallTotal += parseFloat(document.querySelector('.for_total_'+[i]+'').innerText)
    }
    let curr = parseFloat(overallTotal.toFixed(2))
    let oneTT = parseFloat(oneT.toFixed(2))
    let twoTT = parseFloat(twoT.toFixed(2))
    let threeTT = parseFloat(threeT.toFixed(2))
    let fourTT = parseFloat(fourT.toFixed(2))
    let fiveTT = parseFloat(fiveT.toFixed(2))
    let sixTT = parseFloat(sixT.toFixed(2))
    document.querySelector('.total-six').innerText = curr.toLocaleString()
    document.querySelector('.total-first').innerText = oneTT.toLocaleString()
    document.querySelector('.total-second').innerText = twoTT.toLocaleString()
    document.querySelector('.total-third').innerText = threeTT.toLocaleString()
    document.querySelector('.total-fourth').innerText = fourTT.toLocaleString()
    document.querySelector('.total-fifth').innerText = fiveTT.toLocaleString()

    window.print()
    
}
function convertDate(d){
    let months = ['January','February','March','April','May','June','July','August','September','October','November','December']
    let day = d.split('-')[1]
    let year = d.split('-')[0]
    let firstChar = day.charAt(0)
    let secondChar = day.charAt(1)
    // let firstChar = d.slice(0,3)
    // let secondChar = d.slice(4,5)
    var finalMonth
    for(var i = 0; i <= months.length; i++){
        if(firstChar != 0){
            if(day == i){
                finalMonth = months[i-1]
            }
        }
        else{
            if(secondChar == i){
                finalMonth = months[i-1]
            } 
        }
    }
    return finalMonth + ' ' + year
}
</script>
<script>
    // $(document).ready(function(){
    //     window.print()
    // })
</script>
</html>
