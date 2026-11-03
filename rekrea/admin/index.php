<?php $title='Admin'; include '_nav.php'; $c=fn($q)=>db()->query($q)->fetchColumn();
$top=db()->query("SELECT o.naziv,COUNT(r.id) n FROM sportni_objekti o LEFT JOIN rezervacije r ON r.objekt_id=o.id AND r.status IN('cakajoca','potrjena') GROUP BY o.id ORDER BY n DESC LIMIT 5")->fetchAll(); ?>
<h1>Nadzorna plošča</h1><div class="stats"><div class="card"><b><?=$c('SELECT COUNT(*) FROM uporabniki')?></b>Uporabniki</div><div class="card"><b><?=$c('SELECT COUNT(*) FROM sportni_objekti')?></b>Objekti</div><div class="card"><b><?=$c('SELECT COUNT(*) FROM rezervacije')?></b>Rezervacije</div><div class="card"><b><?=$c("SELECT COUNT(*) FROM rezervacije WHERE status='cakajoca'")?></b>Čakajoče</div></div>
<h2>Najbolj rezervirani objekti</h2><table><tr><th>Objekt</th><th>Rezervacij</th></tr><?php foreach($top as $t) echo '<tr><td>'.e($t['naziv']).'</td><td>'.$t['n'].'</td></tr>'; ?></table>
<?php include '../includes/footer.php'; ?>
