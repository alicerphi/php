# put insert,update queries here
USE `php_workshop`;

# use http://www.sha1-online.com/ for password hashing
INSERT INTO `users` (`FirstName`, `LastName`, `Email`, `Gender`, `ProgramingLanguages`, `Description`, `Username`, `Password`)
VALUES ('Kristo', 'Godari', 'kristo.godari@gmail.com', 'MALE', 'PHP|JAVA|JAVASCRIPT|C#', 'Software Engineer', 'kristo.godari', 'dbbe0b9e0ffef386cbf307107379782883c0c50b');


