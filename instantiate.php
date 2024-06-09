<?php

// If we don't set the timezone up front we have to convert from UTC and
// and that's a pain we don't need.
date_default_timezone_set('America/Los_Angeles');

// Create a new database, if the file doesn't exist and open it for reading/writing.
// The extension of the file is arbitrary.
$db = new SQLite3('courses.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
// Errors are emitted as warnings by default, enable proper error handling.
$db->enableExceptions(true);

// Create learning_partners table.
$db->query('CREATE TABLE IF NOT EXISTS "learning_partners" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "created" DATETIME,
    "modified" DATETIME,
    "sortorder" INTEGER,
    "name" VARCHAR,
    "slug" VARCHAR,
    "description" VARCHAR,
    "icon" VARCHAR,
    "logo" VARCHAR,
    "color" VARCHAR,
    "url" VARCHAR,
    "admin_name" VARCHAR,
    "admin_email" VARCHAR
)');

$statement = $db->prepare('INSERT INTO "learning_partners" ("created", "name", "slug", "description", "url") VALUES (:created, :name, :slug, :description, :url)');
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Unassigned');
$statement->bindValue(':slug', 'unassigned');
$statement->bindValue(':description', 'This course does not have a partner assigned.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Learning Centre');
$statement->bindValue(':slug', 'learning-centre');
$statement->bindValue(':description', 'The Learning Centre works closely with partners across the BC public service to offer accessible, timely, and relevant learning resources for employees, supervisors, managers, and leaders. It serves the public servant’s diverse and dynamic learning needs, and helps employees achieve their personal career goals.');
$statement->bindValue(':url', 'https://learningcentre.gww.gov.bc.ca/');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Workplace Health and Safety');
$statement->bindValue(':slug', 'workplace-health-and-safety');
$statement->bindValue(':description', 'The Workplace Health and Safety team envisions a safe, healthy, and flourishing public service. They deliver a range of services and programs including training to support employees, supervisors and leaders build safety-oriented cultures that support psychological health and employee well-being in the workplace.');
$statement->bindValue(':url', 'https://www2.gov.bc.ca/gov/content/careers-myhr/all-employees/health-safety-and-sick-leave-resources');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Digital Academy');
$statement->bindValue(':slug', 'digital-academy');
$statement->bindValue(':description', 'The Digital Academy team supports employees on their digital learning journey. Our team designs, curates, and delivers learning products that empower people to embrace and use digital ways of working in the BC Public Service. We provide or support foundational, advanced, and specialized learning opportunities across a wide spectrum of topics in the digital world. Whether you are upskilling or reskilling, developing your digital literacy or a seasoned practitioner, our growing catalogue and services will help you achieve your goals.');
$statement->bindValue(':url', 'https://digital.gov.bc.ca/learn/home/team/');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Corporate Information and Records Management Office');
$statement->bindValue(':slug', 'corporate-information-and-records-management-office');
$statement->bindValue(':description', 'The Corporate Information and Records Management Office (CIRMO) provides information management training to all ministry employees. Topics include records management, freedom of information, privacy, and digital identity. Courses are available in various formats such as live webinars and eLearning modules.');
$statement->bindValue(':url', 'https://intranet.gov.bc.ca/intranet/content?id=11CB74D8058C4FC884D130C9FB7D647C');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Lean BC');
$statement->bindValue(':slug', 'lean-bc');
$statement->bindValue(':description', 'The LeanBC team offers support to employees as they implement Lean strategies and techniques to make their day-to-day work more efficient and effective. The team provides expert facilitation to empower teams, make meaningful process improvements, train staff, and foster a culture of continuous improvement.');
$statement->bindValue(':url', 'https://intranet.gov.bc.ca/thehub/service-bc/strategic-services/leanbc');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Service BC');
$statement->bindValue(':slug', 'service-bc');
$statement->bindValue(':description', 'Service BC provides access to the Certified Service Professional (CSP) program which draws on over ten years of research by the Institute for Citizen Centred Service (ICCS) regarding what Canadians want from their government service providers. The Certified Service Professional (CSP) program is for anyone delivering services in the Public Sector. Whether assisting a citizen or colleague within our own organization - we all deliver services. This program recognizes that providing great customer service in the public sector is different than the private sector.');
$statement->bindValue(':url', 'https://intranet.gov.bc.ca/thehub/service-bc/service-practice-policy/service-bc-learning/csp-and-csm-programs');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Digital Workplace and Collaboration Services Branch');
$statement->bindValue(':slug', 'digital-workplace-and-collaboration-services-branch');
$statement->bindValue(':description', 'Digital Workplace and Collaboration Services is responsible for implementing high-quality digital tools, and the information and resources that employees need to access and use them, in support of a modern digital workplace. We work hand-in-hand with ministries to evolve the digital employee experience, support flexible work, and empower employees to deliver services to the citizens of B.C.');
$statement->bindValue(':url', 'https://bcgov.sharepoint.com/SitePages/Home.aspx');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Government Digital Experience');
$statement->bindValue(':slug', 'government-digital-experience');
$statement->bindValue(':description', 'The Government Digital Experience (GDX) Division manages public engagement across government and leverages digital technology to improve services for citizens and make government easier to navigate. GDX also delivers technology services to Government Communication and Public Engagement (GCPE) to support its day-to-day operations.');
$statement->bindValue(':url', 'https://intranet.gov.bc.ca/thehub/gdx');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Behavioural Insights');
$statement->bindValue(':slug', 'behavioural-insights');
$statement->bindValue(':description', 'The BC Behavioural Insights Group (BC BIG) takes insights from research into how and why people make decisions and uses them to help public servants improve government programs and services. They rigorously evaluate the solutions – or behavioural interventions – they come up with to learn what works.');
$statement->bindValue(':url', 'https://www2.gov.bc.ca/gov/content/governments/services-for-government/service-experience-digital-delivery/behavioural-insights');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Benefits Design and Programs');
$statement->bindValue(':slug', 'benefits-design-and-programs');
$statement->bindValue(':description', 'In support of our Branch’s vision of creating a total compensation package that attracts and retains the best talent to the BC Public Service, the Benefits Design and Programs team is responsible for the development, implementation, and communications related to health and life insurance benefits programs available to employees.');
$statement->bindValue(':url', 'https://www2.gov.bc.ca/gov/content/careers-myhr/all-employees/pay-benefits/benefits');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'House of Indigenous Learning');
$statement->bindValue(':slug', 'house-of-indigenous-learning');
$statement->bindValue(':description', 'The House of Indigenous Learning (HoIL) is a culturally safe space where public servants across the BCPS can find resources and supports at each step of their Reconciliation learning journey. The HoIL is committed to providing meaningful, relevant corporate learning to inspire, create awareness, develop competency, and build confidence of BCPSA employees across the province.');
$statement->bindValue(':url', 'https://compass.gww.gov.bc.ca/indigenous-learning/house/');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Diversity and Inclusion');
$statement->bindValue(':slug', 'diversity-and-inclusion');
$statement->bindValue(':description', 'The Diversity & Inclusion branch leads the implementation of Where We All Belong, an equity, diversity and inclusion strategy for the BC Public Service. To advance the principles of reconciliation, the branch leads and supports Indigenous initiatives aimed at strengthening representation and inclusion of Indigenous employees. The team also works closely with the Accessibility Secretariat to remove barriers to accessibility in the public service.');
$statement->bindValue(':url', 'https://www2.gov.bc.ca/gov/content/careers-myhr/about-the-bc-public-service/diversity-inclusion');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Leadership, Engagement and Priority Initiatives');
$statement->bindValue(':slug', 'leadership-engagement-and-priority-initiatives');
$statement->bindValue(':description', 'Leadership, Engagement and Priority Initiatives envisions a people-focused public service culture where employees at all levels feel inspired, supported, and empowered to do their best work. Through collaboration, engagement and learning opportunities, we aim to positively impact the workplace experience for all public service employees.');
$statement->bindValue(':url', 'https://www2.gov.bc.ca/gov/content/careers-myhr/all-employees/new-employees');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Corporate Ethics Program');
$statement->bindValue(':slug', 'corporate-ethics-program');
$statement->bindValue(':description', 'The corporate ethics program works with ministries and employees to further enhance and strengthen ethics management in the BCPS and increase transparency and accountability across government.');
$statement->bindValue(':url', 'https://www2.gov.bc.ca/gov/content/careers-myhr/about-the-bc-public-service/ethics-standards-of-conduct/ethics-contacts');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Conflict Management Office');
$statement->bindValue(':slug', 'conflict-management-office');
$statement->bindValue(':description', 'As a Centre of Excellence in the area of conflict resolution, the Conflict Management Office’s (CMO) goal is to create a conflict competent culture in the BC Public Service. It builds employees, supervisors, and executives’ capacity to manage and resolve workplace conflict by focusing on problem solving, effective communication and preserving relationships. The CMO team offers a range of confidential services including consultations, advice and coaching for leaders, facilitated conversations, mediations and workplace improvement processes. ');
$statement->bindValue(':url', 'https://www2.gov.bc.ca/gov/content/careers-myhr/all-employees/working-with-others/cmo');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Coaching Services');
$statement->bindValue(':slug', 'coaching-services');
$statement->bindValue(':description', 'Coaching Services leads the implementation of an Organizational Coaching Strategy, focused on embedding a coaching mindset and skillset within BC Public Service, to support leaders at all levels to drive organizational change. The team works closely with Ministry and PSA partners, providing expert advice, coaching and training.');
$statement->bindValue(':url', 'https://www2.gov.bc.ca/gov/content/careers-myhr/all-employees/career-development/coaching-services');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Gender Equity Office');
$statement->bindValue(':slug', 'gender-equity-office');
$statement->bindValue(':description', 'The Gender Equity Office ensures government\'s commitment to gender equity is reflected in our budgets, policies and programs (GBA+). Through training and information sharing on promising practices, GEO supports Ministries to build out their respective capacity to support the broader public sector in the development and implementation of increasingly inclusive and equitable programs.');
$statement->bindValue(':url', 'https://www2.gov.bc.ca/gov/content/gender-equity');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Emergency Management and Climate Readiness');
$statement->bindValue(':slug', 'emergency-management-and-climate-readiness');
$statement->bindValue(':description', 'The Ministry of Emergency Management and Climate Readiness (EMCR) is British Columbia’s lead coordinating agency for all emergency management activities, including preparedness, response, recovery and mitigation.');
$statement->bindValue(':url', '');$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Better Regulations');
$statement->bindValue(':slug', 'better-regulations');
$statement->bindValue(':description', 'The Better Regulations team works with ministries to develop strong policy and regulations. The team trains and supports ministries with policy development and regulatory analysis, including the Policy Approaches Playbook, Regulatory Reform Initiative, and the Business and Economic Implications Framework.');
$statement->bindValue(':url', 'https://www2.gov.bc.ca/gov/content/governments/about-the-bc-government/regulatory-reform');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Executive Talent Programs');
$statement->bindValue(':slug', 'executive-talent-programs');
$statement->bindValue(':description', 'Executive Talent Programs in the PSA deliver executive programs such as Executive Reviews, Executive Succession, Executive Orientation and Executive Development. Executive Development offerings include Essential Learning for all Executives, On-Demand offerings for Executives to access as per individual needs, and Corporate Executive networking and collaboration opportunities.');
$statement->bindValue(':url', '');$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Service and Content Design');
$statement->bindValue(':slug', 'service-and-content-design');
$statement->bindValue(':description', 'The Service and Content Design team located in the Government Digital Experience Division is changing how citizens access government services by bringing innovation and a human-centred approach to areas such as health care, transportation, education, policy and finance.');
$statement->bindValue(':url', 'https://www2.gov.bc.ca/gov/content/governments/services-for-government/service-experience-digital-delivery/service-content-design');
$statement->execute(); 

