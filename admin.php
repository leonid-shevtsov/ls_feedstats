<h1>Feed statistics</h1>

TODO do a pretty chart

<style>
  table.feedstats td {border-left: solid 1px gray;padding: .5em}
</style>

<table class="feedstats"> 
<thead>
<tr>
  <td>time</td>
  <td>ip</td>
  <td>referer</td>
  <td>user agent</td>
</tr>
</thead>
<?php foreach($visits as $visit) { ?>
  <tr>
    <td><?php echo htmlspecialchars($visit['atime']) ?></td>
    <td><?php echo htmlspecialchars($visit['ip']) ?></td>
    <td><?php echo htmlspecialchars($visit['referer']) ?></td>
    <td><?php echo htmlspecialchars($visit['useragent']) ?></td>
  </tr>
<?php } ?>
</table>
