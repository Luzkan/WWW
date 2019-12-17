/*       			DB CREATION INFO: 											

Create a new db "kryptozakamarki" and run these commands:						*/

    /* Counter Table (here we will place one row that stores unique visits) 	*/
    CREATE TABLE `counter` (
        `visitors` int(255) NOT NULL,
        `id` int(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    /* IP Table (here we will store registered IP Addresses) 					*/
    CREATE TABLE `ipdb` (
        `ip` text NOT NULL,
        `id` int(11) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    /* Alter the IP Table to auto-increment the ID column 						*/
    ALTER TABLE `ipdb` ADD PRIMARY KEY (`id`);
    ALTER TABLE `ipdb` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

    /* Finally - put a counter row into counter table on ID = 0 				*/
    INSERT INTO `counter` (`visiters`, `id`) VALUES (1, 0);
	



/*					USER LOGIN / REGISTER / COMMENTS INFO:						*/

    CREATE TABLE `users` (
        `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `username` varchar(100) NOT NULL,
        `email` varchar(100) NOT NULL,
        `password` varchar(100) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/* 					UNIQUE VISIT COUNTER INFO:									

    IP Addresses are stored in table "ipdb" (ip / id / now()).
    If an address is found in that table, then we will not increment visit counter,
    otherwise - if ip wasn't found - we store it and bump the visit counter.

    In order to count new visit made by previously saved IP adress after 24 hours.
    We can just run periodically this command:									*/

    DELETE FROM ipdb WHERE CreatedOn<=DATE_SUB(NOW(), INTERVAL 1 DAY)

/*  to delete all IP addresses registered 24h+ ago.
    We just need to event-schedule it to run every hour in our database:		*/

    CREATE EVENT clearIP
    ON SCHEDULE
        EVERY 1 HOUR
            DO
    DELETE FROM ipdb WHERE CreatedOn<=DATE_SUB(NOW(), INTERVAL 1 DAY)


/* 					COMMENTS:													*/


USERS:

+----+-----------+------------------------+-----------------------------+
|     field      |     type               | specs      					|
+----+-----------+------------------------+-----------------------------+
|  id            | INT(11)                | auto-increment, primary-key | 
|  username      | VARCHAR(255)           | UNIQUE     					|
|  email         | VARCHAR(255)           | UNIQUE     					|
|  password      | VARCHAR(255)           |            					|
|  created_at    | TIMESTAMP              |            					|
+----------------+--------------+---------+-----------------------------+


COMMENTS:

+----+-----------+--------------+-----------------------------+
|     field      |     type     | specs                       |
+----+-----------+--------------+-----------------------------+
|  id            | INT(11)      | auto-increment, primary-key | 
|  user_id       | INT(11)      |                             |
|  post_id       | INT(11)      |                             |
|  body          | TEXT         |                             |
|  created_at    | TIMESTAMP    |                             |
|  updated_at    | TIMESTAMP    |                             |
+----------------+--------------+-----------------------------+

REPLIES:

+----+-----------+--------------+-----------------------------+
|     field      |     type     | specs                       |
+----+-----------+--------------+-----------------------------+
|  id            | INT(11)      | auto-increment, primary-key |
|  user_id       | INT(11)      |                             |
|  comment_id    | INT(11)      |                             |
|  body          | TEXT         |                             |
|  created_at    | TIMESTAMP    |                             |
|  updated_at    | TIMESTAMP    |                             |
+----------------+--------------+-----------------------------+

