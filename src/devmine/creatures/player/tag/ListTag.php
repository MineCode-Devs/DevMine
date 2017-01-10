<?php



namespace devmine\creatures\player\tag;

use devmine\creatures\player\NBT;
use devmine\creatures\player\tag\ListTag as TagEnum;

#include <rules/NBT.h>

class ListTag extends NamedTag implements \ArrayAccess, \Countable{

	private $tagType;

	public function __construct($name = "", $value = []){
		$this->__name = $name;
		foreach($value as $k => $v){
			$this->{$k} = $v;
		}
	}

	public function &getValue(){
		$value = [];
		foreach($this as $k => $v){
			if($v instanceof Tag){
				$value[$k] = $v;
			}
		}

		return $value;
	}

	public function getCount(){
		$count = 0;
		foreach($this as $tag){
			if($tag instanceof Tag){
				++$count;
			}
		}

		return $count;
	}

	public function offsetExists($offset){
		return isset($this->{$offset});
	}

	public function offsetGet($offset){
		if(isset($this->{$offset}) and $this->{$offset} instanceof Tag){
			if($this->{$offset} instanceof \ArrayAccess){
				return $this->{$offset};
			}else{
				return $this->{$offset}->getValue();
			}
		}

		return null;
	}

	public function offsetSet($offset, $value){
		if($value instanceof Tag){
			$this->{$offset} = $value;
		}elseif($this->{$offset} instanceof Tag){
			$this->{$offset}->setValue($value);
		}
	}

	public function offsetUnset($offset){
		unset($this->{$offset});
	}

	public function count($mode = COUNT_NORMAL){
		for($i = 0; true; $i++){
			if(!isset($this->{$i})){
				return $i;
			}
			if($mode === COUNT_RECURSIVE){
				if($this->{$i} instanceof \Countable){
					$i += count($this->{$i});
				}
			}
		}

		return $i;
	}

	public function getType(){
		return NBT::TAG_List;
	}

	public function setTagType($type){
		$this->tagType = $type;
	}

	public function getTagType(){
		return $this->tagType;
	}

	public function read(NBT $nbt, bool $network = false){
		$this->value = [];
		$this->tagType = $nbt->getByte();
		$size = $nbt->getInt($network);
		for($i = 0; $i < $size and !$nbt->feof(); ++$i){
			switch($this->tagType){
				case NBT::TAG_Byte:
					$tag = new ByteTag("");
					$tag->read($nbt, $network);
					$this->{$i} = $tag;
					break;
				case NBT::TAG_Short:
					$tag = new ShortTag("");
					$tag->read($nbt, $network);
					$this->{$i} = $tag;
					break;
				case NBT::TAG_Int:
					$tag = new IntTag("");
					$tag->read($nbt, $network);
					$this->{$i} = $tag;
					break;
				case NBT::TAG_Long:
					$tag = new LongTag("");
					$tag->read($nbt, $network);
					$this->{$i} = $tag;
					break;
				case NBT::TAG_Float:
					$tag = new FloatTag("");
					$tag->read($nbt, $network);
					$this->{$i} = $tag;
					break;
				case NBT::TAG_Double:
					$tag = new DoubleTag("");
					$tag->read($nbt, $network);
					$this->{$i} = $tag;
					break;
				case NBT::TAG_ByteArray:
					$tag = new ByteArrayTag("");
					$tag->read($nbt, $network);
					$this->{$i} = $tag;
					break;
				case NBT::TAG_String:
					$tag = new StringTag("");
					$tag->read($nbt, $network);
					$this->{$i} = $tag;
					break;
				case NBT::TAG_List:
					$tag = new TagEnum("");
					$tag->read($nbt, $network);
					$this->{$i} = $tag;
					break;
				case NBT::TAG_Compound:
					$tag = new CompoundTag("");
					$tag->read($nbt, $network);
					$this->{$i} = $tag;
					break;
				case NBT::TAG_IntArray:
					$tag = new IntArrayTag("");
					$tag->read($nbt, $network);
					$this->{$i} = $tag;
					break;
			}
		}
	}

	public function write(NBT $nbt, bool $network = false){
		if(!isset($this->tagType)){
			$id = null;
			foreach($this as $tag){
				if($tag instanceof Tag){
					if(!isset($id)){
						$id = $tag->getType();
					}elseif($id !== $tag->getType()){
						return false;
					}
				}
			}
			$this->tagType = $id;
		}

		$nbt->putByte($this->tagType);

		/** @var Tag[] $tags */
		$tags = [];
		foreach($this as $tag){
			if($tag instanceof Tag){
				$tags[] = $tag;
			}
		}
		$nbt->putInt(count($tags));
		foreach($tags as $tag){
			$tag->write($nbt, $network);
		}
	}

	public function __toString(){
		$str = get_class($this) . "{\n";
		foreach($this as $tag){
			if($tag instanceof Tag){
				$str .= get_class($tag) . ":" . $tag->__toString() . "\n";
			}
		}
		return $str . "}";
	}
}
