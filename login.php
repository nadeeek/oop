<?php
require_once 'core/init.php';

if(Input::exist()){
    if(Token::check(Input::get('token'))){
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array('required' => true),
            'password' => array('required' => true)
        ));

        if($validate->passed()){
            $user = new Users();
            $remember = (Input::get('remember') === 'on')? true : false;
//            echo $remember;
            $login = $user->login(Input::get('username'), Input::get('password'), $remember);

            if($login){
                echo 'Success';
                Redirect::to('index.php');
            }else{
                echo 'Failed in login!';
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
        <label for="username">Username</label>
        <input type="text" name="username" id="username" autocomplete="off">
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" autocomplete="off">
    </div>
    <div class="field">
        <label for="remember">
            <input type="checkbox" name="remember" id="remember">Remember
        </label>
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate()?>">
    <input type="submit" value="Login">
</form>