/*
 * Purpose: To generate tables for Pheonix system, statement in MySQL.
 * System:	For both Laravel and Cloud Assignments - semester 01 2017
 */

USE Phoenix;

DROP TABLE Booking;
DROP TABLE Customer_Review;
DROP TABLE Customer;
DROP TABLE Itinerary;
DROP TABLE Trip;
DROP TABLE Tour;
DROP TABLE Vehicle;


CREATE TABLE Vehicle
(
Rego_No				CHAR(6)		NOT NULL,
VIN					VARCHAR(20)	NOT NULL,
Make				VARCHAR(20)	NOT NULL,
Model				VARCHAR(35)	NOT NULL,
Year				INTEGER		NOT NULL,
Capacity			Smallint	NOT NULL,
Fuel_Type			VARCHAR(8),
Equipment			VARCHAR(100),
License_Required	CHAR(2)		NOT NULL,
CONSTRAINT Vehicle_pk PRIMARY KEY (Rego_No)
);

INSERT INTO Vehicle VALUES('JDO682', '90JERN34F9DF3450F', 'Holden', 'Commodore', 2008, 5, 'Petrol', NULL, 'C');
INSERT INTO Vehicle VALUES('AKJ424', '8Y2340JDSNKL9HGS9', 'BCI', 'Fleetmaster 55', 2010, 87, 'Diesel', 'Fire extinguisher, 5 tents, 3 kayaks', 'MR');
INSERT INTO Vehicle VALUES('EIU112', 'SPG4VLEHSDZ98U454', 'Scania', 'K230UB', 2007, 64, 'Diesel', NULL, 'MR');
INSERT INTO Vehicle VALUES('TPO652', '90S8U449S8G9K5N8L', 'Scania', 'K320UB', 2010, 53, 'Diesel', NULL, 'HR');
INSERT INTO Vehicle VALUES('MCN687', 'T3NF8S0D99l9FK6V5', 'BCI', 'Proma', 2011, 35, 'Diesel', 'Fire extinguisher', 'LR');

CREATE TABLE Tour
(
Tour_No		CHAR(3)			NOT NULL,
Tour_Name	VARCHAR(70)		NOT NULL,
Description	VARCHAR(100)	NOT NULL,
Duration	FLOAT(24),
Route_Map	VARCHAR(256),
CONSTRAINT Tour_pk PRIMARY KEY (Tour_No)
);

INSERT INTO Tour VALUES('021', 'Twelve Apostles Drive', 'A drive along the Great Ocean Road to the Twelve Apostles', 28, NULL);
INSERT INTO Tour VALUES('047', 'Northeast Wineries Tour', 'A tour to various wineries in North East Victoria', 32, NULL);
INSERT INTO Tour VALUES('055', 'Melbourne Sightseeing', 'A drive along the Great Ocean Road to the Twelve Apostles', 3.5, 'C:\Documents\Route_Maps\Melbourne_Sightseeing.png');


CREATE TABLE Trip
(
Trip_Id			CHAR(6)	NOT NULL,
Tour_No			CHAR(3)	NOT NULL,
Rego_No			CHAR(6)	NOT NULL,
Departure_Date	DATE,
Max_Passengers	INTEGER NOT NULL,
Standard_Amount		DECIMAL(6,2),
Concession_Amount	DECIMAL(6,2),
CONSTRAINT Trip_pk PRIMARY KEY (Trip_Id),
CONSTRAINT T_Tour_fk FOREIGN KEY (Tour_No) REFERENCES Tour (Tour_No),
CONSTRAINT T_Vehicle_fk FOREIGN KEY (Rego_No) REFERENCES Vehicle (Rego_No)
);

INSERT INTO Trip VALUES('004572', '055', 'EIU112', '2016-05-15', 62,100,80);
INSERT INTO Trip VALUES('004640', '055', 'EIU112', '2016-06-23', 62,200,150);
INSERT INTO Trip VALUES('343271', '021', 'JDO682', '2016-12-04', 3, 400,250);
INSERT INTO Trip VALUES('167005', '047', 'TPO652', '2016-10-20', 51, 120,80);


