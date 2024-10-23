<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Betmaster</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('public')}}/styles/styles2.css">
    <script src="https://kit.fontawesome.com/2564be365d.js" crossorigin="anonymous"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    @php
    $success = Session::get('success');
    $error = Session::get('error');
    @endphp

    <div class="container-fluid" style="background-color: grey;">
        <nav class="navbar navbar-dark header d-flex justify-content-between align-items-center p-2">
            <div class="nametext">Welcome,<br> {{ $user['name'] ??"Not Available"}} </div>
            <a href="/"><img src="{{asset('images')}}/logo.png" class="nav-logo mx-auto" alt="Logo" height="50" width="50"></a>
            <div class="wallet-container">
                <a href="/accountLedger"><i class="fa-solid fa-wallet"></i>
                    <span class="navbar-text">Balance: {{$user['wallet']??0}}</span></a>
            </div>
            @if(Auth::check())
            <div class="wallet-container">
                <a href="/logout"><i class="fa-solid fa-right-from-bracket"></i>
                    <span class="navbar-text">Logout({{$user['name']}})</span></a>
            </div>
            @else
            <div class="wallet-container">
                <a href="/login"><i class="fa-solid fa-right-to-bracket"></i>
                    <span class="navbar-text">Login</span></a>
            </div>
            @endif
            
        </nav>
    </div>

    @yield('content')
    <div class="container-fluid  footer py-3">
        <div class="row text-center">
            <div class="col-4 icon-footer">
                <a href="/"><i class="fa-solid fa-house"></i></a>
                <p>Home</p>
            </div>
            <div class="col-4 icon-footer">
                <a href="/accountLedger"><i class="fa-solid fa-wallet"></i>
                </a>
                <p>Wallet</p>
            </div>
            <div class="col-4 icon-footer">
                <i class="fa-brands fa-whatsapp"></i>
                <p>Support</p>
            </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    var success = "{{!empty($success)?$success:'NA'}}";
    var error = "{{!empty($error)?$error:'NA'}}";
    if (success != 'NA') {
        Swal.fire({
            title: 'Done',
            text: success,
            icon: 'success',
            confirmButtonText: 'Okay',

        }).then((result) => {
            if (result.isConfirmed) {
                window.location.reload();
            }
        })
    }
    if (error != 'NA') {
        Swal.fire({
            title: 'Failed!',
            text: error,
            icon: 'error',
            confirmButtonText: 'Okay',

        });
    }
</script>

</html>