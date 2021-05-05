<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Referrer-Policy: no-referrer");

require_once 'products.php';

$show = isset($_GET['show']) ? htmlspecialchars($_GET['show']) : count($products);
$category = htmlspecialchars($_GET['category']);

//Ta fram vilka kategorier som finns i $products-arrayen
$categories = array_unique(array_column($products, 'category'));

//Urvals-array att plocka från till $response-arrayen
$selection = array();

//Svars-array
$response = array();

//Se till att $category är ett giltigt alternativ och hantera det annars
if(!empty($category) && !in_array($category, $categories)){
   array_push($response, "Inga produkter i kategorin " . $category); 
} elseif(!empty($category) && in_array($category, $categories)){
    foreach($products as $item){
        $item['category']===$category && array_push($selection, $item); 
    } 
} else{
    $selection = $products;
}

//Se till så att ett giltigt antal har matats in
if(!is_numeric($show) || $show < 1 || $show > count($products)){
    array_push($response, "Ange ett antal mellan 1 och " . count($products));
}

//Ställ om antalet till max för kategorin om det behövs
$show > count($selection) && $show = count($selection); 

//Om det inte har uppstått fel pushas valt antal produkter in i svars-arrayen
if(empty($response)){
    //Slumpa fram produkter från urvalet
    shuffle($selection);
    for($i = 0; $i < $show; $i++){
        array_push($response, $selection[$i]);
    }
}

//Skicka svaret som JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE);
