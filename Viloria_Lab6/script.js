let inventory = [];
let nextId = 1;


// Read the data from the form then add
document.getElementById(`the-form`).addEventListener(`submit`, function(event) {

    event.preventDefault(); // Prevent default value to execute 
    const name = document.getElementById(`name`).value;
    const price = parseFloat(document.getElementById(`price`).value);
    const stock = parseInt(document.getElementById(`stock`).value);
    addProductProduct(name, price, stock);
    this.reset(); // Clear the form.
});

function addProduct(name, price, stock) { 

    inventory.push({ id: nextId++, name, price, stock });
    renderTable();
}

// display the table
function renderTable() {

    const tableBody = document.getElementById(`tableBody`);
    tableBody.innerHTML = ``; // clear current table.

    for (const item of inventory) {
        const row = `
            <tr>
                <td>${item.id}</td>
                <td>${item.name}</td>
                <td>${item.price.toFixed(2)}</td>
                <td style = "${item.stock === 0 ? 'color: red' : '' }">
                ${item.stock === 0 ? 'Out of Stock' : item.stock}</td>      
            </tr> `;
            tableBody.innerHTML += row;  
    }
}




