<?php
require_once __DIR__.'/../config/database.php';
if(session_status()===PHP_SESSION_NONE) session_start();
function e($s){ return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8'); }
function user(){ return $_SESSION['user'] ?? null; }
function redirect($u){ header('Location: '.BASE.$u); exit; }
function require_login(){ if(!user()) redirect('prijava.php'); }
function require_admin(){ if(!user()||user()['vloga']!=='admin'){ http_response_code(403); exit('Dostop zavrnjen.'); } }
function flash($m=null,$t='ok'){ if($m){$_SESSION['f']=[$m,$t];return;} $f=$_SESSION['f']??null; unset($_SESSION['f']); return $f; }
function csrf(){ if(empty($_SESSION['c'])) $_SESSION['c']=bin2hex(random_bytes(16)); return '<input type="hidden" name="c" value="'.$_SESSION['c'].'">'; }
function check_csrf(){ if(($_POST['c']??'')!==($_SESSION['c']??'x')){ http_response_code(400); exit('Neveljavna zahteva.'); } }
function prost($oid,$datum,$z,$k){ $s=db()->prepare("SELECT COUNT(*) FROM rezervacije WHERE objekt_id=? AND datum=? AND status IN('cakajoca','potrjena') AND ura_zacetka<? AND ura_konca>?"); $s->execute([$oid,$datum,$k,$z]); return $s->fetchColumn()==0; }
function ure($oid,$datum){ $d=date('N',strtotime($datum)); $s=db()->prepare("SELECT ura_od,ura_do FROM delovni_cas WHERE objekt_id=? AND dan_v_tednu=?"); $s->execute([$oid,$d]); $w=$s->fetch(); if(!$w) return []; $r=[]; for($h=(int)$w['ura_od'];$h<(int)$w['ura_do'];$h++) $r[$h]=prost($oid,$datum,sprintf('%02d:00:00',$h),sprintf('%02d:00:00',$h+1)); return $r; }
function ikona($k){ $m=['Telovadnice'=>'🏀','Športne dvorane'=>'🏟️','Nogometna igrišča'=>'⚽','Teniška igrišča'=>'🎾']; return $m[$k]??'🏅'; }
function kartica($o){ return '<article class="card obj"><div class="top c'.(crc32($o['kat'])%4).'"><span class="ico">'.ikona($o['kat']).'</span><span class="tag">'.e($o['kat']).'</span></div><div class="body"><h3>'.e($o['naziv']).'</h3><p class="muted">📍 '.e($o['naslov']).'</p><div class="meta"><span>👥 '.(int)$o['kapaciteta'].'</span><span class="price">'.number_format($o['cena_na_uro'],2,',','.').' €<small>/uro</small></span></div><a class="btn full" href="'.BASE.'objekt.php?id='.$o['id'].'">Poglej in rezerviraj →</a></div></article>'; }
