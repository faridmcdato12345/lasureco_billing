<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Print</title>
</head>
<style>
@media print 
{
    @page {
      size: A4;
      margin:0;
    }
    body{
      box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
      padding: 2mm;
      
        width: 8.5in;
        float:left;
        font-family: Serif;
        background-size: 100% 100%;
        height: 4.2in;
        margin:0;
        }
}
#invoice-POS {
 
  /* padding: 2mm; */
  
  width: 10in;
  /* background: #fff; */
  float:left;
  font-family: Serif;
  background-image: url('/img/OR2.png');
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
    margin-top: 75px;
  }
  #months2 {
    margin-left: 82px;
    font-size: 10px;
    margin-top: 75px;
  }
  #monthsTable {
    font-size: 10px;
    height: 100%;
    width: 67%;
    margin-left:15px;
  }
  #monthsTable2 {
    font-size: 10px;
    height: 100%;
    width: 67%;
  }
  #totalTbl {
    font-size: 12px;
    width: 70%;
    margin-top: 1%;
    margin-left: 20px;
  }
  #totalTbl2 {
    font-size: 12px;
    width: 70%;
    margin-top: 0.5%;
    margin-left: 20px;
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
</style>
<body onload="PBOR();">
    <div id="invoice-POS">
      <div style="float: left; width: 50%;">
        <br><br>
        <p id="ornum"> 
          0116360
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
            <!-- <tr>
              <td>
                Mar. 2021
              </td>
              <td style="text-align: center;">
                0.00
              </td>
            </tr>
            <tr>
              <td>
                Apr. 2021
              </td>
              <td style="text-align: center;">
                569.69
              </td>
            </tr>
            <tr>
              <td>
                ADVANCE POWER BILL
              </td>
              <td style="text-align: center;">
                230.31
              </td>
            </tr>
            <tr>
              <td>
                &nbsp;
              </td>
            </tr> -->
          </table>
        </div>
        <table id="totalTbl">
            <tr>
              <td id="orT"> </td> <td id = "ttamount" style="text-align: center;">  </td>
            </tr>
            <tr><td> &nbsp; </td></tr>
            <tr>
              <td id="amountEnglish">  </td> <td id="nameTeller" style="text-align: center;"> </td>
            </tr>
          </table>
      </div>
      <div style="float: right; width: 50%;">
        <p id="ornum2">
          0116360
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
            <!-- <tr>
              <td>
                Mar. 2021
              </td>
              <td style="text-align: center;">
                0.00
              </td>
            </tr> -->
            <!-- <tr>
              <td>
                ADVANCE POWER BILL
              </td>
              <td style="text-align: center;">
                230.31
              </td>
            </tr> -->
          </table>
        </div>
        <table id="totalTbl2">
            <tr>
              <td id="orT2">  </td> <td id = "ttamount2" style="text-align: center;">  </td>
            </tr>
            <tr><td> &nbsp; </td></tr>
            <tr>
              <td id="amountEnglish2"> </td> <td id="nameTeller2" style="text-align: center;">  </td>
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
      var num = res.Total_Amount.toString();
      var accountInfo = JSON.parse(localStorage.getItem('accountInfo'));
      var date = res.Date_Paid.split(" ");
      var time = date[1].split(":");
      var cTime = time[0]+":"+time[1]; 
      document.querySelector('#accNo').innerHTML = accountInfo.accNumber;
      var ornum = document.querySelector('#ornum').innerHTML;
      ornum = "03000"+ornum;
      document.querySelector('#orT').innerHTML = ornum;
      document.querySelector('#orT2').innerHTML = ornum;
      document.querySelector('#consName').innerHTML = accountInfo.name;
      document.querySelector('#address').innerHTML = accountInfo.address;
      document.querySelector('#date').innerHTML = date[0] + "<br>" + cTime;
      document.querySelector('#accNo2').innerHTML = accountInfo.accNumber;
      document.querySelector('#consName2').innerHTML = accountInfo.name;
      document.querySelector('#address2').innerHTML = accountInfo.address;
      document.querySelector('#date2').innerHTML = date[0] + "<br>" + cTime;
      var dataNB = JSON.parse(localStorage.getItem('consumer'));
      var output ="";
      var x = 0;
      /*------------------------------------------*/

      /*------------------------------------------*/

      for(let z in dataNB.NB){
        x + 1;
        x++;
      }
      if(x == 4){
      for(let i in dataNB.NB){
        var amountMR = dataNB.NB[i].mr_amount;
        var datereg = dataNB.NB[i].mr_date_reg.split("-");
        var datereg = datereg[0]+datereg[1];
        
        output += "<tr><td>" + datereg + "</td>" +
                  "<td style='text-align: center'>"+ amountMR  +"</td>" +
                  "<tr>";
      }
      output += "<tr><td>"+" "+ "</td></tr>";
      document.querySelector('#monthsTable').innerHTML = output;
      document.querySelector('#monthsTable2').innerHTML = output;
    }
    else if(x == 3){
      for(let i in dataNB.NB){
        var amountMR = dataNB.NB[i].mr_amount;
        var datereg = dataNB.NB[i].mr_date_reg.split("-");
        var datereg = datereg[0]+datereg[1];
        
        output += "<tr><td  style='width:70%;'>" + datereg + "</td>" +
                  "<td style='text-align: center'>"+ amountMR  +"</td>" +
                  "<tr>";
      }
      output += "<tr style='height:15px'><td>"+" "+"</td>"+"<td>"+" "+"</td></tr>";
      output += "<tr><td>"+" "+ "</td></tr>";
      document.querySelector('#monthsTable').innerHTML = output;
      document.querySelector('#monthsTable2').innerHTML = output;
    }
    else if(x == 2){
      for(let i in dataNB.NB){
        var amountMR = dataNB.NB[i].mr_amount;
        var datereg = dataNB.NB[i].mr_date_reg.split("-");
        var datereg = datereg[0]+datereg[1];
        
        output += "<tr><td>" + datereg + "</td>" +
                  "<td style='text-align: center'>"+ amountMR  +"</td>" +
                  "<tr>";
      }
      output += "<tr style='height:15px'><td>"+" "+"</td>"+"<td>"+" "+"</td></tr>";
      output += "<tr style='height:15px'><td>"+" "+"</td>"+"<td>"+" "+"</td></tr>";
      output += "<tr><td>"+" "+ "</td></tr>";
      document.querySelector('#monthsTable').innerHTML = output;
      document.querySelector('#monthsTable2').innerHTML = output;
    }
    else if(x == 1){
      for(let i in dataNB.NB){
        var amountMR = dataPB.NB[i].mr_amount;
        var datereg = dataPB.NB[i].mr_date_reg.split("-");
        var datereg = datereg[0]+datereg[1];
        
        output += "<tr><td>" + datereg + "</td>" +
                  "<td style='text-align: center'>"+ amountMR  +"</td>" +
                  "<tr>";
      }
      output += "<tr style='height:15px'><td>"+" "+"</td>"+"<td>"+" "+"</td></tr>";
      output += "<tr style='height:15px'><td>"+" "+"</td>"+"<td>"+" "+"</td></tr>";
      output += "<tr style='height:15px'><td>"+" "+"</td>"+"<td>"+" "+"</td></tr>";
      output += "<tr><td>"+" "+ "</td></tr>";
      document.querySelector('#monthsTable').innerHTML = output;
      document.querySelector('#monthsTable2').innerHTML = output;
    }
    window.print(); 
  }
</script>