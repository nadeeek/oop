<?php

require_once 'core/init.php';

// Config::get('mysql/host');


//$users = DB::getInstance()->get('users', array('username', '=', 'nadeesha'));
//
//if(!$users->count()){
//    echo 'No Users';
//}else{
//    echo $users->first()->username;
//}

//$users = DB::getInstance()->insert('users', array(
//    'username' => 'udesha',
//    'password' => 'password',
//    'name' => 'Udesha'
//));

//$users = DB::getInstance()->update('users', 2,  array(
//    'password' => 'newpassword',
//     'name' => 'Koshali'
//));

//if(Session::exists('success')){
//    echo Session::flush('Success');
//}
//print_r($_SESSION);
//if(Session::exists('home')){
//
//    echo '<p>'.Session::flush('home').'</p>';
//}

$user = new Users();
if($user->isLoggedIn()){
?>
    <p>Hello <a href="profile.php?user=<?php echo $user->data()->name;?>"><?php echo $user->data()->name;?></a></p>

    <ul>
        <li><a href="logout.php">Logout</a></li>
        <li><a href="update.php">Update Informations</a></li>
        <li><a href="changepassword.php">Change Password</a></li>
    </ul>
<?php

    if($user->hasPermissions('admin')){
        echo 'Your an administrator';
    }

}else{
    echo 'You need to <a href="login.php">login</a> or <a href="register.php">register</a>';
}