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
    xhr.open("GET", "types_get.php", true);
    xhr.send();
}

function addRow() {
    // Get the input value
    var typeInputValue = document.getElementById("typeInput").value;

    // Return if the input is empty
    if (typeInputValue.trim() === "") {
        alert("Please enter a value for Type.");
        return;
    }

    isValueExists(typeInputValue, function (exists) {
        if (exists) {
            // Value already exists, handle accordingly
            alert("Type name already exists");
        } else {
            // Type name doesn't exist
            // AJAX request to insert data into the database
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        // If insertion is successful, update the HTML table
                        updateTable();

                        typeInput.value = "";

                    } else {
                        // Handle the error
                        alert("Error inserting data: " + xhr.statusText);
                    }
                }
            };
            xhr.open("POST", "types_insert.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("value=" + encodeURIComponent(typeInputValue));
        }
    });
}

function deleteRow(button) {
    console.log("deleteRow function called"); 
    var row = button.closest("tr"); // Get the closest row element

    // Get the type value from the first cell of the row
    var typeValue = row.cells[0].textContent.trim();

    // AJAX request to delete data from the database
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            updateTable();
        }
    };
    xhr.open("POST", "types_delete.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("type=" + encodeURIComponent(typeValue));
}

function editRow(button) {
    var row = button.closest("tr"); // Get the closest row element

    // Get the type value from the first cell of the row
    var currentTypeValue = row.cells[0].textContent.trim();

    // Prompt the user for a new type value
    var newTypeValue = prompt("Enter a new value for Type:", currentTypeValue);

    // If the user entered something, update the cell with the new value
    if (newTypeValue !== null) {
        // Check if the new value is not empty
        if (newTypeValue.trim() !== "") {
            // AJAX request to check if the type name is already in the database
            isValueExists(newTypeValue, function (exists) {
                if (exists) {
                    // Value already exists, handle accordingly
                    alert("Type name already exists");
                } else {
                    // Type name doesn't exist
                    // AJAX request to update data in the database
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            updateTable();
                        }
                    };
                    xhr.open("POST", "types_update.php", true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.send("currentType=" + encodeURIComponent(currentTypeValue) + "&newType=" + encodeURIComponent(newTypeValue));
                }
            });
        } else {
            alert("Please enter a non-empty value for Type.");
        }
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
    xhr.open("POST", "types_exists.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("value=" + encodeURIComponent(value));
}