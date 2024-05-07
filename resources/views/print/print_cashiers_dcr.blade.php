<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>CASHIER'S DCR</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <script src="{{asset('js/jquery-3.5.1.js')}}"></script>
</head>
<style media="print">
    @page {
      size: auto;
      margin: 2mm;
    }
</style>
<style>
    *{
        font-size: 12px;
    }
    .page-break {
        page-break-after: always;
    }
    body {
        font-family: Calibri;
    }
    table{
        margin:auto;
        width: 95%;
        height: 100px
    }
    table td {
        border-bottom: 1px solid #ddd;
        height: 40px;
    }
    th{
        border-bottom: 1px solid #555;
        text-align:center;
    }
    td {
        text-align: center;
        height: 30px;
    }
    .page-no p {
        text-align: right;
    }
    .summary_table td{
        text-align:left;
    }
    .summary_table .td_right{
        text-align: right;
    }
    p {
        margin-bottom: 0px;
    }
    .table-bordered thead th,.table-bordered td {
        padding: 1px
    }
    table td {
        height: fit-content;
    }
    .header-dcr p{
        text-align: center;
    }
    @media print {
        .page-1 {
            page-break-after: always;
        }
    }
</style>
<body onload="window.print()">
    <div class="container-fluid">
        <!---page 1 row--->
        <div class="page-1">
            <div class="row">
                <div class="col-md-4">
                    <p>RUNDATE: <span class="run_date"></span></p>
                    <p>RUNTIME: <span class="run_time"></span></p>
                </div>
                <div class="col-md-4 header-dcr">
                    <p>LANAO DEL SUR ELECTRIC COOPERATIVE, INC.</p>
                    <p>BRGY.</p>
                </div>
                <div class="col-md-4 page-no">
                    <p>Page: 1</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>TELLERS DAILY COLLECTION SUMMARY</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <p>TELLER: <span class="teller_label"></span></p>
                </div>
            </div>
            <div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Route</th>
                            <th colspan="2">TOTAL COLLECTIONS</th>
                            <th>Power Bills</th>
                            <th>FIT-ALL</th>
                            <th colspan="2">VAT</th>
                            <th colspan="4">UNIT-CHARGES</th>
                            <th colspan="5">VAT</th>
                            <th>SURCHAGE</th>
                            <th>KWH Used</th>
                            <th>NON BILL</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th># of Bills</th>
                            <th>Amount</th>
                            <th></th>
                            <th></th>
                            <th>2% W/Tax</th>
                            <th>5% F/Tax</th>
                            <th>UCME-SPUG</th>
                            <th>UCME-REDCI</th>
                            <th>UCME-EC</th>
                            <th>UCME-SCC</th>
                            <th>GEN</th>
                            <th>TRANS</th>
                            <th>DIST</th>
                            <th>SYSLOSS</th>
                            <th>OTHERS</th>
                            <th colspan="3"></th>
                        </tr>
                    </thead>
                    <tbody class="table_page_one">

                    </tbody>
                </table>
            </div>
        </div>
        <div class="page-2">
            <!--- page 2 row--->
            <div class="row">
                <div class="col-md-4">
                    <p>RUNDATE: <span class="run_date"></span></p>
                    <p>RUNTIME: <span class="run_time"></span></p>
                </div>
                <div class="col-md-4 header-dcr">
                    <p>LANAO DEL SUR ELECTRIC COOPERATIVE, INC.</p>
                    <p>BRGY.</p>
                </div>
                <div class="col-md-4  page-no">
                    <p>Page: 2</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>TELLERS DAILY COLLECTION SUMMARY</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <p>TELLER: <span class="teller_label"></span></p>
                </div>
            </div>
            <div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Route</th>
                            <th colspan="2">TOTAL COLLECTIONS</th>
                            <th>Power Bills</th>
                            <th>FIT-ALL</th>
                            <th colspan="2">VAT</th>
                            <th colspan="4">UNIT-CHARGES</th>
                            <th colspan="5">VAT</th>
                            <th>SURCHAGE</th>
                            <th>KWH Used</th>
                            <th>NON BILL</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th># of Bills</th>
                            <th>Amount</th>
                            <th></th>
                            <th></th>
                            <th>2% W/Tax</th>
                            <th>5% F/Tax</th>
                            <th>UCME-SPUG</th>
                            <th>UCME-REDCI</th>
                            <th>UCME-EC</th>
                            <th>UCME-SCC</th>
                            <th>GEN</th>
                            <th>TRANS</th>
                            <th>DIST</th>
                            <th>SYSLOSS</th>
                            <th>OTHERS</th>
                            <th colspan="3"></th>
                        </tr>
                    </thead>
                    <tbody class="table_page_two">
                        
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-2">
                        <p>Summary:</p>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-bordered summary_table">
                            <tr>
                                <td>Power Bills</td>
                                <td class="td_power_bills td_right"></td>
                            </tr>
                            <tr>
                                <td>FIT-ALL</td>
                                <td class="td_fit_all td_right"></td>
                            </tr>
                            <tr>
                                <td>UC-ME - SPUG</td>
                                <td class="td_spug td_right"></td>
                            </tr>
                            <tr>
                                <td>UC-ME - RED</td>
                                <td class="td_red td_right"></td>
                            </tr>
                            <tr>
                                <td>UC-EC</td>
                                <td class="td_ec td_right"></td>
                            </tr>
                            <tr>
                                <td>UC-SCC</td>
                                <td class="td_scc td_right"></td>
                            </tr>
                            <tr>
                                <td>Generation</td>
                                <td class="td_gen td_right"></td>
                            </tr>
                            <tr>
                                <td>Transmission</td>
                                <td class="td_trans td_right"></td>
                            </tr>
                            <tr>
                                <td>Distribution</td>
                                <td class="td_dist td_right"></td>
                            </tr>
                            <tr>
                                <td>System Loss</td>
                                <td class="td_sysloss td_right"></td>
                            </tr>
                            <tr>
                                <td>Others</td>
                                <td class="td_others td_right"></td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td class="td_total td_right"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>
    </div>
