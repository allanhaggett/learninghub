<?php 
$db = new SQLite3('courses.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
// Errors are emitted as warnings by default, enable proper error handling.
$db->enableExceptions(true);
?>


<?php require('template/header.php') ?>
<?php require('template/nav.php') ?>

<div class="container">

<div class="row">
<div class="col-md-6">

<div><a href="courses.php">All courses</a></div>

<?php
$cid = $_GET['cid'] ?? 0;
$sql = 'SELECT 
            c.id AS cid, 
            c.name AS cname, 
            c.status AS cstatus, 
            c.description AS cdesc, 
            c.keywords AS ckeys, 
            dm.id AS dmid,
            dm.name AS dmname,
            g.name AS groupname,
            g.id AS groupid,
            a.name AS audiencename,
            a.id AS audienceid,
            top.name AS topicname,
            top.id AS topicid,
            pa.name AS partnername,
            pa.id AS partnerid,
            plat.name AS platformname,
            plat.id AS platformid
        FROM 
            courses c 
        JOIN delivery_methods dm ON dm.id = c.dmethod_id
        JOIN groups g ON g.id = c.group_id
        JOIN audiences a ON a.id = c.audience_id
        JOIN topics top ON top.id = c.topic_id
        JOIN learning_partners pa ON pa.id = c.partner_id
        JOIN learning_platforms plat ON plat.id = c.platform_id
        WHERE c.id = ' . $cid . ';';

$statement = $db->prepare($sql);
$result = $statement->execute();
?>
<?php while ($row = $result->fetchArray()): ?>
    <div class="p-3 mb-2 bg-light-subtle shadow-lg">
        <div><?= strtoupper($row['cstatus']) ?></div>
        <h1><?= $row['cname'] ?></h1>
        <div class="mb-3"><?= $row['cdesc'] ?></div>
        <?php if(!empty($row['ckeys'])): ?>
        <?php $keys = explode(',',$row['ckeys']) ?>
        <div>
        Keywords: 
        <?php foreach($keys as $k): ?>
        <a href="keyword.php?keyword=<?= $k ?>"><?= $k ?></a>, 
        <?php endforeach ?>
        </div>
        <?php endif ?>
        <div>Delivery Method: <a href="filter.php?dmethod=<?= $row['dmid'] ?>"><?= $row['dmname'] ?></a></div>
        <div>Group: <a href="filter.php?group=<?= $row['groupid'] ?>"><?= $row['groupname'] ?></a></div>
        <div>Audience: <a href="filter.php?audience=<?= $row['audienceid'] ?>"><?= $row['audiencename'] ?></a></div>
        <div>Topic: <a href="filter.php?topic=<?= $row['topicid'] ?>"><?= $row['topicname'] ?></a></div>
        <div>Partner: <a href="filter.php?partner=<?= $row['partnerid'] ?>"><?= $row['partnername'] ?></a></div>
        <div>Platform: <a href="filter.php?platform=<?= $row['platformid'] ?>"><?= $row['platformname'] ?></a></div>
    </div>
<?php endwhile ?>

  

</div>
</div>
</div>
</body>
</html>