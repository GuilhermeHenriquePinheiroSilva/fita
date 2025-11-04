<?php
// app/views/dashboard/occupancy.php
// Simple occupancy dashboard (PHP puro). Expects $eventRoomModel (EventRoom) provided by controller.
if (!isset($eventRoomModel)) {
    echo "EventRoom model not supplied.";
    exit;
}
$from = $_GET['from'] ?? date('Y-m-01 00:00:00');
$to = $_GET['to'] ?? date('Y-m-t 23:59:59');
$summary = $eventRoomModel->occupancySummary($from, $to);
?>
<h1>Dashboard de Ocupação</h1>
<p>Período: <?=htmlspecialchars($from)?> — <?=htmlspecialchars($to)?></p>
<table border="1" cellpadding="6">
<thead><tr><th>Sala</th><th>Bookings</th><th>Occupied (hours)</th></tr></thead>
<tbody>
<?php foreach($summary as $s): ?>
    <tr>
        <td><?=htmlspecialchars($s['name'])?></td>
        <td><?=htmlspecialchars($s['bookings'])?></td>
        <td><?=number_format((float)$s['occupied_seconds'] / 3600, 2)?></td>
    </tr>
<?php endforeach; ?>
</tbody>
</table>
