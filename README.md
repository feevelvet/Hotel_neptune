### 
hello !
for the data base here is the sql code :
-- create the database hotel :
CREATE DATABASE IF NOT EXISTS hotel;

-- Use the database hotel
USE hotel;

-- Create the table Chambres 
CREATE TABLE IF NOT EXISTS Chambres (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Personne INT,
    Pieces INT,
    Prix DECIMAL(10, 2),
    Description TEXT,
    Nom VARCHAR(255),
    Image VARCHAR(255),
    Disponibilite BOOLEAN
);

-- Create the table Reservations
CREATE TABLE IF NOT EXISTS Reservations (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    chambre_id INT,
    date_debut DATE,
    date_fin DATE,
    client_nom VARCHAR(255),
    client_email VARCHAR(255),
    FOREIGN KEY (chambre_id) REFERENCES Chambres(Id)
);

-- Create the table Users
CREATE TABLE IF NOT EXISTS Users (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Email VARCHAR(255),
    Password VARCHAR(255),
    Nom VARCHAR(255),
    Prenom VARCHAR(255),
    Role VARCHAR(50)
);


<!--
**feevelvet/Feevelvet** is a ✨ _special_ ✨ repository because its `README.md` (this file) appears on your GitHub profile.

Here are some ideas to get you started:

- 🔭 I’m currently working on ...
- 🌱 I’m currently learning ...
- 👯 I’m looking to collaborate on ...
- 🤔 I’m looking for help with ...
- 💬 Ask me about ...
- 📫 How to reach me: ...
- 😄 Pronouns: ...
- ⚡ Fun fact: ...
-->
