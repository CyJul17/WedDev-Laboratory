document.getElementById(`the-form`).addEventListener(`submit`, function (event) {

    event.preventDefault(); // Prevent default value to execute 
    const NAME = document.getElementById(`name`).value;
    const PRICE = document.getElementById(`price`).value;
    const STOCK = document.getElementById(`stock`).value;
    inventoryManager.addProduct(NAME, PRICE, STOCK);
    this.reset(); // Clear the form.
});

const inventoryManager = {  

    inventory: [],
    nextId: 1,
    // Read the data from the form then add
    addProduct: function(name, price, stock) {
    
        const newProduct = {
        
            id: this.nextId++,
            name: name,
            price: parseFloat(price),
            stock: parseInt(stock),
            }

        this.inventory.push(newProduct);
        this.renderTable();
        },

    // display the table
    renderTable: function() {

        const tableBody = document.getElementById(`tableBody`);
        tableBody.innerHTML = ``; // clear current table.

        for (const item of this.inventory) {
            const row = `
                <tr>
                    <td>${item.id}</td>
                    <td>${item.name}</td>
                    <td>${item.price.toFixed(2)}</td>
                    <td style = "${item.stock === 0 ? 'color: red' : ''}">
                    ${item.stock === 0 ? 'Out of Stock' : item.stock}</td>      
                </tr> `;
            tableBody.innerHTML += row;
            }
        }

};





