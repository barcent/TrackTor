# TrackTor🚚
A Database Management System that provides a platform for farmers to keep track of their product inventory

## User Functionalities
<pre>
  > Account Sign in, Sign Up, and its Management (Update and Delete)
  > Product Type Creation, Read, Update, and Delete
  > Add a Product and Read, Update, and Delete it later
  > Insert a Product to the Inventory, indicate its weight, status and expiration date
  > Read, Update and Delete Inventory entries  
</pre>

## Documentation
  ### Relation Schema
  <pre>
  Entity Sets
    Farmers (farmerID,  first_name, last_name)	
    Inventories (inventoryID, farmerID)
    Products (productID, name, typeID)
    Types(typeID, name)
    
  Relationship Sets
    inventory_products (inventoryID, productID, available_weight, status [Harvested, For Harvest, Planted, For Planting, For Sale, Sold], expiration)
    farmer_products(farmerID, productID)
    farmer_types(farmerID, typeID)
  </pre>

  ### Database
  <pre>
  CREATE TABLE Farmers (
  farmerID int AUTO_INCREMENT PRIMARY KEY,
  first_name varchar(20) NOT NULL,
  last_name varchar(20) NOT NULL
  username tinytext NOT NULL,
  password longtext NOT NULL
  );
  
  CREATE TABLE Inventories (
  inventoryID int AUTO_INCREMENT PRIMARY KEY,
  farmerID int NOT NULL,
  foreign key(farmerID) references Farmers(farmerID)
  	ON DELETE CASCADE
  );
  
  CREATE TABLE Types (
  typeID int AUTO_INCREMENT PRIMARY KEY,
  name varchar(20) NOT NULL
  );
  
  CREATE TABLE Products (
  productID int AUTO_INCREMENT PRIMARY KEY,
  name varchar(20) NOT NULL,
  typeID int NOT NULL,
  inserted BOOLEAN NOT NULL DEFAULT false, 
  foreign key(typeID) references Types(typeID)ON DELETE CASCADE
  );
  
  CREATE TABLE farmer_types (
  farmerID int NOT NULL,
  typeID int NOT NULL,
  foreign key(farmerID) references Farmers(farmerID) ON DELETE CASCADE,
  foreign key(typeID) references Types(typeID) ON DELETE CASCADE
  );
  
  CREATE TABLE farmer_products(
  farmerID int NOT NULL,
  productID int NOT NULL,
  foreign key(farmerID) references Farmers(farmerID) ON DELETE CASCADE,
  foreign key(productID) references Products(productID) ON DELETE CASCADE
  );
  
  CREATE TABLE inventory_products(
  inventoryID int NOT NULL,
  productID int NOT NULL,
  available_weight int NULL CHECK (available_weight>=0 AND available_weight<=2000),
  status varchar(20) NOT NULL,
  expiration date NOT NULL,	
  foreign key(inventoryID) references Inventories(inventoryID) ON DELETE CASCADE,
  foreign key(productID) references Products(productID)ON DELETE CASCADE
  );
  </pre>
  
