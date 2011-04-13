<html>
<body>

<div id="result1" style="background:green">
<?php 
if (isset($_POST["product_name"])) {
   $prod = $_POST["product_name"];
   echo "you entered : ".$prod;
}
?>
<br/><br/></div>
<form name="form1" method="post">

<input type="text" name="product_name" id="prod_name" size="40" value="<?php if (isset($prod)) echo $prod;?>"/>
<select name="sel1">
  <option id="1">option 1</option>
  <option id="2">option 2</option>
  <option id="3">option 3</option>
  <option id="4">option 4</option>
</select>
<br/>
<input type="checkbox" name="chbox1"/>checkbox<br/>
<br/>
<input type="submit" value="Confirm"/>
</form>

</body>
</html>