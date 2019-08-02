<div class="table-block">
    <div class="title">Color</div>
    <div class="text">
        <div>Click on the Color Name to see how many votes for that color.</div>
        <div>Whan you do click on Total, the sum of above numbers will show.</div>
    </div>
    
    <table>
        <tr class="row title">
            <th>Color</th>
            <th>Votes</th>
        </tr>

        <?php foreach ($data['colors'] as $row):?>
        <tr class="row color">
            <td>
                <a href="#" onClick="loadVotes(<?=$row['id']?>)"><?=$row['name']?></a>
            </td>
            <td id="votes-<?=$row['id']?>">&nbsp;</td>
        </tr>
        <?php endforeach;?>

        <tr class="row color">
            <td>
                <a href="#" onClick="calcTotal()">TOTAL</a>
            </td>
            <td id="votes-total">&nbsp;</td>
        </tr>
    </table>
</div>
