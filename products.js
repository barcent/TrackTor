// Initial table update on page load
updateTable();

function updateTable() {
    // AJAX request to retrieve updated data from the database
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Update the HTML table with the retrieved data
            document.getElementById("tableBody").innerHTML = xhr.responseText;
        }
    };
    xhr.open("GET", "products_get.php", true);
    xhr.send();
}

function addProduct() {
    // Get form data
    var productNameInput = document.getElementById("productName");
    var productTypeInput = document.getElementById("mySelect");

    var productName = productNameInput.value;
    var productType = productTypeInput.value;

    // Validate Product Name
    if (!productName.trim()) {
        alert("Error: Product Name cannot be empty.");
        return;
    }

    // Create FormData object and append form data
    var formData = new FormData();
    formData.append("productName", productName);
    formData.append("productType", productType);

    isValueExists(productName, function (exists) {
        if (exists) {
            // Value already exists, handle accordingly
            alert("Product name already exists");
        } else {
            // Product name doesn't exist
            console.log("Adding product:", productName, productType);

            // Send form data using AJAX
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        console.log("Product added successfully:", xhr.responseText);
                        updateTable(); // Make sure this function is defined and working correctly

                        // Clear the input fields after successful insertion
                        productNameInput.value = "";
                        productTypeInput.value = "";
                    } else {
                        console.error("Error adding product:", xhr.statusText);
                        alert("Error adding product. Please try again."); // Display error message
                    }
                }
            };
            xhr.open("POST", "products_insert.php", true);
            xhr.send(formData);
        }
    });
}



function deleteRow(button) {
    console.log("deleteRow function called"); 
    var row = button.closest("tr"); // Get the closest row element

    // Get the type value from the first cell of the row
    var product = row.cells[0].textContent.trim();

    // AJAX request to delete data from the database
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // If deletion is successful, remove the corresponding row from the HTML table
            if (row.parentNode) {
                row.parentNode.removeChild(row); // Remove the row where the delete button was clicked
            }
        }
    };
    xhr.open("POST", "products_delete.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("product=" + encodeURIComponent(product));
}

// Declare a global variable to store the current product name
var currentProdNameGlobal;

function editRow(button) {
    var row = button.closest("tr");
    currentProdNameGlobal = row.cells[0].textContent.trim(); // Store the current product name globally
    var currentProdType = row.cells[1].textContent.trim(); // Assuming the type is in the second cell

    // Show the form
    document.getElementById('editForm').style.display = 'block';

    // Populate the input fields with the current values
    document.getElementById('newProductName').value = currentProdNameGlobal;

    // Update the dropdown to show the current product type
    var select = document.getElementById('newProductType');
    var options = select.options;
    for (var i = 0; i < options.length; i++) {
        if (options[i].text === currentProdType) {
            select.selectedIndex = i;
            break;
        }
    }
}

function closeForm() {
    // Hide the form when the user cancels
    document.getElementById('editForm').style.display = 'none';
}

function saveChanges() {
    var newProdName = document.getElementById('newProductName').value;
    var newProdType = document.getElementById('newProductType').value;
    
    // Use the globally stored current product name
    var currentProdName = currentProdNameGlobal;

    // Create FormData object and append form data
    var formData = new FormData();
    formData.append("currentProdName", currentProdName);
    formData.append("newProdName", newProdName);
    formData.append("newProdType", newProdType);

    if (newProdName.trim() !== "") {
        isValueExists(newProdName, function (exists) {
            if (exists) {
                // Value already exists, handle accordingly
                alert("Product already exists");
            } else {
                // Type name doesn't exist
                // AJAX request to update data in the database
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        console.log("Product updated successfully:", xhr.responseText);
                        updateTable(); // Make sure this function is defined and working correctly
                    }
                };
                xhr.open("POST", "products_update.php", true);
                xhr.send(formData);
            }
        });

        // Hide the form
        closeForm();
    } else {
        alert("Please enter a non-empty value.");
    }
}


function isValueExists(value, callback) {
    // AJAX request to check if the value exists in the database
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // Parse the response as JSON
                var response = JSON.parse(xhr.responseText);
                        
                // Invoke the callback function with the result
                callback(response.exists);
            } else {
                // Handle the error
                console.error("Error checking value existence:", xhr.statusText);
                callback(false); // Assume the value doesn't exist to prevent further issues
            }
        }
    };
    xhr.open("POST", "products_exists.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("value=" + encodeURIComponent(value));
}