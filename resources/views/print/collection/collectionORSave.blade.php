<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{csrf_token()}}">
	<title>Print</title>
</head>
  <p id = "userid" style="display:none">{{Auth::user()->username}}</p>
<style>

#invoice-POS {
  width: 10in;
  float:left;
  font-family: Lucida Console;
  /* background-image: url('/img/OR2.png'); */
  background-repeat: no-repeat;
  background-position: fixed;
  background-size: 100% 100%;
  height: 4.2in;
}

/* * {
    -webkit-print-color-adjust: exact !important;
    color-adjust: exact !important;                
} */
  #ornum {
    color: red; 
    font-family: Lucida Console;
    margin-top: 60px; 
    margin-left:340px; 
    font-size: 25px;
  }
  #accNo {
    font-size: 12px;
    margin-left: 85px;
    margin-top: -10px;
  }
  #consName {
    font-size: 12px;
    margin-left: 85px;
    margin-top: -8px;
  }
  #address {
    font-size: 12px;
    margin-left: 85px;
    margin-top: -10px;
  }
  #date {
    font-size: 12px;
    margin-left: 360px;
    margin-top: -55px;
  }
  #months {
    margin-left: 30px;
    font-size: 10px;
    margin-top: 60px;
    height:80px;
  }
  #months2 {
    margin-left: 30px;
    font-size: 10px;
    margin-top: 60px;
    height:80px;
  }
  #monthsTable {
    font-size: 10px;
    height: 100%;
    width: 60%;
    margin-left:40px;
  }
  #monthsTable2 {
    font-size: 10px;
    height: 100%;
    width: 60%;
    margin-left:35px;
  }
  #totalTbl {
    font-size: 12px;
    width: 80%;
    margin-left: 40px;
    margin-top: -.8%;
    height:50px;
  }
  #totalTbl2 {
    font-size: 12px;
    width: 80%;
    margin-top: -.8%;
    margin-left: 40px;
  }
  #ornum2 {
    color: red; 
    font-family: Lucida Console;
    margin-top: 100px; 
    margin-left: 340px; 
    font-size: 25px;
  }
  #accNo2 {
    font-size: 12px;
    margin-left: 85px;
    margin-top: -15px;
  }
  #consName2 {
    font-size: 12px;
    margin-left: 85px;
    margin-top: -8px;
  }
  #address2 {
    font-size: 12px;
    margin-left: 85px;
    margin-top: -8px;
  }
  #date2 {
    font-size: 12px;
    margin-left: 360px;
    margin-top: -60px;
  }
