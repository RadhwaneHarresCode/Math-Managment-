<?php
function json_out(int $code, array $body): never {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($body, JSON_UNESCAPED_UNICODE);
    exit;
}
function ok(array $data=[], string $msg='OK'): never         { json_out(200,['ok'=>true,'msg'=>$msg,'data'=>$data]); }
function created(array $data=[], string $msg='Created'): never { json_out(201,['ok'=>true,'msg'=>$msg,'data'=>$data]); }
function fail(int $code, string $msg, array $errs=[]): never {
    $b=['ok'=>false,'msg'=>$msg]; if($errs) $b['errors']=$errs;
    json_out($code,$b);
}

function body(): array { return json_decode(file_get_contents('php://input'),true)??[]; }

function clean(string $v): string { return htmlspecialchars(strip_tags(trim($v)),ENT_QUOTES,'UTF-8'); }

function validate(array $d, array $rules): array {
    $e=[];
    foreach($rules as $f=>$r){
        $req=str_contains($r,'required');
        $v=$d[$f]??null;
        if($req && ($v===null||$v==='')){ $e[$f]="$f is required"; continue; }
        if($v===null) continue;
        if(str_contains($r,'email')&&!filter_var($v,FILTER_VALIDATE_EMAIL)) $e[$f]="Invalid email";
        if(preg_match('/min:(\d+)/',$r,$m)&&strlen((string)$v)<(int)$m[1]) $e[$f]="$f min {$m[1]} chars";
        if(preg_match('/max:(\d+)/',$r,$m)&&strlen((string)$v)>(int)$m[1]) $e[$f]="$f max {$m[1]} chars";
    }
    return $e;
}
