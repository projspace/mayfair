<div id="checkOut">
    <div class="block">
        <div class="tab-wrapper">
            <h1><?=$page['name'] ?></h1>
            <ul class="tab-nav">
                <li class="inactive"><a href="#" >addresses</a></li>
                <li class="inactive"><a href="#" >taxes</a></li>
                <li class="inactive"><a href="#" >payment</a></li>
                <li class="active"><a href="#" >thank you</a></li>
            </ul>
            <div id="tab-content" style="min-height: 0;">
            <?
                $page = $elems->qry_Page(23);
                echo $page['content'];
            ?>
            <br />
            <a class="btn big-btn green-btn top-space" href="#" onclick="javascript: window.history.back();">Back</a>
            </div>
        </div>
    </div>
</div>