@media screen and (max-width: 1366px){
  #totalTbl {
    font-size: 12px;
    width: 80%;
    margin-top: -1.5%;
    margin-left: 50px;
  }
  #months {
    margin-left: 40px;
    font-size: 10px;
    margin-top: 60px;
    height:80px;
  }
  #totalTbl2 {
    font-size: 12px;
    width: 80%;
    margin-top: -1.5%;
    margin-left: 50px;
    
  }
  #months2 {
    margin-left: 40px;
    font-size: 10px;
    margin-top: 60px;
    height:80px;
  }
}
@media screen,print and (max-width: 1600px){
  #accNo {
    font-size: 12px;
    margin-left: 110px;
    margin-top: -12px;
  }
  #consName {
    font-size: 12px;
    margin-left: 110px;
    margin-top: -10px;
  }
  #address {
    font-size: 12px;
    margin-left: 110px;
    margin-top: -12px;
  }
  #accNo2 {
    font-size: 12px;
    margin-left: 110px;
    margin-top: -17px;
  }
  #consName2 {
    font-size: 12px;
    margin-left: 110px;
    margin-top: -10px;
  }
  #address2 {
    font-size: 12px;
    margin-left: 110px;
    margin-top: -10px;
  }
  #totalTbl {
    font-size: 12px;
    width: 80%;
    margin-top: -1.5%;
    margin-left: 50px;
  }
  #months {
    margin-left: 40px;
    font-size: 10px;
    margin-top: 60px;
    height:80px;
  }
  #totalTbl2 {
    font-size: 12px;
    width: 80%;
    margin-top: -1.5%;
    margin-left: 50px;
    
  }
  #months2 {
    margin-left: 40px;
    font-size: 10px;
    margin-top: 60px;
    height:80px;
  }
  body{
      /* box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); */
      /* padding: 2mm; */     
      font-family: Lucida Console;
    }
}
  @media print 
{
    @page {
      size: 8.5in 5in portrait;
      margin:0;
    }
    body{
      /* box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); */
      /* padding: 2mm; */     
        font-family: Lucida Console;
        }
}
</style>
<body onload="PBOR();">
    <div id="invoice-POS">
      <div style="font-family:Lucida Console;float: left; width: 50%;">
        <br><br>
        <p id="ornum"> 
          
        </p>
        <br>
        <p id="accNo">
          
        </p>
        <p id="consName">
          
        </p>
        <p id="address">
          
        </p>
        <P id="date">
          
        </p>
        <div id="months">
          <table style="font-family:Lucida Console" id="monthsTable">
          </table>
        </div>
        <table style="font-family:Lucida Console" id="totalTbl">
            <tr><td> &nbsp; </td></tr>
            <tr><td> &nbsp; </td></tr>
            <tr>
              <td style="width: 60%;" id="orT"> </td> <td id = "ttamount" style="padding-left:23px;font-size:12px;text-align: left;">  </td>
            </tr>
            <tr>
            <td style = "font-weight:bold" id="amountEnglish"></td>
            </tr>
            <tr>
              <td style = "font-weight:bold" id="amountEnglish4">  </td> <td id="nameTeller" style="padding-left:23px;font-size:12px;text-align: left;"> </td>
            </tr>
          </table>
      </div>
      <div style="font-family:Lucida Console;float: right; width: 50%;">
        <p id="ornum2">
          
        </p>
        <br>
        <p id="accNo2">
          
        </p>
        <p id="consName2">
          
        </p>
        <p id="address2">
          
        </p>
        <p >
          <P id="date2">
           
          </p>
        </p>
        <div id="months2">
          <table style="font-family:Lucida Console;" id="monthsTable2">
          </table>
        </div>
        <table  style="font-family:Lucida Console" id="totalTbl2">
          <tr><td> &nbsp; </td></tr>
          <tr><td> &nbsp; </td></tr>
            <tr>
              <td id="orT2">  </td> <td  id = "ttamount2" style="text-align: center;">  </td>
            </tr>
            <tr>
            <td style = "font-weight:bold" id="amountEnglish2"></td>
            </tr>
            <tr>
              <td style = "font-weight:bold" id="amountEnglish3"></td> <td id="nameTeller2" style="font-size:12px;text-align: right;">  </td>
            </tr>
          </table>
      </div>
      </div>
    </div>
</body>
</html>