// Create learning_platforms table.
$db->query('CREATE TABLE IF NOT EXISTS "learning_platforms" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "created" DATETIME,
    "modified" DATETIME,
    "sortorder" INTEGER,
    "name" VARCHAR,
    "slug" VARCHAR,
    "description" VARCHAR,
    "logo" VARCHAR,
    "icon" VARCHAR,
    "color" VARCHAR,
    "url" VARCHAR
)');

$statement = $db->prepare('INSERT INTO "learning_platforms" ("created", "name", "slug", "description", "url") VALUES (:created, :name, :slug, :description, :url)');
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Unassigned');
$statement->bindValue(':slug', 'unassigned');
$statement->bindValue(':description', 'No platform.');
$statement->bindValue(':url', '');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'PSA Learning System');
$statement->bindValue(':slug', 'psa-learning-system');
$statement->bindValue(':description', 'AKA ELM');
$statement->bindValue(':url', 'https://learning.gov.bc.ca/CHIPSPLM/signon.html');
$statement->execute(); 

// Create delivery_method table.
$db->query('CREATE TABLE IF NOT EXISTS "delivery_methods" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "created" DATETIME,
    "modified" DATETIME,
    "sortorder" INTEGER,
    "name" VARCHAR,
    "slug" VARCHAR,
    "description" VARCHAR,
    "icon" VARCHAR,
    "color" VARCHAR
)');
$statement = $db->prepare('INSERT INTO "delivery_methods" ("created", "name", "slug",  "description") VALUES (:created, :name, :slug, :description)');
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Unassigned');
$statement->bindValue(':slug', 'unassigned');
$statement->bindValue(':description', 'No delivery method assigned.');
$statement->execute();
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'eLearning');
$statement->bindValue(':slug', 'elearning');
$statement->bindValue(':description', 'Broadly refers to all formal online learning. This includes live sessions and self-paced courses.');
$statement->execute();
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Webinar');
$statement->bindValue(':slug', 'webinar');
$statement->bindValue(':description', 'A live presentation that includes interaction with learners via Q & As, polls, or other engagement techniques.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Classroom');
$statement->bindValue(':slug', 'classroom');
$statement->bindValue(':description', 'Learning that takes place in a physical location with in-person presence required.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Blended');
$statement->bindValue(':slug', 'blended');
$statement->bindValue(':description', 'An offering with any combination of self-paced learning plus live virtual or classroom sessions.');
$statement->execute(); 

