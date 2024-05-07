<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>New Ewallet OR</title>
</head>
<body>
  <p id = "userid" style="display:none">{{Auth::user()->username}}</p>
</body>
<style>

#invoice-POS {
  width: 10in;
  float:left;
  font-family: Lucida Console;
  /* background-image: url('/img/OR2.png'); */
  background-repeat: no-repeat;
  background-position: fixed;
  background-size: 100% 100%;
  height: 5.20in;
  /* border:1px solid black; */
}

/* * {
    -webkit-print-color-adjust: exact !important;
    color-adjust: exact !important;                
} */
  #ornum {
    color: red; 
    font-family: Lucida Console;
    margin-top: 60px; 
    margin-left:250px; 
    font-size: 25px;
    visibility: hidden;
  }
  /* #accNo {
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
  } */
  #months {
    margin-left: 30px;
    font-size: 10px;
    margin-top: 10px;
    height:80px;
    display:block;
  }
  #months2 {
    margin-left: 30px;
    font-size: 10px;
    margin-top: 10px;
    height:80px;
    display:block;
  }
  #monthsTable {
    font-size: 10px;
    height: 100%;
    width: 90%;
    /* border-bottom: 1px solid black; */
    margin-top:-12%;
    margin-left:-16px;
  }
  #monthsTable2 {
    font-size: 10px;
    height: 100%;
    width:90%;
    /* border-bottom: 1px solid black; */
    margin-top:-12%;
    margin-left:-16px;
  }
  #totalTbl {
    font-size: 12px;
    width: 100%;
    margin-left: 40px;
    margin-top: 15%;
    height:50px;
  }
  #totalTbl2 {
    font-size: 12px;
    width: 100%;
    margin-top: 15%;
    margin-left: 40px;
  }
  #ornum2 {
    color: red; 
    font-family: Lucida Console;
    margin-top: 60px; 
    margin-left:250px; 
    font-size: 25px;
    visibility: hidden;
  }
@media screen and (max-width: 1366px){
  #totalTbl {
    font-size: 12px;
    width: 80%;
    margin-top: -1.5%;
    margin-left: 30px;
    position:fixed;
  }
  #totalTbl2 {
    font-size: 12px;
    width: 80%;
    margin-left: 50px;  
    position:fixed; 
  }
  #months {
    margin-left: 30px;
    font-size: 10px;
    margin-top: 10px;
    height:80px;
  }
  #months2 {
    margin-left: 30px;
    font-size: 10px;
    margin-top: 10px;
    height:80px;
  }
}
@media screen,print and (max-width: 1600px){

  #totalTbl {
    font-size: 12px;
    width: 80%;
    margin-left: 40px;
    margin-top: 18%;
    height:50px;
  }
  #totalTbl2 {
    font-size: 12px;
    width: 80%;
    margin-top: 18%;
    margin-left: 40px;
  }
  #months {
    margin-left: 40.5px;
    font-size: 10px;
    margin-top: 60px;
    height:80px;
  }
  #months2 {
    margin-left: 40.5px;
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
      size: 8.5in 5.5in landscape;
      margin: 0;
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
      <div style="float: left; width: 50%;">
        <br><br><br><br>
        <p id="ornum"> 
          
        </p>
        <table style="width:90%;margin-left:5%;border-bottom:1px solid black;font-size:14px;font-family:Lucida Console">
          <tr>
            <td>
              Account Number:
            </td>
            <td id="accNo">
            
            </td>
          </tr>  
          <tr>
            <td>
              Name:
            </td>
          <td id="consName">
            
          </td>
          </tr>
          <tr>
            <td>
              Address:
            </td>
            <td colspan=2 id="address">
            
            </td>
          </tr>
          <tr>
            <td>
              Date:
            </td>
            <td colspan=2 id="date">
              
            </td>
          </tr>
        </table>
        <div id="months">
          <table id="monthsTable">
          </table>
        </div>
        <div style="display:block"></div>
        <br><br>
        <table id="totalTbl">
            <tr><td> &nbsp;  </td></tr>
            
            
            <tr>
              <td style="width: 60%;" id="orT"> </td> <td id = "ttamount" style="text-align: center;">  </td>
            </tr> 
            <tr><td colspan=3 id = "chequeInfo">   </td></tr>
			<tr><td> &nbsp; </td></tr>
			<tr><td> &nbsp;  </td></tr>
            <tr>
              <td id="amountEnglish">  </td> <td id="nameTeller" style="text-align: left;"> </td>
            </tr>
            
          </table>
      </div>
      <div style="float: right; width: 50%;">
      <br><br><br><br>
        <p id="ornum2">
          
        </p>
        <table style="width:90%;margin-left:5%;border-bottom:1px solid black;font-size:14px;font-family:Lucida Console">
          <tr>
            <td>
              Account Number:
            </td>
            <td id="accNo2">
            
            </td>
          </tr>  
          <tr>
            <td>
              Name:
            </td>
          <td id="consName2">
            
          </td>
          </tr>
          <tr>
            <td>
              Address:
            </td>
            <td colspan=2 id="address2">
            
            </td>
          </tr>
          <tr>
            <td>
              Date:
            </td>
            <td colspan=2 id="date2">
              
            </td>
          </tr>
        </table>
        <div id="months2">
          <table id="monthsTable2">
          </table>
        </div>
        <div style="display:block"></div>
        <br><br>
        <table id="totalTbl2">
             <tr><td> &nbsp;  </td></tr>
             

            <tr>
              <td style="width: 60%;" id="orT2">  </td> <td  colspan=2 id = "ttamount2" style="text-align: center;">  </td>
            </tr>
            <tr><td colspan=3 id = "chequeInfo2">  </td></tr>
			<tr><td> &nbsp; </td></tr>
			<tr><td> &nbsp;  </td></tr>
            <tr>
              <td id="amountEnglish2"> </td> <td id="nameTeller2" style="text-align: left;">  </td>
            </tr>
          </table>
      </div>
      </div>
    </div>
