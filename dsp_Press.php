<div id="content-wrapper" class="yui3-g">
    <div id="fullcontent" class="yui3-u press">
        <ul class="press-pager clearfix">
            <li class="float-left" style="padding-left:20px;"><?= strtoupper($types[$type]) ?></li>
            <? for($i=1;$i<=$pages;$i++): ?>
            <li><a href="?page=<?= $i ?>"<?= $i == $page ? ' class="on"' : '' ?>><?= $i ?></a></li>
            <? endfor ?>
        </ul>

        <ul class="press-list clearfix">
            <? while($row = $list->FetchRow()): ?>
            <li>
                <h4><strong><?= date('m.y', $row['date']) ?></strong> <?= strtoupper($row['title']) ?></h4>
                <a href="<?= $config['dir'] ?><?= $type ?>/<?= $row['id'] ?>" class="press-img"><img src="<?= $config['dir'] ?>images/press/list/<?= $row['id'] ?>.<?= $row['imagetype'] ?>" width="207" height="294" title="<?= $row['title'] ?>" alt="<?= $row['title'] ?>"/></a>
                <h5><?= strtoupper($row['release_title']) ?></h5>
                <p><?= nl2br($row['summary']) ?></p>
            </li>
            <? endwhile ?>
        </ul>
    </div>
</div>