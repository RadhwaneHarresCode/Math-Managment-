<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../config/helpers.php';
require_once __DIR__.'/../middleware/auth.php';

mustAdmin();

$action = $_GET['action'] ?? 'stats';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : null;

match(true) {
    $action==='stats'           => stats(),
    $action==='users'&&!$id     => users(),
    $action==='users'&&(bool)$id => oneUser($id),
    $action==='delete'&&(bool)$id => deleteUser($id),
    default                     => fail(404,'Not found'),
};

function stats(): never {
    $d=db();
    $totalStudents = $d->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn();
    $activeSessions= $d->query("SELECT COUNT(*) FROM sessions WHERE expires_at>NOW()")->fetchColumn();
    $dayStats = $d->query("SELECT p.day_number,p.title,COUNT(pr.id) as attempts,SUM(COALESCE(pr.completed,0)) as completions FROM plan_days p LEFT JOIN progress pr ON pr.day_id=p.id GROUP BY p.id ORDER BY p.day_number")->fetchAll();
    $topStudents = $d->query("SELECT u.id,u.name,u.email,u.created_at,COUNT(pr.id) as attempted,SUM(COALESCE(pr.completed,0)) as completed FROM users u LEFT JOIN progress pr ON pr.user_id=u.id WHERE u.role='student' GROUP BY u.id ORDER BY completed DESC LIMIT 10")->fetchAll();
    $recent = $d->query("SELECT u.name,pd.day_number,pd.title,pr.completed,pr.updated_at FROM progress pr JOIN users u ON u.id=pr.user_id JOIN plan_days pd ON pd.id=pr.day_id ORDER BY pr.updated_at DESC LIMIT 15")->fetchAll();
    ok(['summary'=>['total_students'=>(int)$totalStudents,'active_sessions'=>(int)$activeSessions],'day_stats'=>$dayStats,'top_students'=>$topStudents,'recent_activity'=>$recent]);
}

function users(): never {
    $page=(int)($_GET['page']??1); $limit=20; $offset=($page-1)*$limit;
    $total=db()->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $s=db()->prepare("SELECT u.id,u.name,u.email,u.role,u.created_at,SUM(COALESCE(pr.completed,0)) as days_done FROM users u LEFT JOIN progress pr ON pr.user_id=u.id GROUP BY u.id ORDER BY u.created_at DESC LIMIT ? OFFSET ?");
    $s->execute([$limit,$offset]);
    ok(['users'=>$s->fetchAll(),'total'=>(int)$total,'pages'=>(int)ceil($total/$limit),'page'=>$page]);
}

function oneUser(int $id): never {
    $s=db()->prepare("SELECT id,name,email,role,created_at FROM users WHERE id=? LIMIT 1"); $s->execute([$id]);
    $u=$s->fetch(); if(!$u) fail(404,'User not found');
    $s=db()->prepare("SELECT pd.day_number,pd.title,pd.phase,COALESCE(pr.completed,0) as completed,pr.completed_at,pr.notes FROM plan_days pd LEFT JOIN progress pr ON pr.day_id=pd.id AND pr.user_id=? ORDER BY pd.day_number");
    $s->execute([$id]);
    $progress=$s->fetchAll();
    $done=array_sum(array_column($progress,'completed'));
    ok(['user'=>$u,'stats'=>['done'=>(int)$done,'total'=>30,'pct'=>round($done/30*100,1)],'progress'=>$progress]);
}

function deleteUser(int $id): never {
    if($_SERVER['REQUEST_METHOD']!=='POST') fail(405,'POST required');
    $s=db()->prepare("DELETE FROM users WHERE id=? AND role!='admin'"); $s->execute([$id]);
    if($s->rowCount()===0) fail(404,'User not found or cannot delete admin');
    ok([],'User deleted');
}
