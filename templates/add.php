
    <h2>Pridať nový produkt</h2>
    <form method="post" class="filter-form">
        
        <div>
            <label for="title">Názov produktu</label>
            <input id="title" type="text" name="title" placeholder="Názov produktu" required>
        </div>

        <div>
            <label for="description">Popis</label>
            <textarea id="description" name="description" placeholder="Popis"></textarea>
        </div>

        <div>
            <label for="quantity">Počet kusov</label>
            <input id="quantity" type="number" name="quantity" placeholder="Počet ks" min="0" required>
        </div>

        <div>
            <label for="price">Cena (€)</label>
            <input id="price" type="number" name="price" placeholder="Cena" step="0.01" min="0" required>
        </div>

        <div>
            <label for="size">Veľkosť</label>
            <input id="size" type="text" name="size" placeholder="Veľkosť (napr. S, M, L, XL)">
        </div>

        <div>
            <label for="color">Farba</label>
            <input id="color" type="text" name="color" placeholder="Farba">
        </div>

        <div>
            <label for="product_code">Kód produktu</label>
            <input id="product_code" type="text" name="product_code" placeholder="Kód produktu">
        </div>

        <div class="center-btn">
            <button type="submit" name="add">Pridať produkt</button>
        </div>
    </form>
