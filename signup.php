<?php
SESSION_start();
$usernam=$email=$pass=$cpassword="";
$errors=array('usernam'=>"",'email'=>"",'pass'=>"",'cpassword'=>"");
if(isset($_POST['submit'])){
if(empty($_POST['usernam'])){
$errors['usernam']="username is required<br/>";}
else{
    $usernam=$_POST['usernam'];
    if(!preg_match('/^[a-zA-Z\s]+$/',$usernam)){
    $errors['usernam']= "username must be letters and spaces only";
    }}
    if(empty($_POST['email'])){
    $errors['email']= "an email is required<br/>";}
    else{
    $email=$_POST['email'];
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors['email']= "email must be a valid email address<br/>";}
    }
    if(empty($_POST['pass'])){
    $errors['pass']= "password is required<br/>";
    }else{
    $pass=$_POST['pass'];
    }
    if(empty($_POST['cpassword'])){
    $errors['cpassword']= "please confirm the password<br/>";}
    else{
    $cpassword=$_POST['cpassword'];
    if($cpassword!=$pass){
    $errors['cpassword']="password does'nt match<br/>";
    }}}?>
    <?php
    if(isset($_POST['submit'])){
    if(!empty($_POST['usernam']) && !empty($_POST['email'])&& !empty($_POST['pass']) && $pass==$cpassword)
    {
    include('.vscode/connection.php');
    $emailquery="select *from usersignup where email='$email' ";
    $query=mysqli_query($conn,$emailquery);
    $emailcount=mysqli_num_rows($query);
    if($emailcount>0){
    echo "email already exists";}
    else{
    $token = bin2hex(random_bytes(15));
    $y= "Hi, $usernam. click here too verify your email
    http://localhost/nisha/activate.php?token=$token";'</b>';
    $sql = "INSERT INTO usersignup (username, email, password,token,status)
    VALUES ('".$_POST["usernam"]."','".$_POST["email"]."','".$_POST["pass"]."','$token','inactive')";
    $x=mysqli_query($conn,$sql);
    if($x)
    {
    require 'PHPMailerAutoload.php';
    $mail = new PHPMailer(true);
    try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'nisha0502bharti@gmail.com';
    $mail->Password = 'nishai';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->setFrom('nisha0502bharti@gmail.com', 'Nisha');
    $mail->addAddress($_POST['email']);
    $mail->isHTML(true);
    $mail->Subject = 'EMAIL VERIFICATION';
    $mail->Body = $y;
    $mail->send();
    $_SESSION['msg']='check you mail to activate your account ';
    header('location:index.php');
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }}}
    }}
    ?>
    <html>
    <head>
    <title>signup</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <div class="container">
    <div class="row">
    <div class="col-md-4 offset-md-4 form-div">
    <form action="signup.php" method="post" >
    <h3><class="text-center">SIGN UP</h3>
    <div class="form-group">
    <label for="username">Username</label>
    <input type="text" name="usernam" value="<?php if(preg_match('/^[a-zA-Z\s]+$/',$usernam)) echo 
    htmlspecialchars($usernam)?>" class="form-control form-control-lg">
    <div class="errors"><?php echo $errors['usernam'];?></div>
    </div>
    <div class="form-group">
    <label for="email"> Email</label>
    <input type="text" name="email" value="<?php if(filter_var($email, FILTER_VALIDATE_EMAIL)) echo 
    htmlspecialchars($email)?>" class="form-control form-control-lg">
    <div class="errors"><?php echo $errors['email'];?></div>
    </div>
    <div class="form-group">
    <label for="password">Password</label>
    <input type="password" name="pass" value="<?php if($pass==$cpassword)echo htmlspecialchars($pass) ?>" class="form-control 
    form-control-lg">
    <div class="errors"><?php echo $errors['pass'];?></div>
    </div>
    <div class="form-group">
    <label for="passwordconf">Confirm Password</label>
    <input type="password" name="cpassword" value="<?php echo htmlspecialchars($cpassword)?>" class="form-control form-controllg">
    <div class="errors"><?php echo $errors['cpassword'];?></div>
    </div>
    <div class="form-group">
    <button type="submit" name="submit" class="btn btn-primary btn-block btn-lg">SIGN UP</button>
    </div>
    <p class="text-center">Already a member? <a href="index.php">SIGN IN</a></p>
    </form>
    </div>
    </div>
    </div>
    </body>
    </html>