<script>
  function PBOR(){
    
      var res = JSON.parse(localStorage.getItem('res'));
      console.log(res);
      var data = JSON.parse(localStorage.getItem('consumer'));
      var accountInfo = JSON.parse(localStorage.getItem('accountInfo'));
      var date = res.Date_Paid.split(" ");
      var time = date[1].split(":");
      var cTime = time[0]+":"+time[1]; 
      document.querySelector('#accNo').innerHTML = accountInfo.accNumber;
      var ornum = document.querySelector('#ornum').innerHTML;
      document.querySelector('#consName').innerHTML = accountInfo.name;
      document.querySelector('#address').innerHTML = accountInfo.address;
      document.querySelector('#date').innerHTML = date[0] + "<br>" + cTime;
      document.querySelector('#accNo2').innerHTML = accountInfo.accNumber;
      document.querySelector('#consName2').innerHTML = accountInfo.name;
      document.querySelector('#address2').innerHTML = accountInfo.address;
      document.querySelector('#date2').innerHTML = date[0] + "<br>" + cTime;
      ornum = "03000" + data.Details.TOR_No.or_no;
      document.querySelector('#orT').innerHTML = ornum;
      document.querySelector('#orT2').innerHTML = ornum;
      document.querySelector('#ornum').innerHTML = data.Details.TOR_No.or_no;
      document.querySelector('#ornum2').innerHTML = data.Details.TOR_No.or_no;
      var output ="";
    var details = data.Details;
  if(typeof details.PB != 'undefined'){
    if(details.PB.length > 0){
      for(let i in details.PB){
        var amountMR = details.PB[i].mr_amount;
          var datereg = details.PB[i].mr_date_reg.split("-");
          var datereg = datereg[0]+datereg[1];
          var myr = details.PB[i].mr_yr_month;
          var d =['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];
          var myr1 = myr.slice(0,4);
          var myr2 = myr.slice(4);
          output += "<tr><td style='font-size:12px;'>" + myr1 + d[parseInt(myr2)] + "</td>" +
                    "<td style='font-size:12px;text-align: right'>"+ amountMR.toLocaleString("en-US", { minimumFractionDigits: 2 })  +"</td>" +
                    "</tr>";
          if(typeof data.Partial != 'undefined'){
            if(data.Partial != 0){
            output += "<tr><td style='font-size:12px;'>" + 'Partial' + "</td>" +
                    "<td style='font-size:12px;text-align: right'>"+ data.Partial.toLocaleString("en-US", { minimumFractionDigits: 2 })  +"</td>" +
                    "</tr>";  
            }
          }
      }
    }
  }
  if(typeof details.NB != 'undefined'){
    if(details.NB.length > 0){
      for(let x in details.NB){
        output += "<tr>" +
              "<td style='font-size:12px;'>" + details.NB[x].Fees_Desc + "</td>" +
              "<td style='font-size:12px;text-align: right'>"+ details.NB[x].Fee_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) +"</td>" +
        "</tr>";
      }
    }
  }
  if(data.Ewallet_Added != 0 && data.Details.Amounts.getChange == 'yes'){
    output += '<tr><td></td></tr>';
    output +='<tr>' +
    '<td style="font-size:12px;">' + 'Ewallet Deposit' + '</td>' +
    '<td style="font-size:12px;text-align: right">' +data.Ewallet_Added.toLocaleString("en-US", { minimumFractionDigits: 2 })+ '</td>' +
    '</tr>';
  }
  if(data.Ewallet_Applied != 0){
    output += '<tr><td></td></tr>';
    output +='<tr>' +
    '<td style="font-size:12px;">' + 'Ewallet Applied' + '</td>' +
    '<td style="font-size:12px;text-align: right">' +data.Ewallet_Applied.toLocaleString("en-US", { minimumFractionDigits: 2 })+ '</td>' +
    '</tr>';
  }
  if(typeof details.ewallet_credit !="undefined"){
   console.log(details.ewallet_credit.ewc)
  output += '<tr><td></td></tr>';
    output +='<tr>' +
    '<td style="font-size:12px;">' + 'Ewallet Credit' + '</td>' +
    '<td style="font-size:12px;text-align: right">' + parseFloat(details.ewallet_credit.ewc).toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
    '</tr>';
 }

      
      console.log(data);
      document.querySelector('#monthsTable').innerHTML = output;
      document.querySelector('#monthsTable2').innerHTML = output;
      var num = res.Total_Amount;
      document.querySelector('#ttamount').innerHTML = num.toLocaleString("en-US", { minimumFractionDigits: 2 });
      document.querySelector('#ttamount2').innerHTML = num.toLocaleString("en-US", { minimumFractionDigits: 2 });
      var nameTeller = document.querySelector('#userid').innerHTML;
      document.querySelector('#nameTeller').innerHTML = "[" + nameTeller + "]";
      document.querySelector('#nameTeller2').innerHTML = "[" + nameTeller + "]";
      document.querySelector('#amountEnglish4').innerHTML = "Total Arrears:" + " " + res.Total_Arrears.toLocaleString("en-US", { minimumFractionDigits: 2 });
      document.querySelector('#amountEnglish3').innerHTML = "Total Arrears:" + " " + res.Total_Arrears.toLocaleString("en-US", { minimumFractionDigits: 2 });
    // window.print();   
  }
</script>