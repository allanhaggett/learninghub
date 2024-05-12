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

// Now we can loop through each of the exisiting published courses
// and check each against the feedindex array.
//
// If we find a match, then we can look to getting the info from the feed 
// and updating anything that needs updating e.g., add/remove keywords/topics.
// 
// If there isn't a match, then the course isn't in the feed and needs to 
// be made private.
//
// This loop through published courses only covers updates to exisiting 
// courses and marking private (removing) courses that aren't in the feed.
// After this loop is complete we do another run through the individual 
// courses in the feed to cover adding any new courses that don't exist yet.
// 

//
// Start by getting all the courses that are listed as being in the 
// PSA Learning System, whatever the status (we even want existing private 
// courses so that we can simply update and set back to published instead
// of creating a whole new one.)
//

$sql = 'SELECT 
            c.id AS cid, 
            c.name AS cname, 
            c.status AS cstatus, 
            c.description AS cdesc, 
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
            c.platform_id = 1;';
            
$statement = $db->prepare($sql);
$result = $statement->execute();
//
// Create the array to array_push the existing course titles into
$courseindex = [];
// Loop though all the PSALS courses in the system.
while ($row = $result->fetchArray()):
    // Start by adding all the course titles to the courseindex array so that
    // after this loop runs through, we can loop through the feed again
    // and find the courses that are new and need to be created from scratch.
    if(!empty($row['course_id'])) {
    array_push($courseindex, $row['course_id']);

    // Does the course title match a title that's in the feed?
    if(in_array($row['course_id'], $feedindex)) {

        // Get the details for the feedcourse so we can compare
        foreach($feed->items as $f) {
            if(!empty($f->_course_id)) {
                if($f->_course_id == $row['course_id']) {
                    $feedcourse = $f;
                }
            }
        }
    
        // Set a flag to determine if the course has been updated
        // so that we can not touch the database if we don't need to.
        $courseupdated = 0;

        // Compare more throughly for any updates.
        // If everything is the same then we're not actually touching the 
        // database at all in this process.
        if($feedcourse->title != $row['cname']) {
            $row['cname'] = $feedcourse->title;
            $courseupdated = 1;
        }
        // The rest of the sync process goes here but for now we're
        // ploughing ahead with new course creation.
        // ... #TODO

    }



    } else { // Does the course title match a title that's in the feed?

        // This course is not in the feed anymore.
        // Make it PRIVATE.
        $sql = 'UPDATE courses SET status = "private" WHERE id = ' . $row['cid'] . ';';
        $statement = $db->prepare($sql);
        $statement->execute();


    }
