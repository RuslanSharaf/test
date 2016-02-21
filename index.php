<?php
header('Content-type: text/html; charset=utf-8');
$ini_string='
[игрушка мягкая мишка белый]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';
    
[одежда детская куртка синяя синтепон]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';
    
[игрушка детская велосипед]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';
';
$bd = parse_ini_string($ini_string, true);

$orders = array_keys($bd);
var_dump($bd);
foreach ($bd as $key => &$value) {
    if ($value['diskont'] == 'diskont1') {
        $value['diskont'] = '10%';
    }elseif ($value['diskont'] == 'diskont2'){
        $value['diskont'] = '20%';
    }else {
        $value['diskont'] = '0%';
        
    }
    
}
$bd=  parse_ini_string($ini_string, true);
// Расчет суммы по позиции заказа с учетом скидки 
function count_finish_price($prod, $prod_name = '') { // $prod - массив с еденицей товара $prod_name - наименование товара
    global $bycicle_extra_discount;
    global $diskont;
    $diskont = 10*substr($prod['diskont'], -1);
    if ($prod_name == 'игрушка детская велосипед' and $prod['Количество с учетом остатков'] >= 3) {
        $diskont = 30;
        $bycicle_extra_discount = true;
    }
    return $prod['Количество с учетом остатков']*$prod['цена']/100*(100-$diskont);
}
// Расчет  суммы всего заказа
function count_total_sum($sum = 0) {
    static $total_sum;
    $total_sum += $sum;
    return $total_sum;
}
//print_r($ini_string);
//print_r($bd);
echo '<h1>Корзина товаров:</h1>';
echo '<table border = 2><tr><td>Наименование</td><td>Цена</td><td>Кол-во заказано</td><td>Осталось на складе</td><td>Количество с учетом остатков</td><td>Cкидка %</td><td>Cумма</td></tr>';
reset($bd); // Устанавливает внутренний указатель массива на его первый элемент
$total_names = 0;
$total_count = 0;
$diskont = 0;
$bycicle_extra_discount = false;
$notice = '';
for ($i = 0; $i < count($bd); $i++) {
    $product = $bd[key($bd)];
    if ($product['количество заказано'] > $product['осталось на складе']) {
        $product['Количество с учетом остатков'] = $product['осталось на складе'];
        $notice = $notice.'Из требуемых '.$product['количество заказано'].' '.key($bd).' на складе только '.$product['осталось на складе']. '<br>';
    } else {
        $product['Количество с учетом остатков'] = $product['количество заказано'];
    }
    if ($product['Количество с учетом остатков'] > 0) {
        $total_names++;
    }
    $total_count += $product['Количество с учетом остатков'];
    $sum = count_finish_price($product, key($bd));
    count_total_sum($sum);
    echo key($bd) . ' ';
    echo '</td><td>';
    echo $product['цена'] . ' ';
    echo '</td><td>';
    echo $product['количество заказано'] . ' ';
    echo '</td><td>';
    echo $product['осталось на складе'] . ' ';
    echo '</td><td>';
    echo $product['Количество с учетом остатков'] . ' ';
    echo '</td><td>';
    echo $diskont . ' ';
    echo '</td><td>';
    echo $sum . ' ';
    echo '</td></tr>';
    next($bd); // Передвигает внутренний указатель массива на одну позицию вперёд
}
echo '<tr><td colspan="6">Итого</td>';
echo '<td>'.count_total_sum().'</td>';
echo '</table>';
echo '<br><h2>итого:</h2>';
echo 'Всего товаров ' . $total_names . '<br>';
echo 'Общее количество товара ' . $total_count . '<br>';
echo 'Общая сумма заказа ' . count_total_sum() . '<br>';
if (strlen($notice)) {
    echo '<br><h2>УВЕДОМЛЕНИЯ:</h2>';
    echo $notice;
}
if($bycicle_extra_discount){
    echo 'Поздравляем! Вы заказали  3 штук игрушка детская велосипед и на эту позицию Вам автоматически дается скидка 30%';
    
}

?>   
