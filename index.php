<?php
session_start();

class Authorisation{

    private $mail;
    private $pass;
    private $message = '';
    public $user;
    const reg_mail = "/^([a-z0-9\._])*@([a-z0-9\.])*([a-z]){2,4}$/i";
    const reg_pass = "/[A-Z]+[a-z]+[1-9]+/";
    private $data = [
        'admin@mail.ru' => 'sagAzOMGSWT1g', //Admin1
        'moderator@mail.ru'=> 'saBAf0bfcHx3k' //Moderator2
        ];
        
    function __construct()
    {
        $this->mail = &$_SESSION['mail'];
        $this->pass = &$_SESSION['pass'];
        $this->user = &$_SESSION['user'];
    }
    public function setMail($v){
        $this->mail = $v;
    }
    public function setPass($v){
        $this->pass = $v;
    }

    public function getMail(){
        return $this->mail;
    }
    public function getPass(){
        return $this->pass;
    }

    protected function setUserName($user){
        $this->user = $user;
    }
    public function getUserName(){
        return $this->user;
    }
    public function getUser(){
        try{
            foreach ($this->data as $k => $v) {
                if (self::getMail() == $k && self::getPass() == $v) {
                    $str_arr = explode('@',$k);
                    self::setUserName($str_arr[0]);
                    self::set_message('Здравствуйте - " ' . self::getUserName() . ' "');//fghbdtn
                    return;
                }
            }
            throw new Exception('Проверьте логин, пароль.');
        }catch (Exception $e){
            self::set_message($e->getMessage());
        }
    }
    public function mail_valid($query_mail){
        if (preg_match(self::reg_mail,$query_mail)) return true;
        return false;
    }
    public function pass_valid($query_pass){
        if (preg_match(self::reg_pass,$query_pass)) return true;
        return false;
    }
    public function get_message(){
        return $this->message;
    }
    public function set_message($message){
        $this->message = $message;
    }
    public function logout(){
        self::set_message('До свидания - " ' . self::getUserName() . ' "');
        self::clear_Session();
    }
    private function clear_Session(){
        // unset($_SESSION['mail'],$_SESSION['pass'],$_SESSION['user']);
        // session_abort();
        // session_write_close();
        session_register_shutdown();
    }
}

$auth = new Authorisation();

if (isset($_POST['auth'])){

    if ($auth->mail_valid($_POST['mail'])){
        $auth->setMail($_POST['mail']);
    }
    if ($auth->pass_valid($_POST['pass'])){
        $auth->setPass(crypt($_POST['pass'],'salt'));
    }
    $auth->getUser();

}
if (isset($_GET['e']) && $_GET['e'] == 'exit'){
    $auth->logout();
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style>
        #exit{
            border-radius: 4px;
            float: right;
            margin-right: 5px;
        }
        .nav{
            background: #563d7c;
            /*height: 50px;*/
        }
        .nav .nav-link{
            color: #ffe484;
            border: #ffe484 solid 1px;
            border-radius: 3px;
            margin: 10px 10px 10px auto;
            position: relative;
            transition: all linear 0.15s;
        }
        .nav .nav-link:hover{
            background: #ffe484;
            color: #563d7c;
        }
        .enter-form{
            /*display: none;*/
            margin-top: 50px;
            padding: 20px 20px 60px 20px;
            max-width: 450px;
            border-radius: 5px;
            border: 1px solid #b8b8b8;
        }
        .btn-enter-form{
            float: right;
            margin-right: 5px;
        }
        .close{
            margin-top: -5px;
            margin-right: 5px;
        }
    </style>
</head>
<body>

<nav class="nav">
    <a id="enter" class="nav-link active" href="#">Вход</a>
</nav>

<div id="enter-form" class="container enter-form">
    <div class="close">&times;</div>
    <form name="auth" action="index.php" method="post">
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input name="mail" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
            <small id="emailHelp" class="form-text text-muted">Мы никогда нискем не будем делиться вашей почтой.</small>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input name="pass" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
        </div>
        <p><?=$auth->get_message()?></p>
        <a id="exit" href="index.php?e=exit" class="btn btn-warning btn-enter-form">Выход</a>
        <button name="auth" type="submit" class="btn btn-primary btn-enter-form">Вход</button>
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script>

    // $('#enter').click(function () {
    //     $('div#enter-form').toggle();
    // });
    // $('.close').click(function () {
    //     $('div#enter-form').toggle();
    // })

</script>
</body>
</html>