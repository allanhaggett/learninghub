<?php 
$db = new SQLite3('courses.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
// Errors are emitted as warnings by default, enable proper error handling.
$db->enableExceptions(true);

if($_POST):


    $status = $_POST['coursestatus'];  // required
    $sortorder = $_POST['sortorder'] ?? 0;  // optional
    $name = $_POST['coursename']; // required
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['coursename'])));
    $description = $_POST['coursedesc']; // required
    $created = date('Y-m-d H:i:s'); // required
    $modified = ''; // can't assign yet duh
    $expiry_date = $_POST['courseexpire'] ?? ''; // optional
    $user_idir = $_POST['user_idir'];  // required
    $course_id = $_POST['course_id'] ?? ''; // optional
    $weight = $_POST['weight'] ?? 0; // optional
    $url = $_POST['courselink']; // required
    $keywords = $_POST['keywords']; // required
    $refresh_cycle = $_POST['refresh_cycle'] ?? ''; // required
    $partner_id = $_POST['partner_id']; // required
    $platform_id = $_POST['platform_id']; // required
    $dmethod_id = $_POST['dmethod_id']; // required
    $group_id = $_POST['group_id']; // required
    $audience_id = $_POST['audience_id']; // required
    $topic_id = $_POST['topic_id']; // required

    $sql = "INSERT INTO courses (
                            status,
                            sortorder,
                            name,
                            slug,
                            description,
                            created,
                            modified,
                            expiry_date,
                            user_idir,
                            course_id,
                            weight,
                            url,
                            keywords,
                            refresh_cycle,
                            partner_id,
                            platform_id,
                            dmethod_id,
                            group_id,
                            audience_id,
                            topic_id
                        ) VALUES (
                            :status,
                            :sortorder,
                            :name,
                            :slug,
                            :description,
                            :created,
                            :modified,
                            :expiry_date,
                            :user_idir,
                            :course_id,
                            :weight,
                            :url,
                            :keywords,
                            :refresh_cycle,
                            :partner_id,
                            :platform_id,
                            :dmethod_id,
                            :group_id,
                            :audience_id,
                            :topic_id
                        )";

    $statement = $db->prepare($sql);

    $statement->bindValue(':status',$status);
    $statement->bindValue(':sortorder',$sortorder);
    $statement->bindValue(':name',$name);
    $statement->bindValue(':slug',$slug);
    $statement->bindValue(':description',$description);
    $statement->bindValue(':created',$created);
    $statement->bindValue(':modified',$modified);
    $statement->bindValue(':expiry_date',$expiry_date);
    $statement->bindValue(':user_idir',$user_idir);
    $statement->bindValue(':course_id',$course_id);
    $statement->bindValue(':weight',$weight);
    $statement->bindValue(':url',$url);
    $statement->bindValue(':keywords',$keywords);
    $statement->bindValue(':refresh_cycle',$refresh_cycle);
    $statement->bindValue(':partner_id',$partner_id);
    $statement->bindValue(':platform_id',$platform_id);
    $statement->bindValue(':dmethod_id',$dmethod_id);
    $statement->bindValue(':group_id',$group_id);
    $statement->bindValue(':audience_id',$audience_id);
    $statement->bindValue(':topic_id', $topic_id);

    $statement->execute();
    // $courseid = $db->lastInsertId();
    // echo $courseid;
    header('Location: courses.php');

else:
?>

<?php require('template/header.php') ?>


<div class="container">
<form method="post" action="course-add.php">
<input type="hidden" name="user_idir" id="user_idir" value="ahaggett">
<div class="row">
<div class="col-md-6">
    <h1>Add a new course</h1>
</div>
</div>
<div class="row">
<div class="col-md-5">
 
<div class="p-3 mb-2 bg-light-subtle">
    <label class="fw-bold" for="coursestatus">Status</label>
    <select id="coursestatus" name="coursestatus" class="form-select d-inline" id="">
        <option value="published">Published</option>
        <option value="tbsheduled">To be scheduled</option>
        <option value="private">Private</option>
    </select>