</body>
</html>

<script>
  function PBOR(){
    
    var res2 = JSON.parse(localStorage.getItem('data'));
    var res = JSON.parse(localStorage.getItem('res'));
    console.log(res);
      var accountInfo = JSON.parse(localStorage.getItem('accountInfo'));
     console.log(accountInfo) 
     if(accountInfo.name == ''){
        accountInfo.name = 'N/A';
      }
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
      // ornum = "03000" + res2.or_num
      ornum = res2.or_num
      document.querySelector('#orT').innerHTML = ornum;
      document.querySelector('#orT2').innerHTML = ornum;
      document.querySelector('#ornum').innerHTML = res2.or_num;
      document.querySelector('#ornum2').innerHTML = res2.or_num;
      var output="";
      output += '<tr><td style="width:100%;font-size:14px">E-wallet Deposit</td><td  style="width:100%;font-size:14px">' +res2.or_amount.toLocaleString("en-US", { minimumFractionDigits: 2 })+ '</td></tr>';
      document.querySelector('#monthsTable').innerHTML = output;
      document.querySelector('#monthsTable2').innerHTML = output;
      totalamount = parseFloat(res2.or_amount);
      document.querySelector('#ttamount').innerHTML = totalamount.toLocaleString("en-US", { minimumFractionDigits: 2 });
      document.querySelector('#ttamount2').innerHTML = totalamount.toLocaleString("en-US", { minimumFractionDigits: 2 });
      var nameTeller = document.querySelector('#userid').innerHTML;
      document.querySelector('#nameTeller').innerHTML = "[" + nameTeller + "]";
      document.querySelector('#nameTeller2').innerHTML = "[" + nameTeller + "]";
      document.querySelector('#amountEnglish').innerHTML = "Total Arrears:" + " " + res.Total_Arrears.toLocaleString("en-US", { minimumFractionDigits: 2 });
      document.querySelector('#amountEnglish2').innerHTML = "Total Arrears:" + " " + res.Total_Arrears.toLocaleString("en-US", { minimumFractionDigits: 2 });
      window.print();
       localStorage.removeItem("res");
       localStorage.removeItem("data");
       localStorage.removeItem("accountInfo");
      
  }
</script>