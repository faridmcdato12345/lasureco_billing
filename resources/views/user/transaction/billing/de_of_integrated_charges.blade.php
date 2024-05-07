@extends('Layout.master')
@section('title', 'Enter Meter Readings')
@section('content')
<p class="contentheader">Data Entry of Streetlights Inventory </p>
<div class="main">
    <table border=0 class="EMR-table" style="color:white;height: 542px;">
        <tr>
            <td style="width: 50%;">
                <table border=0 style="width: 95%; height: 540px; margin-left: 5%;">
                    <tr>
                        <td>
                            Route:
                        </td>
                        <td>
                            <input type="text" name="route" value="Select Route" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Account Number:
                        </td>
                        <td>
                            <input type="text" name="account" value="Select Account" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Account Name:
                        </td>
                        <td colspan="2">
                            PNP POST-B AREA 4
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Address:
                        </td>
                        <td colspan="2">
                            Sagonsongan, Marawi City
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Type:
                        </td>
                        <td colspan="2">
                            PUB
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Status:
                        </td>
                        <td colspan="2">
                            D
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Account Name:
                        </td>
                        <td colspan="2">
                            PNP POST-B AREA 4
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Fee:
                        </td>
                        <td>
                            <input type="text" name="fee" value="Select Fee" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Non-Bill Integration:
                        </td>
                        <td colspan="2">
                        <input type="text" name="nonBillInt" placeholder="Non-Bill Integration">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            No. of Months to Pay:
                        </td>
                        <td colspan="2">
                        <input type="number" name="monthsToPay" placeholder="0">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Starting Bill Year Application
                        </td>
                        <td colspan="2  ">
                            <input type="month" name="billYear">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Remarks:
                        </td>
                        <td colspan="2">
                            <input type="text" name="remarks" placeholder="Remarks">
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%;">
                <div style="width: 97%; margin-left: auto; height: 40px; margin-right: auto; color: white; background-color: #5B9BD5;">
                    <table border="0" style="width: 97%;">
                        <tr>
                            <td style="width: 33.33%;">
                                &nbsp; #
                            </td>
                            <td style="width: 33.33%;">
                                VAT
                            </td>
                            <td style="width: 33.33%;">
                                Amortization
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="width: 97%; margin-left: auto; margin-right: auto; height: 200px; background-color: white; color: black; overflow-x: hidden;">
                    <table class="amortTable" style="width: 100%; color">
                        <tr>
                            <td style="width: 33.33%;"> &nbsp; 1st Month </td>
                            <td style="width: 33.33%;"> 0.60 </td>
                            <td style="width: 33.33%;"> 5.00 </td>
                        </tr>
                        <tr>
                            <td style="width: 33.33%;"> &nbsp; 2nd Month </td>
                            <td style="width: 33.33%;"> 0.60 </td>
                            <td style="width: 33.33%;"> 5.00 </td>
                        </tr>
                    </table>
                </div>
                <div style="float: right; margin-right: 1.7%; margin-top: 37%;">
                    <button style="width: 90px; font-size: 18px; color: rgb(23, 108, 191); background-color: white; border-radius: 5px; border: none; height: 40px;"> Save </button>
                </div>
            </td>
        </tr>
    </table>
</div>
@endsection
