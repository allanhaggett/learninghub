<?php 
/**
 * LearningHUB 2
 * 
 * Platform Sync for PSA Learning System
 * https://learning.gov.bc.ca/CHIPSPLM/signon.html
 * 
 * PSA Learning System (PSALS) is the primary registration portal for Corporate 
 * Learning in the BC Public Service. It's not the only platform in use,
 * but it is the most widely used. PSALS also acts as BC Gov employees'
 * official training record.
 * 
 * Learners should not ever be faced with the dilema of searching the 
 * catalog, finding a course they want to take and following the link to it,
 * only to find that that course is no longer being offered.
 * 
 * As such, the requirement here is that only courses which appear in this feed 
 * should appear on the site as "available" (or published or whatever).
 * If a course exists in the database here, but it doesn't exist in the feed
 * that course needs to be updated so that it can be segregated from the 
 * regular search results.
 * 
 * The trick here is that a course can become unavailable, but then become
 * available again after a period of time, so we don't want to just delete 
 * courses that don't exist.
 * 
 */

 // Open the database so we can select the courses from it.
$db = new SQLite3('courses.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
// Errors are emitted as warnings by default, enable proper error handling.
$db->enableExceptions(true);

// Get the feed and parse it into an array.
// The following JSON end point is created as part of the ETL process 
// of LSApp https://gww.bcpublicservice.gov.bc.ca/lsapp/course-feed/
// Which takes two separate ELM queries and merges the output into this
// JSON feed.
// Get the feed.
// $url = 'https://learn.bcpublicservice.gov.bc.ca/learning-hub/learning-partner-courses.json';
$url = 'platforms/psa-learning-system.json';
$f = file_get_contents($url);
$feed = json_decode($f);

// Loop through the feed and create a simple index of course IDs that 
// are in the feed so that we can easily use in_array to compare against while
// we loop through all the published courses.
// If I'm understanding the concept correctly, this is basically
// a hashmap. It is much faster and simpler to check the simple list than 
// it is the loop through the entire feed every iteration. 
$feedindex = [];
foreach($feed->items as $feedcourse) {
    if(!empty($feedcourse->_course_id)) {
        array_push($feedindex, $feedcourse->_course_id);
    }
}

?>

<h1>PSA Learning System Sync</h1>

<?php

// Now we can loop through each of the exisiting published courses
// and check each against the feedindex array.
//
// If we find a match, then we can look to getting the info from the feed 
// and updating anything that needs updating e.g., add/remove keywords/topics.
// 
// If there isn't a match, then the course isn't in the feed and needs to 
// be marked as unavailable or otherwise.
//
// This loop through published courses only covers updates to exisiting 
// courses and marking private (removing) courses that aren't in the feed.
// After this loop is complete we do another run through the individual 
// courses in the feed to cover adding any new courses that don't exist yet.
// 

//
// Start by getting all the courses that are listed as being in the 
// PSA Learning System, whatever the status. We even want existing private 
// courses so that we can simply update and set back to published instead
// of creating a whole new one. We want to maintain course IDs through updates.
//
$sql = 'SELECT 
            c.id AS cid, 
            c.name AS cname, 
            c.slug AS cslug, 
            c.status AS cstatus, 
            c.description AS cdesc, 
            c.course_id AS courseid, 
            c.url AS curl, 
            c.keywords AS ckeys, 
            dm.name AS dmname,
            dm.id AS dmid,
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
            c.platform_id = 2;';

$statement = $db->prepare($sql);
$result = $statement->execute();

// $countsql = "SELECT count(*) FROM courses;";
// $statement = $db->prepare($countsql);
// $coursecount = $statement->execute();
// print_r($coursecount); exit;
// Also how many courses are we talking about?
// $nRows = $db->query('select count(*) from blah')->fetchColumn(); 
// echo $nRows; exit;

//
// Create the array to array_push the existing course titles into
$courseindex = [];
// Loop though all the PSALS courses in the system.
// echo '<pre>';print_r($result->fetchArray()); exit;
$count = 1;
while ($row = $result->fetchArray()):
    
    // Start by adding all the course titles to the courseindex array so that
    // after this loop runs through, we can loop through the feed again
    // and find the courses that are new and need to be created from scratch.
    array_push($courseindex, $row['courseid']);

    // Does the course title match a title that's in the feed?
    if(in_array($row['courseid'], $feedindex)) {

        // Get the details for the feedcourse so we can compare
        foreach($feed->items as $f) {
            if(!empty($f->_course_id)) {
                if($f->_course_id == $row['courseid']) {
                    $feedcourse = $f;
                }
            }
        }
    
        // Set a flag to determine if the course has been updated
        // so that we can not touch the database if we don't need to.
        $courseupdated = 0;

        // status
        if($row['cstatus'] != 'published') {
            $row['cstatus'] = 'published';
            $courseupdated = 1;
        }

        // name
        if($feedcourse->title != $row['cname']) {
            $row['cname'] = $feedcourse->title;
            $newslug = strtolower(
                trim(
                    preg_replace('/[^A-Za-z0-9-]+/', '-', $feedcourse->title)
                )
            );
            $row['slug'] = $newslug;
            $courseupdated = 1;
        }
        
        // URL
        if($feedcourse->url != $row['curl']) {
            $row['curl'] = $feedcourse->url;
            $courseupdated = 1;
        }

        // keywords
        if($feedcourse->_keywords != $row['ckeys']) {
            $row['ckeys'] = $feedcourse->_keywords;
            $courseupdated = 1;
        }

        // delivery_method
        $feeddmid = map_dmethod_to_id($feedcourse->delivery_method);
        if($feeddmid != $row['dmid']) {
            $row['dmid'] = $feeddmid;
            $courseupdated = 1;
        }

        // groups
        $feedgrid = map_group_to_id($feedcourse->_group);
        if($feedgrid != $row['groupid']) {
            $row['groupid'] = $feedgrid;
            $courseupdated = 1;
        }

        // audience
        $feedaudienceid = map_audience_to_id($feedcourse->_audience);
        if($feedaudienceid != $row['audienceid']) {
            $row['audienceid'] = $feedaudienceid;
            $courseupdated = 1;
        }

        // topics
        $feedtopid = map_topic_to_id($feedcourse->_topic);
        if($feedtopid != $row['topicid']) {
            $row['topicid'] = $feedtopid;
            $courseupdated = 1;
        }

        // learning_partners
        $feedpartid = map_partner_to_id($feedcourse->_learning_partner);
        if($feedpartid != $row['partnerid']) {
            $row['partnerid'] = $feedpartid;
            $courseupdated = 1;
        }


        if($courseupdated == 1) {
            
            // Construct SQL query
            $sql = "UPDATE courses SET
                                    status = :status,
                                    name = :name,
                                    slug = :slug,
                                    description = :description,
                                    modified = :modified,
                                    course_id = :course_id,
                                    url = :url,
                                    keywords = :keywords,
                                    partner_id = :partner_id,
                                    dmethod_id = :dmethod_id,
                                    group_id = :group_id,
                                    audience_id = :audience_id,
                                    topic_id = :topic_id
                                WHERE
                                    id = :courseid";

            $statement = $db->prepare($sql);

            $statement->bindValue(':status',$row['cstatus']);
            $statement->bindValue(':name',$row['cname']);
            $statement->bindValue(':slug',$row['cslug']);
            $statement->bindValue(':description',$row['cdesc']);
            $statement->bindValue(':modified',date('Y-m-d H:i:s'));
            $statement->bindValue(':course_id',$row['courseid']);
            $statement->bindValue(':url',$row['curl']);
            $statement->bindValue(':keywords',$row['ckeys']);
            $statement->bindValue(':partner_id',$row['partnerid']);
            $statement->bindValue(':dmethod_id',$row['dmid']);
            $statement->bindValue(':group_id',$row['groupid']);
            $statement->bindValue(':audience_id',$row['audienceid']);
            $statement->bindValue(':topic_id', $row['topicid']);
            // WHERE 
            $statement->bindValue(':courseid', $row['cid']);
            $statement->execute();

            echo $count . '. ' . $row['cname'] . ' UPDATED!<br>';

        } else { // there are no updates so just say so.

            echo $count . '. ' . $row['cname'] . ' - ' . $row['courseid'] . ' - NO CHANGE.<br>';
        }
        $count++;


    } else { // Does the course ID match an ID that's in the feed?

        // This course is not in the feed anymore.
        // Make it private if it isn't already.
        if($row['cstatus'] != 'private') {
            $sql = 'UPDATE courses SET status = :status WHERE id = :id;';
            $statement = $db->prepare($sql);
            $stat = 'private';
            $statement->bindValue(':status',$stat);
            $statement->bindValue(':id',$row['cid']);
            $statement->execute();

            echo $row['cname'] . ' - ' . $row['cid'] . ' - REMOVED!<br>';

        } else {
            
            echo $row['cname'] . ' - ' . $row['cid'] . ' - PRIVATE.<br>';
        }

    }
endwhile;

//
// Next, let's loop through the feed again, this time looking at the newly created
// $courseindex array with just the published course IDs in it for easy lookup
//
// If the course doesn't exist within the catalog yet, then we create it!
//
$count = 1;
foreach($feed->items as $feedcourse) {
    if(!empty($feedcourse->_course_id)) {
        if(!in_array($feedcourse->_course_id, $courseindex) && !empty($feedcourse->title)) {

            // This course isn't in the list of published courses
            // so it is new, so we need to create this course from scratch.
            // Set up the new course with basic settings in place

            $status = 'published';  // required
            $sortorder = 0;  // optional
            $name = trim($feedcourse->title); // required
            $slug = strtolower(
                        trim(
                            preg_replace('/[^A-Za-z0-9-]+/', '-', $feedcourse->title)
                        )
                    );
            $description = $feedcourse->summary; // required
            $created = date('Y-m-d H:i:s'); // required
            $modified = ''; // can't assign yet duh
            $expiry_date = ''; // optional
            $user_idir = 'syncbot';  // required
            $course_id = $feedcourse->_course_id; // optional
            $weight = 0; // optional
            $url = $feedcourse->url; // required
            $keywords = $feedcourse->_keywords; // required
            $refresh_cycle = ''; // optional
            $platform_id = 2; // required

            // $topic_id = $_POST['topic_id']; // required
            $topic_id = map_topic_to_id($feedcourse->_topic);
            
            // $partner_id = $_POST['partner_id']; // required
            $partner_id = map_partner_to_id($feedcourse->_learning_partner);
            
            // $dmethod_id = $_POST['dmethod_id']; // required
            $dmethod_id = map_dmethod_to_id($feedcourse->delivery_method);
            
            // $group_id = $_POST['group_id']; // required
            $group_id = map_group_to_id($feedcourse->_group);
            
            // $audience_id = $_POST['audience_id']; // required
            $audience_id = map_audience_to_id($feedcourse->_audience);

            // Construct SQL query
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
            $statement->bindValue(':sortorder',$sortorder, PDO::PARAM_INT);
            $statement->bindValue(':name',$name);
            $statement->bindValue(':slug',$slug);
            $statement->bindValue(':description',$description);
            $statement->bindValue(':created',$created);
            $statement->bindValue(':modified',$modified);
            $statement->bindValue(':expiry_date',$expiry_date);
            $statement->bindValue(':user_idir',$user_idir);
            $statement->bindValue(':course_id',$course_id);
            $statement->bindValue(':weight',$weight, PDO::PARAM_INT);
            $statement->bindValue(':url',$url);
            $statement->bindValue(':keywords',$keywords);
            $statement->bindValue(':refresh_cycle',$refresh_cycle);
            $statement->bindValue(':partner_id',$partner_id, PDO::PARAM_INT);
            $statement->bindValue(':platform_id',$platform_id, PDO::PARAM_INT);
            $statement->bindValue(':dmethod_id',$dmethod_id, PDO::PARAM_INT);
            $statement->bindValue(':group_id',$group_id, PDO::PARAM_INT);
            $statement->bindValue(':audience_id',$audience_id, PDO::PARAM_INT);
            $statement->bindValue(':topic_id', $topic_id, PDO::PARAM_INT);

            if($statement->execute()) {
                //$iid = $db->lastInsertId();
                echo $count . '. ' . ' ' . $name . ' created.<br>';
                $count++;
            } else {
                var_dump($statement->errorInfo());
            }
            

        }
    }
}


function map_topic_to_id ($topic) {

    $topicindex = array(
        [2,'Being a Public Service Employee'],
        [3,'Communication and Facilitation'],
        [4,'Equity, Diversity and Inclusion'],
        [5,'Ethics and Integrity'],
        [6,'Finance and Accounting'],
        [7,'Health, Safety and Well-Being'],
        [8,'Human Resources Management'],
        [9,'Indigenous Learning'],
        [10,'Information Management'],
        [11,'Innovation'],
        [12,'IT and Digital'],
        [13,'Leadership'],
        [14,'Policy and Regulation'],
        [15,'Procurement and Contract Management'],
        [16,'Project Management']
    );
    $tid = 1;
    foreach($topicindex as $t) {
        if($t[1] == $topic) {
            $tid = $t[0];
        }
    }
    return $tid;
}
function map_partner_to_id ($partner) {

    $partnerindex = array(
        [2,'Learning Centre'],
        [3,'Workplace Health and Safety'],
        [4,'Digital Academy'],
        [5,'Corporate Information and Records Management Office'],
        [6,'Lean BC'],
        [7,'Service BC'],
        [8,'Digital Workplace and Collaboration Services Branch'],
        [9,'Government Digital Experience'],
        [10,'Behavioural Insights'],
        [11,'Benefits Design and Programs'],
        [12,'House of Indigenous Learning'],
        [13,'Diversity and Inclusion'],
        [14,'Leadership, Engagement and Priority Initiatives'],
        [15,'Corporate Ethics Program'],
        [16,'Conflict Management Office'],
        [17,'Coaching Services'],
        [18,'Gender Equity Office'],
        [19,'Emergency Management and Climate Readiness'],
        [20,'Better Regulations'],
        [21,'Executive Talent Programs'],
        [22,'Service and Content Design']
    );

    $pid = 1;
    foreach($partnerindex as $p) {
        if($p[1] == $partner) {
            $pid = $p[0];
        }
    }
    return $pid;
}

function map_dmethod_to_id ($method) {

    $methodindex = array(
        [2,'eLearning'],
        [3,'Webinar'],
        [4,'Classroom'],
        [5,'Blended']
    );

    $mid = 1;
    foreach($methodindex as $m) {
        if($m[1] == $method) {
            $mid = $m[0];
        }
    }
    return $mid;
}

function map_group_to_id ($group) {

    $groupindex = array(
        [2,'Mandatory'],
        [3,'Core'],
        [4,'Complementary']
    );
    $gid = 1;
    foreach($groupindex as $g) {
        if($g[1] == $group) {
            $gid = $g[0];
        }
    }
    return $gid;
}

function map_audience_to_id ($audience) {

    $audienceindex = array(
        [2,'All Employees'],
        [3,'People Leaders'],
        [4,'Senior Leaders'],
        [5,'Executive']
    );
    $aid = 1;
    foreach($audienceindex as $a) {
        if($a[1] == $audience) {
            $aid = $a[0];
        }
    }
    return $aid;
}