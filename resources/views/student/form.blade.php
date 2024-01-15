<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</head>

<body>
    <div class="col-md-12">
        <form id="form">
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="Active" for="name">Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Name" id="name">
                </div>
                <div class="form-group col-md-6" l>
                    <label class="Active" for="emai">Email</label>
                    <input type="text" class="form-control" name="email" placeholder="Email" id="email">
                    <button type="button" id="emailverify">check</button>
                </div>


                <div class="form-group col-md-6">
                    <label class="Active" for="phone">Phone Number</label>
                    <input type="text" class="form-control" name="phone" placeholder="Phone Number" id="phone">
                </div>
                <div class="form-group col-md-6">
                    <label class="Active" for="city">City Name</label>
                    <input type="text" class="form-control" name="city" placeholder="City Name" id="city">
                </div>
                <div class="form-group col-md-6">
                    <label class="Active" for="grade">Grade</label>
                    <input type="text" class="form-control" name="grade" placeholder="Grade" id="grade">
                </div>
                <div class="form-group col-md-6">
                    <label class="Active" for="otp">OTP</label>
                    <input type="text" class="form-control" name="otp" placeholder="OTP" id="otp">
                    <button type="button" id="otpverify">check</button>
                </div>
                <div class="form-group col-md-6">
                    <label class="Active" for="state">State</label>
                    <input type="text" class="form-control" name="state" placeholder="State" id="state">
                </div>
                <div class="form-group col-md-6">
                    <label class="Active" for="school">School Name</label>
                    <input type="text" class="form-control" name="school" placeholder="School Name" id="school">
                </div>
                <input type="hidden" name="is_new" id="is_new" value="0">
                <div class="form-group col-md-6">
                    <!-- <input type="button" class="btn btn-primary"> -->
                    <button type="button" id="submit" class="btn btn-primary">submit</button>
                </div>
            </div>
        </form>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        function getToken() {
            var jwttoken = 'data';
            $.ajax({
                url: "{{route('getToken')}}",
                async: false,
                success: function(result) {
                    jwttoken = result.token;
                    return jwttoken;
                }
            });
            return jwttoken;
        }

        var token = 'Bearer ' + getToken();

        $('#emailverify').click(function() {
            console.log(token, 'here');
            var email = $('#email').val();
            $.ajax({
                url: "{{route('checkEmail')}}" + '?email=' + email,
                headers: {
                    "AUTHORIZATION": token
                },
                success: function(result) {
                    if (result.data === undefined) {
                        $("#is_new").val("1");
                    } else {
                        alert(result.data.otp);
                    }
                }
            });
        });

        $('#otpverify').click(function() {
            console.log(token, 'here');
            var email = $('#email').val();
            var otp = $('#otp').val();
            $.ajax({
                url: "{{route('checkOtp')}}" + '?email=' + email + '&otp=' + otp,
                headers: {
                    "AUTHORIZATION": token
                },
                success: function(result) {
                    console.log(result);
                    $('#name').val(result.data.name);
                    $('#phone').val(result.data.phone);
                    $('#grade').val(result.data.grade);
                    $('#city').val(result.data.city);
                    $('#state').val(result.data.state);
                    $('#school').val(result.data.school);
                    alert("otp verified");
                }
            });
        });

        $('#submit').click(function() {
            console.log(token, 'here');
            var email = $('#email').val();
            var name = $('#name').val();
            var phone = $('#phone').val();
            var grade = $('#grade').val();
            var city = $('#city').val();
            var state = $('#state').val();
            var is_new = $('#is_new').val();
            var school = $('#school').val();
            var otp = $('#otp').val();
            $.ajax({
                url: "{{route('student/storeData')}}" + '?name=' + name + '&phone=' + phone + '&grade=' + grade + '&city=' + city + '&state=' + state + '&school=' + school + '&email=' + email + '&is_new=' + is_new + '&otp=' + otp,
                headers: {
                    "AUTHORIZATION": token
                },
                success: function(result) {
                    if(result.message == 'new') {
                        alert(result.data.otp);
                    } else if (result.message == 'old') {
                        alert("entry updated");
                    }
                }
            });
        });


    });
</script>

</html>