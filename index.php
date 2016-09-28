<?php

include_once ('class/class_bag.php');

$inv = new Bag('db.csv', 200);
$inv2 = new Bag('db.csv', 200);


echo '<h2>Most valuable bag (with amount):</h2>';

$inv->getMostValuableBag('amount');

echo '<p>Bag value: '.$inv->getBagValue().'</p>
      <p>Used bag space: '.$inv->getBagSize().' / '.$inv->getBagMaxSize().'</p>
      <p>Bag: </p>';

echo '<table border="1">
        <tr style="text-align: center;">
            <td>Item ID</td>
            <td>Item Value</td>
            <td>Item Weight</td>
            <td>Item Amount</td>
        </tr>';

foreach($inv->getBag() as $key => $item){
    echo '
        <tr style="text-align: center;">
            <td>'.$key.'</td>
            <td>'.$item['value'].'</td>
            <td>'.$item['weight'].'</td>
            <td>'.$item['amount'].'</td>
        </tr>';
}

echo '</table>';

echo '<h2>Most valuable bag (unique):</h2>';

$inv2->getMostValuableBag('unique');

echo '<p>Bag value: '.$inv2->getBagValue().'</p>
      <p>Used bag space: '.$inv2->getBagSize().' / '.$inv2->getBagMaxSize().'</p>
      <p>Bag: </p>';

echo '<table border="1">
        <tr style="text-align: center;">
            <td>Item ID</td>
            <td>Item Value</td>
            <td>Item Weight</td>
        </tr>';

foreach($inv2->getBag() as $key => $item){
    echo '
        <tr style="text-align: center;">
            <td>'.$key.'</td>
            <td>'.$item['value'].'</td>
            <td>'.$item['weight'].'</td>
        </tr>';
}

echo '</table>';

?>