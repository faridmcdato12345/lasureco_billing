@extends('layout.master')
@section('title', 'Collection Report on VAT - PB')
@section('content')

<p class="contentheader">Collection Report on VAT - PB</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td style="width: 15%;"> 
                Area:
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#town" name="area" placeholder="Select Area" readonly>
            </td>
            <td> 
                <table> 
                    <tr> 
                        <td> 
                            <input type="checkbox" name="allAreas" class="checkcheck">
                        </td>
                        <td> 
                            &nbsp; All Areas
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr> 
            <td> 
                Covered Period:
            </td>
            <td> 
                <input type="date" name="coveredFrom">
            </td>
            <td> 
                <input type="date" name="coveredTo">
            </td>
        </tr>
        <tr> 
            <td colspan=4> 
                <button class="modal-button" style="height: 40px; float: right; margin-right: 8px;"> 
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
        c[16].style.color="blue";
     }
</script>
@endsection
