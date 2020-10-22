DROP DATABASE IF EXISTS durgaprasad;
CREATE DATABASE durgaprasad;
USE durgaprasad;
CREATE TABLE BookInventory (
	id INT AUTO_INCREMENT PRIMARY KEY,
	item_name VARCHAR(50) NOT NULL,
    item_quantity INT NOT NULL,
    price INT NOT NULL,
    image_name VARCHAR(30) NOT NULL
);

CREATE TABLE BookInventoryOrder (
	id INT AUTO_INCREMENT,
    fname_customer VARCHAR(25) NOT NULL,
    lname_customer VARCHAR(25) NOT NULL,
	email VARCHAR(40) NOT NULL,
    payment_method VARCHAR(15) NOT NULL,
    item_id INT NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(item_id) REFERENCES BookInventory(id)
);


