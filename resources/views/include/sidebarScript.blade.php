<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var pathname = window.location.pathname;
    let pathArray = pathname.split('/');
    if(pathArray.length > 2){
        $('.main-menu').toggle()
        if($('.back-'+pathArray[2]).length == 0){
            $('.submenu-'+pathArray[2]).prepend("<button class='back "+pathArray[2]+" back-"+pathArray[2]+"'><i class='fas fa-angle-double-left'></i></button>")
        }
        $('.submenu-'+pathArray[2]).toggle()
        $('button:contains('+pathArray[3].toUpperCase().replace('_',' ')+')').addClass('open')
        $('button:contains('+pathArray[3].toUpperCase().replace('_',' ')+')').next('div').slideDown()
    }
    $(document).on('click','.dropdown-btn',function(){
        if($(this).hasClass('open')){
            $(this).next('div').slideUp()
            $(this).removeClass('open')
        }
        else{
            if($('.dropdown-btn.open').length > 0){
                $('.dropdown-btn.open').next('div').slideUp()
                $('.dropdown-btn.open').removeClass('open')
            }
                $(this).addClass('open')
                $(this).next('div').slideDown()

        }
    })
    $('.main-menu div').on('click',function(){
        let x = $(this)[0].innerText
        let y = x.toLowerCase()
        if($('.back-'+y).length == 0){
            $('.submenu-'+y).prepend("<button class='back "+y+" back-"+y+"'><i class='fas fa-angle-double-left'></i></button>")
        }
        $('.main-menu').slideUp()
        $('.submenu-'+y).toggle()
    })
    $(document).on('click','.back',function(){
        let y = $(this)[0].classList[1]
        $('.submenu-'+y).toggle()
        $('.main-menu').slideDown()
    }) 
    var btn = document.querySelectorAll("button.modal-button");
    var userbtn = document.getElementsByClassName("profPic");
        
    userbtn[0].onclick = function(e){
        modal = document.querySelector(e.target.getAttribute("href"));
        modal.style.display = "block";

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }

    var closure = document.querySelectorAll('.closes');
 
    for (var i = 0; i < btn.length; i++) {
        btn[i].onclick = function(e) {
            modal = document.querySelector(e.target.getAttribute("href"));
            modal.style.display = "block";
        }
    }
    for (var i = 0; i < btn.length; i++)  {
        closure[i].onclick = function(e) {
            modal = document.querySelector(e.target.getAttribute("href"));
            modal.style.display = "none";
        }
    }
    var inp = document.querySelectorAll("input.input-Txt");
    var closure1 = document.querySelectorAll('.closes');
    for (var i = 0; i < inp.length; i++) {
        inp[i].onclick = function(e) {
            modal = document.querySelector(e.target.getAttribute("href"));
            modal.style.display = "block";
        }
    }
    for (var i = 0; i < closure.length; i++)  {
        closure1[i].onclick = function(e) {
            modal = document.querySelector(e.target.getAttribute("href"));
            modal.style.display = "none";
            document.querySelector('#errorMessage').style.display = "none";
            document.querySelector('.addErrorMessage').style.display = "none";
            document.querySelector('.addErrorMessageTown').style.display = "none";
            document.querySelector('.addErrorMessageRoute').style.display = "none";
            document.querySelector('.areaInput').style.borderColor = "#ddd";
            document.querySelector('.towninput').style.borderColor = "#ddd";
            document.querySelector('.routeDesc').style.borderColor = "#ddd";
            document.querySelector('.routeCode').style.borderColor = "#ddd";
            document.querySelector('.areaInput').value = "";
            document.querySelector('.towninput').value = "";
            document.querySelector('.routeDesc').value = "";
            document.querySelector('.routeCode').value = "";
        }
    }
    $(document).on('click','.change-password-user',function(){
        var npass = $('#n_pass').val()
        var c_npass = $('#c_n_pass').val()
        if(npass.localeCompare(c_npass) != 0){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'New password is not equal to confirm password!',
            })
        }
        else{
            var userA = "{{Auth::user()->user_id}}"
            var url = "{{route('api.users.update.password',':id')}}"
            var urlUpdate = url.replace(':id',userA)
            $.ajax({
                url: urlUpdate,
                dataType: "json",
                method: "patch",
                data: {
                    password: $('#c_pass').val(),
                    new_password: $('#n_pass').val(),
                },
                success: function(data){
                    $.ajax
                    ({
                        method: 'POST',
                        url: "{{route('logout')}}",
                        success: function()
                        {
                            location.reload();
                        }
                    });
                },
                error: function(error){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: error.responseJSON.message,
                    })
                }
            })
        }
    })
    $('#change_pass').on('click',function(){
        $('#change_password').css('display','block')
        $('#user').css('display','none')
    })
</script>