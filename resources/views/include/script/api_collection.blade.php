<script>
    var auth = "{{Auth::user()->user_full_name}}";
    /*----- Public Variable -----*/
    var vonsumer = new Object();
    var amount = new Object();
    var amountNB = new Object();
    var amountCheque = new Object();
    var datasendED = new Object();
    var ewallCredit = new Object();
    var pb = new Object();
    var totalPbNb = 0;
    var acID;
    var store = [];
    var tempStore = [];
    var el;
    var ewalletamount;
    var cheque = new Object();
    var accounts = new Object();
    var accName2;
    var ac2;
    var tellid2;
    var change1;
    var csh;
    var check;
    var nbtotalamount;
    var tor = new Object();
    var tor1;
    var chqdeposit = 0;
    var consumer;
    var accNo;
    var forDisplay;
    var amt;
    var ctid;
    var newCsh = 0; /* 01-19-22 */
    /*------end of public variable ----*/
    var today = new Date();
    var f = "";
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    today = dd + '/' + mm + '/' + yyyy;
    if(document.querySelector('#accNoID').value == ''){
        if(document.querySelector('.collectionV1') != null){
            document.querySelector('.collectionV1').disabled = true;
        }
        if(document.querySelector('.collectionV') != null){
            document.querySelector('.collectionV').disabled = true;
        }
        document.querySelector('.chequeDisabled').disabled=true;
        document.querySelector('#ewallPayDeposit').disabled = true;
        document.querySelector('#change').disabled = true;
    }  
    if(document.querySelector('#aP').value == '' || document.querySelector('#aP').value == '0.00' ){
        var t = document.querySelector('#ewalletTo');
        var eLet = document.querySelector('#eLet');
        var enabled = document.querySelector('#cash');
        t.disabled=true;
        eLet.disabled=true;
        enabled.disabled=true;
        document.querySelector('.chequeDisabled').disabled=true;
    }
        
        
    /*------ Cash Pay ------ */
    function aaa() {
        var s = document.querySelector('#aaaa');
        var unbBoxes = document.querySelectorAll('.disabledlang');
        for(let i in unbBoxes){
            unbBoxes[i].checked=false;    
        }
        document.querySelector('#aP').value='';
        document.querySelector('#aP').placeholder='0.00';
        document.querySelector('#change').value='';
        document.querySelector('#change').placeholder='0.00';
        document.querySelector('#cash').value='';
        document.querySelector('#cash').placeholder='0.00';
        document.querySelector('#eLet').checked = false;
        var toC = document.getElementById("ewalletTo");
        toC.checked = false;
        toC.disabled = true;
        // document.querySelector('#eAmount').innerHTML = '';
        document.querySelector('#ewalletPay').value = '';
        document.querySelector('#ewalletPay').placeholder = '0.00';
        var output = " ";
        if (s.checked == true) {
            output += '<button  class="collectionV1 form-control" onclick="data_save()">Save</button>';
            document.querySelector('#save').innerHTML = output;
            if(document.querySelector('#accNoID').value == ''){
                document.querySelector('.collectionV1').disabled = true;
            }
            else{
                document.querySelector('.collectionV1').disabled = false;
            }
        } else {
            output += '<button   class="collectionV form-control" onclick="data_send()">PrintOR</button>';
            document.querySelector('#save').innerHTML = output;
            if(document.querySelector('#accNoID').value == ''){
                document.querySelector('.collectionV').disabled = true;
            }
            else{
                document.querySelector('.collectionV').disabled = false;
            }
        }
    }
    
    function cashInput() {
        ewalletamount;
        delete accounts.change;
        delete amount.E_Wallet; 
        delete amount.Cash_Amount;
        amount.getChange = 'no';
        document.querySelector('#ewalletPay').value='';
        document.querySelector('#change').disabled = false;
        var ewalletB = document.querySelector('#e-walletB');
        // document.querySelector('#e-walletB').value = ewalletamount.toFixed(2);
        if(ewalletamount == 0 || ewalletamount > 0){
                document.querySelector('#e-walletCre').style.display = "none";
                document.querySelector('#e-walletCredit').style.display="none";
                document.querySelector('#e-walletB').value = ewalletamount.toLocaleString("en-US", { minimumFractionDigits: 2 });
                document.querySelector('#e-walletCredit').value = '0.00';
                }
        else if(ewalletamount < 0){
            document.querySelector('#e-walletCre').style.display = "block";
            document.querySelector('#e-walletCredit').style.display="block";
            document.querySelector('#e-walletB').value = '0.00';
            document.querySelector('#e-walletCredit').value = ewalletamount.toLocaleString("en-US", { minimumFractionDigits: 2 });
        }
        document.querySelector('.chequeDisabled').disabled=false;
        document.querySelector('#eLet').checked=false;
        if(document.querySelector('.collectionV') != null){
        document.querySelector('.collectionV').disabled = false;
        }
        if(document.querySelector('.collectionV1') != null){
        document.querySelector('.collectionV1').disabled = false;
        }
        document.querySelector('#ewalletTo').checked=false;
        document.querySelector('#change').value = '';
        document.querySelector('#change').placeholder = '0.00';
        if(typeof document.querySelector('#ewalletPay') != 'undefined'){
            delete amount.E_Wallet;
            // document.querySelector('#eAmount').innerHTML = " ";
            document.querySelector('#ewalletPay').value = '';
            document.querySelector('#ewalletPay').placeholder = '0.00';
        }
        var today = new Date();
        accounts.date = today;
        var cash = document.querySelector('#cash');
        if(cash.value == '' || cash.value == 0){
            
            delete accounts.change;
            delete amount.E_Wallet; 
            cash.value = '';
            cash.placeholder='0.00';
            
            if(document.querySelector('.collectionV') != null){
            document.querySelector('.collectionV').disabled = true;
            }
            if(document.querySelector('.collectionV1') != null){
            document.querySelector('.collectionV1').disabled = true;
            }
            document.querySelector("#ewalletTo").disabled = false;
            
            // document.querySelector('#ewalletPay').value = '';
            // document.querySelector('#ewalletPay').placeholder = '0.00';
        }
        else if(parseFloat(cash.value) > 0){
        document.querySelector('#cash').style.border='2px solid gray';
        var aP = document.querySelector('#aP');
        var cashVal = parseFloat(cash.value);
        var val = parseFloat(cashVal);
        csh = val;
        var eLet = document.querySelector('#eLet');
        document.querySelector('#cash').value = val.toFixed(2);
        if(amt != ''){
            console.log('1A');
        amount.Amount_TB_Paid = parseFloat(amt);
        }
        else{
            console.log('2A');
        amount.Amount_TB_Paid = parseFloat(aP.value);  
        }
        if (val != 0) {
            amount.Cash_Amount = val;
        }
        var change = parseFloat(val) - parseFloat(aP.value);
        if (val < aP.value) {
            var x = 0;
           
            var ewallhide = document.querySelector('#toHide');
            var t = document.querySelector('#ewalletTo');
            ewallhide.style.visibility = "visible"; // maintenance
            document.querySelector('#change').value = '';
            document.querySelector('#change').placeholder = '0.00';
                eLet.disabled = true;
            var ewalltoadd = aP.value - csh;
            if (parseFloat(ewalltoadd) > parseFloat(ewalletamount) || val == aP.value){  
                delete amount.E_Wallet;
                // document.querySelector('#eAmount').innerHTML = " ";
                if(ctid != 3){
                document.querySelector('#ewalletPay').value = '';
                document.querySelector('#ewalletPay').placeholder = '0.00';
                 t.disabled = true;
                }
                else if(ctid == 3){
                    t.disabled = false;
                }
            }
            else{
                t.disabled = false;
            }
            
            
        } else {
            if(val == aP.value){
                var ewallhide = document.querySelector('#toHide');
                var t = document.querySelector('#ewalletTo');
                t.disabled = true;
                document.querySelector('#change').value = change.toFixed(2);
                var eLet = document.querySelector('#eLet');
                eLet.disabled = true;
                
            }
            else if(val > aP.value){
                var ewallhide = document.querySelector('#toHide');
                var t = document.querySelector('#ewalletTo');
                t.disabled = true;
                document.querySelector('#change').value = change.toFixed(2);
                var eLet = document.querySelector('#eLet');
                eLet.disabled = false;
            }
            else{
                amount.getChange = "no";
                var ewallhide = document.querySelector('#toHide');
                var t = document.querySelector('#ewalletTo');
                t.disabled = false;
                document.querySelector('#change').value = change.toFixed(2);
                
                var eLet = document.querySelector('#eLet');
    
                eLet.disabled = false;
                accounts.change = change;
       
            }
        }}
        /* new changes */
        else{
            if(document.querySelector('.collectionV1') != null){
            document.querySelector('.collectionV1').disabled = true;
            }
            if(document.querySelector('.collectionV') != null){
                document.querySelector('.collectionV').disabled = true;
            }
            document.getElementById('cash').value = '';
            document.querySelector('#cash').placeholder = '0.00';
            document.querySelector('#cash').style.border='1px solid red';
            var eLet = document.querySelector('#eLet');
            eLet.disabled = true;
            document.querySelector('#change').value = '';
            document.querySelector('#change').placeholder = '0.00';
           
        }
    }
    /*----------- end of cash pay --------- */
    /* 01-19-22 */
    function changeCh(){
    
        document.querySelector('#eLet').checked = false; 
        delete accounts.change;
    
        var chhh = document.querySelector('#change').value;
        var cash = document.querySelector('#cash').value;   
    
        var compareVal;
        if(amt != ''){
            compareVal = forDisplay;
    
        }
        else{
            compareVal = amount.Amount_TB_Paid;
            
        }
        if(parseFloat(amount.Cash_Amount) <= parseFloat(compareVal)){
            
            Swal.fire({
                title: 'Info',
                text: 'Invalid Entry, kindly Re-input Cash Amount',
                icon: 'info',
                confirmButtonText: 'close'
            })
            document.querySelector('#cash').value = '';
            document.querySelector('#cash').placeholder = '0.00';
            document.querySelector('#change').value = '';
            document.querySelector('#change').placeholder='0.00';
            if(document.querySelector('.collectionV1') != null){
            document.querySelector('.collectionV1').disabled = true;
            }
            if(document.querySelector('.collectionV') != null){
                document.querySelector('.collectionV').disabled = true;
            }
            document.querySelector('#change').disabled = true;
            document.querySelector('#eLet').disabled = true;
            delete amount.getChange;  
        }
        else if(chhh <= 0 ){
    
            Swal.fire({
                title: 'Info',
                text: 'Invalid Entry, kindly Re-input Cash Amount',
                icon: 'info',
                confirmButtonText: 'close'
            }) 
            document.querySelector('#cash').value = '';
            document.querySelector('#cash').placeholder = '0.00';
            document.querySelector('#change').value = '';
            document.querySelector('#change').placeholder='0.00';
            if(document.querySelector('.collectionV1') != null){
            document.querySelector('.collectionV1').disabled = true;
            }
            if(document.querySelector('.collectionV') != null){
                document.querySelector('.collectionV').disabled = true;
            }
            document.querySelector('#change').disabled = true;
            document.querySelector('#eLet').disabled = true;
            delete amount.getChange;
        }  
        else if(chhh != ''){
        document.querySelector('#change').value = parseFloat(chhh).toFixed(2);
        newchange = amount.Cash_Amount - parseFloat(chhh);
        var newnewC = parseFloat(newchange) - parseFloat(compareVal);
        var newcash = amount.Cash_Amount - newnewC;
        newCsh = newcash;
        newnewC = newnewC.toFixed(2)
        Swal.fire({
                title: 'Info',
                text: 'Remaining Change:' + ' ' + newnewC,
                icon: 'info',
                confirmButtonText: 'close'
            }) 
        document.querySelector('#cash').value = parseFloat(newcash).toFixed(2);
        amount.Cash_Amount = parseFloat(newcash);
            if(document.querySelector('#eLet').checked == false){
                if(document.querySelector('.collectionV1') != null){
                document.querySelector('.collectionV1').disabled = true;
                }
                if(document.querySelector('.collectionV') != null){
                    document.querySelector('.collectionV').disabled = true;
                }
                accounts.change = parseFloat(chhh);
    
            }
        }
    }
    /* end 01-19-22 */
    /*----------checked ewallet payment --------------*/
    function ewall() {
        if(document.querySelector('.collectionV1') != null){
            document.querySelector('.collectionV1').disabled = false;
        }
        if(document.querySelector('.collectionV') != null){
            document.querySelector('.collectionV').disabled = false;
        }
        document.querySelector('.chequeDisabled').disabled=false;
        var t = document.querySelector('#ewalletTo');
        var aP = document.querySelector('#aP')
        var eBalance = document.querySelector('#e-walletB');
        document.querySelector('#eLet').disabled=true;
        delete accounts.change;
        var change = document.querySelector('#change');
        change.value = '';
        change.placeholder = '';
        change.disabled = true;
        var cash = document.querySelector('#cash');
        if(ctid != 3){
        if (t.checked == true) {
    
            output = " ";
            if(cash.value == '' || cash.value == 0){
                csh = 0;
            }
            if(typeof csh == 'undefined'){
                csh = 0;
            }
            var ewalltoadd = parseFloat(aP.value) - csh;
            amount.E_Wallet = parseFloat(ewalltoadd);
            if(amt != ''){
                
                amount.Amount_TB_Paid = parseFloat(amt);
            }
            else{
                
            amount.Amount_TB_Paid = parseFloat(aP.value);
            }
            var totalewallbalance = parseFloat(ewalletamount) - parseFloat(ewalltoadd);
            if (parseFloat(ewalltoadd) < parseFloat(ewalletamount) || parseFloat(ewalltoadd) == parseFloat(ewalletamount)) {
                t.disabled = false;
                document.querySelector('#ewalletPay').value = ewalltoadd.toLocaleString("en-US", { minimumFractionDigits: 2 });
                // output += '<input id="ewalletPay" type="text" value="' + ewalltoadd.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '" readonly>';
                document.querySelector('#e-walletB').value = totalewallbalance.toLocaleString("en-US", { minimumFractionDigits: 2 });
                // document.querySelector('#eAmount').innerHTML = output;
            } else {
                Swal.fire({
                title: 'Error',
                text: '"Not Enough E-wallet Balance"',
                icon: 'error',
                confirmButtonText: 'close'
            })  
                var t = document.querySelector('#ewalletTo');
                t.checked = false;
                document.querySelector('#e-walletB').value = ewalletamount.toLocaleString('en-US', { minimumFractionDigits: 2 });
            }
        } else {
            delete amount.E_Wallet;
            // document.querySelector('#eAmount').innerHTML = " ";
            document.querySelector('#ewalletPay').value = '';
            document.querySelector('#ewalletPay').placeholder = '0.00';
            document.querySelector('#e-walletB').value = ewalletamount.toLocaleString('en-US', { minimumFractionDigits: 2 });
            var eLet = document.querySelector('#eLet');
            eLet.disabled=true;
        }
        }
        else if(ctid == 3){
            if (t.checked == true) {
           
            output = " ";
            if(cash.value == '' || cash.value == 0){
                csh = 0;
            }
            if(typeof csh == 'undefined'){
                csh = 0;
            }
            var ewalltoadd = parseFloat(aP.value) - csh;
            if(amt != ''){
                
                amount.Amount_TB_Paid = parseFloat(amt);
            }
            else{
              
            amount.Amount_TB_Paid = parseFloat(aP.value);
            }
            var totalewallbalance = parseFloat(ewalletamount) - parseFloat(ewalltoadd);
    
            if(t.checked == true){
                if(parseFloat(amount.Amount_TB_Paid) > (parseFloat(csh) + parseFloat(ewalletamount))){
                    delete amount.E_Wallet;
                    amount.E_Wallet = parseFloat(ewalletamount);
                 
                    document.querySelector('#ewalletPay').value = parseFloat(ewalletamount).toFixed(2);
                    document.querySelector('#e-walletB').value = '';
                    document.querySelector('#e-walletB').placeholder = '0.00';
                }
                else if(parseFloat(amount.Amount_TB_Paid) < (parseFloat(csh) + parseFloat(ewalletamount))){
                  
                    delete amount.E_Wallet;
                    document.querySelector('#ewalletPay').value = ewalltoadd.toLocaleString("en-US", { minimumFractionDigits: 2 });
                    document.querySelector('#e-walletB').value = totalewallbalance.toLocaleString("en-US", { minimumFractionDigits: 2 });
                    amount.E_Wallet = parseFloat(ewalltoadd);
                }
            }
           
            }
            else{
                delete amount.E_Wallet;
               
                document.querySelector('#ewalletPay').value = '';
                document.querySelector('#ewalletPay').placeholder = '0.00';
                document.querySelector('#e-walletB').value = parseFloat(ewalletamount).toFixed(2);
            }
        } 
    }
    
    function eLet11(){
        /* 01-19-22 */
        if(document.querySelector('.collectionV1') != null){
            document.querySelector('.collectionV1').disabled = false;
        }
        if(document.querySelector('.collectionV') != null){
            document.querySelector('.collectionV').disabled = false;
        }
        /* end 01-19-22 */
        var eLet = document.querySelector('#eLet');
        if (eLet.checked) {
            el = eLet.value = "yes";
            amount.getChange = el;
        } else {
            el = eLet.value = "no";
            amount.getChange = el;
        }
                        
    }
    /*------------ endofchecked ewallet payment -----------------*/
    
    /* ------------------------- Consumers Account ------------------ */
    var xhr = new XMLHttpRequest();
    
    var consAccount = "{{route('select.consumer.account')}}";
    xhr.open('GET', consAccount, true);
    xhr.onload = function() {
        if (this.status == 200) {
            var data = JSON.parse(this.responseText);
            var output = " ";
            var output1 = " ";
            var val = data.data;
            var val3 = data.last_page;
            var val2 = data.current_page;
    
            output += '<table style="text-align:left;width:100%;" border=1 class="modal-table" id="table1">';
            output += '<tr>' + '<th style="background-color: #5B9BD5;color: white;">' + 'Account #' + '</th>' + '<th style="background-color: #5B9BD5;color: white;">' + 'Consumer' + '</th></tr>';
            for (var i in val) {
                accName = val[i].cm_account_no;
                var acc = val[i].cm_id;
                output += '<tr>' + '<td name = "'+val[i].cm_full_name+'" id = "' + val[i].cm_id + '" onclick="tdclick(this,' + acc + ')" >' + val[i].cm_account_no + '</td>';
                output += '<td>' + val[i].cm_full_name + '</td>' + '</tr>';
            }
            var b = val2 + 1;
    
            if (val2 <= 1) {
                output1 += '<tr>';
                output1 += '<td>' + '<button id = "btn" class="btn btn-primary" onclick="s(this.value)" value=' + val2 + ' hidden>' + 'Previous' + '</button>';
            } else {
                output1 += '<td>' + '<button id = "btn" class="btn btn-primary" onclick="s(this.value)" value=' + val2 + '>' + 'Previous' + '</button>';
            }
            output1 += '<input style="border:0;width:10%;" type="text" class="currentpage" value="' + val2 + '" >';
            if (val2 < val3) {
                output1 += '<button id = "btn" class="btn btn-primary" onclick="s(this.value)" value=' + b + '  >' + 'Next' + '</button>';
            } else {
                output1 += '<button id = "btn" class="btn btn-primary" onclick="s(this.value)" value=' + b + ' hidden>' + 'Next' + '</button>';
            }
            output += '</td>' + '</tr>' + '</table>';
            document.querySelector('.modaldiv2').innerHTML = output;
            document.querySelector('.pages2').innerHTML = output1;
        }
    }
    xhr.send();
    /* ------------------------- End Consumers Account  ------------------ */
    /*-------------------------- Modal ----------------------------------*/
    
    function cPayment() {
        delete accounts.change;
        localStorage.removeItem('chequeDeposit');
        var change = document.querySelector('#change');
        change.value = '';
        change.placeholder = '0.00';
        var cash = document.querySelector('#cash');
        cash.value = '';
        cash.placeholder = '0.00';
        if(document.querySelector('.collectionV1') != null){
            document.querySelector('.collectionV1').disabled = true;
        }
        if(document.querySelector('.collectionV') != null){
            document.querySelector('.collectionV').disabled = true;
        }
        modalD = document.querySelectorAll(".modal");
        modalD[1].style.display = "block";
    }
    
    function cpaymentClose() {
        modalD = document.querySelectorAll(".modal");
        modalD[1].style.display = "none";
    }
    
    
    function vCollection() {
        document.querySelector('#voidCollection').style.display="block";
        // document.querySelector('#disableto').disabled = true;
        var tellid = document.querySelector('#tellid').innerHTML;
        var xhr = new XMLHttpRequest();
        var collTransShow = "{{route('show.collection.transaction.teller',':tellerid')}}";
        var collTransShow = collTransShow.replace(':tellerid', tellid);
        xhr.open('GET', collTransShow, true);
        xhr.onload = function() {
            if (this.status == 200) {
                var data = JSON.parse(this.responseText);
                var output = "";
                var j = 0;
                for (let i in data.OR_Details) {
                    j++;
                    var ac = data.OR_Details[i].OR_Number;
                    output += '<tr>' +
                        '<td>' + (j) + '</td>' +
                        '<td>' + data.OR_Details[i].Account_Number + '</td>' +
                        '<td>' + data.OR_Details[i].OR_Number + '</td>' +
                        '<td>' + data.OR_Details[i].Payee + '</td>' +
                        '<td>' + data.OR_Details[i].Total_Amount + '</td>' +
                        '<td><button style = "border:1px solid black;width:70px;color:red;" id = "btn" onclick="voidThis(' + tellid + ',' + ac + ')">VOID</button></td>' +
                        '</tr>';
                }
                
            }
            else if(this.status == 422){
                output = "";
            }
            document.querySelector('#voidDataCol').innerHTML = output;
        }
        xhr.send();
    }
    
    function vCollectionClose() {
        document.querySelector('#voidCollection').style.display="none";
    }
    
    function voidThis(tellid, ac) {
        modalD = document.querySelectorAll(".modal");
        modalD[6].style.display = "none";
        modalD[7].style.display = "block";
        ac2 = ac;
        tellid2 = tellid;
        vCollection();
    }
    
    // function textAre() {
    //     var textAreaData = document.querySelector('#textar').value;
    //     if(textAreaData != ''){
    //         document.querySelector('#disableto').disabled = false;
    //     }
    //     else{
    //         document.querySelector('#disableto').disabled = true;
    //     }  
    // }
    /* 1/25/2022 */
    function voidedData(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Do you want to continue?',
            showDenyButton: true,
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
                var textAreaData = document.querySelector('#textar').value;
            if(typeof vonsumer.cm_id != "undefined" && typeof accName2 != "undefined" ){
                var xhr = new XMLHttpRequest();
                var voidOR = "{{route('void.collection.transaction.or',['or'=>':or','teller'=>':teller','remark'=> ':remark'])}}";
                var updateOR = voidOR.replace(':or',ac2);
                var updateOR1 = updateOR.replace(':teller',tellid2);
                var updateOR2 = updateOR1.replace(':remark',textAreaData);
                xhr.open('DELETE', updateOR2, true);
                xhr.onload = function() {
                    if (this.status == 200) {
    
                    }
                }
                xhr.send();
    
                var accID = vonsumer.cm_id;
                Swal.fire({
                    title: 'Success!',
                    text: '"OR has been Voided"',
                    icon: 'success',
                    confirmButtonText: 'close'
                })
                modalD = document.querySelectorAll(".modal");
                modalD[6].style.display = "none";
                modalD[7].style.display = "none";
                accName = accName2;
                document.querySelector('#textar').setAttribute('placeholder','...');
                var aP = document.querySelector('#aP');
                aP.value = '';
                aP.placeholder = '0.00';
                consumer = accName2;
                setConsAcct(consumer);
                torModal();
                }
            else{
                var xhr = new XMLHttpRequest();
                var voidOR = "{{route('void.collection.transaction.or',['or'=>':or','teller'=>':teller','remark'=> ':remark'])}}";
                var updateOR = voidOR.replace(':or',ac2);
                var updateOR1 = updateOR.replace(':teller',tellid2);
                var updateOR2 = updateOR1.replace(':remark',textAreaData);
                xhr.open('DELETE', updateOR2, true);
                xhr.onload = function() {
                    if (this.status == 200) {
    
                    }
                }
                xhr.send();
                Swal.fire({
                    title: 'Success!',
                    text: '"OR has been Voided"',
                    icon: 'success',
                    confirmButtonText: 'close'
                })
                modalD = document.querySelectorAll(".modal");
                modalD[6].style.display = "none";
                modalD[7].style.display = "none";
                document.querySelector('#textar').setAttribute('placeholder','...');
                var aP = document.querySelector('#aP');
                aP.value = '';
                aP.placeholder = '0.00';
                torModal();
                }    
            }
        })
        return false; 
        
    }
    /* end of 1/25/2022 */
    function voidRemarksClose() {
        document.querySelector('#textar').value = '';
        modalD = document.querySelectorAll(".modal");
        modalD[6].style.display = "block";
        modalD[7].style.display = "none";
    }
    
    /*-------------------------- Modal ----------------------------------*/
    
    /* -----------------------Pagination for account numbers-------------------------------------------- */
    // function s(p) {
    //     document.querySelector('#dearch').value = '';
    //     var xhr = new XMLHttpRequest();
    //     var searchAccount = "{{route('select.consumer.account','?page=')}}" + p;
    //     xhr.open('GET', searchAccount, true);
    //     xhr.onload = function() {
    //         if (this.status == 200) {
    //             var data = JSON.parse(this.responseText);
    //             var output = " ";
    //             var output1 = " ";
    //             var val = data.data;
    //             var val3 = data.last_page;
    //             var val2 = data.current_page;
    //             output += '<table style="text-align:left;width:100%;" border=1 class="modal-table" id="table1">';
    //             output += '<tr>' + '<th style="background-color: #5B9BD5;color: white;">' + 'Account #' + '</th>' + '<th style="background-color: #5B9BD5;color: white;">' + 'Consumer' + '</th></tr>';
    //             for (var i in val) {
    //                 var acc = val[i].cm_id;
    //                 output += '<tr>' + '<td name = "'+val[i].cm_full_name+'" id = "' + val[i].cm_id + '" onclick="tdclick(this,' + acc + ')" >' + val[i].cm_account_no + '</td>';
    //                 output += '<td>' + val[i].cm_full_name + '</tr>';
    //             }
    //             var b = val2 + 1;
    //             var c = val2 - 1;
    //             if (val2 <= 1) {
    //                 output1 += '<tr>';
    //                 output1 += '<td>' + '<button id = "btn" class="btn btn-primary" onclick="setTimeout(s(this.value),3000)" value=' + c + ' hidden>' + 'Previous' + '</button>';
    //             } else {
    //                 output1 += '<td>' + '<button id = "btn" class="btn btn-primary" onclick="setTimeout(s(this.value),3000)" value=' + c + '>' + 'Previous' + '</button>';
    //             }
    //             output1 += '<input style="border:0;width:10%;" type="text" class="currentpage" value="' + val2 + '" >';
    //             if (val2 >= val3) {
    //                 output1 += '<button id = "btn" class="btn btn-primary" onclick="setTimeout(s(this.value),3000)" value=' + b + '  hidden>' + 'Next' + '</button>';
    //             } else {
    //                 output1 += '<button id = "btn" class="btn btn-primary" onclick="setTimeout(s(this.value),3000)" value=' + b + ' >' + 'Next' + '</button>';
    //             }
    //             output += '</td>' + '</tr>' + '</table>';
    
    //             document.querySelector('.modaldiv2').innerHTML = output;
    //             document.querySelector('.pages2').innerHTML = output1;
    //         }
    //         else if(this.status == 422){
    //             console.log(2);
    //         }
    //     }
    //     xhr.send();
    // }
    // /* ----------------- ENd Pagination of Account No. --------------------*/
    
    // /* ----------------- Search Account Name / Account Number ------------------- */
    // function tearch() {
    //     var input, filter;
    //     input = document.getElementById("dearch");
    //     filter = input.value.toUpperCase();
    //     if (filter.length == 0) {
    //         var xhr = new XMLHttpRequest();
    //         var consaAccount = "{{route('select.consumer.account')}}";
    //         xhr.open('GET', consaAccount, true);
    //         xhr.onload = function() {
    //             if (this.status == 200) {
    //                 var data = JSON.parse(this.responseText);
    //                 var output = " ";
    //                 var output1 = " ";
    //                 var val = data.data;
    //                 var val3 = data.last_page;
    //                 var val2 = data.current_page;
    //                 output += '<table style="text-align:left;width:100%;" class="modal-table" id="table1">';
    //                 output += '<tr>' + '<th style="background-color: #5B9BD5;color: white;">' + 'Account #' + '</th>' + '<th style="background-color: #5B9BD5;color: white;">' + 'Consumer' + '</th></tr>';
    //                 for (var i in val) {
    //                     var acc = val[i].cm_id;
    //                     output += '<tr>' + '<td name = "'+val[i].cm_full_name+'" id = "' + val[i].cm_id + '"  onclick="tdclick(this,' + acc + ')" >' + val[i].cm_account_no + '</td>';
    //                     output += '<td>' + val[i].cm_full_name + '</tr>';
    //                 }
    
    //                 var b = val2 + 1;
    
    //                 if (val2 <= 1) {
    //                     output1 += '<tr>';
    //                     output1 += '<td>' + '<button id = "btn" class="btn btn-primary" onclick="s(this.value)" value=' + val2 + ' hidden>' + 'Previous' + '</button>';
    //                 } else {
    //                     output1 += '<td>' + '<button id = "btn" class="btn btn-primary" onclick="s(this.value)" value=' + val2 + '>' + 'Previous' + '</button>';
    //                 }
    //                 output1 += '<input style="border:0;width:10%;" type="text" class="currentpage" value="' + val2 + '" >';
    //                 if (val2 < val3) {
    //                     output1 += '<button id = "btn" class="btn btn-primary" onclick="s(this.value)" value=' + b + '  >' + 'Next' + '</button>';
    //                 } else {
    //                     output1 += '<button id = "btn" class="btn btn-primary" onclick="s(this.value)" value=' + b + ' hidden>' + 'Next' + '</button>';
    //                 }
    //                 output += '</td>' + '</tr>' + '</table>';
    
    //             }
    //             document.querySelector('.modaldiv2').innerHTML = output;
    //             document.querySelector('.pages2').innerHTML = output1;
    //         }
    //         xhr.send();
    //     } else{
    //         var xhr = new XMLHttpRequest();
    //         var searchName = "{{route('search.consumer.name.account',['request'=>':req'])}}";
    //         searchName = searchName.replace(':req',filter)
    //         xhr.open('GET', searchName, true);
    //         xhr.onload = function() {
    //             if (this.status == 200) {
    //                 var data = JSON.parse(this.responseText);
    //                 var output = " ";
    //                 var val = data;
    //                 output += '<div style="overflow:scroll;height:270px;">';
    //                 output += '<table style="text-align:left;width:100%;height:20px;" class="modal-table" id="table1">';
    //                 output += '<tr>' + '<th style="background-color: #5B9BD5;color: white;">' + 'Account #' + '</th>' + '<th style="background-color: #5B9BD5;color: white;">' + 'Consumer' + '</th></tr>';
    //                 for (var i in val) {
    //                     var acc = val[i].cm_id;
    //                     output += '<tr>' + '<td name = "'+val[i].cm_full_name+'" id = "' + val[i].cm_id + '" onclick="tdclick(this,' + acc + ')" >' + val[i].cm_account_no + '</td>';
    //                     output += '<td>' + val[i].cm_full_name + '</tr>';
    //                 }
    //                 output += '</table></div>';
    //             }
    //             document.querySelector('.modaldiv2').innerHTML = output;
    //             document.querySelector('.pages2').innerHTML = '';
    
    //         }
    //         xhr.send();
    //     }
    // }
    /* -----End of Search Account Name and Account Number --------------*/
    
    
    function setConsAcct(consumer) {
        // if(accounts != 'undefined'){
        //     delete accounts.change;
        // }
        document.querySelector('#addTC').disabled = false;
        if(document.querySelector('.collectionV1') != null){
            document.querySelector('.collectionV1').disabled = true;
        }
        if(document.querySelector('.collectionV') != null){
            document.querySelector('.collectionV').disabled = true;
        }
        document.querySelector('#consAcct').style.display = "none";
        var s = document.querySelector('#aaaa');
        // document.querySelector('.collectionV1').disabled = false;
        // document.querySelector('.collectionV2').disabled = false;
        accName2 = consumer;
    
        var accName3 = consumer.childNodes[2].innerHTML;
        var aa;
        if(accName3 != ''){
            aa = accName3.split('<br>');
        }
        var taday = document.querySelector('.datePicker');
        if (taday) {
            f = taday.value = today;
        }
        var modalAccNo = document.querySelector('#accNo');
        modalAccNo.style.display = "none";
        var output = " ";
        var xhr = new XMLHttpRequest();
        var mrcollection = "{{route('show.collection.consumer',['id'=>':id'])}}";
        mrcollection = mrcollection.replace(':id',consumer.id);
        xhr.open('GET', mrcollection, true);
        xhr.onload = function() {
            if (this.status == 200) {
                var accName = consumer.childNodes[0].innerHTML;
           
                if(aa[0] == ' '){
           
                    accName3 = 'N/A';
                    document.querySelector('#accNoID').value = accName + '/ ' + accName3;
                }
                else{
                    accName3 = accName3;
                    document.querySelector('#accNoID').value = accName + '/ ' + aa[0];
                }
                document.querySelector('#ewallPayDeposit').disabled = false;
                var data = JSON.parse(this.responseText);
                var dataconsNotify = data.Cons_Notification;
                var notifyremarks = "";
                if(dataconsNotify.length > 0){
                    for(let i in dataconsNotify){
                        notifyremarks += '<tr><td>' + '-' + ' ' + dataconsNotify[i].Notify + '</td></tr>';
                    }
                    document.querySelector(".notifySyt").style.backgroundColor = "white";
                    document.querySelector(".notifySyt").style.color = "black";
                    document.querySelector(".notifySyt").style.border = "2px solid red";
                    document.querySelector(".labelR").style.visibility = "visible";
                    document.querySelector(".notifyI").innerHTML = notifyremarks;
                    document.querySelector(".labelR").style.removeProperty("display");
                    // document.querySelector('#notify').style.display="block";
                    // document.querySelector('#notify').style.backgroundColor = "red";
                    // document.querySelector('#notify').innerHTML = notifyremarks;
                }
                else if(dataconsNotify.length == 0){
                    document.querySelector(".notifySyt").style.removeProperty("background-color");
                    document.querySelector(".notifySyt").style.removeProperty("color");
                    document.querySelector(".notifySyt").style.removeProperty("border");
                    document.querySelector(".labelR").style.display="none";
                    document.querySelector(".notifyI").innerHTML = '';
                    
                }
    
                var consumerDetails = data.Consumer_Details;
                if(consumerDetails.cm_con_status == 1){
                    consumerDetails.cm_con_status = 'Active';
                }
                else{
                    consumerDetails.cm_con_status = 'Disconnected';
                }
                var ct_id = data.Consumer_Details.ct_id;
                ctid = ct_id;
                var ewid = data.Consumer_Details.ew_id;
                var ewTamount = data.Consumer_Details.ew_total_amount;
                var arrears = data.Total_Arrears;
                var ewalletA = consumerDetails.ew_total_amount;
                ewalletamount = consumerDetails.ew_total_amount;
                var billsToPay = data.Bills_to_Pay;
                var address = document.querySelector('.address');
                address = address.value = consumerDetails.cm_address;
                var status = document.querySelector('.status');
                status = status.value = consumerDetails.cm_con_status;
                var mn = document.querySelector('#MN');
                mn = mn.value = consumerDetails.mm_serial_no;
                var typeC = document.querySelector('.TypeC');
                typeC = typeC.value = consumerDetails.ct_desc;
                var totalArrears = document.querySelector('#totalArrears');
                totalArrears = totalArrears.value = arrears.toLocaleString("en-US", { minimumFractionDigits: 2 })
                var ewalletB = document.querySelector('#e-walletB');
                if(ewalletA == 0 || ewalletA > 0){
                document.querySelector('#e-walletCre').style.display = "none";
                document.querySelector('#e-walletCredit').style.display="none";
                ewalletB = ewalletB.value = ewalletA.toLocaleString("en-US", { minimumFractionDigits: 2 });
                document.querySelector('#e-walletCredit').value = '0.00';
                }
                else if(ewalletA < 0){
                    document.querySelector('#e-walletCre').style.display = "block";
                    document.querySelector('#e-walletCredit').style.display="block";
                    ewalletB = ewalletB.value = '0.00';
                    document.querySelector('#e-walletCredit').value = ewalletA.toLocaleString("en-US", { minimumFractionDigits: 2 });
                }
                var tellid = document.querySelector('#tellid').innerHTML;
                vonsumer.user_id = tellid;
                vonsumer.cm_id = consumer.id;
                vonsumer.ct_id = ct_id;
                vonsumer.ew_id = ewid;
                vonsumer.ew_total_amount = ewTamount;
                accounts.name = consumerDetails.cm_full_name;
                accounts.address = address;
                accounts.accNumber = accName;
                var lgu2 = 0;
                var lgu5 = 0;
                if(ct_id != 3){
                for (let i in billsToPay) {
                    var amountDue = billsToPay[i].mr_amount;
                    var myr = billsToPay[i].mr_date_year_month.toString();
                    var d =['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];
                    var myr1 = myr.slice(0,4);
                    var myr2 = myr.slice(4);
    
                    if(billsToPay[i].cm_lgu2 == 1 && billsToPay[i].cm_lgu5 == 0){
                        lgu2 = (billsToPay[i].mr_amount/1.12)*0.02;
                        amountDue -= Math.round(lgu2*100)/100;
                    }else if(billsToPay[i].cm_lgu5 == 1 && billsToPay[i].cm_lgu2 == 0){
                        lgu5 = (billsToPay[i].mr_amount/1.12)*0.05;
                        amountDue -= Math.round(lgu5*100)/100;
                    }else if(billsToPay[i].cm_lgu2 == 1 && billsToPay[i].cm_lgu5 == 1){
                        lgu2 = (billsToPay[i].mr_amount/1.12)*0.02;
                        lgu5 = (billsToPay[i].mr_amount/1.12)*0.05;
                        var test = (lgu2+lgu5);
                        amountDue -= Math.round(test*100)/100;
                    }
                    
                    var v = billsToPay[i].mr_id + "^" + billsToPay[i].mr_bill_no + "^" + amountDue.toFixed(2) + "^" + billsToPay[i].mr_date_reg + "^" + billsToPay[i].mr_date_year_month + "$";
                    output += '<tr style="background-color:white;color:black;">';
                    output += '<td style = "cursor:pointer;" id = "' + billsToPay[i].mr_id + '" onclick="myTD(this.id);">' + billsToPay[i].type + '</td>';
                    output += '<td>' + d[parseInt(myr2)] +' '+ myr1 + '</td>';
                    output += '<td>' + billsToPay[i].mr_kwh_used + '</td>';
                    output += '<td>' + billsToPay[i].mr_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }); + '</td>';
                    output += '<td>' + ' ' + '</td>';
                    output += '<td>' + lgu2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td>' + lgu5.toLocaleString("en-US", { minimumFractionDigits: 2 }); + '</td>';
                    output += '<td style="font-family:italic;font-weight:bold;font-size:50px">' + ' ' + '</td>';
                    output += '<td style="font-family:italic;font-weight:bold;font-size:30px">' + ' ' + '</td>';
                    output += '<td style="font-family:italic;font-weight:bold;font-size:30px">' + ' ' + '</td>';
                    output += '<td style="font-family:italic;font-weight:bold;font-size:25px">' + amountDue.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td><input name = "amountToBePaid" value = "' + v + '" class="disabledlang" id ="' + amountDue.toFixed(2) + '" onchange="r();" type="checkbox"> </td></tr>';
                }
            }
            else{
                for (let i in billsToPay) {
                    var amountDue = billsToPay[i].mr_amount;
                    var myr = billsToPay[i].mr_date_year_month.toString();
                    var d =['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];
                    var myr1 = myr.slice(0,4);
                    var myr2 = myr.slice(4);
    
                    if(billsToPay[i].cm_lgu2 == 1 && billsToPay[i].cm_lgu5 == 0){
                        lgu2 = (billsToPay[i].mr_amount/1.12)*0.02;
                        amountDue -= Math.round(lgu2*100)/100;
                    }else if(billsToPay[i].cm_lgu5 == 1 && billsToPay[i].cm_lgu2 == 0){
                        lgu5 = (billsToPay[i].mr_amount/1.12)*0.05;
                        amountDue -= Math.round(lgu5*100)/100;
                    }else if(billsToPay[i].cm_lgu2 == 1 && billsToPay[i].cm_lgu5 == 1){
                        lgu2 = (billsToPay[i].mr_amount/1.12)*0.02;
                        lgu5 = (billsToPay[i].mr_amount/1.12)*0.05;
                        var test = (lgu2+lgu5);
                        amountDue -= Math.round(test*100)/100;
                    }
                    forDisplay = parseFloat(amountDue) - parseFloat(billsToPay[i].mr_partial)
                    var v = billsToPay[i].mr_id + "^" + billsToPay[i].mr_bill_no + "^" + amountDue.toFixed(2) + "^" + billsToPay[i].mr_date_reg + "^" + billsToPay[i].mr_date_year_month + "$";
                    output += '<tr style="background-color:white;color:black;">';
                    output += '<td style = "cursor:pointer;" id = "' + billsToPay[i].mr_id + '" onclick="myTD(this.id);">' + billsToPay[i].type + '</td>';
                    output += '<td>' + d[parseInt(myr2)] +' '+ myr1 + '</td>';
                    output += '<td>' + billsToPay[i].mr_kwh_used + '</td>';
                    output += '<td>' + billsToPay[i].mr_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td>' + ' ' + '</td>';
                    output += '<td>' + lgu2.toLocaleString("en-US", { minimumFractionDigits: 2 }); + '</td>';
                    output += '<td>' + lgu5.toLocaleString("en-US", { minimumFractionDigits: 2 }); + '</td>';
                    output += '<td style="font-family:italic;font-weight:bold;font-size:50px">' + ' ' + '</td>';
                    output += '<td style="font-family:italic;font-weight:bold;font-size:30px">' + ' ' + '</td>';
                    output += '<td style="font-family:italic;font-weight:bold;font-size:30px">' + billsToPay[i].mr_partial.toFixed(2) + '</td>';
                    output += '<td style="font-family:italic;font-weight:bold;font-size:25px">' + forDisplay.toLocaleString("en-US", { minimumFractionDigits: 2 }); + '</td>';
                    output += '<td><input name = "amountToBePaid" value = "' + v + '" class="disabledlang" id ="' + amountDue.toFixed(2) + '" onchange="bapaMopa();" type="checkbox"> </td></tr>';
                } 
            }
            } else if (this.status == 404) {
                alert('No Remaining Balance');
                location.reload();
            }
            else if(this.status == 422){
                var accName = consumer.childNodes[0].innerHTML;
                if(aa[0] == ' '){
                    accName3 = 'N/A';
                   
                    document.querySelector('#accNoID').value = accName + '/ ' + accName3;
                }
                else{
                    accName3 = accName3;
                    document.querySelector('#accNoID').value = accName + '/ ' + aa[0];
                }
                document.querySelector('#ewallPayDeposit').disabled=false;
                document.querySelector('#totalArrears').value='';
                document.querySelector('#totalArrears').placeholder='0.00';
                output += '<td colspan= 13 style="text-align:center;font-family:italic;color:red;font-weight:bold;font-size:20px">' + 'No Remaining Balance' + '</td>';
                var data = JSON.parse(this.responseText);
                var dataconsNotify = data.Cons_Notification;
                var notifyremarks = "";
                if(dataconsNotify.length > 0){
                    for(let i in dataconsNotify){
                        notifyremarks += '<tr><td>' + '-' + ' ' + dataconsNotify[i].Remarks + '</td></tr>';
                    }
                    document.querySelector(".notifySyt").style.backgroundColor = "white";
                    document.querySelector(".notifySyt").style.color = "black";
                    document.querySelector(".notifySyt").style.border = "2px solid red";
                    
                    document.querySelector(".labelR").style.visibility = "visible";
                    document.querySelector(".notifyI").innerHTML = notifyremarks;
                    document.querySelector(".labelR").style.removeProperty("display");
                    // document.querySelector('#notify').style.display="block";
                    // document.querySelector('#notify').style.backgroundColor = "red";
                    // console.log(notifyremarks);
                    // document.querySelector('#notify').innerHTML = notifyremarks;
                    
                }
                else if(dataconsNotify.length == 0){
                    console.log(2);
                    document.querySelector(".notifySyt").style.removeProperty("background-color");
                    document.querySelector(".notifySyt").style.removeProperty("color");
                    document.querySelector(".notifySyt").style.removeProperty("border");
                    document.querySelector(".labelR").style.display="none";
                    document.querySelector(".notifyI").innerHTML = "";
                }
    
                var consumerDetails = data.Consumer_Details;
                var ct_id = data.Consumer_Details.ct_id;
                var ewid = data.Consumer_Details.ew_id;
                var ewTamount = data.Consumer_Details.ew_total_amount;
                var ewalletA = consumerDetails.ew_total_amount;
                ewalletamount = consumerDetails.ew_total_amount;
                var billsToPay = data.Bills_to_Pay;
                var address = document.querySelector('.address');
                address = address.value = consumerDetails.cm_address;
                var status = document.querySelector('.status');
                status = status.value = consumerDetails.cm_con_status;
                var mn = document.querySelector('#MN');
                mn = mn.value = consumerDetails.mm_serial_no;
                var typeC = document.querySelector('.TypeC');
                typeC = typeC.value = consumerDetails.ct_desc;
                var ewalletB = document.querySelector('#e-walletB');
                if(ewalletA == 0 || ewalletA > 0){
                document.querySelector('#e-walletCre')
                ewalletB = ewalletB.value = ewalletA.toLocaleString("en-US", { minimumFractionDigits: 2 });
                document.querySelector('#e-walletCredit').value = '0.00';
                }
                else if(ewalletA < 0){
                    ewalletB = ewalletB.value = '0.00';
                    document.querySelector('#e-walletCredit').value = ewalletA.toLocaleString("en-US", { minimumFractionDigits: 2 });
                }
                var tellid = document.querySelector('#tellid').innerHTML;
                vonsumer.user_id = tellid;
                vonsumer.cm_id = consumer.id;
                vonsumer.ct_id = ct_id;
                vonsumer.ew_id = ewid;
                vonsumer.ew_total_amount = ewTamount;
                accounts.name = consumerDetails.cm_full_name;
                accounts.address = address;
                accounts.accNumber = accName;
            }
            document.querySelector('#dataCons').innerHTML = output;
        }
        xhr.send();
       
       setTimeout(function(){
        var xhr2 = new XMLHttpRequest();
        var fees = "{{route('index.fees')}}";
        xhr2.open('GET', fees, true);
        xhr2.onload = function() {
            var data1 = JSON.parse(this.responseText);
            var data2 = data1.data;
            var j = 0;
            output3 = "";
            output4 = "";
            for (z in data2) {
                j++;
                var nBill = data2[z].fees_id + "^" + data2[z].fees_amount + "^" + data2[z].fees_vatable + "^" + data2[z].fees_vatable + "^";
                nBill += data2[z].fees_desc;
                output3 += '<tr class= "itonatry" style="background-color:white;">' +
                        '<td style="color:black">' + j + '</td>' +
                        '<td style="color:black">' + data2[z].fees_code + '</td>' +
                        '<td style="color:black">' + data2[z].fees_desc + '</td>' +
    /* 01-19-2022 */   '<td style="color:black"><input type="number" class="'+nBill+'" onfocusout="changeNB(this)" value= "'+data2[z].fees_amount+'">' + '</td>' +
    /* 01-19-2022 */    '<td style="color:black">' + data2[z].fees_vatable + '</td>' +
                        '<td style="color:black" >' + '<input class = "disabledlang" id ="' + data2[z].fees_id + '" name = "nonBill" value="' + nBill + '" onchange="nb();" type="checkbox">' + '</td>' +
                        '</tr>';
            }
            document.querySelector('#dataNonBill').innerHTML = output3;
        }
        xhr2.send();
       },2000); 
    }
    /* 01-19-2022 */
    function changeNB(tt){
        var amountsss = tt.value;
           var asd= tt.getAttribute('class');
            var j = asd.split("^");
            j[1] = amountsss;
            var nonID = j[0];
            var nonData = document.getElementById(nonID);
            var newData = nonData.value;
            var dataSplit = newData.split("^");
            dataSplit[1] = parseFloat(amountsss);
            var finalTo = dataSplit[0] +'^'+ dataSplit[1] + '^' + dataSplit[2] + '^' + dataSplit[3] + '^' + dataSplit[4];
            document.getElementById(nonID).value = finalTo;
             nb();
    }
    function consLedger(){
        var cmid = vonsumer.cm_id;
        document.querySelector('#consLedger').style.display="block";
        var showCons = "{{route('show.consumer.ledger',['cmid'=>':id'])}}";
            showCons = showCons.replace(':id',cmid);
            xhr.open('GET', showCons, true);
            xhr.onload = function() {
                if (this.status == 200) {
                    var data = JSON.parse(this.responseText);
                    localStorage.setItem('data', JSON.stringify(data));
                    ewalletid = data.Consumer_Ewallet.ewallet_id;
                    var pbbills = data.PB_Details;
                    var output='';
                    output += '<table border=0 class="EMR-table" style="font-family:calibri;font-size:12px;color:black;height: 100px;">';
                    output += '<tr>' +
                              '<td class="thead">' +
                               'Account Number:' +
                               '</td>' +
                                '<td  class="input-td">' +
                                '<input type="text" value="'+data.Consumer_Details[0].Account_No+'" style="font-family:calibri;font-size:12px;color:black" readonly>' +
                              '</td>' +
                              '<td class="thead">' +
                               'Type:' +
                               '</td>' +
                                '<td  class="input-td">' +
                                '<input type="text" value="'+data.Consumer_Details[0].Consumer_Type+'" style="font-family:calibri;font-size:12px;color:black" readonly>' +
                              '</td>' +
                             '</tr>';
                    output += '<tr>' +
                              '<td class="thead">' +
                               'Name:' +
                               '</td>' +
                                '<td  class="input-td">' +
                                '<input type="text"  value="'+data.Consumer_Details[0].Account_Name+'" style="font-family:calibri;font-size:12px;color:black" readonly>' +
                              '</td>' +
                              '<td class="thead">' +
                               'Status:' +
                               '</td>' +
                                '<td  class="input-td">' +
                                '<input type="text" value="'+data.Consumer_Details[0].Status+'" style="font-family:calibri;font-size:12px;color:black" readonly>' +
                              '</td>' +
                             '</tr>';
                    output += '<tr>' +
                              '<td class="thead">' +
                               'Address:' +
                               '</td>' +
                                '<td  class="input-td">' +
                                '<input type="text" value="'+data.Consumer_Details[0].Address+'" style="font-family:calibri;font-size:12px;color:black" readonly>' +
                              '</td>' +
                              '<td class="thead">' +
                               'MN:' +
                               '</td>' +
                                '<td  class="input-td">' +
                                '<input type="text" value="'+data.Consumer_Details[0].Meter_Serial_No+'" style="font-family:calibri;font-size:12px;color:black" readonly>' +
                              '</td>' +
                             '</tr>' +
                             '</table>';
                    if(data.Cons_Notify.length > 0){
                        output += '<table>';
                        output += '<tr><td style="font-size:15px;color:red">Note:</td></tr>';
                        for(let x in data.Cons_Notify){
                            output += '<tr><td style="color:red">'+ data.Cons_Notify[x].Remarks +'</td></tr>';
                        }
                        output += '</table>';
                    }
                    output += '<div style="height:150px;overflow-y:scroll">';
                    output += '<table style="font-family:calibri;text-align:left;width:95%;" border=1 class="modal-table" id="table1">';
                    output += '<tr style="color:black">' +
                              '<th style="font-size:12px">Yr/Mo</th>' +
                              '<th style="font-size:12px">Bill No.</th>' +
                              '<th style="font-size:12px">Pres R</th>' +
                              '<th style="font-size:12px">Prev R</th>' +
                              '<th style="font-size:12px"> KwH Used</th>' +
                              '<th style="font-size:12px">Bill Amount</th>' +
                              '<th style="font-size:12px">OR No</th>' +
                              '<th style="font-size:12px">OR Date</th>' +
                              '<th style="font-size:12px">OR Amount</th>' +
                              '<th style="font-size:12px">Adj. Date</th>' +
                              '<th style="font-size:12px">Adj. KWH Used</th>' +
                              '<th style="font-size:12px">Adj Bill Amt</th>' +
                              '<th style="font-size:12px">Current Bill Bal</th>' +
                              '<th style="font-size:12px">Surchage</th>' +
                             '</tr>';
                             for(let i in pbbills){
                        var myr = pbbills[i].mr_date_year_month.toString();
                    var d =['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];
                    var myr1 = myr.slice(0,4);
                    var myr2 = myr.slice(4);
                         if(pbbills[i].Collected_Not_Posted == 'NO'){
                            if(pbbills[i].Adj_KWH_Used == undefined){
                                pbbills[i].Adj_KWH_Used = '';
                            }
                            output += '<tr style="color:black">' +
                            '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;;font-size:12px" onclick = "spugData('+pbbills[i].mr_id+ ',' +pbbills[i].mr_kwh_used+' );">' + d[parseInt(myr2)] +' '+ myr1 + '</td>' +
                            '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].mr_bill_no + '</td>' +
                            '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].mr_pres_reading + '</td>' +
                            '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].mr_prev_reading + '</td>' +
                            '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].mr_kwh_used + '</td>' +
                            '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].mr_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                            '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].or_no + '</td>' +
                            '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].or_date + '</td>' +
                            '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].or_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                            '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].Adj_Date + '</td>' +
                            // console.log(pbbills[i].Adj_Date);
                            '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px;font-size:12px">' + pbbills[i].Adj_KWH_Used + '</td>' +
                            '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px;font-size:12px">' + pbbills[i].Adj_Bill_Amt.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                            '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px;font-size:12px">' + pbbills[i].Current_Bill_Bal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                            '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px;font-size:12px">' + pbbills[i].Sur_Charge + '</td>';
                            
                    output += '</tr>';
                         }
                         else{
                            if(pbbills[i].Adj_KWH_Used == undefined){
                                pbbills[i].Adj_KWH_Used = '';
                            }
                            output += '<tr style="color:black">' +
                            '<td style="font-size:12px" onclick = "spugData('+pbbills[i].mr_id+ ',' +pbbills[i].mr_kwh_used+' );">' + d[parseInt(myr2)] +' '+ myr1 + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].mr_bill_no + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].mr_pres_reading + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].mr_prev_reading + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].mr_kwh_used + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].mr_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].or_no + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].or_date + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].or_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].Adj_Date + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].Adj_KWH_Used + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].Adj_Bill_Amt.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].Current_Bill_Bal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].Sur_Charge + '</td>';
                            
                    output += '</tr>';
                         }
                    }
                    output += '</table>';
                    output += '</div>';
                    output += '<input style="border:1px solid yellow" type="checkbox" disabled><label style="font-family:calibri;font-size:12px;color:black;display:inline">Collected but not Posted</label>&nbsp' +
                           '<input type="checkbox" disabled><label style="font-family:calibri;font-size:12px;color:black;display:inline">Collected and Posted or Unpaid</label>&nbsp';
                    output += '<div class="row">';
                    output += '<div class="col-3">';
                    output += '<table style="font-family:calibri;font-size:12px;color:black;width:100%;">';
                    output += '<tr>' +
                    '<td>Meter Deposit:</td>' +
                    '<td><input style="border:0px;font-family:calibri;font-size:12px;" id="meterD" type="text"></td>';
                    '</tr>';
                    output += '<tr>' +
                    '<td>Senior Citizen Discount:</td>' +
                    '<td><input style="border:0px;font-family:calibri;font-size:12px;" id ="bDate" type="text"></td>';
                    '</tr>';
                    output += '<tr>' +
                    '<td>Bill Date:</td>' +
                    '<td><input style="border:0px;font-family:calibri;font-size:12px;" id="sCD" type="text"></td>';
                    '</tr>';
                    output += '<tr>' +
                    '<td>Bill Due Date:</td>' +
                    '<td><input style="border:0px;font-family:calibri;font-size:12px;" id="bDDate" type="text"></td>';
                    '</tr>';
                    output += '</table>';
                    output += '</div>';
                    output += '<div class="col-6">';
                    output += '<table style="font-family:calibri;font-size:12px;color:black;width:100%;">';
                    output += '<tr>' +
                    '<td>UC-ME SPUG:</td>' +
                    '<td><input style="font-family:calibri;font-size:12px;" type="text" id="UC_ME"></td>' +
                    '<td>Ewallet Payment:</td>' +
                    '<td><input style="font-family:calibri;font-size:12px;" id="advPayment" type="text"></td>' +
                    '</tr>';
                    output += '<tr>' +
                    '<td>UC-ME RED:</td>' +
                    '<td><input style="font-family:calibri;font-size:12px;" id="red" type="text" ></td>' +
                    '<td>E-VAT:</td>' +
                    '<td><input style="font-family:calibri;font-size:12px;" id="vat" type="text" ></td>' +
                    '</tr>';
                    output += '<tr>' +
                    '<td>UC-EC:</td>' +
                    '<td><input style="font-family:calibri;font-size:12px;" id="uc_ec" type="text" ></td>' +
                    '<td>Total Unpaid Integ:</td>' +
                    '<td><input style="font-family:calibri;font-size:12px;" id="tuInteg" type="text" ></td>' +
                    '</tr>';
                    output += '<tr>' +
                    '<td>UC-NPC SCC:</td>' +
                    '<td><input style="font-family:calibri;font-size:12px;" id="scc" type="text" ></td>' +
                    '<td>TSF Rental:</td>' +
                    '<td><input style="font-family:calibri;font-size:12px;" id="tsfRental" type="text"></td>' +
                    '</tr>';
                    output += '</table>';
                    output += '</div>';
                    output += '<div class="col-3">';
                    output += '<table style="font-family:calibri;font-size:12px;color:black;width:80%;">';
                    output += '<tr>' +
                    '<td>Total Unpaid Bills:</td>' +
                    '<td><input style="font-family:calibri;font-size:12px;" type="text" value="'+data.Total_Unpaid_Bills.toLocaleString("en-US", { minimumFractionDigits: 2 })+'"></td>';
                    '</tr>';
                    output += '<tr>' +
                    '<td>Reconnection Fee:</td>' +
                    '<td><input style="font-family:calibri;font-size:12px;" type="text" value="'+data.Reconnection_FEE.toLocaleString("en-US", { minimumFractionDigits: 2 })+'"></td>';
                    '</tr>';
                    output += '<tr>' +
                    '<td>Collectible:</td>' +
                    '<td><input style="font-family:calibri;font-size:12px;" type="text" value="'+data.Collectible+'"></td>';
                    '</tr>';
                    output += '</table>';
                    output += '<div style = "margin-bottom:5px;" ></div>';
                    output += '<button onclick="aPayment2()" class = "btn btn-danger" style="width:50%;font-family:calibri;font-size:12px;margin-right:10px;">Ewallet Payment</button>';
                    output += '<button onlick="" class = "btn btn-success" style="width:30%;font-family:calibri;font-size:12px">Integration</button>';
                    output += '</div>';
                    output += '</div>';
                    
                }
                document.querySelector('#ledgerData').innerHTML = output;
            }
            xhr.send();
    }
    
    
    function ledgerClose(){
        document.querySelector('#consLedger').style.display="none";
    }
    function spugData(mr_id,kwh) {
        var xhr = new XMLHttpRequest();
        var ledgerRates = "{{route('show.consumer.ledger.rates',':id')}}";
        ledgerRates = ledgerRates.replace(':id',mr_id);
        xhr.open('GET', ledgerRates, true);
        xhr.onload = function() {
            if (this.status == 200) {
                var data = JSON.parse(this.responseText);
                var data = data.Rates;
                var UC_ME = data[0].UC_ME_SPUG * kwh;
                var advPayment = data[0].Advance_Payment;
                var red = data[0].UC_ME_RED * kwh;
                var vat = data[0].E_VAT;
                var uc_ec = data[0].UC_EC * kwh;
                var tuInteg = data[0].Total_UnPaid_Integ;
                var scc = data[0].UC_NPC_SCC * kwh;
                var tsfRental = data[0].TSF_Rental;
                var meterD = data[0].Meter_Deposit;
                var bDate = data[0].Bill_Date;
                var sCD = data[0].Senior_Citizen_Discount;
                var bDDate = data[0].Bill_Due_Date;
                bDate = bDate.split(" ");
                bDDate = bDDate.split(" ");
            }
            document.querySelector('#UC_ME').value = parseFloat(UC_ME).toFixed(2);
            document.querySelector('#advPayment').value = parseFloat(advPayment).toFixed(2);
            document.querySelector('#red').value = parseFloat(red).toFixed(2);
            document.querySelector('#vat').value = parseFloat(vat).toFixed(2);
            document.querySelector('#uc_ec').value = parseFloat(uc_ec).toFixed(2);
            document.querySelector('#tuInteg').value = parseFloat(tuInteg).toFixed(2);
            document.querySelector('#scc').value = parseFloat(scc).toFixed(2);
            document.querySelector('#tsfRental').value = parseFloat(tsfRental).toFixed(2);
            document.querySelector('#meterD').value = meterD;
            document.querySelector('#bDate').value = bDate[0];
            document.querySelector('#sCD').value = sCD;
            document.querySelector('#bDDate').value = bDDate[0];
        }
        xhr.send();
    }
    /* ---------------------- ENd Consumer Details ------------------- */
    
    /*----------------------- Disconnection Due ---------------------*/
    function myTD(mrID) {
        var xhr = new XMLHttpRequest();
        var mrDisconDue = "{{route('show.disconnect.due.date',':id')}}";
        mrDisconDue = mrDisconDue.replace(':id',mrID);
        xhr.open('GET', mrDisconDue, true);
        xhr.onload = function() {
            if (this.status == 200) {
                var data = JSON.parse(this.responseText);
                var discoD = data.Discon_Date;
                var dueD = data.Due_Date;
                document.querySelector('#discoID').value = discoD;
                document.querySelector('#dueID').value = dueD;
            }
        }
        xhr.send();
    }
    /* ------------------------- End of Disconnection Due --------- */
    
    /* -------Amount to be paid------------- */
    function r() {
        // delete accounts.change;
        amt = '';
        amount.getChange = "no";
        var eLet = document.querySelector('#eLet');
        if(eLet.checked){
            eLet.checked = false;
        }
        var toC = document.getElementById("ewalletTo");
        var change = document.querySelector('#change');
        toC.checked = false;
        change.value = '';
        change.placeholder = '0.00';
        document.querySelector('.chequeDisabled').disabled=false;
        // document.querySelector('#eAmount').innerHTML = '';
        document.querySelector('#ewalletPay').value = '';
        document.querySelector('#ewalletPay').placeholder = '0.00';
        document.querySelector('#eLet').checked = false;
        var  enabled= document.querySelector('#cash');
        enabled.disabled = false;
        enabled.value = '';
        enabled.placeholder = '0.00';
        var tStore = [];
        var nbStore = [];
        var ttt = 0;
        var output = ''; /* 1/25/2022 */
        if(typeof nbtotalamount == 'undefined'){
            nbtotalamount = 0;
            ttt = nbtotalamount;
        }
        else{
            ttt = parseInt(nbtotalamount);
        }
        var change = document.querySelector('#change');
        var cash = document.querySelector('#cash');
        var aP = document.querySelector('#aP');
        aP.value = '';
        aP.placeholder = '0.00';
        var boxes = document.querySelectorAll('input[name="amountToBePaid"]:checked');
        var nbBoxes = document.querySelectorAll('input[name="nonBill"]:checked');
        var unbBoxes = document.querySelectorAll('.disabledlang');
        var total = 0;
        if (nbBoxes.length + boxes.length >= 7) {
            document.querySelector('.chequeDisabled').disabled=false;
            for (var x = 0; x < unbBoxes.length; x++) {
                if (unbBoxes[x].checked == false) {
                    unbBoxes[x].disabled = true;
                }
            }
            for (var x = 0; x < boxes.length; x++) {
                total += parseFloat(boxes[x].id);
            }
            if(ewalletamount < 0){
            totalPbNb = total + Math.abs(ewalletamount);
            total = total + Math.abs(ewalletamount);
            ewallCredit.ewc=Math.abs(ewalletamount);
            console.log(ewallCredit);
            }
            else{
    
                delete ewallCredit.ewc;
                delete ewallCredit;
                console.log(ewallCredit);
                total = total;
                totalPbNb = total; 
            }
             var tttt = parseFloat(total) + parseFloat(ttt);
             document.querySelector('#aP').value = parseFloat(tttt).toFixed(2);
             /* 1/25/2022 */
                if(parseFloat(vonsumer.ew_total_amount) < parseFloat(tttt)){
                    output += 'Cash to add: ' + (parseFloat(tttt) - parseFloat(vonsumer.ew_total_amount)).toFixed(2);
                    document.querySelector('#needcash').innerHTML = output;
                    }
                else if(parseFloat(vonsumer.ew_total_amount) == 0){
                    document.querySelector('#needcash').innerHTML = '';
                }
            /* end 1/25/2022 */
            Swal.fire({
                title: 'Information',
                text: '"You have reached the maximum number (7) of transaction."',
                icon: 'info',
                confirmButtonText: 'close'
            })
        } 
        else if(nbBoxes.length + boxes.length < 7) {
            if(nbBoxes.length != 0){
                document.querySelector('.chequeDisabled').disabled=true;
            }
            else{
                document.querySelector('.chequeDisabled').disabled=false;
            }
            var toC = document.getElementById("ewalletTo");
            toC.disabled = false;
            document.querySelectorAll('.disabledlang').disabled = false;
            for (var x = 0; x < unbBoxes.length; x++) {
                unbBoxes[x].disabled = false;
            }
            for (var x = 0; x < boxes.length; x++) {
                total += parseFloat(boxes[x].id);
            }
            if(ewalletamount < 0){
            totalPbNb = total + Math.abs(ewalletamount);
            total = total + Math.abs(ewalletamount);
            ewallCredit.ewc=Math.abs(ewalletamount);
            console.log(ewallCredit);
            }
            else{
    
                delete ewallCredit.ewc;
                delete ewallCredit;
                console.log(ewallCredit);
                total = total;
                totalPbNb = total; 
            }
             var tttt = parseFloat(total) + parseFloat(ttt);
             document.querySelector('#aP').value = parseFloat(tttt).toFixed(2);
            /* 1/25/2022 */
                if(parseFloat(vonsumer.ew_total_amount) < parseFloat(tttt)){
                    output += 'Cash to add: ' + (parseFloat(tttt) - parseFloat(vonsumer.ew_total_amount)).toFixed(2);
                    document.querySelector('#needcash').innerHTML = output;
                    }
                else if(parseFloat(vonsumer.ew_total_amount) == 0){
                    document.querySelector('#needcash').innerHTML = '';
                }
            /* end 1/25/2022 */
                if(nbBoxes.length + boxes.length == 0){
                    var change = document.querySelector('#change');
                    var t = document.querySelector('#ewalletTo');
                    var eLet = document.querySelector('#eLet');
                    t.disabled=true;
                    eLet.disabled=true;
                    enabled.disabled=true;
                    if(document.querySelector('.collectionV1') != null){
                        document.querySelector('.collectionV1').disabled = true;
                    }
                    if(document.querySelector('.collectionV') != null){
                        document.querySelector('.collectionV').disabled = true;
                    }
                    document.querySelector('.chequeDisabled').disabled=true;
                    enabled.value = '';
                    enabled.placeholder='0.00';
                    document.querySelector('#aP').value = '';
                    document.querySelector('#aP').placeholder = '0.00';
                    change.value = '';
                    change.placeholder = '0.00';
                    document.querySelector('#needcash').innerHTML = '';
            }
        } 
    }
    function bapaMopa() {
        // delete accounts.change;
        amount.getChange = "no";
        var eLet = document.querySelector('#eLet');
        if(eLet.checked){
            eLet.checked = false;
        }
        var toC = document.getElementById("ewalletTo");
        var change = document.querySelector('#change');
        toC.checked = false;
        change.value = '';
        change.placeholder = '0.00';
        document.querySelector('.chequeDisabled').disabled=false;
        // document.querySelector('#eAmount').innerHTML = '';
        document.querySelector('#ewalletPay').value = '';
        document.querySelector('#ewalletPay').placeholder = '0.00';
        document.querySelector('#eLet').checked = false;
        var  enabled= document.querySelector('#cash');
        enabled.disabled = false;
        enabled.value = '';
        enabled.placeholder = '0.00';
        var tStore = [];
        var nbStore = [];
        var ttt = 0;
        var output = ''; /* 1/25/2022 */
        if(typeof nbtotalamount == 'undefined'){
            nbtotalamount = 0;
            ttt = nbtotalamount;
        }
        else{
            ttt = parseInt(nbtotalamount);
        }
        var change = document.querySelector('#change');
        var cash = document.querySelector('#cash');
        var aP = document.querySelector('#aP');
        aP.value = '';
        aP.placeholder = '0.00';
        var boxes = document.querySelectorAll('input[name="amountToBePaid"]:checked');
        var nbBoxes = document.querySelectorAll('input[name="nonBill"]:checked');
        var unbBoxes = document.querySelectorAll('.disabledlang');
        var total = 0;
        if (nbBoxes.length + boxes.length >= 7) {
            document.querySelector('.chequeDisabled').disabled=false;
            for (var x = 0; x < unbBoxes.length; x++) {
                if (unbBoxes[x].checked == false) {
                    unbBoxes[x].disabled = true;
                }
            }
            for (var x = 0; x < boxes.length; x++) {
                total += parseFloat(boxes[x].id);
            }
            totalPbNb = total;
             var tttt = parseFloat(total) + parseFloat(ttt);
             amt = parseFloat(tttt).toFixed(2);
             document.querySelector('#aP').value = parseFloat(forDisplay).toFixed(2);
             /* 1/25/2022 */
                if(parseFloat(vonsumer.ew_total_amount) < parseFloat(forDisplay)){
                    output += 'Cash to add: ' + (parseFloat(forDisplay) - parseFloat(vonsumer.ew_total_amount)).toFixed(2);
                    document.querySelector('#needcash').innerHTML = output;
                    }
                else if(parseFloat(vonsumer.ew_total_amount) == 0){
                    document.querySelector('#needcash').innerHTML = '';
                }
            /* end 1/25/2022 */
            Swal.fire({
                title: 'Information',
                text: '"You have reached the maximum number (7) of transaction."',
                icon: 'info',
                confirmButtonText: 'close'
            })
        } 
        else if(nbBoxes.length + boxes.length < 7) {
            if(nbBoxes.length != 0){
                document.querySelector('.chequeDisabled').disabled=true;
            }
            else{
                document.querySelector('.chequeDisabled').disabled=false;
            }
            var toC = document.getElementById("ewalletTo");
            toC.disabled = false;
            document.querySelectorAll('.disabledlang').disabled = false;
            for (var x = 0; x < unbBoxes.length; x++) {
                unbBoxes[x].disabled = false;
            }
            for (var x = 0; x < boxes.length; x++) {
                total += parseFloat(boxes[x].id);
            }
            totalPbNb = total;
             var tttt = parseFloat(total) + parseFloat(ttt);
             amt = parseFloat(tttt).toFixed(2);
             document.querySelector('#aP').value = parseFloat(forDisplay).toFixed(2);
            /* 1/25/2022 */
        
                if(parseFloat(vonsumer.ew_total_amount) < parseFloat(forDisplay)){
                    output += 'Cash to add: ' + (parseFloat(forDisplay) - parseFloat(vonsumer.ew_total_amount)).toFixed(2);
                    document.querySelector('#needcash').innerHTML = output;
                    }
                else if(parseFloat(vonsumer.ew_total_amount) == 0){
                    document.querySelector('#needcash').innerHTML = '';
                }
            /* end 1/25/2022 */
                if(nbBoxes.length + boxes.length == 0){
                    var change = document.querySelector('#change');
                    var t = document.querySelector('#ewalletTo');
                    var eLet = document.querySelector('#eLet');
                    t.disabled=true;
                    eLet.disabled=true;
                    enabled.disabled=true;
                    if(document.querySelector('.collectionV1') != null){
                        document.querySelector('.collectionV1').disabled = true;
                    }
                    if(document.querySelector('.collectionV') != null){
                        document.querySelector('.collectionV').disabled = true;
                    }
                    document.querySelector('.chequeDisabled').disabled=true;
                    enabled.value = '';
                    enabled.placeholder='0.00';
                    document.querySelector('#aP').value = '';
                    document.querySelector('#aP').placeholder = '0.00';
                    change.value = '';
                    change.placeholder = '0.00';
                    document.querySelector('#needcash').innerHTML = '';
            }
        } 
    }
    
    
    /* ---------------END of amount to be paid ----------------- */
    function aPayment2() {
        modalD = document.querySelectorAll(".modal");
        modalD[4].style.display = "block";
    
        var xhr = new XMLHttpRequest();
        var ewalletLog = "{{route('get.ewallet',['id'=>':id'])}}";
        ewalletLog = ewalletLog.replace(':id', vonsumer.ew_id)
        xhr.open('GET', ewalletLog, true);
            xhr.onload = function() {
            if (this.status == 200) {
                var data = JSON.parse(this.responseText);
                var wall = data.Ewallet_Log;
                var output = " ";
                var total = 0;
                var total1 = 0;
                var a;
                if(data.additional != 0){
                    output += '<tr style="background-color:white;color:black;">'; 
                    output += '<td>' + 'EBS' + '</td>' +
                    '<td>' + data.additional + '</td>'+
                    '<td>' + ' ' + '</td>'+
                    // '<td>' + ' ' + '</td>'+
                    '<td>' + ' ' + '</td>'+
                    '</tr>';
                }
                for (let i in wall) {
                    
                    if(wall[i].Status == 'U' || wall[i].Status == 'P'){
                            wall[i].Status = '+';
                        }
                    if(wall[i].Status == 'A'){
                            wall[i].Status = "-"; 
                        }
                    output += '<tr style="background-color:white;color:black;">' +
                        '<td>' + ' ' + '</td>' +
                        '<td>' + wall[i].Status + wall[i].Trans_Amount + '</td>' +
                        '<td>' + wall[i].Year_Month + '</td>' +
                        // '<td>' + wall[i].Status + '</td>' +
                        '<td>' + wall[i].OR_Num + '</td>' +
                        '</tr>';
                        if(wall[i].Status == '+' ){
                        total += parseFloat(wall[i].Trans_Amount);
                        }
                        if(wall[i].Status == '-'){
                            total1 += parseFloat(wall[i].Trans_Amount); 
                        }
                }
                
                total = parseFloat(total) + parseFloat(data.additional);
                // output += ''
                document.querySelector('#tde').innerHTML = total.toLocaleString("en-US", { minimumFractionDigits: 2 });
                document.querySelector('#tae').innerHTML = total1.toLocaleString("en-US", { minimumFractionDigits: 2 });
            }
            document.querySelector('#ewallAd').innerHTML = output;
        }
        xhr.send()
    
    }
    
    function aPaymentClose() {
        modalD = document.querySelectorAll(".modal");
        modalD[4].style.display = "none";
    }
    
    function nb() {
        // delete accounts.change;
        amt = '';
        amount.getChange = "no";
        document.querySelector('.chequeDisabled').disabled=false;
        // document.querySelector('.chequeDisabled').disabled=true;
        var eLet = document.querySelector('#eLet');
        var change = document.querySelector('#change');
        var toC = document.getElementById("ewalletTo");
        var change = document.querySelector('#change');
        var output = ''; /* 1/25/2022 */
        toC.checked = false;
        if(eLet.checked){
            eLet.checked = false;
        }
        
        // document.querySelector('.chequeDisabled').disabled=false;
        // document.querySelector('#eAmount').innerHTML = '';
        document.querySelector('#ewalletPay').value = '';
        document.querySelector('#ewalletPay').placeholder = '0.00';
        document.querySelector('#eLet').checked = false;
        change.value = '';
        change.placeholder = '0.00';
        var  enabled= document.querySelector('#cash');
        enabled.disabled = false;
        enabled.value = '';
        enabled.placeholder='0.00';
        document.querySelector('#aP').value = 0;
        if(document.querySelector('#aP').value == 0){
        document.querySelector('#aP').value = '';
        document.querySelector('#aP').placeholder = '0.00';
        }
        var nbBoxes = document.querySelectorAll('input[name="nonBill"]:checked');
        var boxes = document.querySelectorAll('input[name="amountToBePaid"]:checked');
        var unbBoxes = document.querySelectorAll('.disabledlang');
        var tStore = [];
        var nbStore = [];
        if (nbBoxes.length + boxes.length >= 7) {
            // if(nbBoxes.length != 0){
            //     document.querySelector('.chequeDisabled').disabled=true;
            // }
            // else{
            //     document.querySelector('.chequeDisabled').disabled=false;
            // }
            for (var x = 0; x < unbBoxes.length; x++) {
                if (unbBoxes[x].checked == false) {
                    unbBoxes[x].disabled = true;
                }
            }
            for (var x = 0; x < nbBoxes.length; x++) {
                tStore.push(nbBoxes[x].value);
            }
            for (let z in tStore) {
                nbBill = tStore[z].split("^");
                var nbilldata = { "Fee_ID": parseInt(nbBill[0]), "Fee_Amount": parseFloat(nbBill[1]), "Vatable": parseFloat(nbBill[2]), "Vat_Percent": nbBill[3], "Fees_Desc": nbBill[4] };
                nbStore.push(nbilldata);
            }
            if(nbStore.length == 0){
                document.querySelector('#aP').value = totalPbNb.toFixed(2);
                amountNB.Amount_TB_Paid = totalPbNb.toFixed(2);
            }
            else{
                var totalAmount = 0;
                for (var selectedAmount = 0; selectedAmount < nbStore.length; selectedAmount++) {
                    totalAmount += nbStore[selectedAmount].Fee_Amount;
                }
                
                var amtbpaid = parseFloat(totalPbNb) + parseFloat(totalAmount);
                nbtotalamount = totalAmount;
                document.querySelector('#aP').value = amtbpaid.toFixed(2);
                amountNB.Amount_TB_Paid = amtbpaid.toFixed(2);
                /* 1/25/2022 */
                    if(parseFloat(vonsumer.ew_total_amount) == 0){
                            document.querySelector('#needcash').innerHTML = '';
                        }
                    else if(parseFloat(vonsumer.ew_total_amount) < parseFloat(amtbpaid)){
                            output += 'Cash to add: ' + (parseFloat(amtbpaid) - parseFloat(vonsumer.ew_total_amount)).toFixed(2);
                            document.querySelector('#needcash').innerHTML = output;
                        }
                /* end of 1/25/2022 */
            }
            Swal.fire({
                title: 'Information',
                text: '"You have reached the maximum number (7) of transaction."',
                icon: 'info',
                confirmButtonText: 'close'
            })
        } 
        else if (nbBoxes.length + boxes.length < 7) {
            // if(nbBoxes.length != 0){
            //     document.querySelector('.chequeDisabled').disabled=true;
            // }
            // else{
            //     document.querySelector('.chequeDisabled').disabled=false;
            // }
            var toC = document.getElementById("ewalletTo");
            toC.disabled = false;
            document.querySelectorAll('.disabledlang').disabled = false;
            for (var x = 0; x < unbBoxes.length; x++) {
                unbBoxes[x].disabled = false;
            }
            for (var x = 0; x < nbBoxes.length; x++) {
                tStore.push(nbBoxes[x].value);
            }
            for (let z in tStore) {
                nbBill = tStore[z].split("^");
                var nbilldata = { "Fee_ID": parseInt(nbBill[0]), "Fee_Amount": parseFloat(nbBill[1]), "Vatable": parseFloat(nbBill[2]), "Vat_Percent": nbBill[3], "Fees_Desc": nbBill[4] };
                nbStore.push(nbilldata);
            }
            var totalAmount = 0;
            for (var selectedAmount = 0; selectedAmount < nbStore.length; selectedAmount++) {
                totalAmount += nbStore[selectedAmount].Fee_Amount;
            }
            nbtotalamount = totalAmount;
            nbtotalamount = parseFloat(totalAmount);
            var amtbpaid = parseFloat(totalPbNb) + parseFloat(totalAmount);
            document.querySelector('#aP').value = amtbpaid.toFixed(2);
            amountNB.Amount_TB_Paid = amtbpaid.toFixed(2);
                    if(parseFloat(vonsumer.ew_total_amount) == 0){
                            document.querySelector('#needcash').innerHTML = '';
                        }
                    else if(parseFloat(vonsumer.ew_total_amount) < parseFloat(amtbpaid)){
                            output += 'Cash to add: ' + (parseFloat(amtbpaid) - parseFloat(vonsumer.ew_total_amount)).toFixed(2);
                            document.querySelector('#needcash').innerHTML = output;
                        }
                if(nbBoxes.length + boxes.length == 0){
                    var change = document.querySelector('#change');
                    var t = document.querySelector('#ewalletTo');
                    var eLet = document.querySelector('#eLet');
                    t.disabled=true;
                    eLet.disabled=true;
                    enabled.disabled=true;
                    if(document.querySelector('.collectionV1') != null){
                        document.querySelector('.collectionV1').disabled = true;
                    }
                    if(document.querySelector('.collectionV') != null){
                        document.querySelector('.collectionV').disabled = true;
                    }
                    document.querySelector('.chequeDisabled').disabled=true;
                    enabled.value = '';
                    enabled.placeholder='0.00';
                    document.querySelector('#aP').value = '';
                    document.querySelector('#aP').placeholder = '0.00';
                    change.value = '';
                    change.placeholder = '0.00';
                    document.querySelector('#needcash').innerHTML = '';
            }
        }
    }
    
    function data_send() {
        console.log(amount)
        localStorage.removeItem('consumer');
        localStorage.removeItem('res');
        localStorage.removeItem('accountInfo');
        Swal.fire({
            title: 'Do you want to continue?',
            showDenyButton: true,
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
                // tor.or_no = sessionStorage.getItem('TOR');
                var ornum1 = sessionStorage.getItem('TOR');
                var ornum2 = ornum1.replace(/-/g, '');
                // console.log(ornum2);
                tor.or_no = ornum2;
                if(typeof amount.getChange == 'undefined'){
                    amount.getChange = 'no';
                }
                var tellid = document.querySelector('#tellid').innerHTML;
                var teller = new Object();
                teller.user_id = tellid;
                teller.date = today;
                var xhr2 = new XMLHttpRequest();
                var checksalestransact = '{{route("check.pay.bills.CutOff")}}';
                xhr2.open('POST', checksalestransact, true);
                xhr2.setRequestHeader("Accept", "application/json");
                xhr2.setRequestHeader("Content-Type", "application/json");
                xhr2.send(JSON.stringify(teller));
                xhr2.onload = function() {
                    if (this.status == 404) {
                     
                        var check = new Object();
                        check.cutoff = 0;
                        var nbBoxes = document.querySelectorAll('input[name="nonBill"]:checked');
                        var boxes = document.querySelectorAll('input[name="amountToBePaid"]:checked');
                        var temp = "";
                        var checked = boxes.length;
                        for (var x = 0; x < boxes.length; x++) {
                            temp += boxes[x].value;
                        }
                        temp = temp.split("$", checked);
                        for (let z in temp) {
                            v = temp[z].split("^");
                            var j = { "mr_id": parseInt(v[0]), "mr_billno": v[1], "mr_amount": parseFloat(v[2]), "mr_date_reg": v[3], "mr_yr_month": v[4] };
                            tempStore.push(j);
                        }
                        var tStore = [];
                        var nbStore = [];
                        for (var x = 0; x < nbBoxes.length; x++) {
                            tStore.push(nbBoxes[x].value);
                        }
                        for (let z in tStore) {
                            nbBill = tStore[z].split("^");
                            var nbilldata = { "Fee_ID": parseInt(nbBill[0]), "Fee_Amount": parseFloat(nbBill[1]), "Vatable": parseFloat(nbBill[2]), "Vat_Percent": nbBill[3], "Fees_Desc": nbBill[4] };
                            nbStore.push(nbilldata);
                        }
                        var xhr = new XMLHttpRequest();
                        var salestransact = '{{route("pay.bills")}}';
                        xhr.open('POST', salestransact, true); 
                        xhr.setRequestHeader("Accept", "application/json");
                        xhr.setRequestHeader("Content-Type", "application/json");
                        xhr.onload = function() {
                            if (this.status == 201) {
                                // document.querySelector('#selectOR').style.display="block"
                                var data = JSON.parse(this.responseText);
                               
                                var total_amount = data.Total_Amount;
                                var date_paid = data.Date_Paid;
                                var ta = data.Total_Arrears_Amount;
                                var res = new Object();
                                res.Date_Paid = date_paid;
                               
                                res.Total_Amount = total_amount;
                                res.Total_Arrears = ta;
                                // tor1 = parseInt(sessionStorage.getItem('TOR')) + 1;
                                // sessionStorage.setItem("TOR",tor1);
                                checkLastOR();
                                torModal();
                                // Kimar start
                                // $url = '{{route("PBOR")}}';
                                $url = '{{route("newOR")}}';
                                // Kimar end
                                localStorage.setItem('res', JSON.stringify(res));
                                localStorage.setItem('accountInfo', JSON.stringify(accounts));
                                localStorage.setItem('consumer', JSON.stringify(data));
    
                                window.open($url);
                            } else if (this.status == 422) {
                                // tor1 = parseInt(sessionStorage.getItem('TOR'));
                                // sessionStorage.setItem("TOR",tor1);
                                var data = JSON.parse(this.responseText);
                                var a = data.Message;
                                Swal.fire({
                                    title: 'Error!',
                                    text: '"'+ a +'"',
                                    icon: 'error',
                                    confirmButtonText: 'close'
                                })
                                checkLastOR();
                                torModal();
                            }
                        }
                        if(ewalletamount >= 0){
                        if (nbStore.length == 0) {
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amount,
                                'TOR_No': tor,
                                'PB': tempStore,
                                'teller': check
                            };
                        } else if(tempStore.length == 0 ){
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amount,
                                'TOR_No': tor,
                                'NB': nbStore,
                                'teller': check
                            };
                        }else {
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amount,
                                'TOR_No': tor,
                                'NB': nbStore,
                                'PB': tempStore,
                                'teller': check
                            };
    
                        }}
                        else{
                            if (nbStore.length == 0) {
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amount,
                                'TOR_No': tor,
                                'PB': tempStore,
                                'ewallet_credit': ewallCredit,
                                'teller': check
                            };
                            } else if(tempStore.length == 0 ){
                                var Consumer1 = {
                                    'Consumer': vonsumer,
                                    'Amounts': amount,
                                    'TOR_No': tor,
                                    'NB': nbStore,
                                    'ewallet_credit': ewallCredit,
                                    'teller': check
                                };
                            }else {
                                var Consumer1 = {
                                    'Consumer': vonsumer,
                                    'Amounts': amount,
                                    'TOR_No': tor,
                                    'NB': nbStore,
                                    'PB': tempStore,
                                    'ewallet_credit': ewallCredit,
                                    'teller': check
                                };
    
                            }
                        }
                        console.log(Consumer1);
                        var accID = Consumer1.Consumer.cm_id;
                        xhr.send(JSON.stringify(Consumer1));
                        
                        consumer = accName2;
                       
                        var ap = document.getElementById("aP");
                        var cash = document.getElementById("cash");
                        ap.value = 0;
                        if(ap.value == 0){
                            ap.value='';
                            ap.placeholder = '0.00';
                        }
                        cash.value='';
                        cash.placeholder='0.00';
                        setTimeout(function(){tempStore = [];
                        nBstore = [];
                        amount = {};
                        delete accounts.change;
                        tor = {};
                        csh = 0;
                        nbtotalamount = 0;
                        totalPbNb = 0;
                        amt = '';
                        forDisplay = '';
                        // localStorage.removeItem('consumer');
                        // localStorage.removeItem('res');
                        // localStorage.removeItem('accountInfo');
                        var toC = document.getElementById("ewalletTo");
                        if (toC.checked == true) {
                            toC.checked = false;
                            delete amount.E_Wallet;
                        }
                        // document.querySelector('#eAmount').innerHTML = " ";
                        document.querySelector('#needcash').innerHTML = '';
                        document.querySelector('#ewalletPay').value = '';
                        document.querySelector('#ewalletPay').placeholder = '0.00';
                        document.querySelector('#eLet').checked = false;
                        document.querySelector('#change').value = '';
                        document.querySelector('#change').placeholder = '0.00';
                        setConsAcct(consumer);
                        ewall();
                        if(document.querySelector('.collectionV1') != null){
                            document.querySelector('.collectionV1').disabled = true;
                        }
                        if(document.querySelector('.collectionV') != null){
                            document.querySelector('.collectionV').disabled = true;
                        }
                        if(document.querySelector('#aP').value == '' || document.querySelector('#aP').value == '0.00' ){
                            var t = document.querySelector('#ewalletTo');
                            var eLet = document.querySelector('#eLet');
                            var enabled = document.querySelector('#cash');
                            t.disabled=true;
                            eLet.disabled=true;
                            enabled.disabled=true;
                            document.querySelector('.chequeDisabled').disabled=true;
                        }
                        
                    },1500);
                    } else if (this.status == 200) {
                        var check = new Object();
                        check.cutoff = 1;
                        var nbBoxes = document.querySelectorAll('input[name="nonBill"]:checked');
                        var boxes = document.querySelectorAll('input[name="amountToBePaid"]:checked');
                        var temp = "";
                        var checked = boxes.length;
                        for (var x = 0; x < boxes.length; x++) {
                            temp += boxes[x].value;
                        }
                        temp = temp.split("$", checked);
                        for (let z in temp) {
                            v = temp[z].split("^");
                            var j = { "mr_id": parseInt(v[0]), "mr_billno": v[1], "mr_amount": parseFloat(v[2]), "mr_date_reg": v[3], "mr_yr_month": v[4] };
                            tempStore.push(j);
                        }
                        var tStore = [];
                        var nbStore = [];
                        for (var x = 0; x < nbBoxes.length; x++) {
                            tStore.push(nbBoxes[x].value);
                        }
                        for (let z in tStore) {
                            nbBill = tStore[z].split("^");
                            var nbilldata = { "Fee_ID": parseInt(nbBill[0]), "Fee_Amount": parseFloat(nbBill[1]), "Vatable": parseFloat(nbBill[2]), "Vat_Percent": nbBill[3], "Fees_Desc": nbBill[4] };
                            nbStore.push(nbilldata);
                        }
                        var xhr = new XMLHttpRequest();
                        var salestransact = '{{route("pay.bills")}}';
                        xhr.open('POST', salestransact, true); 
                        xhr.setRequestHeader("Accept", "application/json");
                        xhr.setRequestHeader("Content-Type", "application/json");
                        xhr.onload = function() {
                            if (this.status == 201) {
                                // document.querySelector('#selectOR').style.display="block"
                                var data = JSON.parse(this.responseText);
                                var total_amount = data.Total_Amount;
                                var date_paid = data.Date_Paid;
                                var ta = data.Total_Arrears_Amount;
                                var res = new Object();
                                res.Date_Paid = date_paid;
                                res.Total_Amount = total_amount;
                                res.Total_Arrears = ta;
                                // tor1 = parseInt(sessionStorage.getItem('TOR')) + 1;
                                // sessionStorage.setItem("TOR",tor1);
                                checkLastOR();
                                torModal();
                                // Kimar start
                                // $url = '{{route("PBOR")}}';
                                $url = '{{route("newOR")}}';
                                // kimar end
                                localStorage.setItem('res', JSON.stringify(res));
                                localStorage.setItem('accountInfo', JSON.stringify(accounts));
                                localStorage.setItem('consumer', JSON.stringify(data));
                                window.open($url);
                            } else if (this.status == 422) {
                                // tor1 = parseInt(sessionStorage.getItem('TOR'));
                                // sessionStorage.setItem("TOR",tor1);
                                var data = JSON.parse(this.responseText);
                                var a = data.Message;
                                Swal.fire({
                                    title: 'Error!',
                                    text: '"'+ a +'"',
                                    icon: 'error',
                                    confirmButtonText: 'close'
                                })
                                checkLastOR();
                                torModal();
                            }
                        }
                        if(ewalletamount >= 0){
                        if (nbStore.length == 0) {
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amount,
                                'TOR_No': tor,
                                'PB': tempStore,
                                'teller': check
                            };
                        } else if(tempStore.length == 0 ){
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amount,
                                'TOR_No': tor,
                                'NB': nbStore,
                                'teller': check
                            };
                        }else {
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amount,
                                'TOR_No': tor,
                                'NB': nbStore,
                                'PB': tempStore,
                                'teller': check
                            };
    
                        }}
                        else{
                            if (nbStore.length == 0) {
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amount,
                                'TOR_No': tor,
                                'PB': tempStore,
                                'ewallet_credit': ewallCredit,
                                'teller': check
                            };
                            } else if(tempStore.length == 0 ){
                                var Consumer1 = {
                                    'Consumer': vonsumer,
                                    'Amounts': amount,
                                    'TOR_No': tor,
                                    'NB': nbStore,
                                    'ewallet_credit': ewallCredit,
                                    'teller': check
                                };
                            }else {
                                var Consumer1 = {
                                    'Consumer': vonsumer,
                                    'Amounts': amount,
                                    'TOR_No': tor,
                                    'NB': nbStore,
                                    'PB': tempStore,
                                    'ewallet_credit': ewallCredit,
                                    'teller': check
                                };
    
                            }
                        }
                        var accID = Consumer1.Consumer.cm_id;
                        xhr.send(JSON.stringify(Consumer1));
                        
                        consumer = accName2;
                       
                        var ap = document.getElementById("aP");
                        var cash = document.getElementById("cash");
                        ap.value = 0;
                        if(ap.value == 0){
                            ap.value='';
                            ap.placeholder = '0.00';
                        }
                        cash.value='';
                        cash.placeholder='0.00';
                        setTimeout(function(){tempStore = [];
                        nBstore = [];
                        amount = {};
                        delete accounts.change;
                        tor = {};
                        csh = 0;
                        nbtotalamount = 0;
                        totalPbNb = 0;
                        // localStorage.removeItem('consumer');
                        // localStorage.removeItem('res');
                        // localStorage.removeItem('accountInfo');
                        var toC = document.getElementById("ewalletTo");
                        if (toC.checked == true) {
                            toC.checked = false;
                            delete amount.E_Wallet;
                        }
                        // document.querySelector('#eAmount').innerHTML = " ";
                        document.querySelector('#needcash').innerHTML = '';
                        document.querySelector('#ewalletPay').value = '';
                        document.querySelector('#ewalletPay').placeholder = '0.00';
                        document.querySelector('#eLet').checked = false;
                        document.querySelector('#change').value = '';
                        document.querySelector('#change').placeholder = '0.00';
                        setConsAcct(consumer);
                        ewall();
                        if(document.querySelector('.collectionV1') != null){
                            document.querySelector('.collectionV1').disabled = true;
                        }
                        if(document.querySelector('.collectionV') != null){
                            document.querySelector('.collectionV').disabled = true;
                        }
                        if(document.querySelector('#aP').value == '' || document.querySelector('#aP').value == '0.00' ){
                            var t = document.querySelector('#ewalletTo');
                            var eLet = document.querySelector('#eLet');
                            var enabled = document.querySelector('#cash');
                            t.disabled=true;
                            eLet.disabled=true;
                            enabled.disabled=true;
                            document.querySelector('.chequeDisabled').disabled=true;
                        }
                    },1500);
                    }
                }
            } else if (result.isDenied) {
                Swal.fire('Cancelled', '', 'info')
            }
        })
    }
    
    function printledger(){
        Swal.fire({
            title: 'Do you want to continue?',
            showDenyButton: true,
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
                $url = '{{route("cdinquiry")}}';
                localStorage.setItem('auth',auth);
                window.open($url);
            setTimeout(function(){
                localStorage.removeItem('data');
                localStorage.removeItem('auth');
            },2000);
          }
          else if (result.isDenied) {
                Swal.fire('Cancelled', '', 'info')
            }
        })
    }
    
    /*-------------------------------End of Method Post Non-Bill -------------------------------*/
    
    function cashStep(){
        document.querySelector('#chequeAmount').value = '';
        document.querySelector('#chequeAmount').placeholder = '0.00';
        document.querySelector('.chequePrint').disabled = true;
        delete cheque.Cheque_Amount;
        document.querySelector('#useECheque').disabled = true;
    }
    function ewallCheque(){
        var check1 = document.querySelector('#useECheque');
        if(check1.checked == true){
            if(ctid != 3){
            chqdeposit = 0;
            var cqkAmount = document.querySelector('#chequeAmount').value;
            var aP = document.querySelector('#aP');
            document.querySelector("#cashAmount").disabled = true;
            document.querySelector(".chequePrint").disabled= false;
            document.querySelector(".chequePrint").style.visibility = "visible";
            var newEwall = (parseFloat(cqkAmount) + parseFloat(ewalletamount)) - parseFloat(aP.value);
            var ewalltosend = parseFloat(ewalletamount) - parseFloat(newEwall);
            document.querySelector('#chequeA').value = ewalltosend.toFixed(2);
            
            document.querySelector('#ewalletBal').innerHTML= 'Total Balance' + ' ' + newEwall.toFixed(2);
            // ewalltosend = Math.round(100*parseFloat(ewalltosend))/100;
            amountCheque.E_Wallet = parseFloat(ewalltosend);
            }
            else if(ctid == 3){
                var cqkAmount = document.querySelector('#chequeAmount').value;
                var aP = document.querySelector('#aP');
                if(parseFloat(aP.value) > (parseFloat(cqkAmount) + parseFloat(ewalletamount))){
                amountCheque.E_Wallet = parseFloat(ewalletamount);
                document.querySelector('#chequeA').value = parseFloat(ewalletamount).toFixed(2);
                document.querySelector('#ewalletBal').innerHTML= 'Total Balance' + ' ' + '0.00';
                }
                else if(parseFloat(aP.value) < (parseFloat(cqkAmount) + parseFloat(ewalletamount))){
                var ewalltopass = parseFloat(aP.value) - parseFloat(cqkAmount);
                var newtotal = parseFloat(ewalletamount) - parseFloat(ewalltopass);
                amountCheque.E_Wallet = parseFloat(ewalltopass);
                document.querySelector('#chequeA').value = parseFloat(ewalltopass).toFixed(2);
                document.querySelector('#ewalletBal').innerHTML= 'Total Balance' + ' ' + newtotal.toFixed(2);
                }
            }
        }
        else{
            delete amountCheque.E_Wallet;
            document.querySelector("#cashAmount").disabled = false;
            document.querySelector(".chequePrint").disabled= true;
            document.querySelector(".chequePrint").style.visibility = "hidden";
            document.querySelector('#chequeA').value = '';
            document.querySelector('#ewalletBal').innerHTML= '';   
        }
    
    }
    
    function chequeSend() {
        var check1 = document.querySelector('#useECheque');
        if(check1.checked == true){
            delete amountCheque.E_Wallet;
            check1.checked = false;
            document.querySelector('#chequeA').value = '';
        }
        document.querySelector('.chequePrint').disabled = false;
        document.querySelector('#useECheque').disabled = true;
        var cqkAmount = document.querySelector('#chequeAmount').value;
        var cqkName = document.querySelector('#bankAccName').value;
        var cqkDate = document.querySelector('#chequeDate').value;
        var cqkNum = document.querySelector('#chequeNo').value;
        var cqkBank = document.querySelector('#bank').value;
        var cqkBankNo = document.querySelector('#bankAccNo').value;
        var cashAmount = document.querySelector('#cashAmount').value;
        if(cqkName == '' || cqkDate == '' || cqkNum == '' || cqkBank == '' || cqkBankNo == ''){
            Swal.fire({
                title: 'Info',
                text: 'Invalid Entry, Please fill up the information first before entering the cheque amount',
                icon: 'info',
                confirmButtonText: 'close'
            }) 
            document.querySelector(".chequePrint").style.visibility = "hidden";
        }
       
        
        else{
            if(cashAmount == ''){
                delete amountCheque.Cash_Amount;
                
                cashAmount = 0;
                document.querySelector("#cashAmount").disabled = false;
                document.querySelector('#ewalletBal').innerHTML= '';
            }
            else{
                amountCheque.Cash_Amount = parseFloat(cashAmount);
            }
            var aP = document.querySelector('#aP');
            if(amt != ''){
                amountCheque.Amount_TB_Paid = parseFloat(amt);
            }
            else{
                amountCheque.Amount_TB_Paid = parseFloat(aP.value);    
            }
            cheque.Cheque_Amount = parseFloat(cqkAmount);
            cheque.Cheque_Bank = cqkBank;
            cheque.Cheque_Bank_Branch = " ";
            cheque.Cheque_Bank_Acc = cqkBankNo;
            cheque.Cheque_Acc_Name = cqkName;
            cheque.Cheque_No = parseInt(cqkNum);
    
            var output = "";
            // amnttbpaid.value = parseFloat(aP.value);
            var bal = document.querySelector('#bal');
            // bal.value = parseFloat(amnttbpaid.value) - parseFloat(totalcheque.value);
            
            if(parseFloat(cqkAmount) == parseFloat(aP.value)){
                document.querySelector('#useECheque').disabled = true;
                document.querySelector('#cashAmount').disabled = true;
                document.querySelector(".chequePrint").style.visibility = "visible";
                
            }
            if(parseFloat(cqkAmount) > parseFloat(aP.value)){
                document.querySelector('#useECheque').disabled = true;
                document.querySelector("#cashAmount").disabled = false;
                document.querySelector(".chequePrint").style.visibility = "visible";
                chqdeposit = parseFloat(cqkAmount) - parseFloat(aP.value);
              
            }
            else if(parseFloat(cqkAmount) != parseFloat(aP.value)){
                if(parseFloat(cqkAmount) == 0 || cqkAmount == ''){
                    document.querySelector(".chequePrint").disabled = true;
                    
                }
                else{
                if(parseFloat(ewalletamount) + parseFloat(cqkAmount) > parseFloat(aP.value) && ctid != 3){
                    if(parseFloat(cashAmount) == '' || parseFloat(cashAmount) == 0){
                        document.querySelector('#ewalletBal').innerHTML = "E-wallet Balance:" + ' ' + ewalletamount;
                        document.querySelector('#useECheque').disabled = false;
                        document.querySelector("#cashAmount").disabled = true;
                        document.querySelector(".chequePrint").disabled= false;
                        document.querySelector(".chequePrint").style.visibility = "visible";
                        
                    }
                    else{
                        document.querySelector('#useECheque').disabled = true;
                        document.querySelector(".chequePrint").disabled= false;
                        document.querySelector(".chequePrint").style.visibility = "visible";
                     
                    }
                }
                if(parseFloat(ewalletamount) + parseFloat(cqkAmount) == parseFloat(aP.value) && ctid != 3){
                 
                    document.querySelector('#ewalletBal').innerHTML = "E-wallet Balance:" + ' ' + ewalletamount;
                    document.querySelector('#useECheque').disabled = false;
                    document.querySelector("#cashAmount").disabled = true;
                    document.querySelector(".chequePrint").disabled= false;
                    document.querySelector(".chequePrint").style.visibility = "visible";
                   
                }
                else if(parseFloat(ewalletamount) + parseFloat(cqkAmount) < parseFloat(aP.value) && ctid != 3){
                    if(parseFloat(cqkAmount) + parseFloat(cashAmount) > parseFloat(aP.value)) {
                        chqdeposit = (parseFloat(cqkAmount) + parseFloat(cashAmount) ) - parseFloat(aP.value);
                        document.querySelector(".chequePrint").disabled= false;
                        document.querySelector(".chequePrint").style.visibility = "visible";
                       
                    }
                    else{ 
                        delete amountCheque.E_Wallet;
                        document.querySelector('#useECheque').disabled = true;
                        Swal.fire({
                                    title: 'Error!',
                                    text: 'Error!',
                                    icon: 'error',
                                    confirmButtonText: 'close'
                                })
                        document.querySelector(".chequePrint").style.visibility = "hidden";
                        
                    }
                }
                else if(ctid == 3){
                    document.querySelector('#useECheque').disabled = false;
                    document.querySelector(".chequePrint").style.visibility = "visible";
                   
                }
            }
            }   
        }
    }
    
    function sendCheque() {
        localStorage.removeItem('chequeDeposit');
        localStorage.removeItem('consumer');
        localStorage.removeItem('res');
        localStorage.removeItem('accountInfo');
        Swal.fire({
            title: 'Do you want to continue?',
            showDenyButton: true,
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
                var ornum1 = sessionStorage.getItem('TOR');
                var ornum2 = ornum1.replace(/-/g, '');
                // console.log(ornum2);
                tor.or_no = ornum2;
                if(typeof amount.getChange == 'undefined'){
                    amount.getChange = 'no';
                }
                delete amount.E_Wallet;
                delete amount.Cash_Amount;
                if(amt != ''){
                amount.Amount_TB_Paid = parseFloat(amt);
                }
                else{
                amount.Amount_TB_Paid = parseFloat(amount.Amount_TB_Paid)
                }
                var tellid = document.querySelector('#tellid').innerHTML;
                var teller = new Object();
                teller.user_id = tellid;
                teller.date = today;
                var xhr2 = new XMLHttpRequest();
                var checksalestransact = '{{route("check.pay.bills.CutOff")}}';
                xhr2.open('POST', checksalestransact, true);
                xhr2.setRequestHeader("Accept", "application/json");
                xhr2.setRequestHeader("Content-Type", "application/json");
                xhr2.send(JSON.stringify(teller));
    
                xhr2.onload = function() {
                    if (this.status == 404) {
                        var check = new Object();
                        check.cutoff = 0;
                        var nbBoxes = document.querySelectorAll('input[name="nonBill"]:checked');
                        var boxes = document.querySelectorAll('input[name="amountToBePaid"]:checked');
                        var temp = "";
                        var checked = boxes.length;
                        for (var x = 0; x < boxes.length; x++) {
                            temp += boxes[x].value;
                        }
                        temp = temp.split("$", checked);
                        for (let z in temp) {
                            v = temp[z].split("^");
                            var j = { "mr_id": parseInt(v[0]), "mr_billno": v[1], "mr_amount": parseFloat(v[2]), "mr_date_reg": v[3], "mr_yr_month": v[4] };
                            tempStore.push(j);
                        }
                        var tStore = [];
                        var nbStore = [];
                        for (var x = 0; x < nbBoxes.length; x++) {
                            tStore.push(nbBoxes[x].value);
                        }
                        for (let z in tStore) {
                            nbBill = tStore[z].split("^");
                            var nbilldata = { "Fee_ID": parseInt(nbBill[0]), "Fee_Amount": parseFloat(nbBill[1]), "Vatable": parseFloat(nbBill[2]), "Vat_Percent": nbBill[3], "Fees_Desc": nbBill[4] };
                            nbStore.push(nbilldata);
                        }
                        var xhr = new XMLHttpRequest();
                        var salestransact = '{{route("pay.bills")}}';
                        xhr.open('POST', salestransact, true); 
                        xhr.setRequestHeader("Accept", "application/json");
                        xhr.setRequestHeader("Content-Type", "application/json");
                        xhr.onload = function() {
                            if (this.status == 201) {
                                // document.querySelector('#chequeSelectOR').style.display="block";
                                var data = JSON.parse(this.responseText);
                               
                                var total_amount = data.Total_Amount;
                                var date_paid = data.Date_Paid;
                                var ta = data.Total_Arrears_Amount;
                                var res = new Object();
                                res.Date_Paid = date_paid;
                                res.Total_Amount = total_amount;
                                res.Total_Arrears = ta;
                                // tor1 = parseInt(sessionStorage.getItem('TOR')) + 1;
                                // sessionStorage.setItem("TOR",tor1);
                                checkLastOR();
                                torModal();
                                // Kimar start
                                // $url = '{{route("chequeOR")}}';
                                $url = '{{route("newChequeOR")}}';
                                // Kimar end
                                localStorage.setItem('chequeDeposit',JSON.stringify(chqdeposit));
                                localStorage.setItem('res', JSON.stringify(res));
                                localStorage.setItem('accountInfo', JSON.stringify(accounts));
                                localStorage.setItem('consumer', JSON.stringify(data));
                                window.open($url);
                            } else if (this.status == 422) {
                                // tor1 = parseInt(sessionStorage.getItem('TOR'));
                                // sessionStorage.setItem("TOR",tor1);
                                var data = JSON.parse(this.responseText);
                                var a = data.Message;
                                Swal.fire({
                                    title: 'Error!',
                                    text: '"' + a + '"',
                                    icon: 'error',
                                    confirmButtonText: 'close'
                                })
                                checkLastOR();
                                torModal();
                            }
                        }
                        if(ewalletamount >= 0){
                            if (nbStore.length == 0) {
                                var Consumer1 = {
                                    'Consumer': vonsumer,
                                    'Amounts': amountCheque,
                                    'TOR_No': tor,
                                    'Cheque': cheque,
                                    'PB': tempStore,
                                    'teller': check
                                };
                            } 
                            else if(tempStore.length == 0 ){
                                var Consumer1 = {
                                    'Consumer': vonsumer,
                                    'Amounts': amountCheque,
                                    'TOR_No': tor,
                                    'Cheque': cheque,
                                    'NB': nbStore,
                                    'teller': check
                                };
                            }else {
                                var Consumer1 = {
                                    'Consumer': vonsumer,
                                    'Amounts': amountCheque,
                                    'TOR_No': tor,
                                    'NB': nbStore,
                                    'Cheque': cheque,
                                    'PB': tempStore,
                                    'teller': check
                                };
    
                            }
                        }
                        else{
                            if (nbStore.length == 0) {
                                var Consumer1 = {
                                    'Consumer': vonsumer,
                                    'Amounts': amountCheque,
                                    'TOR_No': tor,
                                    'Cheque': cheque,
                                    'PB': tempStore,
                                    'ewallet_credit': ewallCredit,
                                    'teller': check
                                };
                            } 
                            else if(tempStore.length == 0 ){
                                var Consumer1 = {
                                    'Consumer': vonsumer,
                                    'Amounts': amountCheque,
                                    'TOR_No': tor,
                                    'Cheque': cheque,
                                    'NB': nbStore,
                                    'ewallet_credit': ewallCredit,
                                    'teller': check
                                };
                            }else {
                                var Consumer1 = {
                                    'Consumer': vonsumer,
                                    'Amounts': amountCheque,
                                    'TOR_No': tor,
                                    'NB': nbStore,
                                    'Cheque': cheque,
                                    'PB': tempStore,
                                    'ewallet_credit': ewallCredit,
                                    'teller': check
                                };
    
                            }
                        }
                        console.log(Consumer1)
                        var accID = Consumer1.Consumer.cm_id;
                        xhr.send(JSON.stringify(Consumer1));
                        ewall();
                        consumer = accName2;
                        setConsAcct(consumer);
                        document.querySelector('#cashAmount').value = '';
                        document.querySelector('#cashAmount').placeholder = '0.00';
                        var ap = document.getElementById("aP");
                        var cash = document.getElementById("cash");
                        ap.value = '';
                        ap.placeholder='0.00';
                        cash.value='';
                        cash.placeholder='0.00';
                        setTimeout(function(){tempStore = [];
                        nbStore = [];
                        amount = {};
                        delete accounts.change;
    
                        tor = {};
                        csh = 0;
                        nbtotalamount = 0;
                        totalPbNb = 0;
                        chqdeposit = 0;
                        amt = '';
                        forDisplay = '';
                        // localStorage.removeItem('chequeDeposit');
                        // localStorage.removeItem('consumer');
                        // localStorage.removeItem('res');
                        // localStorage.removeItem('accountInfo');
                        var toC = document.getElementById("ewalletTo");
                        if (toC.checked == true) {
                            toC.checked = false;
                            delete amount.E_Wallet;
                        }
                        // document.querySelector('#eAmount').innerHTML = " ";
                        document.querySelector('#needcash').innerHTML = '';
                        document.querySelector('#eLet').checked = false;
                        document.querySelector('#chequeNo').value = '';
                        document.querySelector('#bankAccNo').value = '';
                        document.querySelector('#bank').value = '';
                        document.querySelector('#bankAccName').value = '';
                        document.querySelector('#chequeDate').value = '';
                        document.querySelector('#chequeAmount').value = '';
                        document.querySelector('#change').value = '';
                        document.querySelector('#change').placeholder = '0.00';
                        document.querySelector('#useECheque').checked = false;
                        amountCheque = {};
                        document.querySelector('#ewalletBal').innerHTML= '';   
                        document.querySelector('#chequeA').value = '';
                        document.querySelector('.chequeDisabled').disabled=true;
                        if(document.querySelector('.collectionV1') != null){
                            document.querySelector('.collectionV1').disabled = true;
                        }
                        if(document.querySelector('.collectionV') != null){
                            document.querySelector('.collectionV').disabled = true;
                        }
                        if(document.querySelector('#aP').value == '' || document.querySelector('#aP').value == '0.00' ){
                            var t = document.querySelector('#ewalletTo');
                            var eLet = document.querySelector('#eLet');
                            var enabled = document.querySelector('#cash');
                            t.disabled=true;
                            eLet.disabled=true;
                            enabled.disabled=true;
                            document.querySelector('.chequeDisabled').disabled=true;
                        }
                        
                    },1500);
                    } else if (this.status == 200) {
                        var check = new Object();
                        check.cutoff = 1;
                        var nbBoxes = document.querySelectorAll('input[name="nonBill"]:checked');
                        var boxes = document.querySelectorAll('input[name="amountToBePaid"]:checked');
                        var temp = "";
                        var checked = boxes.length;
                        for (var x = 0; x < boxes.length; x++) {
                            temp += boxes[x].value;
                        }
                        temp = temp.split("$", checked);
                        for (let z in temp) {
                            v = temp[z].split("^");
                            var j = { "mr_id": parseInt(v[0]), "mr_billno": v[1], "mr_amount": parseFloat(v[2]), "mr_date_reg": v[3], "mr_yr_month": v[4] };
                            tempStore.push(j);
                        }
                        var tStore = [];
                        var nbStore = [];
                        for (var x = 0; x < nbBoxes.length; x++) {
                            tStore.push(nbBoxes[x].value);
                        }
                        for (let z in tStore) {
                            nbBill = tStore[z].split("^");
                            var nbilldata = { "Fee_ID": parseInt(nbBill[0]), "Fee_Amount": parseFloat(nbBill[1]), "Vatable": parseFloat(nbBill[2]), "Vat_Percent": nbBill[3], "Fees_Desc": nbBill[4] };
                            nbStore.push(nbilldata);
                        }
                        var xhr = new XMLHttpRequest();
                        var salestransact = '{{route("pay.bills")}}';
                        xhr.open('POST', salestransact, true); 
                        xhr.setRequestHeader("Accept", "application/json");
                        xhr.setRequestHeader("Content-Type", "application/json");
                        xhr.onload = function() {
                            if (this.status == 201) {
                                // document.querySelector('#chequeSelectOR').style.display="block";
                                var data = JSON.parse(this.responseText);
                               
                                var total_amount = data.Total_Amount;
                                var date_paid = data.Date_Paid;
                                var ta = data.Total_Arrears_Amount;
                                var res = new Object();
                                res.Date_Paid = date_paid;
                                res.Total_Amount = total_amount;
                                res.Total_Arrears = ta;
                                // tor1 = parseInt(sessionStorage.getItem('TOR')) + 1;
                                // sessionStorage.setItem("TOR",tor1);
                                checkLastOR();
                                torModal();
                                // Kimar start
                                // $url = '{{route("chequeOR")}}';
                                $url = '{{route("newChequeOR")}}';
                                // Kimar end
                                localStorage.setItem('chequeDeposit',JSON.stringify(chqdeposit));
                                localStorage.setItem('res', JSON.stringify(res));
                                localStorage.setItem('accountInfo', JSON.stringify(accounts));
                                localStorage.setItem('consumer', JSON.stringify(Consumer1));
                                window.open($url);
                            } else if (this.status == 422) {
                                // tor1 = parseInt(sessionStorage.getItem('TOR'));
                                // sessionStorage.setItem("TOR",tor1);
                                var data = JSON.parse(this.responseText);
                                var a = data.Message;
                                Swal.fire({
                                    title: 'Error!',
                                    text: '"' + a + '"',
                                    icon: 'error',
                                    confirmButtonText: 'close'
                                })
                                checkLastOR();
                                torModal();
                            }
                        }
                        if (typeof nbStore == 'undefined') {
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amountCheque,
                                'TOR_No': tor,
                                'Cheque': cheque,
                                'PB': tempStore,
                                'teller': check
                            };
                        }else if(typeof tempStore == 'undefined'){
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amountCheque,
                                'TOR_No': tor,
                                'Cheque': cheque,
                                'NB': nbStore,
                                'teller': check
                            };
                        } else {
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amountCheque,
                                'TOR_No': tor,
                                'NB': nbStore,
                                'Cheque': cheque,
                                'PB': tempStore,
                                'teller': check
                            };
                        }
                        var accID = Consumer1.Consumer.cm_id;
                        xhr.send(JSON.stringify(Consumer1));
                        ewall();
                        consumer = accName2;
                        setConsAcct(consumer);
                        var ap = document.getElementById("aP");
                        var cash = document.getElementById("cash");
                        document.querySelector('#cashAmount').value = '';
                        document.querySelector('#cashAmount').placeholder = '0.00';
                        ap.value = '';
                        ap.placeholder='0.00';
                        cash.value='';
                        cash.placeholder='0.00';
                        setTimeout(function(){tempStore = [];
                        nBstore = [];
                        amount = {};
                        delete accounts.change;
                        tor = {};
                        nbtotalamount = 0;
                        totalPbNb = 0;
                        csh = 0;
                        chqdeposit = 0;
                        // localStorage.removeItem('chequeDeposit');
                        // localStorage.removeItem('consumer');
                        // localStorage.removeItem('res');
                        // localStorage.removeItem('accountInfo');
                        
                        var toC = document.getElementById("ewalletTo");
                        if (toC.checked == true) {
                            toC.checked = false;
                            delete amount.E_Wallet;
                        }
                        // document.querySelector('#eAmount').innerHTML = " ";
                        document.querySelector('#needcash').innerHTML = '';
                        document.querySelector('#ewalletPay').value = '';
                        document.querySelector('#ewalletPay').placeholder = '0.00';
                        document.querySelector('#eLet').checked = false;
                        document.querySelector('#chequeNo').value = '';
                        document.querySelector('#bankAccNo').value = '';
                        document.querySelector('#bank').value = '';
                        document.querySelector('#bankAccName').value = '';
                        document.querySelector('#chequeDate').value = '';
                        document.querySelector('#chequeAmount').value = '';
                        document.querySelector('#change').value = '';
                        document.querySelector('#change').placeholder = '0.00';
                        if(document.querySelector('.collectionV1') != null){
                            document.querySelector('.collectionV1').disabled = true;
                        }
                        if(document.querySelector('.collectionV') != null){
                            document.querySelector('.collectionV').disabled = true;
                        }
                        if(document.querySelector('#aP').value == '' || document.querySelector('#aP').value == '0.00' ){
                            var t = document.querySelector('#ewalletTo');
                            var eLet = document.querySelector('#eLet');
                            var enabled = document.querySelector('#cash');
                            t.disabled=true;
                            eLet.disabled=true;
                            enabled.disabled=true;
                            document.querySelector('.chequeDisabled').disabled=true;
                        }
                    },1500);
                    }
                }
                } else if (result.isDenied) {
                    Swal.fire('Cancelled', '', 'info')
                }
            })
    }
    /* ----------------------Collection of the Day -----------------------------------------------------------*/
    function cListoftheDay() {
        modalD = document.querySelectorAll(".modal");
        modalD[0].style.display = "block";
        var tellid = document.querySelector('#tellid').innerHTML;
        var xhr = new XMLHttpRequest();
        var cttl = "{{route('collection.list',['teller'=>':teller'])}}";
        var cttl2 = cttl.replace(':teller', tellid);
        xhr.open('GET', cttl2, true);
        xhr.onload = function() {
            if (this.status == 200) {
                var data = JSON.parse(this.responseText);
                var cDay = data.Collection_List_For_The_Day;
                var output = "";
                var j = 0;
                output += '<div style="overflow-x:hidden;height:350px;width: 100%; margin-left: auto; margin-right: auto; color: white; background-color: white;">';
                output += '<table class = "EMR-table" style="text-align:left;height: 250px;">';
                output += '<tr style="border-bottom: 2px solid black;"><th>Account</th>' +
                    '<th>No.</th>' +
                    '<th>Payee</th>' +
                    '<th>T Type</th>' +
                    '<th>Fee Code</th>' +
                    '<th>Period</th>' +
                    '<th>Bill No</th>' +
                    '<th>TOR No</th>' +
                    '<th>TOR Date</th>' +
                    '<th>Amount Receipt</th>' +
                    '<th>Void</th></tr>';
                for (let i in cDay) {
                    j++;
                    output += '<tr>' +
                        '<td>' + j + '</td>' +
                        '<td>' + cDay[i].Account_No + '</td>' +
                        '<td>' + cDay[i].Payee + '</td>' +
                        '<td>' + cDay[i].Type + '</td>' +
                        '<td>' + cDay[i].Fee_code + '</td>' +
                        '<td>' + cDay[i].Period + '</td>' +
                        '<td>' + cDay[i].Bill_No + '</td>' +
                        '<td>' + cDay[i].TOR_No + '</td>' +
                        '<td>' + cDay[i].TOR_Date + '</td>' +
                        '<td>' + cDay[i].Amount_Receipt.toFixed(2) + '</td>' +
                        '<td>' + cDay[i].Void + '</td>' +
                        '</tr>';
                }
                output += '</div></table>';
            }
            document.querySelector('.cDayBody').innerHTML = output;
        }
        xhr.send()
    }
    
    function cListoftheDayClose() {
        modalD = document.querySelectorAll(".modal");
        modalD[0].style.display = "none";
    }
    
    function data_save() {
        localStorage.removeItem('consumer');
        localStorage.removeItem('res');
        localStorage.removeItem('accountInfo');
        Swal.fire({
            title: 'Do you want to continue?',
            showDenyButton: true,
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
                var ornum1 = sessionStorage.getItem('TOR');
                var ornum2 = ornum1.replace(/-/g, '');
                // console.log(ornum2);
                tor.or_no = ornum2;
                if(typeof amount.getChange == 'undefined'){
                    amount.getChange = 'no';
                }
                var tellid = document.querySelector('#tellid').innerHTML;
                var teller = new Object();
                teller.user_id = tellid;
                teller.date = today;
                var xhr2 = new XMLHttpRequest();
                var checksalestransact = '{{route("check.pay.bills.CutOff")}}';
                xhr2.open('POST', checksalestransact, true);
                xhr2.setRequestHeader("Accept", "application/json");
                xhr2.setRequestHeader("Content-Type", "application/json");
                xhr2.send(JSON.stringify(teller));
                xhr2.onload = function() {
                    if (this.status == 404) {
                        var check = new Object();
                        check.cutoff = 0;
                        var nbBoxes = document.querySelectorAll('input[name="nonBill"]:checked');
                        var boxes = document.querySelectorAll('input[name="amountToBePaid"]:checked');
                        var temp = "";
                        var checked = boxes.length;
                        for (var x = 0; x < boxes.length; x++) {
                            temp += boxes[x].value;
                        }
                        temp = temp.split("$", checked);
                        for (let z in temp) {
                            v = temp[z].split("^");
                            var j = { "mr_id": parseInt(v[0]), "mr_billno": v[1], "mr_amount": parseFloat(v[2]), "mr_date_reg": v[3], "mr_yr_month": v[4] };
                            tempStore.push(j);
                        }
                        var tStore = [];
                        var nbStore = [];
                        for (var x = 0; x < nbBoxes.length; x++) {
                            tStore.push(nbBoxes[x].value);
                        }
                        for (let z in tStore) {
                            nbBill = tStore[z].split("^");
                            var nbilldata = { "Fee_ID": parseInt(nbBill[0]), "Fee_Amount": parseFloat(nbBill[1]), "Vatable": parseFloat(nbBill[2]), "Vat_Percent": nbBill[3], "Fees_Desc": nbBill[4] };
                            nbStore.push(nbilldata);
                        }
                        var xhr = new XMLHttpRequest();
                        var salestransact = '{{route("pay.bills")}}';
                        xhr.open('POST', salestransact, true);
                        xhr.setRequestHeader("Accept", "application/json");
                        xhr.setRequestHeader("Content-Type", "application/json");
                        xhr.onload = function() {
                            if (this.status == 201) {
                                var data = JSON.parse(this.responseText);
                                var total_amount = data.Total_Amount;
                                var date_paid = data.Date_Paid;
                                var ta = data.Total_Arrears_Amount;
                                var res = new Object();
                                res.Date_Paid = date_paid;
                                res.Total_Amount = total_amount;
                                res.Total_Arrears = ta;
                                // tor1 = parseInt(sessionStorage.getItem('TOR')) + 1;
                                // sessionStorage.setItem("TOR",tor1);
                                checkLastOR();
                                torModal();
                                $url = '{{route("ORsave")}}';
                                localStorage.setItem('res', JSON.stringify(res));
                                localStorage.setItem('accountInfo', JSON.stringify(accounts));
                                localStorage.setItem('consumer', JSON.stringify(data));
                                window.open($url);
                            } else if (this.status == 422) {
                                // tor1 = parseInt(sessionStorage.getItem('TOR'));
                                // sessionStorage.setItem("TOR",tor1);
                                var data = JSON.parse(this.responseText);
                                var a = data.Message;
                                Swal.fire({
                                    title: 'Error!',
                                    text: '"' + a + '"',
                                    icon: 'error',
                                    confirmButtonText: 'close'
                                })
                                checkLastOR();
                                torModal();
                            }
                        }
                        if(ewalletamount >= 0){
                        if (nbStore.length == 0) {
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amount,
                                'TOR_No': tor,
                                'PB': tempStore,
                                'teller': check
                            };
                        } else if(tempStore.length == 0 ){
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amount,
                                'TOR_No': tor,
                                'NB': nbStore,
                                'teller': check
                            };
                        }else {
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amount,
                                'TOR_No': tor,
                                'NB': nbStore,
                                'PB': tempStore,
                                'teller': check
                            };
    
                        }}
                        else{
                            if (nbStore.length == 0) {
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amount,
                                'TOR_No': tor,
                                'PB': tempStore,
                                'ewallet_credit': ewallCredit,
                                'teller': check
                            };
                            } else if(tempStore.length == 0 ){
                                var Consumer1 = {
                                    'Consumer': vonsumer,
                                    'Amounts': amount,
                                    'TOR_No': tor,
                                    'NB': nbStore,
                                    'ewallet_credit': ewallCredit,
                                    'teller': check
                                };
                            }else {
                                var Consumer1 = {
                                    'Consumer': vonsumer,
                                    'Amounts': amount,
                                    'TOR_No': tor,
                                    'NB': nbStore,
                                    'PB': tempStore,
                                    'ewallet_credit': ewallCredit,
                                    'teller': check
                                };
    
                            }
                        }
                        var accID = Consumer1.Consumer.cm_id;
                        xhr.send(JSON.stringify(Consumer1));
                        
                        consumer = accName2;
                       
                        var ap = document.getElementById("aP");
                        var cash = document.getElementById("cash");
                        ap.value = 0;
                        if(ap.value == 0){
                            ap.value='';
                            ap.placeholder = '0.00';
                        }
                        cash.value='';
                        cash.placeholder='0.00';
                        setTimeout(function(){tempStore = [];
                        nBstore = [];
                        amount = {};
                        delete accounts.change;
                        tor = {};
                        csh = 0;
                        nbtotalamount = 0;
                        totalPbNb = 0;
                        amt = '';
                        forDisplay = '';
                        // localStorage.removeItem('consumer');
                        // localStorage.removeItem('res');
                        // localStorage.removeItem('accountInfo');
                        var toC = document.getElementById("ewalletTo");
                        if (toC.checked == true) {
                            toC.checked = false;
                            delete amount.E_Wallet;
                        }
                        // document.querySelector('#eAmount').innerHTML = " ";
                        document.querySelector('#needcash').innerHTML = '';
                        document.querySelector('#ewalletPay').value = '';
                        document.querySelector('#ewalletPay').placeholder = '0.00';
                        document.querySelector('#eLet').checked = false;
                        document.querySelector('#change').value = '';
                        document.querySelector('#change').placeholder = '0.00';
                        setConsAcct(consumer);
                        ewall();
                        if(document.querySelector('.collectionV1') != null){
                            document.querySelector('.collectionV1').disabled = true;
                        }
                        if(document.querySelector('.collectionV') != null){
                            document.querySelector('.collectionV').disabled = true;
                        }
                        if(document.querySelector('#aP').value == '' || document.querySelector('#aP').value == '0.00' ){
                            var t = document.querySelector('#ewalletTo');
                            var eLet = document.querySelector('#eLet');
                            var enabled = document.querySelector('#cash');
                            t.disabled=true;
                            eLet.disabled=true;
                            enabled.disabled=true;
                            document.querySelector('.chequeDisabled').disabled=true;
                        }
                        
                    },1500);
                    } else if (this.status == 200) {
                        var check = new Object();
                        check.cutoff = 1;
                        var nbBoxes = document.querySelectorAll('input[name="nonBill"]:checked');
                        var boxes = document.querySelectorAll('input[name="amountToBePaid"]:checked');
                        var temp = "";
                        var checked = boxes.length;
                        for (var x = 0; x < boxes.length; x++) {
                            temp += boxes[x].value;
                        }
                        temp = temp.split("$", checked);
                        for (let z in temp) {
                            v = temp[z].split("^");
                            var j = { "mr_id": parseInt(v[0]), "mr_billno": v[1], "mr_amount": parseFloat(v[2]), "mr_date_reg": v[3], "mr_yr_month": v[4] };
                            tempStore.push(j);
                        }
                        var tStore = [];
                        var nbStore = [];
                        for (var x = 0; x < nbBoxes.length; x++) {
                            tStore.push(nbBoxes[x].value);
                        }
                        for (let z in tStore) {
                            nbBill = tStore[z].split("^");
                            var nbilldata = { "Fee_ID": parseInt(nbBill[0]), "Fee_Amount": parseFloat(nbBill[1]), "Vatable": parseFloat(nbBill[2]), "Vat_Percent": nbBill[3], "Fees_Desc": nbBill[4] };
                            nbStore.push(nbilldata);
                        }
                        var xhr = new XMLHttpRequest();
                        var salestransact = '{{route("pay.bills")}}';
                        xhr.open('POST', salestransact, true);                  
                        xhr.setRequestHeader("Accept", "application/json");
                        xhr.setRequestHeader("Content-Type", "application/json");
                        xhr.onload = function() {
                            if (this.status == 201) {
                                var data = JSON.parse(this.responseText);
                                var total_amount = data.Total_Amount;
                                var date_paid = data.Date_Paid;
                                var ta = data.Total_Arrears_Amount;
                                var res = new Object();
                                res.Date_Paid = date_paid;
                                res.Total_Amount = total_amount;
                                res.Total_Arrears = ta;
                                // tor1 = parseInt(sessionStorage.getItem('TOR')) + 1;
                                // sessionStorage.setItem("TOR",tor1);
                                checkLastOR();
                                torModal();
                                $url = '{{route("ORsave")}}';
                                localStorage.setItem('res', JSON.stringify(res));
                                localStorage.setItem('accountInfo', JSON.stringify(accounts));
                                localStorage.setItem('consumer', JSON.stringify(data));
                                window.open($url);
                            } else if (this.status == 422) {
                                // tor1 = parseInt(sessionStorage.getItem('TOR'));
                                // sessionStorage.setItem("TOR",tor1);
                                var data = JSON.parse(this.responseText);
                                var a = data.Message;
                                Swal.fire({
                                    title: 'Error!',
                                    text: '"' + a + '"',
                                    icon: 'error',
                                    confirmButtonText: 'close'
                                })
                                checkLastOR();
                                torModal();
                            }
                        }
                        if(ewalletamount >= 0){
                        if (nbStore.length == 0) {
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amount,
                                'TOR_No': tor,
                                'PB': tempStore,
                                'teller': check
                            };
                        } else if(tempStore.length == 0 ){
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amount,
                                'TOR_No': tor,
                                'NB': nbStore,
                                'teller': check
                            };
                        }else {
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amount,
                                'TOR_No': tor,
                                'NB': nbStore,
                                'PB': tempStore,
                                'teller': check
                            };
    
                        }}
                        else{
                            if (nbStore.length == 0) {
                            var Consumer1 = {
                                'Consumer': vonsumer,
                                'Amounts': amount,
                                'TOR_No': tor,
                                'PB': tempStore,
                                'ewallet_credit': ewallCredit,
                                'teller': check
                            };
                            } else if(tempStore.length == 0 ){
                                var Consumer1 = {
                                    'Consumer': vonsumer,
                                    'Amounts': amount,
                                    'TOR_No': tor,
                                    'NB': nbStore,
                                    'ewallet_credit': ewallCredit,
                                    'teller': check
                                };
                            }else {
                                var Consumer1 = {
                                    'Consumer': vonsumer,
                                    'Amounts': amount,
                                    'TOR_No': tor,
                                    'NB': nbStore,
                                    'PB': tempStore,
                                    'ewallet_credit': ewallCredit,
                                    'teller': check
                                };
    
                            }
                        }
                        var accID = Consumer1.Consumer.cm_id;
                        xhr.send(JSON.stringify(Consumer1));
                        
                        consumer = accName2;
                       
                        var ap = document.getElementById("aP");
                        var cash = document.getElementById("cash");
                        ap.value = 0;
                        if(ap.value == 0){
                            ap.value='';
                            ap.placeholder = '0.00';
                        }
                        cash.value='';
                        cash.placeholder='0.00';
                        setTimeout(function(){tempStore = [];
                        nBstore = [];
                        amount = {};
                        delete accounts.change;
                        tor = {};
                        csh = 0;
                        nbtotalamount = 0;
                        totalPbNb = 0;
                        // localStorage.removeItem('consumer');
                        // localStorage.removeItem('res');
                        // localStorage.removeItem('accountInfo');
                        var toC = document.getElementById("ewalletTo");
                        if (toC.checked == true) {
                            toC.checked = false;
                            delete amount.E_Wallet;
                        }
                        // document.querySelector('#eAmount').innerHTML = " ";
                        document.querySelector('#needcash').innerHTML = '';
                        document.querySelector('#ewalletPay').value = '';
                        document.querySelector('#ewalletPay').placeholder = '0.00';
                        document.querySelector('#eLet').checked = false;
                        document.querySelector('#change').value = '';
                        document.querySelector('#change').placeholder = '0.00';
                        setConsAcct(consumer);
                        ewall();
                        if(document.querySelector('.collectionV1') != null){
                            document.querySelector('.collectionV1').disabled = true;
                        }
                        if(document.querySelector('.collectionV') != null){
                            document.querySelector('.collectionV').disabled = true;
                        }
                        if(document.querySelector('#aP').value == '' || document.querySelector('#aP').value == '0.00' ){
                            var t = document.querySelector('#ewalletTo');
                            var eLet = document.querySelector('#eLet');
                            var enabled = document.querySelector('#cash');
                            t.disabled=true;
                            eLet.disabled=true;
                            enabled.disabled=true;
                            document.querySelector('.chequeDisabled').disabled=true;
                        }
                        
                    },1500);
                    }
                }
            } else if (result.isDenied) {
                Swal.fire('Cancelled', '', 'info')
            }
        })
    }
    
    function addToConsent(){
        Swal.fire({
            title: 'Do you want to continue?',
            showDenyButton: true,
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
        var dataToConsent = new Object();
        var userId = "{{Auth::user()->user_id}}";
        dataToConsent.cons_id= vonsumer.cm_id;
        dataToConsent.user_id = userId;
    
        var xhr2 = new XMLHttpRequest();
        var route = "{{route('consumer.consent.entry')}}";
        xhr2.open('POST', route, true);
        xhr2.setRequestHeader("Accept", "application/json");
        xhr2.setRequestHeader("Content-Type", "application/json");
        xhr2.send(JSON.stringify(dataToConsent));
    
        xhr2.onload = function() {
            if (this.status == 200) {
                 var dats = JSON.parse(this.responseText);
    
                 Swal.fire({
                    title: 'Success!',
                    text: '"' + dats.info + '"',
                    icon: 'success',
                    confirmButtonText: 'close'
                })   
            }
            else if(this.status == 422){
                var dats = JSON.parse(this.responseText);
                Swal.fire({
                    title: 'Error!',
                    text: '"' + dats.info + '"',
                    icon: 'error',
                    confirmButtonText: 'close'
                })  
                }
            }
            }
            else if (result.isDenied) {
                    Swal.fire('Cancelled', '', 'info')
                }
        });   
    
    }
    function consentList(){
        document.querySelector('#consentList').style.display="block";
        var userId = "{{Auth::user()->user_id}}";
        var xhr = new XMLHttpRequest();
        var route = "{{route('list.consumer.consent',['id'=>':id'])}}";
        var route2 = route.replace(':id', userId);
        xhr.open('GET', route2, true);
        xhr.send();
        xhr.onload = function() {
            if (this.status == 200) {
                var no = 0;
               var data = JSON.parse(this.responseText);
               var output = '';
               output += '<table class="table"';
               output += '<tr>' +
               '<th>No.</th>' + 
               '<th>Account Number</th>' +
               '<th>Name</th>' +
               '<th>Bill Period To Pay</th>' +
               '<th>Amount to Pay</th>' +
               '<th>Ewallet Balance</th>' +
               '</tr>';
               for(let i in data.info){
                no++;
                output += '<tr>';
                output += '<td>' + (parseInt(no)) + '</td>' +
                '<td>' + data.info[i].account_no + '</td>' +
                '<td>' + data.info[i].name + '</td>' +
                '<td>' + data.info[i].bill_period_to_pay + '</td>' +
                '<td>' + data.info[i].amount_to_pay + '</td>' +
                '<td>' + data.info[i].remaining_ewallet + '</td>' +
                '</tr>';
               }
               output += '</table>';
               document.querySelector('.consentListBody').innerHTML = output;
            }
        }
    }
    function consentListClose(){
        document.querySelector('#consentList').style.display="none";
    }
    /* -------------------- End of the Collection of the Day --------------------------------------------------*/
    </script>