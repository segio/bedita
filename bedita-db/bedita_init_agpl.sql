--
-- Basic initialization data
--

INSERT INTO object_types (id, name, module) VALUES
(1, 'area', 'areas'),
(3, 'section', 'areas'),
(10, 'befile', 'multimedia'),
(12, 'image', 'multimedia'),
(31, 'audio', 'multimedia'),
(32, 'video', 'multimedia'),
(29, 'gallery', 'galleries'),
(13, 'comment', 'comments'),
(18, 'shortnews', 'news'),
(21, 'event', 'events'),
(22, 'document', 'documents'),
(33, 'link', NULL),
(34, 'card', 'addressbook');
	
-- ----------------------------------
-- default user and groups
-- ----------------------------------
INSERT INTO `users` ( id, `userid` , `realname` , `passwd` ) VALUES (1, 'bedita', 'BEdita', MD5( 'bedita' ));

INSERT INTO `groups` ( `name` ) VALUES ('administrator');
INSERT INTO `groups` ( `name` ) VALUES ('guest');
INSERT INTO `groups` ( `name` ) VALUES ('editor');
INSERT INTO `groups` ( `name` ) VALUES ('reader');
INSERT INTO `groups` ( `name` ) VALUES ('frontend');

INSERT INTO `groups_users` ( `user_id` , `group_id` ) VALUES (1, (SELECT id FROM groups WHERE name = 'administrator'));

-- ---------------------------
-- module data
-- ---------------------------
INSERT INTO `modules` (`label`, `name`, `path`, `status`) VALUES
('admin', 'admin', 'admin', 'on'),
('areas', 'areas', 'areas', 'on'),
('documents', 'documents', 'documents', 'on'),
('galleries', 'galleries', 'galleries', 'on'),
('multimedia', 'multimedia', 'multimedia', 'on'),
('news', 'news', 'news', 'on'),
('events', 'events', 'events', 'on'),
('tags', 'tags', 'tags', 'on'),
('comments', 'comments', 'comments', 'on'),
('addressbook', 'addressbook', 'addressbook', 'on'),
('translations', 'translations', 'translations', 'on');


-- administrator permissions
INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'admin'), (SELECT id FROM groups WHERE name = 'administrator'), 'group', '3');

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'areas'), (SELECT id FROM groups WHERE name = 'administrator'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'documents'), (SELECT id FROM groups WHERE name = 'administrator'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'galleries'), (SELECT id FROM groups WHERE name = 'administrator'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'multimedia'), (SELECT id FROM groups WHERE name = 'administrator'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'news'), (SELECT id FROM groups WHERE name = 'administrator'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'events'), (SELECT id FROM groups WHERE name = 'administrator'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'tags'), (SELECT id FROM groups WHERE name = 'administrator'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'comments'), (SELECT id FROM groups WHERE name = 'administrator'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'translations'), (SELECT id FROM groups WHERE name = 'administrator'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'addressbook'), (SELECT id FROM groups WHERE name = 'administrator'), 'group', '3' );


-- editor perms
INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'areas'), (SELECT id FROM groups WHERE name = 'editor'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'documents'), (SELECT id FROM groups WHERE name = 'editor'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'galleries'), (SELECT id FROM groups WHERE name = 'editor'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'multimedia'), (SELECT id FROM groups WHERE name = 'editor'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'news'), (SELECT id FROM groups WHERE name = 'editor'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'events'), (SELECT id FROM groups WHERE name = 'editor'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'tags'), (SELECT id FROM groups WHERE name = 'editor'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'comments'), (SELECT id FROM groups WHERE name = 'editor'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'translations'), (SELECT id FROM groups WHERE name = 'editor'), 'group', '3' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'addressbook'), (SELECT id FROM groups WHERE name = 'editor'), 'group', '3' );

-- reader perms
INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'areas'), (SELECT id FROM groups WHERE name = 'reader'), 'group', '1' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'documents'), (SELECT id FROM groups WHERE name = 'reader'), 'group', '1' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'galleries'), (SELECT id FROM groups WHERE name = 'reader'), 'group', '1' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'multimedia'), (SELECT id FROM groups WHERE name = 'reader'), 'group', '1' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'news'), (SELECT id FROM groups WHERE name = 'reader'), 'group', '1' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'events'), (SELECT id FROM groups WHERE name = 'reader'), 'group', '1' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'tags'), (SELECT id FROM groups WHERE name = 'reader'), 'group', '1' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'comments'), (SELECT id FROM groups WHERE name = 'reader'), 'group', '1' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'translations'), (SELECT id FROM groups WHERE name = 'reader'), 'group', '1' );

INSERT INTO `permission_modules` ( `module_id` , `ugid` , `switch` , `flag` )
VALUES ((SELECT id FROM modules WHERE label = 'addressbook'), (SELECT id FROM groups WHERE name = 'reader'), 'group', '1' );