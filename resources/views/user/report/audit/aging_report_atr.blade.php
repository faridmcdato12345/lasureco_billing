@extends('layout.master')
@section('title', 'Aging Report(Area,Town,Route)')
@section('content')

<p class="contentheader"> Aging Report(Area/Town/Route)</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td style="width: 17%;"> 
                Area Code:
            </td>
            <td style="width: 39%;"> 
                <input type="text" class="input-Txt" href="#town" name="area" placeholder="Select Area" readonly>
            </td>
            <td rowspan=5> 
                <table class="ARATRTable"> 
                    <tr> 
                        <td> 
                            <input type="text" name="" placeholder="0">
                        </td>
                        <td> 
                            <input type="text" name="" placeholder="0">
                        </td>
                        <td style="width: 35%;"> 
                            Past Due
                        </td>
                    </tr>
                    <tr> 
                        <td> 
                            <input type="text" name="" placeholder="0">
                        </td>
                        <td> 
                            <input type="text" name="" placeholder="0">
                        </td>
                        <td> 
                            Past Due
                        </td>
                    </tr>
                    <tr> 
                        <td> 
                            <input type="text" name="" placeholder="0">
                        </td>
                        <td> 
                            <input type="text" name="" placeholder="0">
                        </td>
                        <td> 
                            Past Due
                        </td>
                    </tr>
                    <tr> 
                        <td> 
                            <input type="text" name="" placeholder="0">
                        </td>
                        <td> 
                            <input type="text" name="" placeholder="0">
                        </td>
                        <td> 
                            Past Due
                        </td>
                    </tr>
                    <tr> 
                        <td> </td>
                        <td> 
                            <input type="text" name="" placeholder="0">
                        </td>
                        <td> 
                            Over One(1) Year
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr> 
            <td> 
                Town Code:
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#town" name="town" placeholder="Select Town" readonly>
            </td>
        </tr>
        <tr> 
            <td> 
                Route Code:
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#route" name="route" placeholder="Select Route" readonly>
            </td>
        </tr>
        <tr> 
            <td> 
                As of Date:
            </td>
            <td> 
                <input type="date" name="asOfDate"> 
            </td>
        </tr>
        <tr> 
            <td> 
                Status:
            </td>
            <td> 
                <select name="status"> 
                    <option value="active"> Active </option>
                    <option value="inacive"> Inactive </option>
                </select>
            </td>
        </tr>
        <tr> 
            <td> 
                Prepared By:
            </td>
            <td> 
                <input type="text" name="prepBy" placeholder="Prepared By">
            </td>
            <td> 
                &nbsp; <input type="text" name="prepByPos" placeholder="Position">
            </td>
        </tr>
        <tr> 
            <td> 
                Checked By:
            </td>
            <td> 
                <input type="text" name="checkBy" placeholder="Checked By">
            </td>
            <td> 
                &nbsp; <input type="text" name="checkByPos" placeholder="Position">
            </td>
        </tr>
        <tr> 
            <td colspan=4> 
                <button class="modal-button" style="height: 40px; float: right; margin-right: 5px;"> 
                    Print 
                </button>
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
        c[20].style.color="blue";
     }
</script>
@endsection
