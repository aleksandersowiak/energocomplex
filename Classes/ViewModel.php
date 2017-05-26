<?php
/**
 * Created by PhpStorm.
 * User: aso@ccp
 * Date: 28.04.17
 * Time: 05:20
 */

class ViewModel {

    public function Add($name,$value) {
        $this->$name = $value;
    }
}

