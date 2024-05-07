@extends('layout.master')
@section('title', 'Street Light Maintenance')
@section('content')

<p class="contentheader">Street Lights Maintenance</p>
<div class="main">
    <table border=0 class="content-table">
        <tr>
            <td style="height: 50px;">
                <table border=0 style="margin-left: 5px; margin-top: 20px;">
                    <tr>
                        <td>
                            Account Number: &nbsp;&nbsp;
                        </td>
                        <td>
                            <input type="text" class="input-Txt" href="#accNo" name="accountNo" placeholder="Select Account Number" readonly>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="height: 10px;">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td>
                <div style="width: 97%; margin-left: auto; margin-right: auto; margin-top: -10px; color: white; background-color: #5B9BD5; height: 40px;">
                    <table border="0" style="width: 100%;">
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
                        </tr>
                    </table>
                </div>
                <div style="width: 97%; height: 300px; overflow-x: hidden; margin-left: auto; margin-right: auto; background-color: white; color: black;">
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
                            <td style="width: 90px; text-align: center;">
                                <button style="border: none; border-radius: 3px; height: 30px; background-color: #6C757E; color: white; font-size: 17px;"> Modify </button>
                            </td>
                            <td style="width: 120px; text-align: center;">
                                <button style="border: none; border-radius: 3px; height: 30px; background-color: #FF0000; color: white; font-size: 17px;"> Delete </button>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div style="float: right; margin-right: 1.65%;">
                    <button style="width: 90px; font-size: 18px; color: rgb(23, 108, 191); background-color: white; border-radius: 5px; border: none; height: 40px;"> Print </button>
                </div>
            </td>
        </tr>
    </table>
</div>
@endsection
