<?php
/**
 * Functions for LearningHUB 2
 * 
 */

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