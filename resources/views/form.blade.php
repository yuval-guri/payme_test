<TABLE>
    <FORM action="process_form" method="GET" >
    <tr>
        <td>Product name</td>
        <td><input name="product_name" required /></td>
    </tr>
    <tr>
        <td>Price</td>
        <td><input name="sale_price" type="number" step="0.01" required /></td>
    </tr>
    <tr>
        <td>Currency</td>
        <td>
            <select name = "currency">
                <option value="0">USD</option>
                <option value="1">EUR</option>
                <option value="2" SELECTED>ILS</option>
            </select>
        </td>
    </tr><td><input type="SUBMIT" value="Insert Payment Details"/></td></tr>
    </FORM>
</TABLE>