endwhile;
//
// Next, let's loop through the feed again, this time looking at the newly created
// $courseindex array with just the published course names in it for easy lookup
//
// If the course doesn't exist within the catalog yet, then we create it!
//
foreach($feed->items as $feedcourse) {
    if(!empty($feedcourse->_course_id)) {
        if(!in_array($feedcourse->_course_id, $courseindex) && !empty($feedcourse->title)) {

            // This course isn't in the list of published courses
            // so it is new, so we need to create this course from scratch.
            // Set up the new course with basic settings in place

            $status = 'published';  // required
            $sortorder = 0;  // optional
            $name = $feedcourse->title; // required
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $feedcourse->title)));
            $description = $feedcourse->summary; // required
            $created = date('Y-m-d H:i:s'); // required
            $modified = ''; // can't assign yet duh
            $expiry_date = ''; // optional
            $user_idir = 'syncbot';  // required
            $course_id = $feedcourse->_course_id; // optional
            $weight = 0; // optional
            $url = $feedcourse->url; // required
            $keywords = $feedcourse->tags; // required
            $refresh_cycle = ''; // optional

            // $topic_id = $_POST['topic_id']; // required
            if($feedcourse->_topic == 'Being a Public Service Employee') $topic_id = 1;
            if($feedcourse->_topic == 'Communication and Facilitation') $topic_id = 2;
            if($feedcourse->_topic == 'Equity, Diversity and Inclusion') $topic_id = 3;
            if($feedcourse->_topic == 'Ethics and Integrity') $topic_id = 4;
            if($feedcourse->_topic == 'Finance and Accounting') $topic_id = 5;
            if($feedcourse->_topic == 'Health, Safety and Well-Being') $topic_id = 6;
            if($feedcourse->_topic == 'Human Resources Management') $topic_id = 7;
            if($feedcourse->_topic == 'Indigenous Learning') $topic_id = 8;
            if($feedcourse->_topic == 'Information Management') $topic_id = 9;
            if($feedcourse->_topic == 'Innovation') $topic_id = 10;
            if($feedcourse->_topic == 'IT and Digital') $topic_id = 11;
            if($feedcourse->_topic == 'Leadership') $topic_id = 12;
            if($feedcourse->_topic == 'Policy and Regulation') $topic_id = 13;
            if($feedcourse->_topic == 'Procurement and Contract Management') $topic_id = 14;
            if($feedcourse->_topic == 'Project Management') $topic_id = 15;

            // $partner_id = $_POST['partner_id']; // required
            if($feedcourse->_learning_partner == 'Learning Centre') $partner_id = 1;
            if($feedcourse->_learning_partner == 'Workplace Health and Safety') $partner_id = 2;
            if($feedcourse->_learning_partner == 'Digital Academy') $partner_id = 3;
            if($feedcourse->_learning_partner == 'Corporate Information and Records Management Office') $partner_id = 4;
            if($feedcourse->_learning_partner == 'Lean BC') $partner_id = 5;
            if($feedcourse->_learning_partner == 'Service BC') $partner_id = 6;
            if($feedcourse->_learning_partner == 'Digital Workplace and Collaboration Services Branch') $partner_id = 7;
            if($feedcourse->_learning_partner == 'Government Digital Experience') $partner_id = 8;
            if($feedcourse->_learning_partner == 'Behavioural Insights') $partner_id = 9;
            if($feedcourse->_learning_partner == 'Benefits Design and Programs') $partner_id = 10;
            if($feedcourse->_learning_partner == 'House of Indigenous Learning') $partner_id = 11;
            if($feedcourse->_learning_partner == 'Diversity and Inclusion') $partner_id = 12;
            if($feedcourse->_learning_partner == 'Leadership, Engagement and Priority Initiatives') $partner_id = 13;
            if($feedcourse->_learning_partner == 'Corporate Ethics Program') $partner_id = 14;
            if($feedcourse->_learning_partner == 'Conflict Management Office') $partner_id = 15;
            if($feedcourse->_learning_partner == 'Coaching Services') $partner_id = 16;
            if($feedcourse->_learning_partner == 'Gender Equity Office') $partner_id = 17;
            if($feedcourse->_learning_partner == 'Emergency Management and Climate Readiness') $partner_id = 18;
            if($feedcourse->_learning_partner == 'Better Regulations') $partner_id = 19;
            if($feedcourse->_learning_partner == 'Executive Talent Programs') $partner_id = 20;
            if($feedcourse->_learning_partner == 'Service and Content Design') $partner_id = 21;

            $platform_id = 1; // required

            // $dmethod_id = $_POST['dmethod_id']; // required
            if($feedcourse->delivery_method == 'eLearning') $dmethod_id = 1;
            if($feedcourse->delivery_method == 'Webinar') $dmethod_id = 2;
            if($feedcourse->delivery_method == 'Classroom') $dmethod_id = 3;
            if($feedcourse->delivery_method == 'Blended') $dmethod_id = 4;

            // $group_id = $_POST['group_id']; // required
            if($feedcourse->_group == 'Mandatory') $group_id = 1;
            if($feedcourse->_group == 'Core') $group_id = 2;
            if($feedcourse->_group == 'Complementary') $group_id = 3;

            // $audience_id = $_POST['audience_id']; // required
            if($feedcourse->_audience == 'All Employees') $audience_id = 1;
            if($feedcourse->_audience == 'People Leaders') $audience_id = 2;
            if($feedcourse->_audience == 'Senior Leaders') $audience_id = 3;
            if($feedcourse->_audience == 'Executive') $audience_id = 4;
            

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

            // Success!

        }
    }
}


    