<div class="product-thumbs product-title">
    <ul>
    <?
        $index = 0;
        while($row = $brands->FetchRow()): ?>
        <li <?=($index%4 == 3)?'class="omega"':''?>>
            <h2><?= htmlentities(strtoupper($row['name']), ENT_NOQUOTES, 'UTF-8') ?></h2>
            <a href="<?= $config['dir'] ?>shop-by-brand/<?=$row['id'] ?>"><img src="<?= $config['dir'] ?>images/brand/<?=$row['id'] ?>.<?=$row['imagetype'] ?>" alt="<?= htmlentities($row['name'], ENT_COMPAT, 'UTF-8') ?>" width="281"  height="281"/></a>
        </li>
        <? $index++; ?>
        <? endwhile; ?>
    </ul>
</div>