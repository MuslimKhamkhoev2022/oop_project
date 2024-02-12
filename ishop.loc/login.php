<?php
require_once "init.php";

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {

        $validate = new Validate();

        $validate->check($_POST, [
            'email' => ['required' => true, 'email' => true],
            'password' => ['required' => true]
        ]);

        if ($validate->passed()) {

            $user = new User;
            $login = $user->login(Input::get('email'), Input::get('password'));
            if ($login) {
                echo 'login successful';
            } else {
                echo 'login failed';
            }
        } else {
            foreach ($validate->errors() as $error) {
                echo $error . '<br>';
            }
        }
    }
}
?>

<form action="" method="post">
    <div class="field">
        <label for="email">Email</label>
        <input type="text" name="email" value="<?php echo Input::get('email') ?>">
    </div>
    <div class="field">
        <label for="">Password</label>
        <input type="text" name="password">
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">

    <div class="field">
        <button type="submit">Submit</button>
    </div>
</form>
