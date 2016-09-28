<?php

class Bag
{
    private $csvFile;
    private $itemArray;
    private $bagSize;
    private $bagMaxSize;
    private $bag = [];

    //Database files

    public function getCsvFile()
    {
        return $this->csvFile;
    }

    public function setCsvFile($csvFile)
    {
        $this->csvFile = array_map('str_getcsv', file($csvFile));
    }

    //Clear Item Array

    public function getItemArray()
    {
        return $this->itemArray;
    }

    public function setItemArray($itemArray)
    {
        $this->itemArray = $itemArray;
    }

    //Actual Bag size

    public function getBagSize()
    {
        return $this->bagSize;
    }

    public function setBagSize($bagSize)
    {
        $this->bagSize = $bagSize;
    }

    //Max Bag Size

    public function getBagMaxSize()
    {
        return $this->bagMaxSize;
    }

    public function setBagMaxSize($bagMaxSize)
    {
        $this->bagMaxSize = $bagMaxSize;
    }

    /**
     * @return array
     */
    public function getBag()
    {
        return $this->bag;
    }

    /**
     * @param array $bag
     */
    public function setBag($bag)
    {
        $this->bag = $bag;
    }

    /**
     * @param $a array to sort
     * @param $subkey column name to sort
     * @return sorted array by column name
     */
    function subval_sort($a, $subkey) {
        foreach($a as $k=>$v) {
            $b[$k] = strtolower($v[$subkey]);
        }
        arsort($b);
        foreach($b as $key=>$val) {
            $c[] = $a[$key];
        }
        return $c;
    }

    /**
     * Convert CSV to array and add new array key item profit
     * @return array from csv file
     */
    public function convertCsvToArray()
    {
        $i = 1;
        foreach ($this->getCsvFile() as $value) {
            if ($value[0] != 'item_id;item_weight;item_value') {
                $tmp = explode(";", $value[0]);

                $item[$i]['id'] = $tmp[0];
                $item[$i]['value'] = $tmp[2];
                $item[$i]['weight'] = $tmp[1];
                $item[$i]['profit'] = $tmp[2] - $tmp[1];
                $i++;
            }
        }
        return $item;
    }

    /**
     * Inventory constructor.
     * @param $csvFile
     * @param $bagMaxSize = size of the bag
     */
    public function __construct($csvFile, $bagMaxSize)
    {
        $this->setCsvFile($csvFile);
        $this->setItemArray($this->convertCsvToArray());
        $this->setBagSize(0);
        $this->setBagMaxSize($bagMaxSize);
    }

    /**
     * Function add item to bag.
     * @param $item array
     * @param string $option ( amount or unique )
     * @return true or false
     */
    public function addItem($item, $option = 'amount'){
        if($item){
            if ($option == 'unique' || $option == 'amount'){
                if($this->getBagSize() + $item['weight'] <= $this->getBagMaxSize()) {
                    if ($option == 'unique' || $option == 'amount' && array_key_exists($item['id'], $this->getBag()) == false) {
                            $this->bag[$item['id']]['value'] = $item['value'];
                            $this->bag[$item['id']]['weight'] = $item['weight'];
                            $this->bag[$item['id']]['amount'] = 1;
                            $this->setBagSize($this->getBagSize() + $item['weight']);
                    } else if($option == 'amount'){
                            $this->bag[$item['id']]['amount'] = $this->bag[$item['id']]['amount'] + 1;
                            $this->setBagSize($this->getBagSize() + $item['weight']);
                    }

                    return true;
                }
            }
        }
        return false;
    }
    public function checkBeforeAddItem($item, $option = 'amount'){
        if($this->getBagSize() + $item['weight'] <= $this->getBagMaxSize()) {

            if ($option == 'amount' || ($option == 'unique' && array_key_exists($item['id'], $this->getBag()) == false)) {

                return true;
            }
        }

        return false;
    }

    /**
     * @return int value of the bag
     */
    public function getBagValue(){
        if($this->getBag()){
            $bagValue = 0;
            foreach ($this->getBag() as $value){

                $bagValue += $value['value'] * $value['amount'];
            }
            
            return $bagValue;
        } else {

            return 0;
        }
    }

    /**
     * @param string $option amount or unique
     * @return add items to bag
     */
    public function getMostValuableBag($option = 'amount')
    {
        $sortItem = $this->subval_sort($this->getItemArray(), 'profit');

        for ($i = 0; $i < count($sortItem); $i++) {
            while ($this->checkBeforeAddItem($sortItem[$i], $option) == true) {
                $this->addItem($sortItem[$i], $option);

                if($this->getBagSize() == $this->getBagMaxSize()){
                   break;
                }
            }
        }
    }

}