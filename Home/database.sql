-- Creating Database
CREATE DATABASE IF NOT EXISTS user_database;
USE user_database;

-- Dropping users signatures
DROP TABLE IF EXISTS `signatures`;

-- Dropping users table
DROP TABLE IF EXISTS `users`;

-- Table structure for table `users`
CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `company` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(30) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `password_expires` datetime NOT NULL,
  `token` varchar(255) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Indexes for table `users`
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

-- AUTO_INCREMENT for table `users`
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

-- Table structure for table `signatures`
CREATE TABLE `signatures` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `image` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Indexes for table `signatures`
ALTER TABLE `signatures`
  ADD PRIMARY KEY (`id`);

-- AUTO_INCREMENT for table `signatures`
ALTER TABLE `signatures`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

  ALTER TABLE `signatures`
ADD CONSTRAINT `signatures_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
