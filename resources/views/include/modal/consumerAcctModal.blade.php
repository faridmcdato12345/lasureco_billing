<style>
    input {
        cursor: pointer;
    }
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #consAcctTbl {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #consAcctTbl td{
        height: 45px;
        border-bottom: 1px #ddd solid;
        cursor: pointer;
        padding: 15px;
    }
    #consAcctTbl th{
        height: 45px;
        border-bottom: 1px #ddd solid;
        background-color: #5B9BD5;
        color: white;
    }
    #consAcctTbl tr:hover{
        transition: background 1s;
        background: gray;
    }
    .consAcctDiv {
        padding-left: 15px;
        padding-right: 15px;
        margin: 15px;
    }
    #paginateCons {
        width: 100%;
        margin: auto;
        margin-top: 0.5%;
    }
    #paginateCons button {
        background-color: royalblue;
        border-radius: 3px;
        height: 35px;
        width: 100%; 
    }
    #paginateCons input {
        margin: auto;
    }
</style>

<div id="consAcct" class="modal">
    <div class="modal-content" style="margin-top: 10px; width: 70%; height: auto;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Consumer Account Lookup</h3>
            <span href = "#consAcct" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="row" style="width: 95%; margin: auto">
                <input type="text" class="form-control input-sm" id="searchAccount" placeholder="Search Consumer Account" style="cursor: pointer;">
            </div>
            <div class="consAcctDiv" style="overflow-y:scroll"> </div>
        </div>
    </div>
</div>


<script>
    var page = 1;
    function showConsumerAcct(){
        var newPage = page;
        var route = "{{route('select.consumer.account','?page=')}}"+newPage;
        var xhr = new XMLHttpRequest();
        
		xhr.open('GET',route.replace(':par',newPage),true);
		// xhr.open('GET','http://10.12.10.100:8082/api/v1/employee/meter_reader',true);
        xhr.onload = function(){
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
                var output = " ";
                output += '<table style="text-align:left;width:100%;" border=1" id="consAcctTbl">';
                output +='<tr> <th>Account No.</th> <th> Consumer Name</th> </tr>';

                var lastPage = data.last_page;
                // console.log(data);

                for(let i in data.data){
                    var consID = data.data[i].cm_id;
                    var acctNo = data.data[i].cm_account_no;
                    var seqNo = data.data[i].cm_seq_no;
                    console.log(data.data[i]);
                    var name = data.data[i].cm_full_name;
                    var address = data.data[i].cm_address;
                    var acct = acctNo.toString();
                    var a = acct.slice(0,2);
                    var b = acct.slice(2,6);
                    var c = acct.slice(6,10);
                    output += '<tr onclick="setConsAcct(this)" class="'+seqNo+'" id="'+ consID +'" consName="'+name+'" accNo="' + acctNo + '">'+
                                '<td>' + a+'-'+b+'-'+c + '</td> <td>'+ name + ' <br>' +address +  '</td>';
                    output += '</tr>';
                }
                output += '</table>';

                output += "<table id='paginateCons'> <tr>";
                if(page == 1) {
                    output += "<td> <button id='" + newPage + "' class='prev' button='prev' onclick='paginateCons(this)' disabled> Prev </button> </td>";
                } else {
                    output += "<td> <button id='" + newPage + "' class='prev' button='prev' onclick='paginateCons(this)' enabled> Prev </button> </td>";
                } 
                output += "<td> <input type='number' value='" + newPage + "' readonly> </td>";
                if(page == lastPage) {
                    output += "<td> <button id='" + newPage + "' class='next' button='next' onclick='paginateCons(this)' disabled> Next </button> </td> </tr>";
                } else{
                    output += "<td> <button id='" + newPage + "' class='next' button='next' onclick='paginateCons(this)' enabled> Next </button> </td> </tr>";  
                }
                output += "</table>";
			}
            
            document.querySelector('.consAcctDiv').innerHTML = output;
            document.querySelector('#consAcct').style.display = "block";
            document.querySelector('#searchAccount').focus();

		}
        xhr.send();
    }



    var searchAccount = document.querySelector("#searchAccount");
    searchAccount.addEventListener("change", function(){
        var xhr = new XMLHttpRequest();
        if(searchAccount.value !== ""){
            var route = "{{route('search.consumer.name.account',['request'=>':par'])}}"
            xhr.open('GET', route.replace(':par', searchAccount.value), true);
            xhr.send();
            xhr.onload = function(){
                if(this.status == 200){
                    var data = JSON.parse(this.responseText);
                    console.log(data);
                    if(data != ""){
                        var output = '<table style="text-align:left;width:100%;" border=1" id="consAcctTbl">';
                        output +='<tr> <th>Account No.</th> <th style="color:white"> Consumer Name</th> </tr>';
                        
                        for(var a in data){
                            var consID = data[a].cm_id;
                            var seqNo = data[a].cm_seq_no;
                            var acctNo = data[a].cm_account_no;
                            var name = data[a].cm_full_name;
                            var address = data[a].cm_address;
                            var acct = acctNo.toString();
                            var a = acct.slice(0,2);
                            var b = acct.slice(2,6);
                            var c = acct.slice(6,10); 
                            output += '<tr onclick="setConsAcct(this)" class="'+seqNo+'" id='+ consID +' consName='+name+' accNo="' + acctNo + '">'+
                                        '<td>'+ a+'-'+b+'-'+c + '</td> <td>'+ name + ' <br>' +address +  '</td>';
                            output += '</tr>';
                        }

                        output += "</table>";
                        document.querySelector('.consAcctDiv').innerHTML= output;
                        document.querySelector('.consAcctDiv').style.height = "auto";
                        document.querySelector('.consAcctDiv').style.borderBottom = "1px solid #ddd";
                        document.querySelector('.consAcctDiv').style.overflowY  = "scroll";
                    } else {
                        var output = "<table style='color: black; margin: auto;'> <br> <br>"; 
                        output += "<tr> <td style='font-size: 25px; color: gray;'> No Consumer Account found! </td> </tr> </table>"; 
                        document.querySelector('.consAcctDiv').innerHTML= output;
                    }
                }
            }
        } else {
            showConsumerAcct(); 
        }
    })

    function paginateCons(e){
        var pages = e.id;
        var button = e.getAttribute('button');

        if(button == "next"){
            page += 1;
            document.querySelector(".prev").disabled = false;
            showConsumerAcct();
        } else if(button == "prev"){
            page = page - 1;
            showConsumerAcct();
        }
    }


    // function setConsAcct(rowSelected){
	//     var hide = document.querySelector('#consAcct');
    //     hide.style.display="none";
    //     document.getElementById('#mR').value = rowSelected.childNodes[0].innerHTML;
    //     // document.getElementById('#mR').value = rowSelected.getAttribute('meterNames');
    //     storage.consAcct = rowSelected.id;
    // }

</script>
</html>