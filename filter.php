<?php 
/**
 * LearningHUB 2.0
 */

$db = new SQLite3('courses.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
// Errors are emitted as warnings by default, enable proper error handling.
$db->enableExceptions(true);

// Parse the URL as it's been passed and break it apart so we can 
// build the URLs for filtering navigation
// $parts = parse_url($_SERVER['REQUEST_URI']);
// print_r($parts['query']); exit;
// parse_str($parts['query'], $query);
// $urlquery = $parts['query'];

// What topic(s) are we filtering upon? There can be more than one topic
// and we're using topic[]=1 parameter arrays 
if(!empty($_GET['audience'])) {
    $audsql = 'SELECT * FROM audiences WHERE '; 
    $lastkey = end($_GET['audience']);
    foreach($_GET['audience'] as $aid) {
        $audsql .= 'id = ' . $aid;
        if($aid != $lastkey) {
            $audsql .= ' OR ';
        }
    }
    $audsql .= ';';
    $audinfo = $db->prepare($audsql);
    $aud = $audinfo->execute();
}
if(!empty($_GET['group'])) {
    $topsql = 'SELECT * FROM groups WHERE '; 
    $lastkey = end($_GET['group']);
    foreach($_GET['group'] as $gid) {
        $grpsql .= 'id = ' . $gid;
        if($gid != $lastkey) {
            $grpsql .= ' OR ';
        }
    }
    $grpsql .= ';';
    $grpinfo = $db->prepare($grpsql);
    $grp = $grpinfo->execute();
}
if(!empty($_GET['topic'])) {
    $topsql = 'SELECT * FROM topics WHERE '; 
    $lastkey = end($_GET['topic']);
    foreach($_GET['topic'] as $tid) {
        $topsql .= 'id = ' . $tid;
        if($tid != $lastkey) {
            $topsql .= ' OR ';
        }
    }
    $topsql .= ';';
    $topicinfo = $db->prepare($topsql);
    $top = $topicinfo->execute();
}

?>
<?php require('template/header.php') ?>

<div class="container">
<div class="row justify-content-md-center">
<div class="col-md-4">



<h2>Audiences</h2>
<!-- <p class="mb-1">Who is the learning for?</p> -->
<?php
$statement = $db->prepare('SELECT a.id, a.slug, a.name, a.description, COUNT(c.id) as ccount FROM audiences a LEFT JOIN courses c ON c.audience_id = a.id WHERE c.status = "published" GROUP BY a.id, a.name;');
$result = $statement->execute();
?>
<form class="p-3 mb-3 bg-light-subtle border border-secondary-subtle rounded-3"
        action="filter.php"
        method="GET">
<?php while ($row = $result->fetchArray()): ?>
<?php if($row['id'] == 1) continue ?>
<?php $active = '' ?>
<?php if(!empty($_GET['audience']) && in_array($row['id'],$_GET['audience'])) $active = 'checked' ?>
<div>
    <label>
        <input type="checkbox" value="<?= $row['id'] ?>" name="audience[]" id="audience<?= $row['id'] ?>" <?= $active ?>>
        <?= $row['name'] ?>
    </label>
    (<?= $row['ccount'] ?>)
</div>
<?php endwhile ?>
<?php if(!empty($_GET['topic'])): ?>
<?php foreach($_GET['topic'] as $tid): ?>
<input type="hidden" name="topic[]" value="<?= $tid ?>">
<?php endforeach ?>
<?php endif ?>
<button class="btn btn-sm bg-dark-subtle mt-2">Apply</button>
</form>




<h2>Groups</h2>
<!-- <p class="mb-1">What type of learning is it?</p> -->

<?php
$statement = $db->prepare('SELECT g.id, g.name, g.slug, g.description, COUNT(c.id) as ccount FROM groups g LEFT JOIN courses c ON g.id = c.group_id GROUP BY g.id, g.name;');
$result = $statement->execute();
?>
<form class="p-3 mb-3 bg-light-subtle border border-secondary-subtle rounded-3">
<?php while ($row = $result->fetchArray()): ?>
<?php if($row['id'] == 1) continue ?>
<div>
    <label>
        <input type="checkbox" value="<?= $row['slug'] ?>">
        <?= $row['name'] ?>
    </label>
    (<?= $row['ccount'] ?>)
</div>
<?php endwhile ?>
<button class="btn btn-sm bg-dark-subtle mt-2">Apply</button>
</form>



<h2>Topics</h2>
<!-- <p class="mb-1">What is the learning about?</p> -->
<?php 
$statement = $db->prepare('SELECT t.id, t.name, t.description, COUNT(c.id) AS ccount FROM topics t LEFT JOIN courses c ON t.id = c.topic_id WHERE c.status = "published" GROUP BY t.id, t.name;');
$result = $statement->execute();
?>
<form class="p-3 mb-3 bg-light-subtle border border-secondary-subtle rounded-3"
        action="filter.php"
        method="GET">
<?php if(!empty($_GET['audience'])): ?>
<?php foreach($_GET['audience'] as $aid): ?>
<input type="hidden" name="audience[]" value="<?= $aid ?>">
<?php endforeach ?>
<?php endif ?>
<?php while ($row = $result->fetchArray()): ?>
<?php if($row['id'] == 1) continue ?>
<?php $active = '' ?>
<?php if(!empty($_GET['topic']) && in_array($row['id'],$_GET['topic'])) $active = 'checked' ?>
<div>
    <label>
        <input type="checkbox" value="<?= $row['id'] ?>" name="topic[]" id="topic<?= $row['id'] ?>" <?= $active ?>>
        <?= $row['name'] ?>
        (<?= $row['ccount'] ?>)
    </label>
</div>
<?php endwhile ?>
<button class="btn btn-sm bg-dark-subtle mt-2">Apply</button>
</form>

<h2>Delivery Methods</h2>
<!-- <p class="mb-1">How is the learning offered?</p> -->

<?php
$statement = $db->prepare('SELECT dm.id, dm.name, dm.slug, dm.description, COUNT(c.id) AS ccount FROM delivery_methods dm LEFT JOIN courses c ON dm.id = c.dmethod_id GROUP BY dm.id, dm.name;');
$result = $statement->execute();
?>
<form class="p-3 mb-3 bg-light-subtle border border-secondary-subtle rounded-3">
<?php while ($row = $result->fetchArray()): ?>
    <?php if($row['id'] == 1) continue ?>
    <div>
        <label>
            <input type="checkbox" value="<?= $row['id'] ?>">
            <?= $row['name'] ?>
        </label>
        (<?= $row['ccount'] ?>)
    </div>
<?php endwhile ?>
<button class="btn btn-sm bg-dark-subtle mt-2">Apply</button>
</form>

<div style="height: 300px"></div>

</div>
<div class="col-md-8">

    <div class="fw-bold">Filters:</div>

    <div class="p-3 mb-3 bg-light-subtle border border-secondary-subtle rounded-3 d-flex">
    <?php if(!empty($_GET['delivery_method'])): ?>
        <div class="flex-fill">
            <div>Delivery Method:</div>
        </div>
    <?php endif ?>
    <?php if(!empty($_GET['group'])): ?>
        <div class="flex-fill">
            <div>Group:</div>
            <?php while ($row = $grp->fetchArray()): ?>
            <div><button class="btn bg-dark-subtle btn-sm">x</button> <?= $row['name'] ?></div>
            <?php endwhile ?>
        </div>
    <?php endif ?>
    <?php if(!empty($_GET['audience'])): ?>
        <div class="flex-fill">
            <div>Audience:</div>
            <?php while ($row = $aud->fetchArray()): ?>
            <div><button class="btn bg-dark-subtle btn-sm">x</button> <?= $row['name'] ?></div>
            <?php endwhile ?>
        </div>
    <?php endif ?>
    <?php if(!empty($_GET['topic'])): ?>
        <div class="flex-fill">
            <div>Topics:</div>
            <?php while ($row = $top->fetchArray()): ?>
            <div><button class="btn bg-dark-subtle btn-sm">x</button> <?= $row['name'] ?></div>
            <?php endwhile ?>
        </div>
    <?php endif ?>

</div>



<?php
// if(!empty($_GET['s']) && !empty($_GET['audience']) && !empty($_GET['topic'])) :
// Setup initial query with all the joins
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

// Keyword search 
if(!empty($_GET['s'])) {
    $sql .= ' c.search LIKE :search';
}

// Audience filter
if(!empty($_GET['audience'])) {

    if(!empty($_GET['s'])) {
        $sql .= ' AND (';
    } else {
        $sql .= ' (';
    }
    $lastkey = end($_GET['audience']);
    foreach($_GET['audience'] as $aid) {
        $sql .= 'c.audience_id = ' . $aid;
        if($aid != $lastkey) $sql .= ' OR ';
    }
    $sql .= ')';
}

// Topic filter
if(!empty($_GET['topic'])) {

    if(!empty($_GET['audience'])) {
        $sql .= ' AND (';
    } else {
        $sql .= ' (';
    }
    $lastkey = end($_GET['topic']);
    foreach($_GET['topic'] as $tid) {
        $sql .= 'c.topic_id = ' . $tid;
        if($tid != $lastkey) $sql .= ' OR ';
    }
    $sql .= ')';
}
$sql .= ' ORDER BY platform_last_updated DESC;';
// echo '<pre>' . $sql . '</pre>';

$statement = $db->prepare($sql);

if(!empty($_GET['s'])) {
    $sq = '%' . $_GET['s'] . '%';
    $statement->bindValue(':search',$sq);
}
$result = $statement->execute();
while($rows[] = $result->fetchArray()){} $count = count($rows);
?>
<div class="mb-3 d-flex">
    <div class="mr-3 pt-1 fw-bold"><?= $count - 1 ?> courses</div>
    <div class="dropdown px-2">
        <button class="btn btn-sm bg-dark-subtle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Sort by
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Most Recent</a></li>
            <li><a class="dropdown-item" href="#">Alphabetical</a></li>
            <li><a class="dropdown-item" href="#">Delivery Method</a></li>
            <li><a class="dropdown-item" href="#">Group</a></li>
            <li><a class="dropdown-item" href="#">Audience</a></li>
            <li><a class="dropdown-item" href="#">Topic</a></li>
        </ul>
    </div>
    <button id="expall" class="btn btn-sm bg-dark-subtle px-2 d-block">Expand All</button>
    <button id="collapseall" class="btn btn-sm bg-dark-subtle px-2 d-block">Collapse All</button>
</div>
<div id="courselist">

<?php while ($row = $result->fetchArray()): ?>
<div class="bg-light-subtle p-2 mb-2 border border-secondary-subtle rounded-3">
    <details>
        <summary class="coursename mb-0 ms-3" style="list-style-position: outside;">
            <div class="d-flex justify-content-between">
                <div class="fw-bold"><?= $row['cname'] ?></div>
                <div class="text-muted text-decoration-none text-end flex-shrink-0 mt-1" style="font-size: 12px;">
                    <div class="ms-3">
                        <div title="Delivery Method">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="d-inline-block" viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                <path d="M160 64c0-35.3 28.7-64 64-64H576c35.3 0 64 28.7 64 64V352c0 35.3-28.7 64-64 64H336.8c-11.8-25.5-29.9-47.5-52.4-64H384V320c0-17.7 14.3-32 32-32h64c17.7 0 32 14.3 32 32v32h64V64L224 64v49.1C205.2 102.2 183.3 96 160 96V64zm0 64a96 96 0 1 1 0 192 96 96 0 1 1 0-192zM133.3 352h53.3C260.3 352 320 411.7 320 485.3c0 14.7-11.9 26.7-26.7 26.7H26.7C11.9 512 0 500.1 0 485.3C0 411.7 59.7 352 133.3 352z" />
                            </svg>
                            <a href="filter.php?dmethod=<?= $row['dmid'] ?>"><?= $row['dmname'] ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </summary>
        <div class="mt-2 ms-3">
            <div class="mb-3">
            <?= $row['cdesc'] ?>
            </div>
                <div class="my-2">
                    <a class="bg-gov-blue btn btn-primary" href="<?= $row['curl'] ?>" target="_blank" rel="noopener">
                        Launch
                        <span class="icon-svg baseline-svg"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16" width="16" height="16">
                                <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z" />
                                <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z" />
                            </svg></span>
                    </a>
                    <div class="d-inline-block ms-2" style="font-size: 14px">
                        <a title="Permanent link to this course's page" style="text-decoration: none;" href="course.php?cid=<?= $row['cid'] ?>">
                            <span class="icon-svg baseline-svg"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" width="16" height="16">
                                    <path d="M6.354 5.5H4a3 3 0 0 0 0 6h3a3 3 0 0 0 2.83-4H9c-.086 0-.17.01-.25.031A2 2 0 0 1 7 10.5H4a2 2 0 1 1 0-4h1.535c.218-.376.495-.714.82-1z" />
                                    <path d="M9 5.5a3 3 0 0 0-2.83 4h1.098A2 2 0 0 1 9 6.5h3a2 2 0 1 1 0 4h-1.535a4.02 4.02 0 0 1-.82 1H12a3 3 0 1 0 0-6H9z" />
                                </svg></span>Course page
                        </a>
                    </div>
                </div>

                <div class="text-muted text-decoration-none" style="font-size: 14px;">
                <p>
                    <strong>Partner:</strong>
                    <a href="filter.php?partner=<?= $row['partnerid'] ?>" rel="tag"><?= $row['partnername'] ?></a>
                     <br>
                    <strong>Platform:</strong> 
                    <a class="text-decoration-none" href="filter.php?platform=<?= $row['platformid'] ?>">
                            <?= $row['platformname'] ?>
                    </a>
                </p>


            <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
                <div title="Group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="d-inline-block" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                        <path d="M264.5 5.2c14.9-6.9 32.1-6.9 47 0l218.6 101c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 149.8C37.4 145.8 32 137.3 32 128s5.4-17.9 13.9-21.8L264.5 5.2zM476.9 209.6l53.2 24.6c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 277.8C37.4 273.8 32 265.3 32 256s5.4-17.9 13.9-21.8l53.2-24.6 152 70.2c23.4 10.8 50.4 10.8 73.8 0l152-70.2zm-152 198.2l152-70.2 53.2 24.6c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 405.8C37.4 401.8 32 393.3 32 384s5.4-17.9 13.9-21.8l53.2-24.6 152 70.2c23.4 10.8 50.4 10.8 73.8 0z" />
                    </svg>
                    <a href="filter.php?group=<?= $row['groupid'] ?>" rel="tag"><?= $row['groupname'] ?></a>
                </div>
                <div title="Audience">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="16" height="16" fill="currentColor" class="d-inline-block"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                        <path d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192h42.7c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0H21.3C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7h42.7C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3H405.3zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352H378.7C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7H154.7c-14.7 0-26.7-11.9-26.7-26.7z" />
                    </svg>
                    <a href="filter.php?audience=<?= $row['audienceid'] ?>" rel="tag"><?= $row['audiencename'] ?></a>
                </div>
                <div title="Topic">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="d-inline-block" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                        <path d="M0 80V229.5c0 17 6.7 33.3 18.7 45.3l176 176c25 25 65.5 25 90.5 0L418.7 317.3c25-25 25-65.5 0-90.5l-176-176c-12-12-28.3-18.7-45.3-18.7H48C21.5 32 0 53.5 0 80zm112 32a32 32 0 1 1 0 64 32 32 0 1 1 0-64z" />
                    </svg>
                    <a href="filter.php?topic[]=<?= $row['topicid'] ?>" rel="tag"><?= $row['topicname'] ?></a>                </div>
            </div>
            </div>
            
        </div>

    </details>
</div>


<?php endwhile ?>

</div>
<div style="height: 300px"></div>
</div>
</div>
</div>
<script type="module">
// ||||||||||||||||||||
// 
// Details/Summary niceties
//
// By default, all the courses are hidden behind a details/summary
// and subsequently the description/launch links are as well.
// This supports allowing the learner to choose to "expand all" and 
// show everything on the page all at once, or "collapse all" and 
// hide everything. 
//
// ||||||||||||||||||||

// Show everything all in once fell swoop.
let expall = document.getElementById('expall');
let steplist = document.getElementById('courselist');
let deets = steplist.querySelectorAll('details');
expall.addEventListener('click', (e) => {
    Array.from(deets).forEach(function(element) {
        element.setAttribute('open','open');
    });
});
// Conversley, "collapse all" hides everyting open in one fell swoop.
let collapseall = document.getElementById('collapseall');
collapseall.addEventListener('click', (e) => {
    Array.from(deets).forEach(function(element) {
        element.removeAttribute('open');
    });
});
</script>

<?php require('template/footer.php') ?>