<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Print</title>
</head>
<body>
  <p id = "userid" style="display:none">{{Auth::user()->username}}</p>
</body>
<style>

#invoice-POS {
 
  
  width: 10in;
  float:left;
  font-family: Consolas;
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
    font-family: Calibri; 
    margin-top: 60px; 
    margin-left:340px; 
    font-size: 25px;
  }
  #accNo {
    font-size: 12px;
    margin-left: 120px;
    margin-top: -10px;
  }
  #consName {
    font-size: 12px;
    margin-left: 120px;
    margin-top: -8px;
  }
  #address {
    font-size: 12px;
    margin-left: 120px;
    margin-top: -10px;
  }
  #date {
    font-size: 12px;
    margin-left: 330px;
    margin-top: -55px;
  }
  #months {
    margin-left: 70px;
    font-size: 10px;
    height:80px;
  }
  #months2 {
    margin-left: 82px;
    font-size: 10px;
    height:80px;
  }
  #monthsTable {
    font-size: 12px;
    height: 100%;
    width: 60%;
    margin-left:40px;
  }
  #monthsTable2 {
    font-size: 12px;
    height: 100%;
    width: 60%;
    margin-left:35px;
  }
  #totalTbl {
    font-size: 12px;
    width: 80%;
    margin-left: 50px;
    margin-top: 4.5%;
    height:50px;
  }
  #totalTbl2 {
    font-size: 12px;
    width: 80%;
    margin-left: 50px;
    margin-top: 4.5%;
  }
  #ornum2 {
    color: red; 
    font-family: Calibri; 
    margin-top: 100px; 
    margin-left: 340px; 
    font-size: 25px;
  }
  #accNo2 {
    font-size: 12px;
    margin-left: 120px;
    margin-top: -15px;
  }
  #consName2 {
    font-size: 12px;
    margin-left: 120px;
    margin-top: -8px;
  }
  #address2 {
    font-size: 12px;
    margin-left: 120px;
    margin-top: -8px;
  }
  #date2 {
    font-size: 12px;
    margin-left: 330px;
    margin-top: -60px;
  }
  @media print 
{
    @page {
      size: 8.5in 5in portrait;
      margin:0;
    }
    body{
      /* box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
      padding: 2mm; */

        font-family: Consolas;
        }
}
</style>
<body onload="PBOR();">
    <div id="invoice-POS">
      <div style="float: left; width: 50%;">
        <br><br>
        <p id="ornum"> 
          
        </p>
        <p id="accNo">
          
        </p>
        <p id="consName">
          
        </p>
        <p id="address">
          
        </p>
        <P id="date">
          
        </p>
        <div id="months">
          <table id="monthsTable">
          </table>
        </div>
        <table id="totalTbl">
            <tr>
              <td style="width: 67%;" id="orT"> </td> <td id = "ttamount" style="text-align: center;">  </td>
            </tr>
            
            <tr>
              <td id="amountEnglish">  </td> <td id="nameTeller" style="text-align: left;"> </td>
            </tr>
          </table>
      </div>
      <div style="float: right; width: 50%;">
        <p id="ornum2">
          
        </p>
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
          <table id="monthsTable2">
          </table>
        </div>
        <table id="totalTbl2">
            <tr>
              <td id="orT2">  </td> <td  id = "ttamount2" style="text-align: center;">  </td>
            </tr>
            
            <tr>
              <td id="amountEnglish2"> </td> <td id="nameTeller2" style="text-align: right;">  </td>
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
      ornum = "03000" + res2.or_num
      document.querySelector('#orT').innerHTML = ornum;
      document.querySelector('#orT2').innerHTML = ornum;
      document.querySelector('#ornum').innerHTML = res2.or_num;
      document.querySelector('#ornum2').innerHTML = res2.or_num;
      var output="";
      output += '<tr><td style="width:100%;">E-wallet Deposit</td><td>' +res2.or_amount.toLocaleString("en-US", { minimumFractionDigits: 2 })+ '</td></tr>';
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