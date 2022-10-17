<?php


namespace LemurEngine\LemurBot\Classes;

use Exception;

/**
 * TagStack singleton class.
 * The tag stack is order of the tags are controlled...
 * Tags are taken off the stack either parsed or updated/saved to be parsed in the future
 **/
class TagStack
{

    private array $stack=[];
    private array $templateStack=[];
    private array $templateStackId=[];
    private ?string $index = null;


    // Hold the class instance.
    private static ?Tagstack $instance = null;

    // The constructor is private
    // to prevent initiation with outer code.
    private function __construct()
    {}

    // The object is created from within the class itself
    // only if the class has no instance.
    public static function getInstance() :self
    {
        if (self::$instance == null) {
            self::$instance = new TagStack();
        }

        return self::$instance;
    }

    /**
     * @param $value
     * @param $key
     * @throws Exception
     */
    public function push($value, $key)
    {
        $this->resetPointer();
        if (is_null($this->index)) {
            throw new Exception("TagStack index not set");
        }

        $maxStackId = count($this->templateStackId[$this->index]);
        $maxStack = count($this->stack[$this->index]);
        $this->templateStackId[$this->index][$maxStackId]=$key;
        $this->stack[$this->index][$maxStack]=$this->prestore($value);
    }


    public function pop()
    {

        if (isset($this->stack[$this->index])) {
            $stackIdMax = count($this->templateStackId[$this->index])-1;
            $stackMax = count($this->stack[$this->index])-1;

            if (isset($this->stack[$this->index][$stackMax])) {
                $lastItem = $this->stack[$this->index][$stackMax];
                unset($this->stack[$this->index][$stackMax]);
                unset($this->templateStackId[$this->index][$stackIdMax]);
                return $this->unstore($lastItem);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }



    public function prestore($item)
    {
        return $item;
    }

    public function unstore($item)
    {
        return $item;
    }



    public function count():int
    {
        return count($this->stack[$this->index]);
    }

    public function lastItem()
    {
        if (!empty($this->stack[$this->index])) {
            $max = $this->count()-1;
            if (!empty($this->stack[$this->index][$max])) {
                return $this->stack[$this->index][$max];
            }
        }
        return false;
    }

    /**
     * @throws Exception
     */
    public function getPositionOfTag($tagId) :int|false
    {

        foreach ($this->stack[$this->index] as $i => $tagInStack) {
            $item = $this->item($i);
            if ($item->getTagId() === $tagId) {
                return $i;
            }
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function previousItemByCurrentPosition($i)
    {

        $previousI = $i-1;

        if (isset($this->stack[$this->index][$previousI])) {
            return $this->item($previousI);
        }
        return false;
    }

    /**
     * @throws Exception
     */
    public function getItemCurrentPosition($i)
    {


        if (isset($this->stack[$this->index][$i])) {
            return $this->item($i);
        }
        return false;
    }

    /**
     * @throws Exception
     */
    public function previousItemByTagId($tagId)
    {
        $i = $this->getPositionOfTag($tagId);
        if ($i !== false) {
            return $this->previousItemByCurrentPosition($i);
        }
        return false;
    }

    public function previousItem()
    {
        if (!empty($this->stack[$this->index])) {
            $max = $this->count()-2;
            if (!empty($this->stack[$this->index][$max])) {
                return $this->stack[$this->index][$max];
            }
        }
        return false;
    }


    public function resetPointer()
    {

        if ($this->stack && $this->index && isset($this->stack[$this->index])) {
            end($this->stack[$this->index]);
        }
    }

    public function item($i)
    {
        $this->resetPointer();
        if (!isset($this->stack[$this->index][$i])) {
            throw new Exception("Cannot find index [$i] in tag stack");
        }

        return $this->unstore($this->stack[$this->index][$i]);
    }


    public function exists($i): bool
    {
        $this->resetPointer();
        if (!isset($this->templateStackId[$this->index][$i])) {
            return false;
        }

        return true;
    }

    public function maxPosition()
    {

        $this->resetPointer();
        if (empty($this->stack[$this->index])) {
            return false;
        }

        return max(array_keys($this->stack[$this->index]));
    }


    public function isFinalTag(): bool
    {

        if (is_null($this->index) && empty($this->stack)) {
            return true;
        }

        return count($this->stack[$this->index])===1;
    }


    public function overWrite($tag, $i = false)
    {

        $this->resetPointer();
        if (!$i) {
            $i = $this->maxPosition();
        }
        unset($this->stack[$this->index][$i]);
        return $this->stack[$this->index][$i]=$this->prestore($tag);
    }


    public function incIndex($tagId)
    {
        $this->index=$tagId;
        $this->templateStack[]=$tagId;
        $this->stack[$tagId]=[];
        $this->templateStackId[$tagId]=[];

        $this->index=$tagId;
    }


    public function decIndex($tagId)
    {



        unset($this->stack[$tagId]);
        unset($this->templateStackId[$tagId]);
        array_pop($this->templateStack);
        if (!empty($this->templateStack)) {
            $this->index = end($this->templateStack);
        } else {
            $this->index = null;
        }
    }

    public function getIndex(): ?string
    {
        return $this->index;
    }


    public function getStack($which = 'stack')
    {

        return $this->$which;
    }

    public function destroy()
    {
        self::$instance = null;
    }
}
