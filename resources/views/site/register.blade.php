<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile First Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styles.css">
    <script src="https://kit.fontawesome.com/2564be365d.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    @php
    $success = Session::get('success');
    $error = Session::get('error');
    @endphp

    <header class="bg-dark">
        <div class="container navcontainer">
            <div class="d-flex align-items-center justify-content-between">
                <div class="flex-shrink-1 text-left header-logo">
                    <img src="assets/media/betmasters.png" alt="Logo" class="img-fluid header-logo">
                </div>
                <div class="flex-shrink-1 text-right">
                    <p class="title-text">Bet Master 777</p>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6">
                    <form id="login-form" action="/registerUser" method="POST">
                        @csrf
                        <h2 class="text-center">Register</h2>
                        <div class="form-group">
                            <input type="tel" id="login-mobile" class="form-control" name="name" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <input type="tel" id="login-mobile" class="form-control" name="number" pattern="[0-9]{10}" placeholder="Enter Mobile Number">
                        </div>
                        <div class="form-group">
                            <input type="password" id="login-otp" class="form-control" name="password" placeholder="Enter Password">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                        <p class="text-center">Already a member? <a href="/login" onclick="toggleForms()">Click here to login</a></p>
                        <p class="text-center"><a href="#" onclick="forgotForms()">Forgot Password</a></p>
                    </form>
                </div>
            </div>
        </div>

        <!-- Image Slider -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="images/1.png" height="200" width="200" class="d-block w-100" alt="...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-light text-center">
        <div class="container">
            <p>&copy; 2024 My Mobile First Page</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="assets/login.js"></script>

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
</body>

</html>