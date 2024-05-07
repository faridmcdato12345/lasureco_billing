<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">'
    <title>Complaint Form</title>
    <link rel="shortcut icon" href="/img/logo.png">
    <link rel="stylesheet" href="{{asset('css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/buttons.bootstrap.min.css')}}">
</head>
<style>
    body{
        padding: 20px 20px 0px 20px;
        font-size:12px;
    }
    @media print{
        @page{
            size:Legal portrait;
            margin:0;
        }
    }

</style>
<body>
<div id = "complaint_print">


</div>

<script>
    var data2 = localStorage.getItem('newData');
    data2 = data2.split(',');
    console.log(data2);
    var data = JSON.parse(localStorage.getItem('data'));
    console.log(data);
    var output='';
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var date = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();
    if(typeof data2[5] == 'undefined'){
        data2[5] = '';
    }
    output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
    output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
    output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
    output += '<label style="font-size: 20px;">' + 'INSTITUTIONAL SERVICES DEPARTMENT' + '</label><br>';
    output += '<label style="font-size: 20px;">' + 'Complaint Form' + '</label><br></center><hr>';

    output +='<table class="table" style="font-size:12px;width:100%">' +
        '<tr>' +
            '<td>Fullaname:</td>' +
            '<td>' + data.cm_full_name  + '</td>' +
            '<td>Account Number:</td>' +
            '<td>' + data.cm_account_no  + '</td>' +
        '</tr>' +
        '<tr>' +
            '<td>Address:</td>' +
            '<td>' + data.cm_address  + '</td>' +
            '<td>Type:</td>' +
            '<td>' + data.ct_desc  + '</td>' +
        '</tr>' +
        '<tr>' +
            '<td>Meter Number:</td>' +
            '<td>' + data.mm_master  + '</td>' +
            '<td>Complaint No.</td>' +
            '<td>' + data2[2]  + '</td>' +
        '</tr>' +
    '</table>';
    output += '<table>' +
        '<tr>' +
            '<td>Nature of Complaint:</td>' +
        '</tr>' +
        '<tr>' +
            '<td style="padding-left:15px;text-align:right">' + data2[3] + '</td>' +
            '<td style="padding-left:25px;text-align:right">' + data2[5] + '</td>' +
        '</tr>' +
    '</table><br>'; 
    output += '<div class="row">' +
           '<div class="col">' +
                '<label>Consumer/Representative:</label><br><br><br>'+
                '<label style="border-top:1px solid black;font-family:italic">(Signature over Printed Name)</label>' +
            '</div>' +
            '<div class="col">' +
                '<label style="margin-left:40%;">Attended By:</label><br><br><br>'+
                '<label style="margin-left:40%;border-top:1px solid black;font-family:italic">(Signature over Printed Name)</label>' +
            '</div>' +
    '</div><br><br>';
    output += '<div class="row">' +
           '<div class="col">' +
                '<label>Checked by:</label><br><br><br>'+
                '<label style="border-top:1px solid black;font-family:italic">ASHARY B. BATO<br>OIC-CS Section Head</label>' +
            '</div>' +
            '<div class="col">' +
                '<label>Reviewd by:</label><br><br><br>'+
                '<label style="border-top:1px solid black;font-family:italic">NASIFA D. MANALOCON <br> OIC-MSD Supervisor</label>' +
            '</div>' +
            '<div class="col">' +
                '<label style="margin-left:40%;">Noted by:</label><br><br><br>'+
                '<label style="margin-left:40%;border-top:1px solid black;font-family:italic">ABDULLATIF U. AMER<br>OIC-ISD Manager</label>' +
            '</div>' +
    '</div><br><br><br>';

    output += '<center><label>Approved by:</label></center><br><br><br>';
    output += '<label>CONCERNED DEPARTMENT<label style="font-family:italic">(Please check)</label></label><br><br>';
    output += '<label style="margin-left:20%">Technical Services Department</label><br><br><br><br>';
    output += '<label>Action Taken</label><br><br><br><br>';
    output += '<div style="width:100%;border-style:dashed;"></div><br>';
    output += '<label>COMPLAINT FORM</label><br><br>';
    output += '<label>Acknowledgment Receipt</label><br><br>';
    output += '<table style="margin-left:5%">' +
        '<tr>' +
            '<td style="text-align:right">Date:</td>' +
            '<td style="padding-left:5px;font-weight:bold">'+date+'</td>' +
        '</tr>' +
        '<tr>' +
            '<td style="text-align:right">Complaint No:</td>' +
            '<td style="padding-left:5px;font-weight:bold">'+data2[1]+'</td>' +
        '</tr>' +
        '<tr>' +
            '<td style="text-align:right">Account No:</td>' +
            '<td style="padding-left:5px;font-weight:bold">' +data.cm_account_no+ '</td>' +
        '</tr>' +
        '<tr>' +
            '<td style="text-align:right">Consumer Name:</td>' +
            '<td style="padding-left:5px;font-weight:bold">'+data.cm_full_name+'</td>' +
        '</tr>' +
        '<tr>' +
            '<td style="text-align:right">Address:</td>' +
            '<td style="padding-left:5px;font-weight:bold">'+data.cm_address+'</td>' +
        '</tr>' +
    '</table><br><br><br>';
    output += '<div class="row">' +
           '<div class="col"><br>' +
                '<label>Attended by:</label><br><br><br><br>'+
                '<label style="border-top:1px solid black;font-family:italic">(Signature over Printed Name)</label>' +
            '</div>' +
            '<div class="col"><br>' +
                '<label style="margin-left:40%;">For further details and inquiries:</label><br>'+
                '<label style="margin-left:40%;">please contact:</label><br>'+
                '<label style="font-weight:bold;margin-left:40%;">GLOBE: 0915-9480-139</label><br>'+
                '<label style="font-weight:bold;margin-left:40%;">SMART: 0950-5752-540</label><br>'+
            '</div>' +
    '</div><br><br>';
    document.querySelector('#complaint_print').innerHTML = output;
    window.print();
</script>
</body>
</html>

<!-- web route  -->

