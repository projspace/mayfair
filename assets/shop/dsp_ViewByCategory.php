<div class="product-thumbs product-title">
    <ul>
    <?
        $index = 0;
        while($row = $categories->FetchRow()): ?>
        <li <?=($index%4 == 3)?'class="omega"':''?>>
            <h2><?= htmlentities(strtoupper($row['name']), ENT_NOQUOTES, 'UTF-8') ?></h2>
            <a href="<?= category_url($row['id'], $row['name']) ?>"><img src="<?= $config['dir'] ?>images/category/box_<?=$row['id'] ?>.<?=$row['box_imagetype'] ?>" alt="<?= htmlentities($row['name'], ENT_COMPAT, 'UTF-8') ?>" width="281"  height="281"/></a>
        </li>
        <? $index++; ?>
        <? endwhile; ?>
    </ul>
</div>