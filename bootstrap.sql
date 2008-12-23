CREATE TABLE 'updates' (
  'user'          INTEGER,
  'time'          INTEGER,
  'reset'         INTEGER
);

CREATE TABLE 'posts' (
  'id'            INTEGER,
  'qid'           INTEGER,
  'user'          INTEGER,
  'time'          INTEGER,
  'rep'           INTEGER,
  'accepted'      INTEGER,
  'favorites'     INTEGER,
  'answers'       INTEGER,
  'views'         INTEGER,
  'title'         TEXT
);

CREATE TABLE 'profiles' (
  'user'          INTEGER,
  'time'          INTEGER,
  'rep'           INTEGER,
  'questions'     INTEGER,
  'answers'       INTEGER,
  'upvotes'       INTEGER,
  'downvotes'     INTEGER,
  'tags'          INTEGER,
  'badges'        INTEGER,
  'age'           INTEGER,
  'acctage'       INTEGER,
  'name'          TEXT,
  'type'          TEXT,
  'website'       TEXT,
  'location'      TEXT,
  'bio'           TEXT
);

CREATE TABLE 'badgetypes' (
  'id'            INTEGER PRIMARY KEY,
  'name'          TEXT
);

CREATE TABLE 'badges' (
  'id'            INTEGER PRIMARY KEY,
  'type'          INTEGER,               
  'name'	      TEXT,
  'desc'          TEXT
);

CREATE TABLE 'userbadges' (
  'user'          INTEGER,
  'badge'         INTEGER,
  'quantity'      INTEGER
);

INSERT INTO badgetypes VALUES (0, 'bronze');
INSERT INTO badgetypes VALUES (1, 'silver');
INSERT INTO badgetypes VALUES (2, 'gold');

INSERT INTO badges VALUES (9, 0, 'Autobiographer', 'Completed all user profile fields');
INSERT INTO badges VALUES (30, 1, 'Beta', 'Actively participated in the Stack Overflow private beta');
INSERT INTO badges VALUES (8, 0, 'Citizen Patrol', 'First flagged post');
INSERT INTO badges VALUES (32, 1, 'Civic Duty', 'Voted 300 times');
INSERT INTO badges VALUES (4, 0, 'Cleanup', 'First rollback');
INSERT INTO badges VALUES (31, 0, 'Commentatior', 'Left 10 comments');
INSERT INTO badges VALUES (7, 0, 'Critic', 'First down vote');
INSERT INTO badges VALUES (37, 0, 'Disciplined', 'Deleted own post with 3 or more upvotes');
INSERT INTO badges VALUES (3, 0, 'Editor', 'First Edit');
INSERT INTO badges VALUES (19, 1, 'Enlightened', 'First answer was accepted with at least 10 up votes');
INSERT INTO badges VALUES (28, 2, 'Famous Question', 'Asked a question with 10,000 views');
INSERT INTO badges VALUES (33, 1, 'Favorite Question', 'Question favored by 25 users');
INSERT INTO badges VALUES (15, 1, 'Generalist', 'Active in many different tags');
INSERT INTO badges VALUES (24, 1, 'Good Answer', 'Answer voted up more than 25 times');
INSERT INTO badges VALUES (21, 1, 'Good Question', 'Question voted up more than 25 times');
INSERT INTO badges VALUES (25, 2, 'Great Answer', 'Answer voted up more than 100 times');
INSERT INTO badges VALUES (22, 2, 'Great Question', 'Question voted up more than 100 times');
INSERT INTO badges VALUES (18, 1, 'Guru', 'Judged best answer and voted up 40 times');
INSERT INTO badges VALUES (17, 1, 'Necromancer', 'Answered a question more than 60 days later with at least 5 votes');
INSERT INTO badges VALUES (23, 0, 'Nice Answer', 'Answer voted up more than 10 times');
INSERT INTO badges VALUES (20, 0, 'Nice Question', 'Question voted up more than 10 times');
INSERT INTO badges VALUES (27, 1, 'Notable Question', 'Asked a question with 2,500 views');
INSERT INTO badges VALUES (5, 0, 'Organizer', 'First retag');
INSERT INTO badges VALUES (38, 0, 'Peer Pressure', 'Deleted own post with 3 or more downvotes');
INSERT INTO badges VALUES (26, 0, 'Popular Question', 'Asked a question with 1,000 views');
INSERT INTO badges VALUES (10, 0, 'Scholar', 'First accepted answer');
INSERT INTO badges VALUES (14, 0, 'Self-Learner', 'Answered your own question with at least 3 up votes');
INSERT INTO badges VALUES (16, 1, 'Specialist', 'Highly active within a specific tag');
INSERT INTO badges VALUES (36, 2, 'Stellar Question', 'Question favorited by 100 users');
INSERT INTO badges VALUES (12, 1, 'Strunk & White', 'Edited 100 entries');
INSERT INTO badges VALUES (2, 0, 'Student', 'Asked first question with at least one up vote');
INSERT INTO badges VALUES (6, 0, 'Supporter', 'First up vote');
INSERT INTO badges VALUES (11, 1, 'Taxonomist', 'Created a tag used by 50 questions');
INSERT INTO badges VALUES (1, 0, 'Teacher', 'Answered first question with at least one up vote');
INSERT INTO badges VALUES (13, 1, 'Yearling', 'Active member for a year');