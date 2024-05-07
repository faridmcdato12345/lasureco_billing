@extends('layout.master')
@section('title', 'Cashiers DCR')
@section('content')

<p class="contentheader">Cashier's DCR</p>
<div class="main">
    <table border=0 class="content-table">
        <tr style="height: 50px;">
            <td style="width: 100px;">
                &nbsp; Month:
            </td>
            <td>
                <input type="month" name="CDCRMonth" style="width: 215px;">
            </td>
        </tr>
        <tr>
            <td colspan=2>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <table border=0 style="width: 100%;">
                    <tr>
                        <td style="width: 25%;">
                            <div class="CDCRT1HeadDiv">
                                <table border=0 class="CDCRT1HeadTable">
                                    <tr>
                                        <td>
                                            &nbsp; CDCR Date
                                        </td>
                                        <td>
                                            Total Amount
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="CDCRT1TableDiv">
                                <table border=0 class="CDCRT1Table">
                                    <tr>
                                        <td>
                                            &nbsp; CDCR Table
                                        </td>
                                        <td>
                                            Total Amount
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td>
                            <div class="CDCRT2HeadDiv">
                                <table class="CDCRT2HeadTable">
                                    <tr>
                                        <td>
                                            &nbsp; Office
                                        </td>
                                        <td>
                                            ARN
                                        </td>
                                        <td>
                                            ARD
                                        </td>
                                        <td>
                                            Coll. Date From
                                        </td>
                                        <td>
                                            Coll. Date To
                                        </td>
                                        <td>
                                            Teller
                                        </td>
                                        <td>
                                            TOR From
                                        </td>
                                        <td>
                                            TOR To
                                        </td>
                                        <td>
                                            Total Collected
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="CDCRT2TableDiv">
                                <table class="CDCRT2Table">
                                    <tr>
                                        <td>
                                            &nbsp; Office
                                        </td>
                                        <td>
                                            ARN
                                        </td>
                                        <td>
                                            ARD
                                        </td>
                                        <td>
                                            Coll. Date From
                                        </td>
                                        <td>
                                            Coll. Date To
                                        </td>
                                        <td>
                                            Teller
                                        </td>
                                        <td>
                                            TOR From
                                        </td>
                                        <td>
                                            TOR To
                                        </td>
                                        <td>
                                            Total Collected
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn2');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container2').childNodes;
        c[9].style.color="blue";
     }
</script>
@endsection
