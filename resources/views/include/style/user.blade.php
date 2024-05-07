<style>
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
    #addUser{
        padding-top: 2%!important;
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
    #showRole .modal-content,#editRole .modal-content{
        width:90%;
    }
    #showRole input[type=checkbox],#editRole input[type=checkbox]{
        width: 10%;
    }
    label.badge{
        display:inline-block!important;
        margin-right: 3px;
    }
    .remove-role{
        background-color: red;
        border-radius: 50px;
        width: fit-content;
        padding: 0.09px 3px;
        color: #fff;
        font-size: 8px;
        font-weight: bold;
        position: absolute;
        top: 0px;
        cursor: pointer;
        right: 0;
    }
    .remove-role:hover{
        background-color: #5c1823;
    }
    #role-container{
        width: fit-content;
        position: relative;
    }
</style>