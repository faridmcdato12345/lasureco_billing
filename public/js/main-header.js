$(document).ready(function(){
    let mainMenu = window.location.pathname.split('/')
    $('.main_menus').find('.main-'+mainMenu[2]).addClass('active-main-menu')
})