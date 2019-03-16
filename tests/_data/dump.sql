CREATE TABLE IF NOT EXISTS Record (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  fName VARCHAR(60) NOT NULL NULL,
  lName VARCHAR(60),
  phone VARCHAR(20) NOT NULL NULL,
  countryCode VARCHAR(2),
  timeZone VARCHAR(40),
  insertedOn DATETIME,
  updatedOn DATETIME,
  PRIMARY KEY (id)
  )  ENGINE=INNODB;

CREATE INDEX i_fName ON Record (fName);
CREATE INDEX i_lName ON Record (lName);


INSERT INTO Record VALUES
(1, 'Luke', 'Skywalker', '+11 123 445674312', 'AR', 'Pacific/Saipan', '2019-03-12 09:22', '2019-03-12 11:43');
INSERT INTO Record VALUES
(2, 'Chewbacca', '', '+20 333 459935766', 'GF', 'Europe/Athens', '2019-03-12 12:43:00', '2019-03-12 13:40:00');
INSERT INTO Record VALUES
(3, 'Han', 'Solo', '+02 144 265555890', 'JM', 'Europe/Bucharest', '2019-03-15 12:43:00', ' 2019-03-15 18:40:00');
INSERT INTO Record VALUES
(4, 'Moff Kohl', 'Seerdon', '+44 333 265786344', 'SC', 'America/Denver', ' 2019-03-11 10:43:00', '2019-03-15 15:20:00');
INSERT INTO Record VALUES
(5, 'Darth', 'Vader', '+99 876 265111657', 'VU', 'Antarctica/DumontDUrville', ' 2019-03-11 10:43:00', '2019-03-15 15:20:00');
