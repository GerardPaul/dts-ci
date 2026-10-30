-- Demo data for screenshots. Run after dostxi_dts.sql:
--   mysql -uroot dostxi_dts < db/demo_data.sql
-- Logins: administrator / administrator. Every other demo user: username below, password "demo1234".
-- Safe to re-run: it clears the demo tables first.

SET sql_mode = '';
SET FOREIGN_KEY_CHECKS = 0;
DELETE FROM dts_notification;
DELETE FROM dts_chat;
DELETE FROM dts_track;
DELETE FROM dts_logs;
DELETE FROM dts_files;
DELETE FROM dts_document;
DELETE FROM dts_user WHERE id > 1;
SET FOREIGN_KEY_CHECKS = 1;

UPDATE dts_user SET firstname = 'Administrator', lastname = 'User', email = 'admin@example.com', salt = 'demosalt', password = SHA2(CONCAT('demosalt', 'administrator', 'demosalt'), 256) WHERE id = 1;

INSERT INTO dts_user (id, firstname, lastname, email, username, password, salt, userType, division, status) VALUES
(2,  'Maria',   'Santos',     'maria.santos@example.com',   'rd',     '', 'demosalt', 'RD',  1, 1),
(3,  'Elena',   'Reyes',      'elena.reyes@example.com',    'sec',    '', 'demosalt', 'SEC', 1, 1),
(4,  'Roberto', 'Cruz',       'roberto.cruz@example.com',   'ard.fasd','', 'demosalt', 'ARD', 2, 1),
(5,  'Liza',    'Mendoza',    'liza.mendoza@example.com',   'ard.tsd','', 'demosalt', 'ARD', 3, 1),
(6,  'Paolo',   'Garcia',     'paolo.garcia@example.com',   'paolo',  '', 'demosalt', 'EMP', 2, 1),
(7,  'Angela',  'Torres',     'angela.torres@example.com',  'angela', '', 'demosalt', 'EMP', 2, 1),
(8,  'Miguel',  'Ramos',      'miguel.ramos@example.com',   'miguel', '', 'demosalt', 'EMP', 3, 1),
(9,  'Carla',   'Dizon',      'carla.dizon@example.com',    'carla',  '', 'demosalt', 'EMP', 3, 1),
(10, 'Joel',    'Navarro',    'joel.navarro@example.com',   'joel',   '', 'demosalt', 'EMP', 4, 1),
(11, 'Grace',   'Villanueva', 'grace.villanueva@example.com','grace', '', 'demosalt', 'EMP', 4, 1);
UPDATE dts_user SET password = SHA2(CONCAT(salt, 'demo1234', salt), 256) WHERE id > 1;

INSERT INTO dts_document (id, subject, description, action, `from`, dueDate, due15Days, deadline, attachment, status, referenceNumber, dateReceived, archive) VALUES
(1, 'Request for Technical Assistance on Rainwater Harvesting', 'LGU request for site assessment and design support for a community rainwater harvesting facility.', 'Handle', 'Municipality of Compostela', CURDATE() + INTERVAL 5 DAY, CURDATE() + INTERVAL 8 DAY, CURDATE() + INTERVAL 5 DAY, 'Has attachment.', 'On-Going', '26-0001', CURDATE() - INTERVAL 7 DAY, 0),
(2, 'Submission of Quarterly Financial Report', 'Quarterly financial accomplishment report for grants-in-aid projects, for consolidation.', 'Prepare Draft', 'DOST Central Office - FMS', CURDATE() + INTERVAL 2 DAY, CURDATE() + INTERVAL 6 DAY, CURDATE() + INTERVAL 2 DAY, 'Has attachment.', 'On-Going', '26-0002', CURDATE() - INTERVAL 9 DAY, 0),
(3, 'Invitation to Regional Science and Technology Week', 'Invitation to participate and exhibit at the regional S&T Week celebration.', 'Information', 'DOST Regional Office XI', NULL, CURDATE() + INTERVAL 1 DAY, CURDATE() + INTERVAL 14 DAY, 'No File.', 'Compiled', '26-0003', CURDATE() - INTERVAL 20 DAY, 0),
(4, 'Procurement of Laboratory Equipment', 'Request for comments on the technical specifications for new laboratory equipment.', 'Comment', 'Technical Services Division', CURDATE() - INTERVAL 3 DAY, CURDATE() - INTERVAL 1 DAY, CURDATE() - INTERVAL 3 DAY, 'Has attachment.', 'On-Going', '26-0004', CURDATE() - INTERVAL 18 DAY, 0),
(5, 'Memorandum on Updated Travel Guidelines', 'Revised guidelines on official travel, per diem and liquidation of cash advances.', 'Information', 'DOST Central Office - Admin', NULL, CURDATE() - INTERVAL 10 DAY, CURDATE() - INTERVAL 5 DAY, 'No File.', 'Compiled', '26-0005', CURDATE() - INTERVAL 25 DAY, 0),
(6, 'Request for Reply: Partnership Proposal', 'Proposal from a state university to partner on a community-based innovation program.', 'Reply', 'Davao de Oro State College', CURDATE() + INTERVAL 9 DAY, CURDATE() + INTERVAL 12 DAY, CURDATE() + INTERVAL 9 DAY, 'Has attachment.', 'On-Going', '26-0006', CURDATE() - INTERVAL 3 DAY, 0),
(7, 'Cancelled: Training on Data Management', 'Training cancelled due to venue unavailability. Will be rescheduled.', 'Information', 'Planning Division', NULL, CURDATE() - INTERVAL 4 DAY, CURDATE() - INTERVAL 2 DAY, 'No File.', 'Cancelled', '26-0007', CURDATE() - INTERVAL 15 DAY, 0),
(8, 'Annual Procurement Plan Review', 'Review of the previous year procurement plan. Archived after completion.', 'Discuss with Me', 'Finance and Administration Services Division', NULL, CURDATE() - INTERVAL 40 DAY, CURDATE() - INTERVAL 35 DAY, 'No File.', 'Compiled', '26-0008', CURDATE() - INTERVAL 60 DAY, 1),
(9, 'Newly Received: Request for Speaker', 'Invitation for a speaker at a university research forum. Not yet assigned.', NULL, 'University of Mindanao', CURDATE() + INTERVAL 20 DAY, CURDATE() + INTERVAL 15 DAY, CURDATE() + INTERVAL 20 DAY, 'No File.', NULL, '26-0009', CURDATE(), 0);

