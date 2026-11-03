<nav class="nav"><div class="wrap navin"><a class="logo" href="<?=BASE?>index.php">REK<span>REA</span></a><button class="burger" onclick="document.querySelector('.links').classList.toggle('open')">☰</button><div class="links">
<a href="<?=BASE?>objekti.php">Objekti</a>
<?php if($u): ?><a href="<?=BASE?>moje_rezervacije.php">Moje rezervacije</a><a href="<?=BASE?>profil.php">Profil</a><?php if($u['vloga']==='admin'): ?><a href="<?=BASE?>admin/index.php">Admin</a><?php endif; ?><a href="<?=BASE?>odjava.php">Odjava (<?=e($u['ime'])?>)</a>
<?php else: ?><a href="<?=BASE?>prijava.php">Prijava</a><a class="btn" href="<?=BASE?>registracija.php">Registracija</a><?php endif; ?></div></div></nav>
