<?php 
$db = new SQLite3('courses.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
// Errors are emitted as warnings by default, enable proper error handling.
$db->enableExceptions(true);
?>


<?php require('template/header.php') ?>
<?php require('template/nav.php') ?>

<div class="container">

<div class="row">
<div class="col-md-12">

    <h1>How is learning organized?</h1>

</div>
<div class="row">
<div class="col-md-6">

<h2>Audiences</h2>
<p>Who is the learning for?</p>

<?php
$statement = $db->prepare('SELECT a.id, a.slug, a.name, a.description, COUNT(c.id) as ccount FROM audiences a LEFT JOIN courses c ON c.audience_id = a.id GROUP BY a.id, a.name;');
$result = $statement->execute();
?>
<?php while ($row = $result->fetchArray()): ?>
    <?php if($row['id'] == 1) continue ?>
    <div class="p-3 mb-2 bg-light-subtle rounded-3">
        <div>
            <a class="fw-bold" href="filter.php?audience=<?= $row['id'] ?>">
                <?= $row['name'] ?> (<?= $row['ccount'] ?>)
            </a>
        </div>
        <div><?= $row['description'] ?></div>
    </div>
<?php endwhile ?>


<h2>Groups</h2>
<p>What type of learning is it?</p>

<?php
$statement = $db->prepare('SELECT g.id, g.name, g.slug, g.description, COUNT(c.id) as ccount FROM groups g LEFT JOIN courses c ON g.id = c.group_id GROUP BY g.id, g.name;');
$result = $statement->execute();
?>
<?php while ($row = $result->fetchArray()): ?>
    <?php if($row['id'] == 1) continue ?>
    <div class="p-3 mb-2 bg-light-subtle rounded-3">
     <div>
        <a class="fw-bold" href="filter.php?group=<?= $row['id'] ?>">
            <?= $row['name'] ?> (<?= $row['ccount'] ?>)
        </a>
    </div>
    <div><?= $row['description'] ?></div>
    </div>
<?php endwhile ?>

<h2>Delivery Methods</h2>
<p>How is the learning offered?</p>

<?php
$statement = $db->prepare('SELECT dm.id, dm.name, dm.slug, dm.description, COUNT(c.id) AS ccount FROM delivery_methods dm LEFT JOIN courses c ON dm.id = c.dmethod_id GROUP BY dm.id, dm.name;');
$result = $statement->execute();
?>
<?php while ($row = $result->fetchArray()): ?>
    <?php if($row['id'] == 1) continue ?>
    <div class="p-3 mb-2 bg-light-subtle rounded-3">
        <div>
            <a class="fw-bold" href="filter.php?delivery_method=<?= $row['id'] ?>">
                <?= $row['name'] ?> (<?= $row['ccount'] ?>)
            </a>
        </div>
        <div><?= $row['description'] ?></div>
    </div>
<?php endwhile ?>

<h2>Platforms</h2>
<p>Where do you register for the learning?</p>

<?php
$statement = $db->prepare('SELECT p.id, p.name, p.description, COUNT(c.id) as ccount FROM learning_platforms p LEFT JOIN courses c ON p.id = c.platform_id GROUP BY p.id, p.name;');
$result = $statement->execute();
?>
<?php while ($row = $result->fetchArray()): ?>
    <?php if($row['id'] == 1) continue ?>
    <div class="p-3 mb-2 bg-light-subtle rounded-3">
    <div>
        <a class="fw-bold" href="filter.php?platform=<?= $row['id'] ?>">
            <?= $row['name'] ?> (<?= $row['ccount'] ?>)
        </a>
    </div>
    <div><?= $row['description'] ?></div>
    </div>
<?php endwhile ?>

</div>
<div class="col-md-6">

<h2>Topics</h2>
<p>What is the learning about?</p>

<?php
$statement = $db->prepare('SELECT t.id, t.name, t.description, COUNT(c.id) AS ccount FROM topics t LEFT JOIN courses c ON t.id = c.topic_id GROUP BY t.id, t.name;');
$result = $statement->execute();
?>
<?php while ($row = $result->fetchArray()): ?>
    <?php if($row['id'] == 1) continue ?>
    <div class="p-3 mb-2 bg-light-subtle rounded-3">
    <div>
        <a class="fw-bold" href="filter.php?topic[]=<?= $row['id'] ?>">
            <?= $row['name'] ?> (<?= $row['ccount'] ?>)
        </a>
    </div>
    <div><?= $row['description'] ?></div>
    </div>
<?php endwhile ?>



<details class="mb-5">
<summary class="bg-dark-subtle p-3 my-4">Learning Partners</summary>

<?php
$statement = $db->prepare('SELECT p.id, p.name, p.description, p.url, COUNT(c.id) as ccount FROM learning_partners p LEFT JOIN courses c ON p.id = c.platform_id GROUP BY p.id, p.name;');
$result = $statement->execute();
?>
<?php while ($row = $result->fetchArray()): ?>
    <?php if($row['id'] == 1) continue ?>
    <div class="p-3 mb-2 bg-light-subtle rounded-3">
    <div>
        <a class="fw-bold" href="filter.php?partner=<?= $row['id'] ?>">
            <?= $row['name'] ?> (<?= $row['ccount'] ?>)
        </a>
    </div>
    <div><?= $row['description'] ?></div>
    <div><a href="<?= $row['url'] ?>" target="_blank">Visit Partner Website</a></div>
    </div>
<?php endwhile ?>
</details>
</div>
</div>
</div>
<?php require('template/footer.php') ?>