// Create audiences table.
$db->query('CREATE TABLE IF NOT EXISTS "audiences" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "created" DATETIME,
    "modified" DATETIME,
    "sortorder" INTEGER,
    "name" VARCHAR,
    "slug" VARCHAR,
    "description" VARCHAR,
    "icon" VARCHAR,
    "color" VARCHAR
)');
$statement = $db->prepare('INSERT INTO "audiences" ("created", "name", "slug",  "description") VALUES (:created, :name, :slug, :description)');
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Unassigned');
$statement->bindValue(':slug', 'unassigned');
$statement->bindValue(':description', 'No audience assigned.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'All Employees');
$statement->bindValue(':slug', 'all-employees');
$statement->bindValue(':description', 'All BCPS employees (whether they supervise or not).');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'People Leaders');
$statement->bindValue(':slug', 'people-leaders');
$statement->bindValue(':description', 'Any BCPS member with direct reports.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Senior Leaders');
$statement->bindValue(':slug', 'senior-leaders');
$statement->bindValue(':description', 'Directors, Bands 4-6.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Executive');
$statement->bindValue(':slug', 'executive');
$statement->bindValue(':description', 'ADMs, DMs, Executive Leads.');
$statement->execute(); 

// Create topics table.
$db->query('CREATE TABLE IF NOT EXISTS "topics" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "created" DATETIME,
    "modified" DATETIME,
    "sortorder" INTEGER,
    "name" VARCHAR,
    "slug" VARCHAR,
    "description" VARCHAR,
    "icon" VARCHAR,
    "color" VARCHAR
)');
$statement = $db->prepare('INSERT INTO "topics" ("created", "name", "slug", "description") VALUES (:created, :name, :slug, :description)');
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Unassigned');
$statement->bindValue(':slug', 'Unassigned');
$statement->bindValue(':description', 'No topic assigned.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Being a Public Service Employee');
$statement->bindValue(':slug', 'being-public-service-employee');
$statement->bindValue(':description', 'Unique to the public service or public service context. Might not be found outside of the PS. Might address our responsibilities & expectations as BCPS employees. May includes Government Essentials and more.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Communication and Facilitation');
$statement->bindValue(':slug', 'communication-facilitation');
$statement->bindValue(':description', 'Please provide description.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Equity, Diversity and Inclusion');
$statement->bindValue(':slug', 'equity-diversity-inclusion');
$statement->bindValue(':description', 'Equity, diversity, accessibility, anti-racism, and inclusion. Does not include courses where Indigenous learning is the primary focus. Includes Impact of Bias and Assumption on the Workplace and more.  ');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Ethics and Integrity');
$statement->bindValue(':slug', 'ethics-integrity');
$statement->bindValue(':description', 'Conceptual approach to ethical principles, practices, and procedures. Includes Ethics for Everyone and more.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Finance and Accounting');
$statement->bindValue(':slug', 'finance-accounting');
$statement->bindValue(':description', 'The recording and summarizing of business and financial transactions including analyzing, forecasting, verifying, and reporting the results. Includes FM 111 Government Expense Authority Fundamentals and more.  ');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Health, Safety and Well-Being');
$statement->bindValue(':slug', 'health-safety-well-being');
$statement->bindValue(':description', 'The legislative, contractual, and operational requirements around safety and health, as well as our overall physical, mental and social well-being. Includes Ergonomics Where You Work, OHS for Supervisors, and more.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Human Resources Management');
$statement->bindValue(':slug', 'human-resources-management');
$statement->bindValue(':description', 'Day-to-day management of operational and administrative aspects of HR such as managing and processing STIIP, Time & Leave, performance development, labour relations, etc. Includes HR Foundations for People Leaders, Supervising in the BCPS and more.  ');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Indigenous Learning');
$statement->bindValue(':slug', 'indigenous-learning');
$statement->bindValue(':description', 'Supports government commitments to reconciliation.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Information Management');
$statement->bindValue(':slug', 'information-management');
$statement->bindValue(':description', 'Modernized information use and management in the BC public service context. Familiarizing yourself with the integrated legislation and policy surrounding information management.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Innovation');
$statement->bindValue(':slug', 'innovation');
$statement->bindValue(':description', 'Learning that promotes innovative ways of working in the BCPS. ');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'IT and Digital');
$statement->bindValue(':slug', 'it-digital');
$statement->bindValue(':description', 'Digital ways of working in the BCPS including how to use and work with tech tools. May include MS Teams and Power BI training and more.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Leadership');
$statement->bindValue(':slug', 'leadership');
$statement->bindValue(':description', 'Fosters the desired organizational culture within the BCPS, including practicing leadership skills regardless of position. Includes the Coaching course suite, the Fierce Training Program for effective conversations and decision-making, and more.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Policy and Regulation');
$statement->bindValue(':slug', 'policy-regulation');
$statement->bindValue(':description', 'How to write, develop, and implement policy and key government documents such as briefing notes, business cases, and treasury board and cabinet operation submissions. Includes Policy 101 and more.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Procurement and Contract Management');
$statement->bindValue(':slug', 'procurement-contract-management');
$statement->bindValue(':description', 'Legislative requirements and conceptual approach to procurement and contract management principles, practices, and procedures. Include PCMP 206: The Procurement Lifecycle and more.  ');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Project Management');
$statement->bindValue(':slug', 'project-management');
$statement->bindValue(':description', 'Design, communication, execution, and evaluation of projects, including Agile, Lean and Change Management. Includes PM 100 and 200, Change Management Foundations, and more.');
$statement->execute(); 

