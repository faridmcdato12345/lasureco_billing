<style>
    .consumer-container{
        color:#000000;
        width: 95%;
        opacity: 0.91;
    }
    .consumer-container table.dataTable td{
        font-size: 12px;
    }
    .form-inline{
        display: block!important;
    }
    .table.dataTable.no-footer{
        width: 100%!important;
    }
    .paginate_button a:hover{
        color: #fff;
    }
    .paginate_button:hover{
        border: 1px solid #585858 !important;
    }
    .paginate_button a{
        color: #000000 !important;
    }
    table.dataTable tbody tr{
        background-color: transparent!important;
    }
    #datatable_wrapper .row{
        margin-top: 10px;
    }
    .action-bttn{
        margin-right:2px;
    }
    .add-button{
        margin-left:10px;
    }
    #createConsumer,#createConsumerRoute,#mainConsumer,#nearestConsumer,#metered,#change_meter{
        padding-top: 0.5%!important;
    }
    .consumer-info-button button{
        margin-bottom: 1px;
    }
    #createConsumer img{
        width:100%;
        margin-bottom: 2px;
    }
    #createConsumer img:hover{
        content: 'Click to uploade image';
        cursor: pointer;
    }
    .badge.badge-secondary{
        position: absolute;
        display: block;
        width: 56%;
        z-index: 1000;
        left: 71px;
        top: 135px;
    }
    #createConsumer .form-group,#createConsumer label{
        margin-bottom: 0.1rem;
    }

    #addUser .modal-content{
        width: 40%!important;
    }
    #addUser .modal-body{
        padding: 2% 16px!important;
    }
    .search_permission_result{
        max-height: 280px;
        height: 280px;
        background: #f9f9f9;
        overflow-y: scroll;
        margin-top: 10px;
    }
    #permissions{
        width:100%;
        background-image: url("{{asset('img/searchicon.png')}}");
        background-position: 10px 12px;
        background-repeat: no-repeat;
        padding: 12px 20px 12px 40px;
        border: 1px solid #ddd;
    }
    #addUser ul{
        padding-left: 0;
        padding-top: 1%;
    }
    .search_permission{
        max-height: 400px;
        height: 400px;
        overflow-y: scroll;
        margin-bottom: 6px;
    }
    #addUser input[type=checkbox]{
        width: 120%!important;
    }
    #showRole ul,#editRole ul{
        columns:3;
        -webkit-columns: 3;
        -moz-columns: 3;
    }
    #showRole ul li, #editRole ul li{
        list-style-type: none;
    }
    #createConsumer .modal-content{
        width:90%;
    }
    #createConsumerRoute .modal-content{
        width: 80%;
    }
    #showRole input[type=checkbox],#editRole input[type=checkbox]{
        width: 10%;
    }
    label.badge{
        display:inline-block!important;
        margin-right: 3px;
    }
    #createConsumer input[type=text]{
        text-align: center;
    }
    #createConsumer input[type=text]:disabled{
        cursor: pointer;
    }
    .required_field{
        border-color: red;
    }
    #create_meter{
        z-index: 1000000!important;
        padding-top:10px;
    }
    .action-bttn {
        font-size: 12px;
    }
    #create_meter .row{
        margin-bottom: 5px;
    }
    #create_meter .bottom-row .col-md-4 {
        border-right: groove;
    }
    #create_meter .bottom-row label{
        width:100%;
        text-align: center;
    }
    #create_meter .multi-title{
        background-color: #176CBF;
        color: #fff;
        font-weight: bold;
        padding: 5px;
    }
    #show_meter_brand{
        z-index: 1000001!important;
    }
    #create_brand{
        z-index: 1000002!important;
    }
    #change_meter_remarks{        
        width: 50%;
        padding-top: 0px;
        left: 25rem;
    }
    .print-button {
        background-color: royalblue;
        color: white;
        height: 37px;
        width: 7%;
        margin-left: 0.5%;
    }
</style>