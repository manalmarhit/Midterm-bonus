-- here is the database sql file
DROP DATABASE IF EXISTS SocialMediaDB;
CREATE DATABASE SocialMediaDB;
USE SocialMediaDB;

CREATE TABLE IF NOT EXISTS Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);


INSERT INTO Users (username, password)
VALUES (
    'manal',
    '$2y$10$m2RMaK32JDvAwiiS3dkK.Od2GtHv16XhROT1UBs6Em5cFAs2rKDgu'
);