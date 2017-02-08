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
          'name' => array(
              'required' => true,
              'min' => 2,
              'max' => 50
          )
       ));

       if($validate->passed()){

           try{
                $user->update(array(
                    'name' => Input::get('name')
                ));

               Session::flush('home', 'User successfully updated');
               Redirect::to('index.php');
               
           }catch (Exception $e){
               die($e->getMessage());
           }
       }else{
           foreach ($validate->errors() as $error){
                echo $error.'<br>';
           }
       }
   }
}
?>



<form action="" method="post">
    <div class="field">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?php echo $user->data()->name?>">

        <input type="hidden" name="token" value="<?php echo Token::generate()?>">
        <input type="submit" value="Update">
    </div>
</form>
