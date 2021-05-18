CREATE TABLE users (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    name TEXT NOT NULL,
    email TEXT,
    phone TEXT
);

INSERT INTO users (id, username, password, name, email, phone) VALUES (1,'kyle_harms', '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.','Kyle','kyle.harms@cornell.edu','111-222-3333'); --username: kyle, pw: monkey
INSERT INTO users (id, username, password, name, email, phone) VALUES (2,'nathan', '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.', 'Nathan', 'njb93@cornell.edu', '201-222-2222'); --username: nathan, pw: monkey
INSERT INTO users (id, username, password, name, email, phone) VALUES (3,'grader', '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.', 'Grader', 'grader@cornell.edu', '607-991-2983'); --username: grader, pw: monkey
INSERT INTO users (id, username, password, name, email, phone) VALUES (4,'martha', '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.', 'Martha', 'martha@cornell.edu', '607-358-13i2'); --username: grader, pw: monkey
INSERT INTO users (id, username, password, name, email, phone) VALUES (5,'jack', '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.', 'Jack', 'jack17@cornell.edu', '607-178-9888'); --username: jack, pw: monkey
INSERT INTO users (id, username, password, name, email, phone) VALUES (6,'carl', '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.', 'Carl', 'carl32@cornell.edu', '607-143-3413'); --username: carl, pw: monkey
INSERT INTO users (id, username, password, name, email, phone) VALUES (7,'alex39', '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.', 'Alex', 'alex@cornell.edu', '607-334-2222'); --username: alex39, pw: monkey
INSERT INTO users (id, username, password, name, email, phone) VALUES (8,'ashley83', '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.', 'ashley', 'ashley@cornell.edu', '607-214-7482'); --username: ashley83, pw: monkey
INSERT INTO users (id, username, password, name, email, phone) VALUES (9,'amanda', '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.', 'Amanda', 'amanda@cornell.edu', '607-214-3423'); --username: amanda, pw: monkey
INSERT INTO users (id, username, password, name, email, phone) VALUES (10,'michael', '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.', 'Michael', 'michael32@cornell.edu', '212-214-3423'); --username: michael, pw: monkey
INSERT INTO users (id, username, password, name, email, phone) VALUES (11,'lawrence', '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.', 'Lawrence', 'lw94@cornell.edu', '918-214-8931'); --username: lawrence, pw: monkey
INSERT INTO users (id, username, password, name, email, phone) VALUES (12,'marcus', '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.', 'Marcus', 'marcus@cornell.edu', '609-351-3431'); --username: marcus, pw: monkey
INSERT INTO users (id, username, password, name, email, phone) VALUES (13,'julius', '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.', 'Julius', 'julius@cornell.edu', '413-214-4931'); --username: julius, pw: monkey


CREATE TABLE sessions (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	user_id INTEGER NOT NULL,
    session TEXT NOT NULL UNIQUE,
    last_login TEXT NOT NULL,

    FOREIGN KEY(user_id) REFERENCES users(id)
);

CREATE TABLE groups (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	name TEXT NOT NULL UNIQUE
);

INSERT INTO groups (id, name) VALUES (1, 'Property Manger'); -- can access/create/edit non-value listing details, tags
INSERT INTO groups (id, name) VALUES (2, 'Regular User'); -- can create tags, access listings
INSERT INTO groups (id, name) VALUES (3, 'Site Admin'); -- can do everything

CREATE TABLE memberships (
	id  INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    group_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,

    FOREIGN KEY(group_id) REFERENCES groups(id),
    FOREIGN KEY(user_id) REFERENCES users(id)
);

