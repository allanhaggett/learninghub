<?php 
/**
 * LearningHUB 2.0
 */

$db = new SQLite3('courses.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
// Errors are emitted as warnings by default, enable proper error handling.
$db->enableExceptions(true);

// Parse the URL as it's been passed and break it apart so we can 
// build the URLs for filtering navigation
$parts = parse_url($_SERVER['REQUEST_URI']);
parse_str($parts['query'], $query);

// What topic(s) are we filtering upon? There can be more than one topic
// and we're using topic[]=1 parameter arrays 
$topsql = 'SELECT * FROM topics WHERE '; 
// echo $lastkey; exit;
if(!empty($_GET['topic'])) {
    $lastkey = end($_GET['topic']);
    foreach($_GET['topic'] as $tid) {
        $topsql .= 'id = ' . $tid;
        if($tid != $lastkey) {
            $topsql .= ' OR ';
        }
    }
    $topsql .= ';';
    // echo $topsql; exit;
    $topicinfo = $db->prepare($topsql);
    $top = $topicinfo->execute();
}

?>
<?php require('template/header.php') ?>
<?php require('template/nav.php') ?>

<div class="container">

<div class="row justify-content-md-center">
<div class="col-md-3">

<h2>Audiences</h2>
<p>Who is the learning for?</p>

<?php
$statement = $db->prepare('SELECT * FROM audiences;');
$result = $statement->execute();
?>
    <div class="p-3 mb-2 bg-light-subtle rounded-3">
<?php while ($row = $result->fetchArray()): ?>
    <?php if($row['id'] == 1) continue ?>
        <div><a  href="filter.php?audience=<?= $row['slug'] ?>"><?= $row['name'] ?></a></div>
        <?php endwhile ?>
    </div>


<h2>Groups</h2>
<p>What type of learning is it?</p>

<?php
$statement = $db->prepare('SELECT * FROM groups;');
$result = $statement->execute();
?>
    <div class="p-3 mb-2 bg-light-subtle rounded-3">
<?php while ($row = $result->fetchArray()): ?>
    <?php if($row['id'] == 1) continue ?>
        <div><a  href="filter.php?group=<?= $row['slug'] ?>"><?= $row['name'] ?></a></div>
        <?php endwhile ?>
    </div>



<h2>Topics</h2>
<p>What is the learning about?</p>
<?php 
$statement = $db->prepare('SELECT * FROM topics;');
$result = $statement->execute();
?>
    <div class="p-3 mb-2 bg-light-subtle rounded-3">
    <?php while ($row = $result->fetchArray()): ?>
        <?php if($row['id'] == 1) continue ?>
    <?php 
    if(!empty($query['topic'])) {
        $urlquery = '';
        foreach($query['topic'] as $t) {
            if($t == $row['id']) { 
                continue;
            } else {
                $urlquery .= '&topic[]=' . $row['id'];
            }
            // $urlquery .= '&topic[]=' . $t;
        }
    } else {
        $urlquery .= '&topic[]=' . $row['id'];
    }
    ?>
    <div>
        <a href="filter.php?<?= $urlquery ?>">
            <?= $row['name'] ?>
        </a>
    </div>
    <?php endwhile ?>
    </div>

<h2>Delivery Methods</h2>
<p>How is the learning offered?</p>

<?php
$statement = $db->prepare('SELECT * FROM delivery_methods;');
$result = $statement->execute();
?>
<div class="p-3 mb-2 bg-light-subtle rounded-3">
<?php while ($row = $result->fetchArray()): ?>
    <?php if($row['id'] == 1) continue ?>
    <div><a  href="filter.php?delivery_method=<?= $row['slug'] ?>"><?= $row['name'] ?></a></div>
<?php endwhile ?>
</div>



</div>
<div class="col-md-8">

<div class="p-3 mb-2 bg-light-subtle rounded-3">
<div class="fw-bold">Filters:</div>
<?php if(!empty($_GET['topic'])): ?>
<?php while ($row = $top->fetchArray()): ?>
    <div><?= $row['name'] ?></div>
<?php endwhile ?>
<?php endif ?>
</div>
<?php

$sql = 'SELECT 
            c.id AS cid, 
            c.name AS cname, 
            c.url AS curl, 
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
        WHERE 
            c.status = "published"
        AND ';
        if(!empty($_GET['s'])) {
            $sql .= ' c.name LIKE "%' . $_GET['s'] . '%" OR';
            $sql .= ' c.description LIKE "%' . $_GET['s'] . '%"';
        }
        if(!empty($_GET['topic'])) {
        foreach($_GET['topic'] as $tid) {
            $sql .= 'c.topic_id = ' . $tid;
            if($tid != $lastkey) $sql .= ' OR ';
        }
        }
        $sql .= ';';

$statement = $db->prepare($sql);
$result = $statement->execute();

?>

<?php while ($row = $result->fetchArray()): ?>
    <div class="p-3 mb-2 bg-light-subtle rounded-3">
        <h2 class="fs-4"><a href="course.php?cid=<?= $row['cid'] ?>"><?= $row['cname'] ?></a></h2>
        <div class="mb-3"><?= $row['cdesc'] ?></div>
        <div class="my-3">
            <a class="btn bg-primary text-white" style="" href="<?= $row['curl'] ?>">
                Launch
                <i class="bi bi-box-arrow-up-right"></i>
            </a>
        </div>
        <div>Delivery Method: <a href="filter.php?dmethod=<?= $row['dmid'] ?>"><?= $row['dmname'] ?></a></div>
        <div>Group: <a href="filter.php?group=<?= $row['groupid'] ?>"><?= $row['groupname'] ?></a></div>
        <div>Audience: <a href="filter.php?audience=<?= $row['audienceid'] ?>"><?= $row['audiencename'] ?></a></div>
        <div>Topic: <a href="filter.php?topic[]=<?= $row['topicid'] ?>"><?= $row['topicname'] ?></a></div>
        <div>Partner: <a href="filter.php?partner=<?= $row['partnerid'] ?>"><?= $row['partnername'] ?></a></div>
        <div>Platform: <a href="filter.php?platform=<?= $row['platformid'] ?>"><?= $row['platformname'] ?></a></div>
        <?php if(!empty($row['ckeys'])): ?>
        <?php $keys = explode(',',$row['ckeys']) ?>
        <details>
            <summary>Keywords</summary>
            <?php foreach($keys as $k): ?>
            <a href="keyword.php?keyword=<?= $k ?>"><?= $k ?></a>, 
            <?php endforeach ?>
        </details>
        <?php endif ?>
    </div>
<?php endwhile ?>
</div>
</div>
</div>
<?php require('template/footer.php') ?>