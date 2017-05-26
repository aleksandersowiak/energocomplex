<?php
require_once(dirname(__FILE__) . '/../data/config.php');

abstract class BaseModel extends ViewModel
{
    private $database;
    private $_name = 'defaults'; // used to select default database, can be change to use.
    protected $_db;
    private $_configApp = null;

    public function __construct()
    {
        $this->database = $this->getDb();
        $this->_configApp = $this->getConfig();
        $schema = $this->database[$this->_name];
        $this->_db = mysqli_connect(
            $schema['host'] . ':' . $schema['port'],
            $schema['un'],
            $schema['pw']
        )
        or die(mysqli_connect_error());
        $this->_db->set_charset("utf8");
        $this->_db->query('USE `' . $schema['db'] . '`');

    }

    protected function getDb()
    {
        global $db;
        $this->database = $db;
        return $this->database;
    }

    public function getConfig()
    {
        global $configApp;
        $this->_configApp = $configApp;
        return $this->_configApp;
    }

    public function select($query)
    {
        $sql = $this->_db->query($query);
        if (mysqli_errno($this->_db) > 0) {
            die($this->_db->error);
        }
        $data = array();
        while ($d = mysqli_fetch_assoc($sql)) {
            $data[] = $d;
        }
        return $data;
    }

    public function insert($table, $insData)
    {
        $escaped_values = array();
        $columns = implode(", ", array_keys($insData));
        foreach (array_values($insData) as $val) {

            $escaped_values[] = $this->_quote($val);
        }
        $values = implode(", ", $escaped_values);
        $sql = "INSERT INTO `" . $table . "` ($columns) VALUES ($values)";
        $result = $this->_db->query($sql);
        if ($result === FALSE) {
            die($this->_db->error);
        }
    }

    public function update($table,$upData, $where)
    {
        foreach ($upData as $key => $testimonials) {
            $column = ($key);
            $value = ($testimonials);
            $sql = "UPDATE `" . $table . "` SET `" . $column . "`='" . $value . "' WHERE  $where";
            $result = $this->_db->query($sql);
            if ($result === FALSE) {
                die($this->_db->error);
            }
        }
    }

    protected function _quote($value)
    {
        if (is_int($value) || is_float($value)) {
            return $value;
        }
        return "'" . $this->_db->real_escape_string($value) . "'";
    }
}
