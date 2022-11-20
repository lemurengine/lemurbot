<?php


namespace LemurEngine\LemurBot\Classes;

use Exception;

/**
 * FlowStack singleton class.
 * Simplified debugging logger - this might superceed the existing debuging
 **/
class FlowStack
{

    private array $flowStack=[];


    // Hold the class instance.
    private static ?FlowStack $instance = null;

    // The constructor is private
    // to prevent initiation with outer code.
    private function __construct()
    {}

    // The object is created from within the class itself
    // only if the class has no instance.
    public static function getInstance() :self
    {
        if (self::$instance == null) {
            self::$instance = new FlowStack();
        }

        return self::$instance;
    }


    /**
     * @param $key
     * @param $value
     */
    public function push($key, $value)
    {
        $total = count($this->flowStack)+1;
        $index = $total.': '.$key;
        $this->flowStack[$index]=$value;
    }

    /**
     * @param $value
     * @param $key
     * @throws Exception
     */
    public function getFlowStack()
    {
        return $this->flowStack;
    }

    public function destroy()
    {
        self::$instance = null;
    }
}
