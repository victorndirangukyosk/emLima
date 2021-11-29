<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <td><?= $name ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?= $email ?></td>
    </tr>
    <tr>
        <th>Telephone</th>
        <td><?= $telephone ?></td>
    </tr>
    <tr>
        <th>Address</th>
        <td>
            <?= $address ?><br />
        </td>
    </tr>
    <?php if($latitude && $longitude) { ?>
    <tr>
        <th>View on map</th>
        <td>
            <a target="_blank" href="http://www.google.com/maps/place/<?= $latitude.','.$longitude ?>">
                http://www.google.com/maps/place/<?= $latitude.','.$longitude ?>
            </a>
        </td>
    </tr>
    <?php } ?>
</table>