// Create groups table.
$db->query('CREATE TABLE IF NOT EXISTS "groups" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "created" DATETIME,
    "modified" DATETIME,
    "sortorder" INTEGER,
    "name" VARCHAR,
    "slug" VARCHAR,
    "description" VARCHAR,
    "icon" VARCHAR,
    "color" VARCHAR
)');
$statement = $db->prepare('INSERT INTO "groups" ("created", "name", "slug", "description") VALUES (:created, :name, :slug, :description)');
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Unassigned');
$statement->bindValue(':slug', 'unassigned');
$statement->bindValue(':description', 'No group assigned.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Mandatory');
$statement->bindValue(':slug', 'mandatory');
$statement->bindValue(':description', 'Required by policy or regulations. Most require periodic renewal.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Core');
$statement->bindValue(':slug', 'core');
$statement->bindValue(':description', 'Learning that is foundational for all employees or key for certain roles across government.');
$statement->execute(); 
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':name', 'Complementary');
$statement->bindValue(':slug', 'complementary');
$statement->bindValue(':description', 'Courses that add value to your foundational or role-based learning.');
$statement->execute(); 


// Create courses table.
$db->query('CREATE TABLE IF NOT EXISTS "courses" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "status" VARCHAR NOT NULL,
    "sortorder" INTEGER,
    "name" VARCHAR NOT NULL,
    "slug" VARCHAR,
    "description" VARCHAR NOT NULL,
    "created" DATETIME NOT NULL,
    "modified" DATETIME,
    "platform_last_updated" DATETIME,
    "expiry_date" DATETIME,
    "user_idir" VARCHAR,
    "course_id" INTEGER,
    "weight" INTEGER,
    "url" VARCHAR,
    "search" TEXT,
    "keywords" VARCHAR,
    "refresh_cycle" TEXT,
    "partner_id" INTEGER,
    "platform_id" INTEGER,
    "dmethod_id" INTEGER,
    "group_id" INTEGER,
    "audience_id" INTEGER,
    "topic_id" INTEGER,
    FOREIGN KEY(partner_id) REFERENCES learning_partners(id),
    FOREIGN KEY(platform_id) REFERENCES learning_platforms(id),
    FOREIGN KEY(dmethod_id) REFERENCES delivery_methods(id),
    FOREIGN KEY(group_id) REFERENCES groups(id),
    FOREIGN KEY(audience_id) REFERENCES audiences(id),
    FOREIGN KEY(topic_id) REFERENCES topics(id)
)');

