<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sketch File</title>
    <link rel="shortcut icon" href="/img/logo.png">
    <link rel="stylesheet" href="{{asset('css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/buttons.bootstrap.min.css')}}">
</head>
<body>
    <div>      
    @foreach ($data as $user)
        <img  width="1050" height="550" src="{{asset('images')}}/{{$user->sketch_path}}" alt="">
    @endforeach
    </div>
</body>
</html>
<script>
    window.print();
</script>