<?php 
$db = new SQLite3('courses.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
// Errors are emitted as warnings by default, enable proper error handling.
$db->enableExceptions(true);

// Get the feed and parse it into an array.
// The following JSON end point is created as part of the ETL process 
// of LSApp https://gww.bcpublicservice.gov.bc.ca/lsapp/course-feed/
// Which takes two separate ELM queries and merges the output into this
// JSON feed.
$f = file_get_contents('https://learn.bcpublicservice.gov.bc.ca/learning-hub/learning-partner-courses.json');
$feed = json_decode($f);

// Create a simple index of course IDs that are in the feed
// so that we can easily use in_array to compare against while
// we loop through all the published courses.
$feedindex = [];
foreach($feed->items as $feedcourse) {
    if(!empty($feedcourse->_course_id)) {
        array_push($feedindex, $feedcourse->_course_id);
    }
}


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


    