let productsContainer = document.querySelector(".products");
let cart = {}; 

fetch("../api/cart.php")
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            return data.products; 
        } else {
            alert(data.message); 
            window.location.href = "login.html"; 
        }
    })
    .then(products => {
        products.forEach(product => {
            cart[product.id] = product.quantity; 

            let div = document.createElement("div");
            div.classList.add("product");
            div.id = product.id;

            let figure = document.createElement("figure");
            let h2 = document.createElement("h2");
            h2.innerText = product.name;

            let img = document.createElement("img");
            img.src = `../api/imgs/${product.image}`;
            img.alt = product.name;

            let description = product.description.replace(/\\/g, ''); 
            let figcaption = document.createElement("figcaption");
            figcaption.innerText = description;

            let price = document.createElement("span");
            price.innerText = `$${product.price}`;

            let stock = document.createElement("span");
            stock.innerText = `Left in stock: ${product.num}`;

            let userq = document.createElement("span");
            userq.innerText = `Your products: ${product.quantity}`;
            userq.classList.add("userquantity");

            let quantityContainer = document.createElement("div");
            quantityContainer.classList.add("quantity-container");
            let quantityLabel = document.createElement("span");
            quantityLabel.innerText = "Quantity:";

            let quantityInput = document.createElement("input");
            quantityInput.type = "number";
            quantityInput.classList.add("quantity");
            quantityInput.value = product.quantity;
            quantityInput.min = "1"; 

            let updateButton = document.createElement("button");
            updateButton.innerText = "Update Quantity";
            updateButton.classList.add("update");

            quantityContainer.appendChild(quantityLabel);
            quantityContainer.appendChild(quantityInput);
            quantityContainer.appendChild(updateButton);

            let deleteButton = document.createElement("button");
            deleteButton.classList.add("delete");
            deleteButton.innerText = "Delete";

            figure.appendChild(h2);
            figure.appendChild(img);
            figure.appendChild(figcaption);
            figure.appendChild(price);
            figure.appendChild(stock);
            figure.appendChild(userq);
            figure.appendChild(quantityContainer);
            figure.appendChild(deleteButton);
            div.appendChild(figure);
            productsContainer.appendChild(div);
        });
    })
    .then(() => {
        let deleteButtons = document.querySelectorAll(".delete");
        deleteButtons.forEach(button => {
            button.addEventListener("click", event => {
                let productId = event.target.closest('.product').id;
                fetch("../api/deletefromcart.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: new URLSearchParams({
                        'product_id': productId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        event.target.closest('.product').remove();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error deleting product from cart:', error));
            });
        });

        let updateButtons = document.querySelectorAll(".update");
        updateButtons.forEach(button => {
            button.addEventListener("click", event => {
                let productElement = event.target.closest('.product');
                let productId = productElement.id;
                let quantity = productElement.querySelector(".quantity").value;
                fetch("../api/addtocart.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: new URLSearchParams({
                        'product_id': productId,
                        "quantity": quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        productElement.querySelector('.userquantity').innerText = `Your products: ${quantity}`;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error updating product quantity:', error));
            });
        });
    })
    .catch(error => console.error('Error fetching products:', error));
