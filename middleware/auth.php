<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../config/helpers.php';

function getUser(): ?array {
    $h = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (!str_starts_with($h,'Bearer ')) return null;
    $token = trim(substr($h,7));
    $s = db()->prepare("SELECT u.id,u.name,u.email,u.role FROM sessions s JOIN users u ON u.id=s.user_id WHERE s.token=? AND s.expires_at>NOW() LIMIT 1");
    $s->execute([$token]);
    return $s->fetch()?:null;
}

function mustAuth(): array  { $u=getUser(); if(!$u) fail(401,'Unauthorized'); return $u; }
function mustAdmin(): array { $u=mustAuth(); if($u['role']!=='admin') fail(403,'Admins only'); return $u; }

function makeToken(int $uid): string {
    $tok=bin2hex(random_bytes(32));
    $exp=date('Y-m-d H:i:s',strtotime('+7 days'));
    db()->prepare("INSERT INTO sessions(user_id,token,expires_at) VALUES(?,?,?)")->execute([$uid,$tok,$exp]);
    return $tok;
}
