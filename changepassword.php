<?php
require_once 'core/init.php';
$user = new Users();

if(!$user->isLoggedIn()){
    Redirect::to('index.php');
}

if(Input::exist()){
   if(Token::check(Input::get('token'))){
       $validate = new Validate();
       $validation = $validate->check($_POST, array(
           'password'=> array(
               'required' => true,
               'min' => 6
           ),
           'password_new'=> array(
               'required' => true,
               'min' => 6
           ),
           'password_again'=> array(
               'required' => true,
               'min' => 6,
               'matches' => 'password_new'
           ),
       ));

       if($validation->passed()){
            //change password
           if(Hash::make(Input::get('password'), $user->data()->salt) !== $user->data()->password){
                echo 'Your current password is worng..!';
           }else{
               $salt = Hash::salt(32);
               $user->update(array(
                   'password' => Hash::make(Input::get('password_new'), $salt),
                   'salt' => $salt
               ));

               Session::flush('home', 'Your password has been updated..');
               Redirect::to('index.php');
           }
       }else{
           foreach ($validation->errors() as $error){
               echo $error. '<br>';
           }
       }
   }
}
?>


<form method="post" action="">
    <div class="field">
        <label for="password">Current Password :</label>
        <input type="password" name="password" id="password" autocomplete="off">
    </div>
    <div class="field">
        <label for="password_new">New Password :</label>
        <input type="password" name="password_new" id="password_new" autocomplete="off">
    </div>
    <div class="field">
        <label for="password_again">New Password again :</label>
        <input type="password" name="password_again" id="password_again" autocomplete="off">
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate()?>">
    <input type="submit" value="Change Password">
</form>