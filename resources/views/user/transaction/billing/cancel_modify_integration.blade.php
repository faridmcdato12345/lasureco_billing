@extends('layout.master')
@section('title', 'Cancel Integration')
@section('content')
<p class="contentheader">Cancel Integration</p>
<div class="main">
    <table style ="height:500x;" border=0 class="content-table">
        <tr>
            <td style="width: 20%;">
                Account Number:
            </td>
            <td style="width: 35%;">
                <input type="text" name="area" class="input-Txt" href="#accNo" placeholder="Select Account Number" readonly><br>
                <p style="font-family: italic;
                          font-size: 1vw;
                          display: flex;
                          position: absolute;">
                    Datu Sobo Maranda
                    <br>
                    Brgy. Baclayan Lilod, Ditsaan-Ramain, LDS
                </p>
            </td>
            <td style="width: 15%;">
                &nbsp; No of Months:
            </td>
            <td>
                2
            </td>
        </tr>
        <tr>
            <td>
                Integration:
            </td>
            <td>
                <input type="number" name="intergration" placeholder="0.00">
            </td>
            <td>
                &nbsp; Monthly Fee:
            </td>
            <td>
                5.00
           </td>
        </tr>
        <tr>
        <td>
                Integration Amount:
            </td>
            <td>
                10.00
            </td>
            <td>
                &nbsp; Start Deduct:
            </td>
            <td>
                202103
            </td>
        </tr>
        <tr>
            <td>
                <button style="width: 145px;
                               margin-top: 30px;
                               height:40px;
                               color: white;
                               background-color: rgb(89,89,89);"
                        class="modal-button"
                        href="#intDetails">
                    Integration Details
                </button>
            </td>
            <td colspan=3>
                <button style="width: 145px;
                               margin-top: 30px;
                               height:40px;
                               color: rgb(23, 108, 191);"
                        id="printBtn"
                        href="#cancelInt">
                    Cancel Integration
                </button>
            </td>
        </tr>
    </table>
</div>

<div class="modal" id="intDetails">
    <div class="modal-content">
        <div class="modal-header">
            <h3> Integration Details </h3>
            <span class="closes" href="#intDetails"> &times; </span>
        </div>
        <div class="modal-body">
            <div class="intDetailsHeadDiv">
                <table class="intDetailsHeadTable">
                    <tr>
                        <td>
                            &nbsp; Series
                        </td>
                        <td>
                            Period
                        </td>
                        <td>
                            Monthly Amount
                        </td>
                        <td>
                            VAT Amount
                        </td>
                        <td>
                            Billing Status
                        </td>
                        <td>
                            Collected
                        </td>
                        <td>
                            TOR No.
                        </td>
                        <td style="width: 10%;"> </td>
                    </tr>
                </table>
            </div>
            <div class="intDetailsDiv">
                <table class="intDetailsTable">
                    <tr>
                        <td>
                            &nbsp; Series
                        </td>
                        <td>
                            Period
                        </td>
                        <td>
                            Monthly Amount
                        </td>
                        <td>
                            VAT Amount
                        </td>
                        <td>
                            Billing Status
                        </td>
                        <td>
                            Collected
                        </td>
                        <td>
                            TOR No.
                        </td>
                        <td style="width: 10%;
                                   text-align: center;">
                            <button style="width: 70%;
                                           border-radius: 5px;
                                           border: none;
                                           background-color: rgb(23, 108, 191);
                                           color: white;">
                                Edit
                            </button>
                        </td>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container').childNodes;
        c[5].style.color="blue";
     }
</script>
@endsection
