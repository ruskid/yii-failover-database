<?php

/**
 * @copyright Copyright Victor Demin, 2014
 * @license https://github.com/ruskid/yii-failover-database/LICENSE
 * @link https://github.com/ruskid/yii-failover-database#readme
 */

/**
 * The class will rotate connections on fail
 * @author Victor Demin <demmbox@gmail.com>
 */
class FoDbConnection extends CDbConnection {

    /**
     * Array of failover connections names for if this connection fails
     * @var array
     */
    public $failOverConnections = [];

    /**
     * Stack of failover connections
     * @var array
     */
    private $_connections = [];

    /**
     * @var boolean
     */
    private $_init = true;

    /**
     * Extends CDbConnection open() method
     * @throws CException If it can not connect to any DBs
     */
    protected function open() {
        try {
            //try to connect to the default DB
            parent::open();
        } catch (Exception $e) {
            //Will get fail over connections array for the first time.
            if ($this->_init) {
                $this->_connections = $this->getFailOverConnections($this->failOverConnections);
                $this->_init = false;
            }

            if (!empty($this->_connections)) {
                //Get first connection, remove it from stack and try to connect
                $this->connectionString = $this->_connections[0]['connectionString'];
                $this->username = $this->_connections[0]['username'];
                $this->password = $this->_connections[0]['password'];
                array_shift($this->_connections);
                $this->open();
            } else {
                throw new CDbException('Could Not Connect to a DB.');
            }
        }
    }

    /**
     * Will get an array of failover connections
     * @param array $connectionNames
     * @return array
     */
    public static function getFailOverConnections($connectionNames) {
        $temp = [];
        foreach (Yii::app()->getComponents(false) as $index => $component) {
            if (is_array($component) && isset($component['class']) &&
                    $component['class'] == 'FoDbConnection') {
                if (in_array($index, $connectionNames)) {
                    array_push($temp, $component);
                }
            }
        }
        return $temp;
    }

}
