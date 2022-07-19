<?php
class Constants{
static $DB_SERVER = "localhost";
static $DB_NAME = "pricelist_table";
static $USERNAME = "root";
static $PASSWORD= "";

static $SQL_SELECT_ALL="SELECT * FROM products";

}
class Products{
    public function connect(){
        $con = new mysqli(Constants::$DB_SERVER, Constants::$USERNAME,
         Constants::$PASSWORD, Constants::$DB_NAME);
         if($con->connect_error){
            echo "unable to connect";
            return null;
         }else{
            //echo "Connected";
         return $con;
         }
    }
    public function select(){
     $con=$this->connect();
     if($con != null){
        $result=$con->query(Constants::$SQL_SELECT_ALL);
        if($result->num_rows>0){
            $products=array();
            while($row=$result->fetch_array()){
            array_push($products, array("product_id"=>$row['product_id'],
            "product_name"=>$row['product_name'],
            "product_price" =>$row['product_price']));
            }
            print(json_encode(array_reverse($products)));
        }
      
             else
             {
                 print(json_encode(array("PHP EXCEPTION : CAN'T RETRIEVE FROM MYSQL. ")));
             }
             $con->close();

         }else{
             print(json_encode(array("PHP EXCEPTION : CAN'T CONNECT TO MYSQL. NULL CONNECTION.")));
         }
    }
    public function selectWhere(){
        $con=$this->connect();
        if($con != null)
        {
            
            $productName = mysqli_real_escape_string($con, $_POST['Product_name']);
            
            $query = "SELECT * FROM products WHERE product_name LIKE '$productName%'";
            $results = mysqli_query($con, $query);
            if($results->num_rows>0){
                $products=array();
                while($row=$results->fetch_array()){
                array_push($products, array("product_id"=>$row['product_id'],
                "product_name"=>$row['product_name'],
                "product_price"=>$row['product_price']));
                }
                print(json_encode(array_reverse($products)));
            } else
            {
                print(json_encode(array("PHP EXCEPTION : CAN'T RETRIEVE FROM MYSQL. ")));
            }
            $con->close();
        }
        else{
            print(json_encode(array("PHP EXCEPTION : CAN'T CONNECT TO MYSQL. NULL CONNECTION.")));
        }

    
    }
    public function insert(){
        $con=$this->connect();
        if($con != null)
        {
            $productName = mysqli_real_escape_string($con, $_POST['Product_name']);
            $productPrice = mysqli_real_escape_string($con, $_POST['Product_price']);
            $query = "INSERT INTO products (product_price, product_name)
            VALUES ('$productPrice', '$productName')";
            $results = mysqli_query($con, $query);
            if($result >0){
                echo "product added succesfully";
            }
        }
        else{
            print(json_encode(array("PHP EXCEPTION : CAN'T CONNECT TO MYSQL. NULL CONNECTION.")));
        }
        $con->close();
    
    }
    public function update(){
        $con=$this->connect();
        if($con != null)
        {
            $productId = (int)mysqli_real_escape_string($con, $_POST['Product_id']);
            $productName = mysqli_real_escape_string($con, $_POST['Product_name']);
            $productPrice = (double)mysqli_real_escape_string($con, $_POST['Product_price']);
            $sttm = "UPDATE `products` SET `product_id`=$productId,`product_price`=$productPrice,`product_name`='$productName' WHERE `product_id`=$productId";
            $results = $con->$query($sttm);
            if($result >0){
                echo "product modified succesfully";
            }
            else
            print(json_encode(array("PHP EXCEPTION : CAN'T RETRIEVE FROM MYSQL. ")));
        }
        else{
            print(json_encode(array("PHP EXCEPTION : CAN'T CONNECT TO MYSQL. NULL CONNECTION.")));
        }
        $con->close();
    
    }
    public function delete(){
        $con=$this->connect();
        if($con != null)
        {
            $productId = mysqli_real_escape_string($con, $_POST['Product_id']);
            $query = "DELETE FROM products WHERE product_id = $productId";
            $results = mysqli_query($con, $query);
            if($result >0){
                echo "product deleted succesfully";
            }
        }
        $con->close();
    
    }
}

    
$product = new Products();
if($_SERVER['REQUEST_METHOD'] === 'GET')
$product->select();
else{
    if($_POST['Method'] === 'Insert')
    $product->insert();
    else if($_POST['Method'] === 'Update')
    $product->update();
    else if($_POST['Method'] === 'Delete')
    $product->delete();
    else if($_POST['Method'] === 'Query')
    $product->selectWhere();
}

?>