<div class="wysiwyg">
    <h1><?=htmlentities($page['name'], ENT_NOQUOTES, 'UTF-8') ?></h1>

    <?=$content->fields['content'] ?>

    <?
        if($page['identifier'] == 'shipping-policy')
        {
            $shipping = array();
            while($row = $rates->FetchRow())
                $shipping[$row['name']][] = $row;

            echo '<style type="text/css">
                table.rates { width: 500px; }
                table.rates td { border: 1px solid black; }
            </style>';

            echo '<table class="rates">';
            echo '<tr><td>Method</td><td>Merchandise value</td><td>Price</td></tr>';
            foreach($shipping as $method=>$rates)
            {
                $last_price = 0;
                foreach($rates as $index=>$row)
                {
                    $top = ($row['value'] == 1000000000)?price($last_price+0.01).'+':price($last_price+0.01).' - '.price($row['value']);
                    echo '<tr>'.(($index==0)?'<td rowspan="'.count($rates).'">'.$row['name'].'</td>':'').'<td>'.$top.'</td><td>'.price($row['price']).'</td></tr>';
                    $last_price = $row['value'];
                }
            }
            echo '</table>';
        }
    ?>
</div>