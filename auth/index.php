<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../config/helpers.php';
require_once __DIR__.'/../middleware/auth.php';

$action = $_GET['action'] ?? '';

match($action) {
    'register' => register(),
    'login'    => login(),
    'logout'   => logout(),
    'me'       => me(),
    default    => fail(404,'Not found'),
};

function register(): never {
    if($_SERVER['REQUEST_METHOD']!=='POST') fail(405,'Method not allowed');
    $d=body(); $e=validate($d,['name'=>'required|min:2','email'=>'required|email','password'=>'required|min:8']);
    if($e) fail(422,'Validation failed',$e);
    $s=db()->prepare("SELECT id FROM users WHERE email=? LIMIT 1"); $s->execute([strtolower($d['email'])]);
    if($s->fetch()) fail(409,'Email already in use');
    $hash=password_hash($d['password'],PASSWORD_BCRYPT,['cost'=>12]);
    db()->prepare("INSERT INTO users(name,email,password) VALUES(?,?,?)")->execute([clean($d['name']),strtolower($d['email']),$hash]);
    $id=(int)db()->lastInsertId();
    created(['token'=>makeToken($id),'user'=>['id'=>$id,'name'=>clean($d['name']),'email'=>strtolower($d['email']),'role'=>'student']],'Account created');
}

function login(): never {
    if($_SERVER['REQUEST_METHOD']!=='POST') fail(405,'Method not allowed');
    $d=body(); $e=validate($d,['email'=>'required|email','password'=>'required']);
    if($e) fail(422,'Validation failed',$e);
    $s=db()->prepare("SELECT id,name,email,password,role FROM users WHERE email=? LIMIT 1"); $s->execute([strtolower($d['email'])]);
    $u=$s->fetch();
    if(!$u||!password_verify($d['password'],$u['password'])) fail(401,'Invalid credentials');
    db()->prepare("DELETE FROM sessions WHERE user_id=? AND expires_at<NOW()")->execute([$u['id']]);
    ok(['token'=>makeToken((int)$u['id']),'user'=>['id'=>$u['id'],'name'=>$u['name'],'email'=>$u['email'],'role'=>$u['role']]],'Login successful');
}

function logout(): never {
    mustAuth();
    $tok=trim(substr($_SERVER['HTTP_AUTHORIZATION'],7));
    db()->prepare("DELETE FROM sessions WHERE token=?")->execute([$tok]);
    ok([],'Logged out');
}

function me(): never {
    $u=mustAuth(); ok(['user'=>$u]);
}
