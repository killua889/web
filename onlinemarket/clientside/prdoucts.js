let products = document.querySelector(".products");
fetch("../api/products.php")
    .then(res => res.json())
    .then(data => {
        data.forEach(product => {
            let div = document.createElement("div");
            div.classList.add("product");
            div.id = product.id;
            
            let f = document.createElement("figure");
            let h = document.createElement("h2");
            h.innerText = product.name;
            
            let im = document.createElement("img");
            im.src = `../api/imgs/${product.image}`;
            im.alt = `${product.name}`;
            
            let dis = product.description.replace(/\\/g, '');
            let fc = document.createElement("figcaption");
            fc.innerText = dis;
            
            let p = document.createElement("span");
            p.innerText = `$${product.price}`;
            
            let number = document.createElement("span");
            number.innerText = `left in stock ! ${product.num}`;
            
            let btn = document.createElement("button");
            btn.classList.add("add");
            btn.innerText = "Add";
            
            f.appendChild(h);
            f.appendChild(im);
            f.appendChild(fc);
            f.appendChild(p);
            f.appendChild(number);
            f.appendChild(btn);
            div.appendChild(f);
            products.appendChild(div);
        });
    })
    .then(() => {
        let addbtn = document.querySelectorAll(".add");
        addbtn.forEach(button => {
            button.addEventListener("click", event => {
                let productId = event.target.closest('.product').id;
                fetch("../api/addtocart.php", {
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
                        event.target.innerText = "Added";
                        setTimeout(() => {
                            event.target.innerText = "Add";
                        }, 1000);
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error adding product to cart:', error));
            });
        });
    })
    .catch(error => console.error('Error fetching products:', error));