CREATE TABLE Itinerary
(
Tour_No			CHAR(3)	NOT NULL,
Day_No				TINYINT	NOT NULL,
Hotel_Booking_No	CHAR(6)	NOT NULL,
Activities			VARCHAR(150),
Meals				VARCHAR(150),
CONSTRAINT Itinerary_pk PRIMARY KEY (Tour_No, Day_No),
CONSTRAINT I_Tour_fk FOREIGN KEY (Tour_No) REFERENCES Tour (Tour_No)
);

INSERT INTO Itinerary VALUES('055', 001, '000342', 'Guided tour around the CBD', 'Lunch on Lygon Street');
INSERT INTO Itinerary VALUES('047', 001, '000599', 'Wine tasting at Pizzini''s', 'Lunch at Pizzini''s');


CREATE TABLE Customer
(
Customer_Id		CHAR(6)			NOT NULL,
First_Name		VARCHAR(35)		NOT NULL,
Middle_Initial	CHAR(1),
Last_Name		VARCHAR(35)		NOT NULL,
Street_No		VARCHAR(12)		NOT NULL,
Street_Name		VARCHAR(50)		NOT NULL,
Suburb			VARCHAR(35)		NOT NULL,
Postcode		VARCHAR(4)			NOT NULL,
Email			VARCHAR(150)	NOT NULL,
Phone			VARCHAR(10),
Auth      VARCHAR(32),

CONSTRAINT Customer_pk PRIMARY KEY (Customer_Id)
);

INSERT INTO Customer VALUES('031642', 'Freddie', NULL, 'Khan', '500', 'Waverly Road', 'Chadstone', '3555', 'fred.khan@holmesglen.edu.au', NULL,'b5ae141b1493dc513e0758811c983d66');
INSERT INTO Customer VALUES('001484', 'William', 'B', 'Pitt', '200', 'St. Kilda Road', 'St. Kilda', '3147', 'bill.pitt@gmail.com','0351806451','481f0a5bd7d36c5ceefb647f128f0d7e');
INSERT INTO Customer VALUES('008099', 'James',NULL,'Mangold','646','fw street','Melbourne','3000','James.mang@gmail.com',NULL,'c8db0556f8994dd794d57d58519c6b98');


CREATE TABLE Booking
(
Booking_No	CHAR(6)	NOT NULL,
Customer_Id	CHAR(6)	NOT NULL,
Trip_Id     CHAR(6) NOT NULL,
Booking_Date DATE,
Num_Concessions	INTEGER	NOT NULL,
Num_Adults		INTEGER	NOT NULL,
Deposit_Amount DECIMAL(6,2),

CONSTRAINT Booking_No_pk PRIMARY KEY (Booking_No),
CONSTRAINT B_Customer_Id_fk FOREIGN KEY (Customer_Id) REFERENCES Customer (Customer_Id),
CONSTRAINT B_Trip_Id_fk FOREIGN KEY(Trip_Id) REFERENCES Trip (Trip_Id)
);

INSERT INTO Booking VALUES('004564', '031642','004572','2016-08-15',2,2,100);
INSERT INTO Booking VALUES('007214', '001484','004572','2016-05-27',1,0,500);
INSERT INTO Booking VALUES('008050', '001484','343271','2016-11-01',2,0,150);


CREATE TABLE Customer_Review
(
Trip_Id				CHAR(6)			NOT NULL,
Customer_Id			CHAR(6)			NOT NULL,
Rating				Tinyint			NOT NULL CHECK (Rating >= 0 and Rating <= 5),
General_Feedback	VARCHAR(256),
Likes				VARCHAR(256),
Dislikes			VARCHAR(256),
CONSTRAINT Customer_Review_pk PRIMARY KEY (Trip_Id, Customer_Id),
CONSTRAINT CR_Trip_fk FOREIGN KEY (Trip_Id) REFERENCES Trip (Trip_Id),
CONSTRAINT CR_Customer_fk FOREIGN KEY (Customer_Id) REFERENCES Customer (Customer_Id)
);

INSERT INTO Customer_Review VALUES('004572', '031642', 5, 'Excellent trip, I will be booking with you guys again next year!', 'The whole trip was very reasonably priced.', 'None!');
INSERT INTO Customer_Review VALUES('167005', '001484', 3, 'It was okay, not as good as Kontiki', 'Staff were nice', 'The food was rubbish');
INSERT INTO Customer_Review VALUES('343271', '001484', 4, 'Better than the last one', 'Staff were nice like last time and the food was better', 'The tour bus was too noisy');
