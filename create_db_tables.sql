CREATE TABLE Artists (
    ArtistID INT PRIMARY KEY,
    ArtistName VARCHAR(255),
    Description VARCHAR(255)
);

CREATE TABLE `Artwork` (
    `ArtworkID` int NOT NULL,
    `ArtworkName` varchar(255) DEFAULT NULL,
    `Image` mediumblob,
    `ArtistID` int DEFAULT NULL,
    PRIMARY KEY (`ArtworkID`),
    KEY `ArtistID` (`ArtistID`),
    CONSTRAINT `artwork_ibfk_1` FOREIGN KEY (`ArtistID`) REFERENCES `artists` (`ArtistID`) ON DELETE
    SET
        DEFAULT ON UPDATE CASCADE
);

CREATE TABLE Genre (
    GenreID INT PRIMARY KEY,
    GenreName VARCHAR(255),
    ArtistID INT,
    FOREIGN KEY (ArtistID) REFERENCES Artists(ArtistID) ON DELETE
    SET
        DEFAULT ON UPDATE CASCADE
);

CREATE TABLE Theme (
    ThemeID INT PRIMARY KEY,
    ThemeName VARCHAR(255),
    ArtistID INT,
    FOREIGN KEY (ArtistID) REFERENCES Artists(ArtistID) ON DELETE
    SET
        DEFAULT ON UPDATE CASCADE
);

