<?php
require __DIR__ . '/dbconfig.php';
error_reporting(E_ALL);
ini_set("display_errors", 1);

echo "<pre>";
if ($type == "mysql") {
    try {
        echo "Creating Database...<br><br>";
        $connection = new PDO("$type:host=$servername", $username, $password);
        $sql = "CREATE DATABASE appointment_manager";
        // set the PDO error mode to exception
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->exec($sql);
        echo "Success: Database added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br><br><br>";
    }
    echo "*** Adding tables ***<br><br>";

    // create appointments table
    try {
        echo "Creating Table: appointments...<br>";
        $connection = new PDO("$type:host=$servername;dbname=$database", $username, $password);
        $sql = "CREATE TABLE `appointments` (
            `id` int(11) NOT NULL,
            `user_id` int(11) NOT NULL,
            `type` int(11) NOT NULL,
            `timeslot` int(11) NOT NULL,
            `starttime` datetime NOT NULL,
            `endtime` datetime NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $connection->exec($sql);
        echo "Success: Table added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }

    // create types table
    try {
        echo "Creating Table: types...<br>";
        $sql = "CREATE TABLE `types` (
        `id` int(11) NOT NULL,
        `type` varchar(255) NOT NULL,
        `price` decimal(10,2) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $connection->exec($sql);
        echo "Success: Table added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }

    // create usersbasic table
    try {
        echo "Creating Table: types...<br>";
        $sql = "CREATE TABLE `usersbasic` (
        `id` int(11) NOT NULL,
        `name` varchar(50) NOT NULL,
        `email` varchar(255) NOT NULL,
        `password_hash` varchar(255) NOT NULL,
        `admin` int(11) DEFAULT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $connection->exec($sql);
        echo "Success: Table added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }

    echo "*** Creating Indexes ***<br><br>";
    // create appointments indexes
    try {
        echo "Creating Indexes: appointments...<br>";
        $sql = "ALTER TABLE `appointments`
        ADD PRIMARY KEY (`id`),
        ADD KEY `user_id` (`user_id`),
        ADD KEY `type` (`type`);";
        $connection->exec($sql);
        echo "Success: Indexes added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }

    // create types indexes
    try {
        echo "Creating Indexes: types...<br>";
        $sql = "ALTER TABLE `types`
    ADD PRIMARY KEY (`id`);";
        $connection->exec($sql);
        echo "Success: Indexes added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }

    // create usersbasic indexes
    try {
        echo "Creating Indexes: usersbasic...<br>";
        $sql = "ALTER TABLE `usersbasic`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `email` (`email`);";
        $connection->exec($sql);
        echo "Success: Indexes added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }
    // create appointments auto_increment
    try {
        echo "Creating auto_increment: appointments...<br>";
        $sql = "ALTER TABLE `appointments`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
        $connection->exec($sql);
        echo "Success: A_I added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }

    // create types auto_increment
    try {
        echo "Creating auto_increment: types...<br>";
        $sql = "ALTER TABLE `types`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
        $connection->exec($sql);
        echo "Success: A_I added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }

    // create usersbasic auto_increment
    try {
        echo "Creating auto_increment: types...<br>";
        $sql = "ALTER TABLE `usersbasic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
        $connection->exec($sql);
        echo "Success: A_I added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }

    echo "*** Adding Constraints ***<br><br>";
    // create constraint
    try {
        echo "Creating constraint...<br>";
        $connection = new PDO("$type:host=$servername;dbname=$database", $username, $password);
        $sql = "ALTER TABLE `appointments`
        ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usersbasic` (`id`),
        ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`type`) REFERENCES `types` (`id`);
      COMMIT;";
        $connection->exec($sql);
        echo "Success: Indexes added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }

    echo "*** Adding data ***<br><br>";

    // adding types data
    try {
        echo "Adding data: types...<br>";
        $connection = new PDO("$type:host=$servername;dbname=$database", $username, $password);
        $sql = "INSERT INTO `types` (`id`, `type`, `price`) VALUES
    (1, 'Haircut', '34.00'),
    (2, 'Wash & Haircut', '36.00'),
    (3, 'Clippers', '20.00'),
    (4, 'Wash', '9.50');";
        $connection->exec($sql);
        echo "Success: Data added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }
    // adding usersbasic data
    try {
        echo "Adding data: usersbasic...<br>";
        $connection = new PDO("$type:host=$servername;dbname=$database", $username, $password);
        $hash1 = '$2y$10$DKLbcAVXyfrbiDfyPxxMzeA5Ulg1gTJwnfmLXpcjYucY3Izu34tzW';
        $hash2 = '$2y$10$NzFniVgWB3Dm8duJrhdUnOnG8PlgFQdLGOFS1fKdJVY2HjWCQV4eS';
        $sql = "INSERT INTO `usersbasic` (`id`, `name`, `email`, `password_hash`, `admin`) VALUES
    (1, 'admin', 'admin@admin.admin', '$hash1', 1),
    (16, 'Mark de Haan', 'Mark.deHaan@inholland.nl', '$hash2', NULL);";
        $connection->exec($sql);
        echo "Success: Data added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }
    // adding appointments data
    try {
        echo "Adding data: appointments...<br>";
        $connection = new PDO("$type:host=$servername;dbname=$database", $username, $password);
        $sql = "INSERT INTO `appointments` (`id`, `user_id`, `timeslot`, `starttime`, `endtime`, `type`) VALUES
    (20, 16, 1, '2022-01-26 10:00:00', '2022-01-26 10:45:00', 3);";
        $connection->exec($sql);
        echo "Success: Data added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }

    echo "Done!";
} else {
    try {
        $connection = new PDO("$type:host=$servername;dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    echo "*** Adding tables ***<br><br>";


    // create types table
    try {
        echo "Creating Table: types...<br>";
        $sql = "CREATE TABLE types (
        id SERIAL PRIMARY KEY,
        type varchar(255) NOT NULL,
        price decimal(10,2) NOT NULL
      )";
        $connection->exec($sql);
        echo "Success: Table added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }

    // create usersbasic table
    try {
        echo "Creating Table: usersbasic...<br>";
        $sql = "CREATE TABLE usersbasic (
        id SERIAL PRIMARY KEY,
        name varchar(50) NOT NULL,
        email varchar(255) NOT NULL UNIQUE,
        password_hash varchar(255) NOT NULL,
        admin int DEFAULT NULL
      )";
        $connection->exec($sql);
        echo "Success: Table added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }
    // create appointments table
    try {
        echo "Creating Table: appointments...<br>";
        $sql = "CREATE TABLE appointments (
        id SERIAL PRIMARY KEY,
        user_id SERIAL NOT NULL REFERENCES usersbasic (id),
        type SERIAL NOT NULL REFERENCES types (id),
        timeslot int NOT NULL,
        starttime timestamp NOT NULL,
        endtime timestamp NOT NULL
      )";
        $connection->exec($sql);
        echo "Success: Table added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }

    echo "*** Creating Indexes ***<br><br>";

    echo "*** Adding Constraints ***<br><br>";

    // create constraint
    try {
        echo "Creating constraint...<br>";
        $connection = new PDO("$type:host=$servername;dbname=$database", $username, $password);
        $sql = "ALTER TABLE appointments
        ADD CONSTRAINT appointments_ibfk_1 FOREIGN KEY (user_id) REFERENCES usersbasic (id), ADD CONSTRAINT appointments_ibfk_2 FOREIGN KEY (type) REFERENCES types (id); COMMIT;";
        $connection->exec($sql);
        echo "Success: Indexes added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }

    echo "*** Adding data ***<br><br>";
    // adding types data
    try {
        echo "Adding data: types...<br>";
        $connection = new PDO("$type:host=$servername;dbname=$database", $username, $password);
        $sql = "INSERT INTO types (id, type, price) VALUES
    (1, 'Haircut', '34.00'),
    (2, 'Wash & Haircut', '36.00'),
    (3, 'Clippers', '20.00'),
    (4, 'Wash', '9.50');";
        $connection->exec($sql);
        echo "Success: Data added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }
    // adding usersbasic data
    try {
        echo "Adding data: usersbasic...<br>";
        $connection = new PDO("$type:host=$servername;dbname=$database", $username, $password);
        $hash1 = '$2y$10$DKLbcAVXyfrbiDfyPxxMzeA5Ulg1gTJwnfmLXpcjYucY3Izu34tzW';
        $hash2 = '$2y$10$NzFniVgWB3Dm8duJrhdUnOnG8PlgFQdLGOFS1fKdJVY2HjWCQV4eS';
        $sql = "INSERT INTO usersbasic (id, name, email, password_hash, admin) VALUES
    (1, 'admin', 'admin@admin.admin', '$hash1', 1),
    (16, 'Mark de Haan', 'Mark.deHaan@inholland.nl', '$hash2', NULL);";
        $connection->exec($sql);
        echo "Success: Data added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }
    // adding usersbasic data
    try {
        echo "Adding data: appointments...<br>";
        $connection = new PDO("$type:host=$servername;dbname=$database", $username, $password);
        $sql = "INSERT INTO appointments (id, user_id, timeslot, starttime, endtime, type) VALUES
    (20, 16, 1, '2022-01-26 10:00:00', '2022-01-26 10:45:00', 3);";
        $connection->exec($sql);
        echo "Success: Data added! <br><br><br>";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "<br>";
    }

    echo "Done!";
    echo "</pre>";
}
