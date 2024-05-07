@extends('Layout.master')
@section('title', 'Data Entry of Streetlight Inventory')
@section('content')
<p class="contentheader">Data Entry of Streetlight Inventory</p>
<div class="main">
    <table border=0 class="EMR-table" style="height: 467px;">
        <tr style="height: 45px;">
            <td class="thead">
                &nbsp; Route:
            </td>
            <td>
                <input type="text" class="input-Txt" href="#route" name="route" placeholder="Select Route" readonly>
            </td>
            <td>
                &nbsp; Reading Date
            </td>
            <td>
                <input id="nobtninput" name="readingDate" type="date" style="width: 95%;">
            </td>
        </tr>
        <tr style="height: 45px;">
            <td class="rightTxtTD">
                &nbsp; Book:
            </td>
            <td>
                <input type="number" name="book" id="nobtninput" placeholder="Book">
            </td>
            <td>
                &nbsp; Meter Reader:
            </td>
            <td>
                <input type="text" class="input-Txt" href="#meterreader" name="meterReader" style="width: 95%;" placeholder="Select Meter Reader" readonly>
            </td>
        </tr>
        <tr style="height: 45px;">
            <td>
                &nbsp; Billing Period:
            </td>
            <td>
                <input id="nobtninput" type="month">
            </td>
            <td colspan=2> </td>
        </tr>
        <tr>
            <td colspan="9">
                <div style="width: 95%; margin-left: auto; margin-right: auto; color: white; background-color: #5B9BD5; height: 40px;">
                    <table border="0" style="width: 100%;">
                        <tr>
                            <td style="width: 220px;">
                                &nbsp; Account Number
                            </td>
                            <td style="width: 220px;">
                                Consumer Number
                            </td>
                            <td style="width: 220px;">
                                Previous Reading
                            </td>
                            <td>
                                KWH/Used
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="width: 95%; height: 230px; overflow-x: hidden; margin-left: auto; margin-right: auto; background-color: white; color: black;">
                    <table class="streetLightTable" border="0" style="width: 100%;">
                        <tr>
                            <td style="width: 220px;">
                                &nbsp; Quantity
                            </td>
                            <td style="width: 220px;">
                                Wattage
                            </td>
                            <td style="width: 220px;">
                                KWH/Bulb
                            </td>
                            <td>
                                KWH/Used
                            </td>
                            <td style="width: 200px; text-align: center;">
                                <button style="border: none; border-radius: 3px; height: 30px; width: 75px; background-color: rgb(23, 108, 191); color: white; font-size: 19px;"> Add </button>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="9">
                <div style="float: right; margin-right: 1.5%;">
                    <button style="width: 90px; font-size: 18px; color: rgb(23, 108, 191); background-color: white; border-radius: 5px; border: none; height: 40px;"> Print </button>
                </div>
            </td>
        </tr>
    </table>
</div>
@endsection
