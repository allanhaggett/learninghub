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

<h2>Audiences</h2>
<p>Who is the learning for?</p>

<?php
$statement = $db->prepare('SELECT * FROM audiences;');
$result = $statement->execute();
?>
<?php while ($row = $result->fetchArray()): ?>
    <?php if($row['id'] == 1) continue ?>
    <div class="p-3 mb-2 bg-light-subtle rounded-3">
        <div><a class="fw-bold" href="filter.php?audience=<?= $row['slug'] ?>"><?= $row['name'] ?></a></div>
        <div><?= $row['description'] ?></div>
    </div>
<?php endwhile ?>


<h2>Groups</h2>
<p>What type of learning is it?</p>

<?php
$statement = $db->prepare('SELECT * FROM groups;');
$result = $statement->execute();
?>
<?php while ($row = $result->fetchArray()): ?>
    <?php if($row['id'] == 1) continue ?>
    <div class="p-3 mb-2 bg-light-subtle rounded-3">
        <div><a class="fw-bold" href="filter.php?group=<?= $row['slug'] ?>"><?= $row['name'] ?></a></div>
        <div><?= $row['description'] ?></div>
    </div>
<?php endwhile ?>

<h2>Delivery Methods</h2>
<p>How is the learning offered?</p>

<?php
$statement = $db->prepare('SELECT * FROM delivery_methods;');
$result = $statement->execute();
?>
<?php while ($row = $result->fetchArray()): ?>
    <?php if($row['id'] == 1) continue ?>
    <div class="p-3 mb-2 bg-light-subtle rounded-3">
        <div><a class="fw-bold" href="filter.php?delivery_method=<?= $row['slug'] ?>"><?= $row['name'] ?></a></div>
        <div><?= $row['description'] ?></div>
    </div>
<?php endwhile ?>

<h2>Learning Partners</h2>
<p></p>

<?php
$statement = $db->prepare('SELECT * FROM learning_partners;');
$result = $statement->execute();
?>
<?php while ($row = $result->fetchArray()): ?>
    <?php if($row['id'] == 1) continue ?>
    <div class="p-3 mb-2 bg-light-subtle rounded-3">
        <div><a class="fw-bold" href="filter.php?partner=<?= $row['slug'] ?>"><?= $row['name'] ?></a></div>
        <div><?= $row['description'] ?></div>
        <div><a href="<?= $row['url'] ?>" target="_blank">Visit Partner Website</a></div>
    </div>
<?php endwhile ?>

</div>
<div class="col-md-6">

<h2>Topics</h2>
<p>What is the learning about?</p>

<?php
$statement = $db->prepare('SELECT * FROM topics;');
$result = $statement->execute();
?>
<?php while ($row = $result->fetchArray()): ?>
    <?php if($row['id'] == 1) continue ?>
    <div class="p-3 mb-2 bg-light-subtle rounded-3">
        <div><a class="fw-bold" href="filter.php?topic[]=<?= $row['id'] ?>"><?= $row['name'] ?></a></div>
        <div><?= $row['description'] ?></div>
    </div>
<?php endwhile ?>

<h2>Platforms</h2>
<p>Where do you register for the learning?</p>

<?php
$statement = $db->prepare('SELECT * FROM learning_platforms;');
$result = $statement->execute();
?>
<?php while ($row = $result->fetchArray()): ?>
    <?php if($row['id'] == 1) continue ?>
    <div class="p-3 mb-2 bg-light-subtle rounded-3">
        <div><a class="fw-bold" href="filter.php?platform=<?= $row['slug'] ?>"><?= $row['name'] ?></a></div>
        <div><?= $row['description'] ?></div>
    </div>
<?php endwhile ?>

  

</div>
</div>
</div>
<?php require('template/footer.php') ?>