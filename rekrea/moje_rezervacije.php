<?php $title='Moje rezervacije'; require_once 'includes/functions.php'; require_login();
if($_SERVER['REQUEST_METHOD']==='POST'){ check_csrf(); db()->prepare("UPDATE rezervacije SET status='preklicana' WHERE id=? AND uporabnik_id=? AND status IN('cakajoca','potrjena')")->execute([(int)$_POST['id'],user()['id']]); flash('Rezervacija preklicana.'); redirect('moje_rezervacije.php'); }
include 'includes/header.php';
$s=db()->prepare('SELECT r.*,o.naziv FROM rezervacije r JOIN sportni_objekti o ON o.id=r.objekt_id WHERE uporabnik_id=? ORDER BY datum DESC,ura_zacetka DESC'); $s->execute([user()['id']]); $r=$s->fetchAll(); ?>
<h1>Moje rezervacije</h1><div class="tw"><table><tr><th>Objekt</th><th>Datum</th><th>Ura</th><th>Status</th><th></th></tr>
<?php foreach($r as $x): ?><tr><td><?=e($x['naziv'])?></td><td><?=date('d.m.Y',strtotime($x['datum']))?></td><td><?=substr($x['ura_zacetka'],0,5).'–'.substr($x['ura_konca'],0,5)?></td><td><span class="st <?=$x['status']?>"><?=$x['status']?></span></td>
<td><?php if(in_array($x['status'],['cakajoca','potrjena'])&&$x['datum']>=date('Y-m-d')): ?><form method="post"><?=csrf()?><input type="hidden" name="id" value="<?=$x['id']?>"><button class="red" data-confirm="Prekličem rezervacijo?">Prekliči</button></form><?php endif; ?></td></tr><?php endforeach; if(!$r) echo '<tr><td colspan=5>Še nimate rezervacij.</td></tr>'; ?></table></div>
<?php include 'includes/footer.php'; ?>
