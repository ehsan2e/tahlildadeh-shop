<?php

global $dependencies;
$dependencies=array('checkInput','authHelper');


function main()
{

        // Login Form
	if(isset($_POST['login'])){
		// handle login
        $email=$_POST['email'];
        $password=  sha1($_POST['password']);

        $sql="SELECT * FROM `customers` WHERE `customer_email`='$email' AND `customer_password`='$password';";
        $result=dbQuery($sql);

        
        if(mysql_num_rows($result)!=1){
            $url=BASE_URL.'/signup';
            //@todo create error message
            addMessage('نام کاربری یا رمز عبور اشتباه وارد شده است.',FAILURE);
        }
        else{
            $user=mysql_fetch_assoc($result);
            //@todo save user id in session
            //@todo create welcome message
            $url=BASE_URL.'/customer';
            $spKey=getSpKey();
            $_SESSION[$spKey]['customer']=$user['id'];
            $userName = $user['customer_name'];
            
            addMessage($userName.' عزیز خوش آمدید.',SUCSESS);
        }

        mysql_free_result($result);

        return array('redirect'=>$url);
	}
        
    
    // SignUp Form
    if(isset($_POST['signup']))
    {
        $firstName=safeQuery($_POST['firstName']);
        $lastName=safeQuery($_POST['lastName']);
        $mobile=safeQuery($_POST['mobile']);
        $email=safeQuery($_POST['email']);
        $password=sha1($_POST['password']);
        $gender=$_POST['gender'];

        if((isPhone($mobile))&&(isEmail($email))&& !empty(trim($firstName)) && !empty(trim($lastName)) && !empty(trim($mobile)) && !empty(trim($email)) && !empty(trim($password)))
        {
            $sql="SELECT * FROM `customers` WHERE  `customer_email`='$email'";
            $result=dbQuery($sql);
            
            if(mysql_num_rows($result)==0){
                $sql="INSERT INTO `customers`(`customer_name`,`customer_family`,`customer_email`,`customer_password`,`customer_gender`,`customer_mobile`)
                                        VALUES('$firstName','$lastName','$email','$password','$gender','$mobile')";
                $result=dbQuery($sql);
                
                addMessage('ثبت نام شما با موفقیت انجام شد. با آدرس ایمیل و رمز عور انتخابی وارد شوید', SUCSESS);

                
                
                $url=BASE_URL.'/customer';
            }
            else{
                $url=BASE_URL.'/signup';
                //@todo create error message
                addMessage('آدرس ایمیل واد شده تکراری میباشد، برای بازیابی رمز عبور کلیک کنید.',FAILURE);
            }
        mysql_free_result($result);
        }
        else {
            $url=BASE_URL.'/signup';
            //@todo create error message
            addMessage('اطلاعات فرم ثبت نام به درستی وارد نشده است.',FAILURE);

        }

        return array('redirect'=>$url);

    }
    
    
    
    
    
    
    

}

require(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Engine.php');