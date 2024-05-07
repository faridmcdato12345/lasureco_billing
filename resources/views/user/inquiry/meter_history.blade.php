@extends('layout.master')
@section('title', 'Meter History')
@section('content')

<p class="contentheader">Meter History by Meter</p>
<div class="main">
    <table class="content-table">
        <tr> 
            <td> 
                Brand:
            </td>
            <td> 
                <input type="text" name="brand" placeholder="Brand">
            </td>
            <td> 
                &nbsp; Serial No.:
            </td>
            <td> 
                <input type="number" name="serial" placeholder="Serial No">
            </td>
        </tr>
        <tr> 
            <td> 
                Account Number:
            </td>
            <td> 
                <input type="text" class="input-Txt" name="account" href="#town" placeholder="Select Account" readonly>
            </td>
            <td> 
                &nbsp; Type: 
            </td>
            <td> 
                <input type="text" name="type" placeholder="type">
            </td>
        </tr>
        <tr> 
            <td> 
                Name:
            </td>
            <td> 
                <input type="text" name="name" placeholder="Name">
            </td>
            <td> 
                &nbsp; Address: 
            </td>
            <td> 
                <input type="text" name="address" placeholder="Address">
            </td>
        </tr>
        <tr> 
            <td> 
                Status:
            </td>
            <td> 
                <input type="text" name="status" placeholder="Status">
            </td>
            <td> 
                &nbsp; Meter Type:
            </td>
            <td> 
                <input type="text" name="meterType" placeholder="Meter Type">
            </td>
        </tr>
        <tr> 
            <td> 
                Brand:
            </td>
            <td> 
                <table style="width: 100%;">
                    <tr>
                        <td>
                            <input type="text" name="brand2" placeholder="Brand"> 
                        </td>
                        <td> 
                            Correct
                        </td>
                    </tr>
                </table>
            </td>
            <td> 
                &nbsp; Serial No.
            </td>
            <td> 
                <input type="text" name="serail2" placeholder="Serial No.">
            </td>
        </tr>
        <tr> 
            <td colspan=4> 
                <button style="float: right;
                               margin-right: 10px;
                               width: 80px;
                               height: 40px;
                               border: none;
                               border-radius: 3px;
                               background-color: white;
                               color: rgb(23, 108, 191);">
                    Print 
                </button>
            </td>
        </tr>
    </table>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn4');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container2').childNodes;
        c[16].style.color="blue";
     }
</script>
@endsection
