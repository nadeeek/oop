<?php
require_once 'core/init.php';

if(!$username = Input::get('user')){
    Redirect::to('index.php');
}else{
    $user = new Users($username);

    if(!$user->exsits()){
        Redirect::to(404);
    }else{
       $data = $user->data();
//        print_r($data);
    }
    ?>
    <h1><?php echo $data->username;?></h1>
    <p>Full Name : <?php echo $data->name;?></p>
<?php
}