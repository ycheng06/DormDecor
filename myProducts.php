<?php
function displayProducts() {
require_once('db_connect.php');
$result1 = mysql_query('SELECT Product_id FROM Users_Products WHERE
Login = \'' . $_SESSION['login'] . '\'') or die(mysql_error());
echo '<h2>My Watched Products</h2>';
while ($prod = mysql_fetch_assoc($result1)) {
$prodID = $prod['Product_id'];
$result2 = mysql_query('SELECT * FROM Product WHERE Product_id = \'' .
$prodID . '\'') or die(mysql_error());
$product = mysql_fetch_assoc($result2);
echo '<div id="table_wrap">';
echo '<table id="product_table">';
echo '<tr>';
echo '<td>';
echo '<a href="' . $product['Product_url'] . '"><img src="' .
$product['Product_image'] . '" alt="' . $product['Product_name'] .
'"/></a>';
echo '<td><a href="' . $product['Product_url'] . '"><h5
class="product_name">' . $product['Product_name'] . '</a></h5>';
echo '<p class="prod_info">' . $product['Product_Price'] . ' from ' .
$product['Vendor_name'] . '</p>';
echo '<p class="prod_desc">' . $product['Product_desc'] . '</p>';
echo '</td>';
echo '</tr>';
echo '</table>';
echo '</div>';
}
}
?>