</body>
<script src="{{asset('app/app.js')}}"></script>
<script>
    $(document).ready(function(){
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        let data = JSON.parse(localStorage.getItem('summary_dcr_data'))
        let d = data.Summary_DCR
        let teller
        let total_no_of_bills = 0
        let total_amount = 0
        let total_power_bills = 0
        let total_fit = 0
        let total_spug = 0
        let total_red = 0
        let total_ec = 0
        let total_scc = 0
        let total_gen = 0
        let total_trans = 0
        let total_dist = 0
        let total_sysloss = 0
        let total_others = 0
        let total_surcharge = 0
        let total_kwh_used = 0
        let total_non_bill = 0
        let total_summary = 0
        for(let i in d){
            for(let x in d[i]){
                teller = d[i][x][0].Teller
                total_no_of_bills += d[i][x][0].Number_Of_Bills
                total_amount += d[i][x][0].Amount
                total_power_bills += d[i][x][0].Power_Bill
                total_fit += d[i][x][0].Fit_All
                total_spug += d[i][x][0].UC_ME_SPUG
                total_red += d[i][x][0].UC_ME_RED
                total_ec += d[i][x][0].UC_EC
                total_scc += d[i][x][0].UC_SCC
                total_gen += d[i][x][0].GEN_VAT
                total_trans += d[i][x][0].TRANS_VAT
                total_dist += d[i][x][0].DIST_VAT
                total_sysloss += d[i][x][0].SYSLOSS_VAT
                total_others += d[i][x][0].OTHERS
                total_surcharge += d[i][x][0].SURCHARGE
                total_kwh_used += d[i][x][0].Kwh_Used
                total_non_bill += d[i][x][0].None_Bill
                let tbody = '<tr>'
                    +'<td>'+d[i][x][0].Route_Code+'</td>'
                    +'<td>'+d[i][x][0].Number_Of_Bills+'</td>'
                    +'<td>'+formatter.format(d[i][x][0].Amount)+'</td>'
                    +'<td>'+formatter.format(d[i][x][0].Power_Bill)+'</td>'
                    +'<td>'+formatter.format(d[i][x][0].Fit_All)+'</td>'
                    +'<td></td>'
                    +'<td></td>'
                    +'<td>'+formatter.format(d[i][x][0].UC_ME_SPUG)+'</td>'
                    +'<td>'+formatter.format(d[i][x][0].UC_ME_RED)+'</td>'
                    +'<td>'+formatter.format(d[i][x][0].UC_EC)+'</td>'
                    +'<td>'+formatter.format(d[i][x][0].UC_SCC)+'</td>'
                    +'<td>'+formatter.format(d[i][x][0].GEN_VAT)+'</td>'
                    +'<td>'+formatter.format(d[i][x][0].TRANS_VAT)+'</td>'
                    +'<td>'+formatter.format(d[i][x][0].DIST_VAT)+'</td>'
                    +'<td>'+formatter.format(d[i][x][0].SYSLOSS_VAT)+'</td>'
                    +'<td>'+formatter.format(d[i][x][0].OTHERS)+'</td>'
                    +'<td>'+d[i][x][0].SURCHARGE+'</td>'
                    +'<td>'+d[i][x][0].Kwh_Used+'</td>'
                    +'<td>'+formatter.format(d[i][x][0].None_Bill)+'</td>'
                    '</tr>'
                $('.table_page_one').append(tbody)
            }
        }
        let tbody = '<tr>'
                    +'<td></td>'
                    +'<td>'+total_no_of_bills+'</td>'
                    +'<td>'+formatter.format(total_amount)+'</td>'
                    +'<td>'+formatter.format(total_power_bills)+'</td>'
                    +'<td>'+formatter.format(total_fit)+'</td>'
                    +'<td></td>'
                    +'<td></td>'
                    +'<td>'+formatter.format(total_spug)+'</td>'
                    +'<td>'+total_red+'</td>'
                    +'<td>'+formatter.format(total_ec)+'</td>'
                    +'<td>'+formatter.format(total_scc)+'</td>'
                    +'<td>'+formatter.format(total_gen)+'</td>'
                    +'<td>'+formatter.format(total_trans)+'</td>'
                    +'<td>'+formatter.format(total_dist)+'</td>'
                    +'<td>'+formatter.format(total_sysloss)+'</td>'
                    +'<td>'+formatter.format(total_others)+'</td>'
                    +'<td></td>'
                    +'<td>'+total_kwh_used+'</td>'
                    +'<td>'+formatter.format(total_non_bill)+'</td>'
                    '</tr>'
        $('.table_page_two').append(tbody)
        total_summary = total_power_bills + total_fit + total_spug + total_red + total_ec + total_scc + total_gen + total_trans + total_dist + total_sysloss + total_others
        summary_power_bill = total_summary - total_others - total_sysloss - total_dist - total_trans - total_gen - total_scc - total_ec - total_red - total_spug - total_fit
        $('.td_power_bills').text(formatter.format(summary_power_bill))
        $('.td_fit_all').text(formatter.format(total_fit))
        $('.td_spug').text(formatter.format(total_spug))
        $('.td_red').text(formatter.format(total_red))
        $('.td_ec').text(formatter.format(total_ec))
        $('.td_scc').text(formatter.format(total_scc))
        $('.td_red').text(formatter.format(total_red))
        $('.td_gen').text(formatter.format(total_gen))
        $('.td_trans').text(formatter.format(total_trans))
        $('.td_dist').text(formatter.format(total_dist))
        $('.td_sysloss').text(formatter.format(total_sysloss))
        $('.td_red').text(formatter.format(total_red))
        $('.td_others').text(formatter.format(total_others))
        $('.td_total').text(formatter.format(total_summary))
        $('.teller_label').text(teller)
        $('.run_date').text(mm + '/' + dd + '/' + yyyy)
        $('.run_time').text(new Date().toLocaleTimeString())
    })
    var formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'PHP',
    });
</script>
</html>