INSERT INTO memberships (id, group_id, user_id) VALUES (1,3,1); -- Kyle, Admin
INSERT INTO memberships (id, group_id, user_id) VALUES (2,3,2); -- Nathan, Admin
INSERT INTO memberships (id, group_id, user_id) VALUES (3,3,3); -- Grader, Admin
INSERT INTO memberships (id, group_id, user_id) VALUES (4,2,4); -- Martha, User
INSERT INTO memberships (id, group_id, user_id) VALUES (5,2,5); -- Jack, User
INSERT INTO memberships (id, group_id, user_id) VALUES (6,2,6); -- Carl, User
INSERT INTO memberships (id, group_id, user_id) VALUES (7,2,7); -- Alex, User
INSERT INTO memberships (id, group_id, user_id) VALUES (8,2,8); -- Ashley, User
INSERT INTO memberships (id, group_id, user_id) VALUES (9,2,9); -- Amanda, User
INSERT INTO memberships (id, group_id, user_id) VALUES (10,1,10); -- Michael, Manager
INSERT INTO memberships (id, group_id, user_id) VALUES (11,1,11); -- Lawrence, Manager
INSERT INTO memberships (id, group_id, user_id) VALUES (12,1,12); -- Marcus, Manager
INSERT INTO memberships (id, group_id, user_id) VALUES (13,1,13); -- Julius, Manager


CREATE TABLE listings (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    seller INTEGER NOT NULL,
	street_address TEXT NOT NULL,
    price INTEGER NOT NULL,
    bed TEXT NOT NULL,
    bath TEXT NOT NULL,
	sqft INTEGER NOT NULL,
	descript TEXT NOT NULL,
    value_score INTEGER,
    value_rating TEXT,
    image_ext TEXT,

    FOREIGN KEY(seller) REFERENCES users(id)
);

INSERT INTO listings (id, seller, street_address, price, bed, bath, sqft, descript, value_score, value_rating, image_ext) VALUES (1,2,"122 McGraw Pl", 900, '1', '1', 750, "Modern 1 bedroom apt right next to CTB", 97, "great", 'jpg');
INSERT INTO listings (id, seller, street_address, price, bed, bath, sqft, descript, value_score, value_rating, image_ext) VALUES (2,1,"210 Cook St", 2300, '3', '2', 1500, "Quaint 3 bed apt. in the heart of Collegetown", 82, "good", 'jpg');
INSERT INTO listings (id, seller, street_address, price, bed, bath, sqft, descript, value_score, value_rating, image_ext ) VALUES (3,2,"113 College Ave", 4400, '4','2', 3000, "Beautiful house on College Ave. with porch and yard", 93, "great", 'jpg');
INSERT INTO listings (id, seller, street_address, price, bed, bath, sqft, descript, value_score, value_rating, image_ext) VALUES (4,3,"202 Dryden Rd", 2100, '3', '1', 850, "3 bed apt. located above Jack's", 53, 'poor', 'jpg');
INSERT INTO listings (id, seller, street_address, price, bed, bath, sqft, descript, value_score, value_rating, image_ext) VALUES (5,3,"108 E Seneca Dr", 5400, '6', '3', 3400, "Ultra-liveable 6 bedroom house", 83, 'good', 'jpg');
INSERT INTO listings (id, seller, street_address, price, bed, bath, sqft, descript, value_score, value_rating, image_ext) VALUES (6,2,"119 Catherine St", 5200, '5', '4', 2000, "Large house with wrap-around porch", 73, 'fair', 'jpg');
INSERT INTO listings (id, seller, street_address, price, bed, bath, sqft, descript, value_score, value_rating, image_ext) VALUES (7,1,"342 Cook St", 2450, '3', '2', 750, "Historic 3 Bed Home", 64, 'poor', 'jpg');
INSERT INTO listings (id, seller, street_address, price, bed, bath, sqft, descript, value_score, value_rating, image_ext) VALUES (8,2,"407 College Ave", 4350, '4', '3', 900, "Gothic Grad-Student Living", 87, 'good', 'jpg');
INSERT INTO listings (id, seller, street_address, price, bed, bath, sqft, descript, value_score, value_rating, image_ext) VALUES (9,3,"18 Catherine St", 2400, '2', '1', 650, "2 Bed Apartment in Premier Collegetown Location", 62, 'poor', 'jpg');
INSERT INTO listings (id, seller, street_address, price, bed, bath, sqft, descript, value_score, value_rating, image_ext) VALUES (10,2,"182 Cook St", 2200, '2', '2', 1750, "Grad Student Living with Full Amenities", 53, 'poor', 'jpg');
INSERT INTO listings (id, seller, street_address, price, bed, bath, sqft, descript, value_score, value_rating, image_ext) VALUES (11,2,"207 Seneca Dr", 2400, '2', '1', 1750, "Luxury Apartment with Rooftop Views", 87, 'good', 'jpg');
INSERT INTO listings (id, seller, street_address, price, bed, bath, sqft, descript, value_score, value_rating, image_ext) VALUES (12,3,"837 Warren Blvd", 8000, '5', '5', 5000, "Off-Campus Mansion", 91, 'great', 'jpg');