// Create platform_syncs table.
$db->query('CREATE TABLE IF NOT EXISTS "platform_syncs" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "created" DATETIME,
    "platform_id" INTEGER,
    "hash" TEXT,
    "result" VARCHAR,
    FOREIGN KEY(platform_id) REFERENCES learning_platforms(id)
    )');
$statement = $db->prepare('INSERT INTO "platform_syncs" ("created", "platform_id", "hash", "result") VALUES (:created, :platform_id, :hash, :result)');
$statement->bindValue(':created', date('Y-m-d H:i:s'));
$statement->bindValue(':platform_id', 1);
$statement->bindValue(':hash', 'cd18a084a3c4f19c7cc582809c9c9e07');
$statement->bindValue(':result', 'Success');
$statement->execute(); 



// Create journeys table.
// $db->query('CREATE TABLE IF NOT EXISTS "journeys" (
//     "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
//     "parent_id" INTEGER,
//     "created" DATETIME,
//     "modified" DATETIME,
//     "sortorder" INTEGER,
//     "name" VARCHAR,
//     "slug" VARCHAR,
//     "description" VARCHAR,
//     "icon" VARCHAR,
//     "color" VARCHAR
// )');
// $statement = $db->prepare('INSERT INTO "journeys" ("created", "name", "slug", "description") VALUES (:created, :name, :slug, :description)');
// $statement->bindValue(':created', date('Y-m-d H:i:s'));
// $statement->bindValue(':name', 'Mandatory');
// $statement->bindValue(':slug', 'mandatory');
// $statement->bindValue(':description', 'Required by policy or regulations. Most require periodic renewal.');
// $statement->execute(); 


$db->close();