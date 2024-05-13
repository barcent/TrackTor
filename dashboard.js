
// Function to dynamically load product options
function loadProducts(callback) {
    var productSelect = document.getElementById("productSelect");

    // AJAX request to fetch products from the server
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Parse the response as JSON
            var products = JSON.parse(xhr.responseText);

            // Clear existing options
            productSelect.innerHTML = "";

            // Add a default option
            var defaultOption = document.createElement("option");
            defaultOption.value = ""; 
            defaultOption.text = "Select Product";
            productSelect.add(defaultOption);

                        
            // Populate the product options in the dropdown
            products.forEach(function (product) {
                var option = document.createElement("option");
                option.value = product.productID;
                option.text = product.productName;
                productSelect.add(option);
            });

            // Call the callback function if provided
            if (typeof callback === 'function') {
                callback();
            }
        }
    };
    xhr.open("GET", "dash_loadprod.php", true);
    xhr.send();
}


// Function to dynamically load product type based on the selected product
function loadProductType(callback) {
    var productSelect = document.getElementById("productSelect");

    // Get the selected product ID
    var selectedProductId = productSelect.value;

    // AJAX request to fetch product type based on the selected product
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    // Log the raw response for debugging
                    console.log("Raw Server Response:", xhr.responseText);

                    // Check if the response is not empty
                    if (xhr.responseText.trim() !== "") {
                        // Parse the response as JSON
                        var productType = JSON.parse(xhr.responseText);

                        // Check if the response has a valid product type
                        if (productType && productType.typeName) {
                            // If a callback function is provided, invoke it with the result
                            if (typeof callback === "function") {
                                callback({
                                    productId: selectedProductId,
                                    productType: productType
                                });
                            } else {
                                // If no callback is provided, update the form directly
                                document.getElementById("productType").value = productType.typeName;
                            }
                        } else {
                            alert("Invalid response from the server.");
                        }
                    } else {
                        console.error("Empty server response");

                        alert("Empty server response. Please check your server.");
                    }
                } catch (error) {
                    // Log the parsing error for debugging
                    console.error("Error parsing server response:", error);

                    alert("Error parsing server response.");
                }
            } else {
                // Log the HTTP status for debugging
                console.error("HTTP Status:", xhr.status);

                alert("Error fetching product type. Please try again.");
            }
        }
    };

    xhr.open("POST", "dash_loadtype.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Send the selected product ID in the POST request
    xhr.send("selectedProductID=" + encodeURIComponent(selectedProductId));
}

// Declare xhr as a global variable
var xhr;

// Fetch user's product inventory
function fetchInventory() {
    // Make an AJAX request to retrieve user's inventory data
    xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // Handle the response from the server
                try {
                    document.getElementById("tableBody").innerHTML = xhr.responseText;
                } catch (error) {
                    console.error("Error parsing JSON:", error);
                }
            } else {
                // Handle errors
                console.error("Error:", xhr.status);
            }
        }
    };

    xhr.open("GET", "dash_get.php", true);
    xhr.send();
}

// Function to handle delete action
function deleteRow(button) {
    var row = button.closest("tr"); // Get the closest row element

    // Get the type value from the first cell of the row
    var product = row.cells[0].textContent.trim();

    // AJAX request to delete data from the database
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            updateTable();
        }
    };
    xhr.open("POST", "dash_delete.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("product=" + encodeURIComponent(product));
}

// Call the fetchInventory function when the page loads
document.addEventListener("DOMContentLoaded", function () {
    fetchInventory();
    loadProducts();
});

function addInventory() {
    // Load product type and then proceed to add inventory
    loadProductType(function (result) {
        // Retrieve other form values
        var weightInput = document.getElementById("weight");
        var statusInput = document.getElementById("status");
        var expirationInput = document.getElementById("expiration");

        // Add your AJAX logic or other processing here
        sendFormDataToServer(result, function () {
            // Callback function to load products after successful insertion
            loadProducts();

            fetchInventory();
            // Clear the form fields after successful submission
            weightInput.value = "";
            statusInput.value = "";  
            expirationInput.value = "";  
            document.getElementById("productSelect").selectedIndex = -1;
            document.getElementById("productType").value = "";
        });
    });
}


function sendFormDataToServer(result, callback) {
    // Send the form data to the PHP file using AJAX
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // Handle the response from the server
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    // Call the callback function if provided
                    if (typeof callback === 'function') {
                        callback();
                    }
                } else {
                    // Handle other responses or errors
                    console.error("Error:", response.message);
                }
            } else {
                // Handle errors
                console.error("Error:", xhr.status);
            }
        }
    };

    xhr.open("POST", "dash_insert.php", true);
    xhr.setRequestHeader("Content-type", "application/json");

    // Convert the form data object to JSON
    var jsonData = JSON.stringify({
        productId: result.productId,
        productType: result.productType.typeName,
        weight: document.getElementById("weight").value,
        status: document.getElementById("status").value,
        expiration: document.getElementById("expiration").value
    });

    // Send the JSON data in the POST request
    xhr.send(jsonData);
}


// Function to edit a row in the inventory table
function editRow(button) {
    // Show the edit form
    document.getElementById("editInventoryForm").style.display = "block";

    // Get the selected row
    var row = button.closest("tr");

    // Populate the edit form fields with the values from the selected row
    document.getElementById("editWeight").value = row.cells[1].textContent.trim();
    document.getElementById("editStatus").value = row.cells[2].textContent.trim();
    document.getElementById("editExpiration").value = row.cells[3].textContent.trim();

    // Store the original product value in a data attribute
    document.getElementById("editInventoryForm").setAttribute("data-product", row.cells[0].textContent.trim());
}

// Function to save changes to the inventory
function saveInventoryChanges() {
    // Get the edited values from the edit form
    var editedWeight = document.getElementById("editWeight").value;
    var editedStatus = document.getElementById("editStatus").value;
    var editedExpiration = document.getElementById("editExpiration").value;

    // Get the original product value from the data attribute
    var originalProduct = document.getElementById("editInventoryForm").getAttribute("data-product");

    // AJAX request to save the changes to the server
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // If the update is successful, hide the edit form and fetch updated inventory
            console.log("Updated inventory");
            document.getElementById("editInventoryForm").style.display = "none";
            fetchInventory();
        }
    };

    xhr.open("POST", "dash_update.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Send the edited data in the POST request
    xhr.send("originalProduct=" + encodeURIComponent(originalProduct) +
        "&editedWeight=" + encodeURIComponent(editedWeight) +
        "&editedStatus=" + encodeURIComponent(editedStatus) +
        "&editedExpiration=" + encodeURIComponent(editedExpiration));
}

// Function to close the edit form without saving changes
function closeInventoryForm() {
    // Hide the edit form
    document.getElementById("editInventoryForm").style.display = "none";
}