-- SEED IMAGE CITATIONS
-- All Photos are From Unsplash, and made available through the Unsplash License
-- Individual Attributes
-- 122 McGraw Pl: Photo by Logan Huff, https://unsplash.com/photos/1geC7V4TYsU
-- 210 Cook St: Photo by Camylla Battani, https://unsplash.com/photos/ashxH5TQ8Go
-- 113 College Ave: Photo by Jonathan Borba, https://unsplash.com/photos/WvwpIu2RRiU
-- 202 Dryden Rd: Photo by Brandon Griggs, https://unsplash.com/photos/wR11KBaB86U
-- 108 E Seneca Dr: Photo by Bailey Anselme, https://unsplash.com/photos/Bkp3gLygyeA
-- 119 Catherine St: Photo by Francesca Tosolini, https://unsplash.com/photos/XcVm8mn7NUM
-- 342 Cook St: Photo by Terrah Holly, https://unsplash.com/photos/pmhdkgRCbtE
-- 407 College Ave: Photo by Abbilyn Zavgorodniaia, https://unsplash.com/photos/uOYak90r4L0
-- 18 Catherine St: Photo by Étienne Beauregard-Riverin, https://unsplash.com/photos/B0aCvAVSX8E
-- 182 Cook St: Photo by Brian Babb, https://unsplash.com/photos/XbwHrt87mQ0
-- 207 Seneca Dr: Photo by Emily Wang, https://unsplash.com/photos/Wv65tpVIdDg
-- 837 Warren Blvd: Photo by Liv Cashman, https://unsplash.com/photos/jBI5n6d8ZwY

CREATE TABLE saves (
	id	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    listing_id INTEGER NOT NULL,
    saved_by INTEGER NOT NULL,
    FOREIGN KEY(listing_id) REFERENCES listings(id),
    FOREIGN KEY(saved_by) REFERENCES users(id)
);

INSERT INTO saves (id, listing_id, saved_by) VALUES (1, 1, 1);
INSERT INTO saves (id, listing_id, saved_by) VALUES (2, 2, 1);
INSERT INTO saves (id, listing_id, saved_by) VALUES (3, 3, 1);
INSERT INTO saves (id, listing_id, saved_by) VALUES (4, 4, 1);
INSERT INTO saves (id, listing_id, saved_by) VALUES (5, 5, 1);
INSERT INTO saves (id, listing_id, saved_by) VALUES (6, 6, 1);
INSERT INTO saves (id, listing_id, saved_by) VALUES (7, 7, 1);
INSERT INTO saves (id, listing_id, saved_by) VALUES (8, 4, 2);
INSERT INTO saves (id, listing_id, saved_by) VALUES (9, 5, 2);
INSERT INTO saves (id, listing_id, saved_by) VALUES (10, 6, 2);
INSERT INTO saves (id, listing_id, saved_by) VALUES (11, 7, 2);
INSERT INTO saves (id, listing_id, saved_by) VALUES (12, 8, 2);
INSERT INTO saves (id, listing_id, saved_by) VALUES (13, 9, 2);
INSERT INTO saves (id, listing_id, saved_by) VALUES (14, 3, 3);
INSERT INTO saves (id, listing_id, saved_by) VALUES (15, 5, 3);
INSERT INTO saves (id, listing_id, saved_by) VALUES (16, 7, 3);
INSERT INTO saves (id, listing_id, saved_by) VALUES (17, 9, 3);
INSERT INTO saves (id, listing_id, saved_by) VALUES (18, 2, 4);
INSERT INTO saves (id, listing_id, saved_by) VALUES (19, 4, 4);
INSERT INTO saves (id, listing_id, saved_by) VALUES (20, 6, 4);
INSERT INTO saves (id, listing_id, saved_by) VALUES (21, 8, 4);