-- received = '0000-00-00' means not yet seen
INSERT INTO dts_track (id, document, user, received) VALUES
(1, 1, 5, CURDATE() - INTERVAL 6 DAY), (2, 1, 8, CURDATE() - INTERVAL 5 DAY), (3, 1, 9, '0000-00-00'),
(4, 2, 4, CURDATE() - INTERVAL 8 DAY), (5, 2, 6, CURDATE() - INTERVAL 7 DAY), (6, 2, 7, CURDATE() - INTERVAL 4 DAY),
(7, 3, 4, CURDATE() - INTERVAL 19 DAY), (8, 3, 6, CURDATE() - INTERVAL 18 DAY), (9, 3, 10, CURDATE() - INTERVAL 18 DAY), (10, 3, 11, CURDATE() - INTERVAL 17 DAY),
(11, 4, 5, CURDATE() - INTERVAL 16 DAY), (12, 4, 8, CURDATE() - INTERVAL 15 DAY), (13, 4, 9, '0000-00-00'),
(14, 5, 4, CURDATE() - INTERVAL 24 DAY), (15, 5, 5, CURDATE() - INTERVAL 24 DAY), (16, 5, 6, CURDATE() - INTERVAL 22 DAY), (17, 5, 10, CURDATE() - INTERVAL 21 DAY),
(18, 6, 4, CURDATE() - INTERVAL 2 DAY), (19, 6, 7, '0000-00-00'), (20, 6, 10, '0000-00-00'),
(21, 7, 10, CURDATE() - INTERVAL 12 DAY), (22, 7, 11, '0000-00-00'),
(23, 8, 4, CURDATE() - INTERVAL 58 DAY), (24, 8, 6, CURDATE() - INTERVAL 57 DAY);

INSERT INTO dts_chat (id, document, user, message, created) VALUES
(1, 1, 2, 'Please handle the site assessment and coordinate with the municipality.', NOW() - INTERVAL 6 DAY),
(2, 1, 5, 'Noted. Miguel will lead the site visit this week.', NOW() - INTERVAL 5 DAY),
(3, 1, 8, 'Visit scheduled for Thursday. I will send photos and a short report afterwards.', NOW() - INTERVAL 4 DAY),
(4, 2, 2, 'Prepare the draft report and route it to FASD for review.', NOW() - INTERVAL 8 DAY),
(5, 2, 6, 'Draft is 80% done. Waiting on the final figures from accounting.', NOW() - INTERVAL 3 DAY),
(6, 2, 4, 'Please submit by Friday so I can consolidate.', NOW() - INTERVAL 2 DAY),
(7, 4, 2, 'Please comment on the specifications, especially the calibration requirements.', NOW() - INTERVAL 16 DAY),
(8, 4, 8, 'Specs look fine, but the delivery timeline needs to be shorter.', NOW() - INTERVAL 12 DAY),
(9, 6, 2, 'Please prepare a reply. We are generally open to the partnership.', NOW() - INTERVAL 2 DAY);

INSERT INTO dts_notification (id, creator, receiver, object, type, status, dateCreated) VALUES
(1, 2, 7, 19, 1, 0, NOW() - INTERVAL 2 DAY),
(2, 2, 10, 20, 1, 0, NOW() - INTERVAL 2 DAY),
(3, 4, 7, 6, 2, 0, NOW() - INTERVAL 2 DAY),
(4, 5, 9, 3, 1, 0, NOW() - INTERVAL 5 DAY),
(5, 2, 6, 5, 2, 1, NOW() - INTERVAL 3 DAY);

INSERT INTO dts_logs (actionTaken, timeOccured) VALUES
('sec has added a new document, ref no. 26-0001.', NOW() - INTERVAL 7 DAY),
('rd has assigned document, ref no. 26-0001 to ard.tsd.', NOW() - INTERVAL 7 DAY),
('sec has added a new document, ref no. 26-0002.', NOW() - INTERVAL 9 DAY),
('rd has assigned document, ref no. 26-0002 to ard.fasd.', NOW() - INTERVAL 8 DAY),
('paolo has changed status of document, ref no. 26-0003.', NOW() - INTERVAL 5 DAY),
('sec has added a new document, ref no. 26-0006.', NOW() - INTERVAL 3 DAY),
('rd has assigned document, ref no. 26-0006 to ard.fasd.', NOW() - INTERVAL 2 DAY),
('sec has added a new document, ref no. 26-0009.', NOW());
