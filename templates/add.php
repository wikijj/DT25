<h2>Pridať nový produkt</h2>
<form method="post" class="filter-form">
    <label>Názov produktu</label>
    <input type="text" name="title" placeholder="Nazov produktu" required>

    <label>Popis</label>
    <textarea name="description" placeholder="Popis"></textarea>

    <label>Počet kusov</label>
    <input type="number" name="quantity" placeholder="Pocet ks" min="0" required>

    <label>Cena</label>
    <input type="number" name="price" placeholder="Cena" step="0.01" min="0" required>

    <label>Veľkosť</label>
    <input type="text" name="size" placeholder="Veľkosť (napr. S, M, L, XL)">

    <label>Farba</label>
    <input type="text" name="color" placeholder="Farba">

    <label>Kód produktu</label>
    <input type="text" name="product_code" placeholder="Kod produktu">

    <div class="center-btn">
        <button type="submit" name="add">Pridať produkt</button>
    </div>
</form>
