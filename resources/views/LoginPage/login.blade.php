<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<style>
.main{
    display: flex;
    height: 315px;
    max-width: 800px;
    background-color: white;
    top: 25%;
    left: 25%;
    position: absolute;
}

.sub1{
    height: 40vh;
    width: 70%;
    margin: 5px;
    align-items: center;
    display: flex;
}
.sub2{
    height: 290px;
    width: 70%;
    margin: 5px;
}
.image1{
    width: 90%;
    margin: 12px;
}
.formlogin{

    align-items: center;
    display: block;
    margin: 10px;
    margin-top: 15px;
}
.formlogin input[type = "text"], input[type = "password"]{
    border: 0;
    border-bottom: 2px solid rgb(23, 108, 191);
    padding: 10px;
    margin-bottom: 10px;
}
.formlogin label{
    display:block;
}
</style>
</head>
<body>
    <header>
        <table border="0" class="headerTable">
            <tr>
                <td style="width: 75px;" rowspan="6">
                    <img id="logo" src="/img/logo.png">
                </td>
            </tr>
            <tr>
                <td>
                    <h3 class="mainheadertxt"> LASURECO ELECTRIC BILLING SYSTEM</h3>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="headertxt"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </p>
                </td>
            </tr>
            <tr>
                <td>

                    <p class="headertxt2"> Brgy Gadongan, Marawi City </p>
                </td>
                <td colspan="3">

                </td>
            </tr>
        </table>

    </header>
    <section>

        <table border="0" class="bodytable">
            <tr>
                <td class="main-td">
                <div class="main">
            <div class="sub1">
                <img class="image1" src="/img/1.png">
            </div>
        <div class="sub2">

            <Form class="formlogin">
                <h3 style="color:black;">Sign In</h3>
                <label style="color:black;" for="">Username</label>
                <input id="userN" type="text" placeholder="Username">
                <label style="color:black;" for="">Password</label>
                <input id="pass" type="text" placeholder="Password">
                <input style = "width:20%;" type="submit" value="Login">
            </Form>
        </div>
    </div>
                </form>
                </td>
            </tr>
        </table>
    </section>


    <script>
    function getDateTime() {
        var now     = new Date();
        var year    = now.getFullYear();
        var month   = now.getMonth()+1;
        var day     = now.getDate();
        var hour    = now.getHours();
        var minute  = now.getMinutes();
        var second  = now.getSeconds();
        if(month.toString().length == 1) {
             month = '0'+month;
        }
        if(day.toString().length == 1) {
             day = '0'+day;
        }
        if(hour.toString().length == 1) {
             hour = '0'+hour;
        }
        if(minute.toString().length == 1) {
             minute = '0'+minute;
        }
        if(second.toString().length == 1) {
             second = '0'+second;
        }
        var dateTime =  year+'/'+month+'/'+day+' '+hour+':'+minute+':'+second;
         return dateTime;
    }

    // example usage: realtime clock
    setInterval(function(){
        currentTime = getDateTime();
        document.getElementById("digital-clock").innerHTML = currentTime;
    }, 1000);
    </script>