</div>
<div class="p-3 mb-2 bg-light-subtle">
    <label class="fw-bold" for="coursename">Course Name</label>
    <input id="coursename" name="coursename" class="form-control form-control-lg" type="text" placeholder="Enter course name" required>
</div>
<div class="p-3 mb-2 bg-light-subtle">
    <label class="fw-bold" for="coursedesc">Course Description</label>
    <textarea id="coursedesc" name="coursedesc" class="form-control" required></textarea>
</div>
<div class="p-3 mb-2 bg-light-subtle">
    <label class="fw-bold" for="courselink">Course Link</label>
    <input id="courselink" name="courselink" class="form-control" type="text" placeholder="https://..." required>
</div>
<div class="p-3 mb-2 bg-light-subtle">
    <label class="fw-bold" for="keywords">Keywords</label>
    <input id="keywords" name="keywords" class="form-control" type="text" placeholder="Comma separated values">
</div>
<div class="p-3 mb-2 bg-light-subtle">
    <label class="fw-bold" for="courseexpire">Expiration date</label>
    <input id="courseexpire" name="courseexpire" class="form-control" type="date" placeholder="https://...">
</div>

<button class="my-5 btn btn-lg d-block btn-primary">Add Course</button>

</div>
<div class="col-md-7">


<div class="row">
<div class="col-md-6">

<div class="p-3 mb-2 bg-light-subtle">
<label class="fw-bold" for="audience_id">Audience</label>
<p>Who is the learning for?</p>
<?php
$statement = $db->prepare('SELECT * FROM audiences;');
$result = $statement->execute();
?>
<select id="audience_id" name="audience_id" class="form-select" required>
<?php while ($row = $result->fetchArray()): ?>
<option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
<?php endwhile ?>
</select>

</div>
<div class="p-3 mb-2 bg-light-subtle">

<label class="fw-bold" for="groupselect">Group</label>
<p>What type of learning is it?</p>

<?php
$statement = $db->prepare('SELECT * FROM groups;');
$result = $statement->execute();
?>
<select id="group_id" name="group_id" class="form-select" required>
<?php while ($row = $result->fetchArray()): ?>
<option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
<?php endwhile ?>
</select>


</div>
<div class="p-3 mb-2 bg-light-subtle">

<label class="fw-bold" for="dmethod_id">Delivery Method</label>
<p>How is the learning offered?</p>

<?php
$statement = $db->prepare('SELECT * FROM delivery_methods;');
$result = $statement->execute();
?>
<select id="dmethod_id" name="dmethod_id" class="form-select" required>
<?php while ($row = $result->fetchArray()): ?>
<option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
<?php endwhile ?>
</select>

</div>

</div>
<div class="col-md-6">

<div class="p-3 mb-2 bg-light-subtle">

<label class="fw-bold" for="partner_id">Learning Partner</label>
<p>Who is offering the learning?</p>

<?php
$statement = $db->prepare('SELECT * FROM learning_partners;');
$result = $statement->execute();
?>
<select id="partner_id" name="partner_id" class="form-select" required>
<?php while ($row = $result->fetchArray()): ?>
<option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
<?php endwhile ?>
</select>


</div>
<div class="p-3 mb-2 bg-light-subtle">

<label class="fw-bold" for="topic_id">Topic</label>
<p>What is the learning about?</p>

<?php
$statement = $db->prepare('SELECT * FROM topics;');
$result = $statement->execute();
?>
<select id="topic_id" name="topic_id" class="form-select" required>
<?php while ($row = $result->fetchArray()): ?>
<option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
<?php endwhile ?>
</select>


</div>
<div class="p-3 mb-2 bg-light-subtle">


<label class="fw-bold" for="platform_id">Platform</label>
<p>Where do you register?</p>

<?php
$statement = $db->prepare('SELECT * FROM learning_platforms;');
$result = $statement->execute();
?>
<select id="platform_id" name="platform_id" class="form-select" required>
<?php while ($row = $result->fetchArray()): ?>
<option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
<?php endwhile ?>
</select>

</div>
</div>
</div>
</div>


</div>
</div>

</form>

</div>
</body>
</html>
<?php endif ?>