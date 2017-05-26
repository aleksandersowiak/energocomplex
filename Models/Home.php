<?php
class HomeModel extends BaseModel{
	public function Index() {
		return array("Value 1", "Value 2", "Value 3");
	}
    public function selectModel() {
        $query = 'Select * from `table`';
        $result = $this->select($query);
        return $result;
    }
}