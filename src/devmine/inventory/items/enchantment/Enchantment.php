<?php



namespace devmine\inventory\items\enchantment;

use devmine\inventory\items\ChainBoots;
use devmine\inventory\items\ChainChestplate;
use devmine\inventory\items\ChainHelmet;
use devmine\inventory\items\ChainLeggings;
use devmine\inventory\items\DiamondAxe;
use devmine\inventory\items\DiamondBoots;
use devmine\inventory\items\DiamondChestplate;
use devmine\inventory\items\DiamondHelmet;
use devmine\inventory\items\DiamondHoe;
use devmine\inventory\items\DiamondLeggings;
use devmine\inventory\items\DiamondPickaxe;
use devmine\inventory\items\DiamondShovel;
use devmine\inventory\items\DiamondSword;
use devmine\inventory\items\GoldAxe;
use devmine\inventory\items\GoldBoots;
use devmine\inventory\items\GoldChestplate;
use devmine\inventory\items\GoldHelmet;
use devmine\inventory\items\GoldHoe;
use devmine\inventory\items\GoldLeggings;
use devmine\inventory\items\GoldPickaxe;
use devmine\inventory\items\GoldShovel;
use devmine\inventory\items\GoldSword;
use devmine\inventory\items\IronAxe;
use devmine\inventory\items\IronBoots;
use devmine\inventory\items\IronChestplate;
use devmine\inventory\items\IronHelmet;
use devmine\inventory\items\IronHoe;
use devmine\inventory\items\IronLeggings;
use devmine\inventory\items\IronPickaxe;
use devmine\inventory\items\IronShovel;
use devmine\inventory\items\IronSword;
use devmine\inventory\items\Item;
use devmine\inventory\items\LeatherBoots;
use devmine\inventory\items\LeatherCap;
use devmine\inventory\items\LeatherPants;
use devmine\inventory\items\LeatherTunic;
use devmine\inventory\items\StoneAxe;
use devmine\inventory\items\StoneHoe;
use devmine\inventory\items\StonePickaxe;
use devmine\inventory\items\StoneShovel;
use devmine\inventory\items\StoneSword;
use devmine\inventory\items\WoodenAxe;
use devmine\inventory\items\WoodenHoe;
use devmine\inventory\items\WoodenPickaxe;
use devmine\inventory\items\WoodenShovel;
use devmine\inventory\items\WoodenSword;

class Enchantment{

	const TYPE_INVALID = -1;

	const TYPE_ARMOR_PROTECTION = 0;
	const TYPE_ARMOR_FIRE_PROTECTION = 1;
	const TYPE_ARMOR_FALL_PROTECTION = 2;
	const TYPE_ARMOR_EXPLOSION_PROTECTION = 3;
	const TYPE_ARMOR_PROJECsolidentity_PROTECTION = 4;
	const TYPE_ARMOR_THORNS = 5;
	const TYPE_WATER_BREATHING = 6;
	const TYPE_WATER_SPEED = 7;
	const TYPE_WATER_AFFINITY = 8;
	const TYPE_WEAPON_SHARPNESS = 9;
	const TYPE_WEAPON_SMITE = 10;
	const TYPE_WEAPON_ARTHROPODS = 11;
	const TYPE_WEAPON_KNOCKBACK = 12;
	const TYPE_WEAPON_FIRE_ASPECT = 13;
	const TYPE_WEAPON_LOOTING = 14;
	const TYPE_MINING_EFFICIENCY = 15;
	const TYPE_MINING_SILK_TOUCH = 16;
	const TYPE_MINING_DURABILITY = 17;
	const TYPE_MINING_FORTUNE = 18;
	const TYPE_BOW_POWER = 19;
	const TYPE_BOW_KNOCKBACK = 20;
	const TYPE_BOW_FLAME = 21;
	const TYPE_BOW_INFINITY = 22;
	const TYPE_FISHING_FORTUNE = 23;
	const TYPE_FISHING_LURE = 24;

	const RARITY_COMMON = 0;
	const RARITY_UNCOMMON = 1;
	const RARITY_RARE = 2;
	const RARITY_MYTHIC = 3;

	const ACTIVATION_EQUIP = 0;
	const ACTIVATION_HELD = 1;
	const ACTIVATION_SELF = 2;

	const SLOT_NONE = 0;
	const SLOT_ALL = 0b11111111111111;
	const SLOT_ARMOR = 0b1111;
	const SLOT_HEAD = 0b1;
	const SLOT_TORSO = 0b10;
	const SLOT_LEGS = 0b100;
	const SLOT_FEET = 0b1000;
	const SLOT_SWORD = 0b10000;
	const SLOT_BOW = 0b100000;
	const SLOT_TOOL = 0b111000000;
	const SLOT_HOE = 0b1000000;
	const SLOT_SHEARS = 0b10000000;
	const SLOT_FLINT_AND_STEEL = 0b10000000;
	const SLOT_DIG = 0b111000000000;
	const SLOT_AXE = 0b1000000000;
	const SLOT_PICKAXE = 0b10000000000;
	const SLOT_SHOVEL = 0b10000000000;
	const SLOT_FISHING_ROD = 0b100000000000;
	const SLOT_CARROT_STICK = 0b1000000000000;

	public static $words = ["the", "elder", "scrolls", "klaatu", "berata", "niktu", "xyzzy", "bless", "curse", "light", "darkness", "fire", "air",
		"earth", "water", "hot", "dry", "cold", "wet", "ignite", "snuff", "embiggen", "twist", "shorten", "stretch", "fiddle", "destroy", "imbue", "galvanize",
		"enchant", "free", "limited", "range", "of", "towards", "inside", "sphere", "cube", "self", "other", "ball", "mental", "physical", "grow", "shrink",
		"demon", "elemental", "spirit", "animal", "creature", "beast", "humanoid", "undead", "fresh", "stale"];


	/** @var Enchantment[] */
	protected static $enchantments;

	public static function init(){
		self::$enchantments = new \SplFixedArray(256);

		self::$enchantments[self::TYPE_ARMOR_PROTECTION] = new Enchantment(self::TYPE_ARMOR_PROTECTION, "%enchantment.protect.all", self::RARITY_COMMON, self::ACTIVATION_EQUIP, self::SLOT_ARMOR);
		self::$enchantments[self::TYPE_ARMOR_FIRE_PROTECTION] = new Enchantment(self::TYPE_ARMOR_FIRE_PROTECTION, "%enchantment.protect.fire", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_ARMOR);
		self::$enchantments[self::TYPE_ARMOR_FALL_PROTECTION] = new Enchantment(self::TYPE_ARMOR_FALL_PROTECTION, "%enchantment.protect.fall", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_FEET);

		self::$enchantments[self::TYPE_ARMOR_EXPLOSION_PROTECTION] = new Enchantment(self::TYPE_ARMOR_EXPLOSION_PROTECTION, "%enchantment.protect.explosion", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_ARMOR);
		self::$enchantments[self::TYPE_ARMOR_PROJECsolidentity_PROTECTION] = new Enchantment(self::TYPE_ARMOR_PROJECsolidentity_PROTECTION, "%enchantment.protect.projecsolidentity", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_ARMOR);
		self::$enchantments[self::TYPE_ARMOR_THORNS] = new Enchantment(self::TYPE_ARMOR_THORNS, "%enchantment.protect.thorns", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_SWORD);
		self::$enchantments[self::TYPE_WATER_BREATHING] = new Enchantment(self::TYPE_WATER_BREATHING, "%enchantment.protect.waterbrething", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_FEET);
		self::$enchantments[self::TYPE_WATER_SPEED] = new Enchantment(self::TYPE_WATER_SPEED, "%enchantment.waterspeed", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_FEET);
		self::$enchantments[self::TYPE_WATER_AFFINITY] = new Enchantment(self::TYPE_WATER_AFFINITY, "%enchantment.protect.wateraffinity", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_FEET);

		self::$enchantments[self::TYPE_WEAPON_SHARPNESS] = new Enchantment(self::TYPE_WEAPON_SHARPNESS, "%enchantment.weapon.sharpness", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_SWORD);
		self::$enchantments[self::TYPE_WEAPON_SMITE] = new Enchantment(self::TYPE_WEAPON_SMITE, "%enchantment.weapon.smite", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_SWORD);
		self::$enchantments[self::TYPE_WEAPON_ARTHROPODS] = new Enchantment(self::TYPE_WEAPON_ARTHROPODS, "%enchantment.weapon.arthropods", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_SWORD);
		self::$enchantments[self::TYPE_WEAPON_KNOCKBACK] = new Enchantment(self::TYPE_WEAPON_KNOCKBACK, "%enchantment.weapon.knockback", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_SWORD);
		self::$enchantments[self::TYPE_WEAPON_FIRE_ASPECT] = new Enchantment(self::TYPE_WEAPON_FIRE_ASPECT, "%enchantment.weapon.fireaspect", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_SWORD);
		self::$enchantments[self::TYPE_WEAPON_LOOTING] = new Enchantment(self::TYPE_WEAPON_LOOTING, "%enchantment.weapon.looting", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_SWORD);
		self::$enchantments[self::TYPE_MINING_EFFICIENCY] = new Enchantment(self::TYPE_MINING_EFFICIENCY, "%enchantment.mining.efficiency", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_TOOL);
		self::$enchantments[self::TYPE_MINING_SILK_TOUCH] = new Enchantment(self::TYPE_MINING_SILK_TOUCH, "%enchantment.mining.silktouch", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_TOOL);
		self::$enchantments[self::TYPE_MINING_DURABILITY] = new Enchantment(self::TYPE_MINING_DURABILITY, "%enchantment.mining.durability", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_TOOL);
		self::$enchantments[self::TYPE_MINING_FORTUNE] = new Enchantment(self::TYPE_MINING_FORTUNE, "%enchantment.mining.fortune", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_TOOL);
		self::$enchantments[self::TYPE_BOW_POWER] = new Enchantment(self::TYPE_BOW_POWER, "%enchantment.bow.power", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_BOW);
		self::$enchantments[self::TYPE_BOW_KNOCKBACK] = new Enchantment(self::TYPE_BOW_KNOCKBACK, "%enchantment.bow.knockback", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_BOW);
		self::$enchantments[self::TYPE_BOW_FLAME] = new Enchantment(self::TYPE_BOW_FLAME, "%enchantment.bow.flame", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_BOW);
		self::$enchantments[self::TYPE_BOW_INFINITY] = new Enchantment(self::TYPE_BOW_INFINITY, "%enchantment.bow.infinity", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_BOW);
		self::$enchantments[self::TYPE_FISHING_FORTUNE] = new Enchantment(self::TYPE_FISHING_FORTUNE, "%enchantment.fishing.fortune", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_FISHING_ROD);
		self::$enchantments[self::TYPE_FISHING_LURE] = new Enchantment(self::TYPE_FISHING_LURE, "%enchantment.fishing.lure", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_FISHING_ROD);

	}

	/**
	 * @param int $id
	 * @return $this
	 */
	public static function getEnchantment($id){
		if(isset(self::$enchantments[$id])){
			return clone self::$enchantments[(int) $id];
		}
		return new Enchantment(self::TYPE_INVALID, "unknown", 0, 0, 0);
	}

	public static function getEffectByName($name){
		if(defined(Enchantment::class . "::TYPE_" . strtoupper($name))){
			return self::getEnchantment(constant(Enchantment::class . "::TYPE_" . strtoupper($name)));
		}
		return null;
	}

	public static function getEnchantAbility(Item $item){
		switch($item->getId()){
			case Item::BOOK:
			case Item::BOW:
			case Item::FISHING_ROD:
				return 4;
		}

		if($item->isArmor()){
			if($item instanceof ChainBoots or $item instanceof ChainChestplate or $item instanceof ChainHelmet or $item instanceof ChainLeggings) return 12;
			if($item instanceof IronBoots or $item instanceof IronChestplate or $item instanceof IronHelmet or $item instanceof IronLeggings) return 9;
			if($item instanceof DiamondBoots or $item instanceof DiamondChestplate or $item instanceof DiamondHelmet or $item instanceof DiamondLeggings) return 10;
			if($item instanceof LeatherBoots or $item instanceof LeatherTunic or $item instanceof LeatherCap or $item instanceof LeatherPants) return 15;
			if($item instanceof GoldBoots or $item instanceof GoldChestplate or $item instanceof GoldHelmet or $item instanceof GoldLeggings) return 25;
		}

		if($item->isTool()){
			if($item instanceof WoodenAxe or $item instanceof WoodenHoe or $item instanceof WoodenPickaxe or $item instanceof WoodenShovel or $item instanceof WoodenSword) return 15;
			if($item instanceof StoneAxe or $item instanceof StoneHoe or $item instanceof StonePickaxe or $item instanceof StoneShovel or $item instanceof StoneSword) return 5;
			if($item instanceof DiamondAxe or $item instanceof DiamondHoe or $item instanceof DiamondPickaxe or $item instanceof DiamondShovel or $item instanceof DiamondSword) return 10;
			if($item instanceof IronAxe or $item instanceof IronHoe or $item instanceof IronPickaxe or $item instanceof IronShovel or $item instanceof IronSword) return 14;
			if($item instanceof GoldAxe or $item instanceof GoldHoe or $item instanceof GoldPickaxe or $item instanceof GoldShovel or $item instanceof GoldSword) return 22;
		}

		return 0;
	}

	public static function getEnchantWeight(int $enchantmentId){
		switch($enchantmentId){
			case self::TYPE_ARMOR_PROTECTION:
				return 10;
			case self::TYPE_ARMOR_FIRE_PROTECTION:
				return 5;
			case self::TYPE_ARMOR_FALL_PROTECTION:
				return 2;
			case self::TYPE_ARMOR_EXPLOSION_PROTECTION:
				return 5;
			case self::TYPE_WATER_BREATHING:
				return 2;
			case self::TYPE_WATER_AFFINITY:
				return 2;
			case self::TYPE_WEAPON_SHARPNESS:
				return 10;
			case self::TYPE_WEAPON_SMITE:
				return 5;
			case self::TYPE_WEAPON_ARTHROPODS:
				return 5;
			case self::TYPE_WEAPON_KNOCKBACK:
				return 5;
			case self::TYPE_WEAPON_FIRE_ASPECT:
				return 2;
			case self::TYPE_WEAPON_LOOTING:
				return 2;
			case self::TYPE_MINING_EFFICIENCY:
				return 10;
			case self::TYPE_MINING_SILK_TOUCH:
				return 1;
			case self::TYPE_MINING_DURABILITY:
				return 5;
			case self::TYPE_MINING_FORTUNE:
				return 2;
			case self::TYPE_BOW_POWER:
				return 10;
			case self::TYPE_BOW_KNOCKBACK:
				return 2;
			case self::TYPE_BOW_FLAME:
				return 2;
			case self::TYPE_BOW_INFINITY:
				return 1;
		}
		return 0;
	}

	public static function getEnchantMaxLevel(int $enchantmentId){
		switch($enchantmentId){
			case self::TYPE_ARMOR_PROTECTION:
			case self::TYPE_ARMOR_FIRE_PROTECTION:
			case self::TYPE_ARMOR_FALL_PROTECTION:
			case self::TYPE_ARMOR_EXPLOSION_PROTECTION:
			case self::TYPE_ARMOR_PROJECsolidentity_PROTECTION:
				return 4;
			case self::TYPE_ARMOR_THORNS:
				return 3;
			case self::TYPE_WATER_BREATHING:
			case self::TYPE_WATER_SPEED:
				return 3;
			case self::TYPE_WATER_AFFINITY:
				return 1;
			case self::TYPE_WEAPON_SHARPNESS:
			case self::TYPE_WEAPON_SMITE:
			case self::TYPE_WEAPON_ARTHROPODS:
				return 5;
			case self::TYPE_WEAPON_KNOCKBACK:
			case self::TYPE_WEAPON_FIRE_ASPECT:
				return 2;
			case self::TYPE_WEAPON_LOOTING:
				return 3;
			case self::TYPE_MINING_EFFICIENCY:
				return 5;
			case self::TYPE_MINING_SILK_TOUCH:
				return 1;
			case self::TYPE_MINING_DURABILITY:
			case self::TYPE_MINING_FORTUNE:
				return 3;
			case self::TYPE_BOW_POWER:
				return 5;
			case self::TYPE_BOW_KNOCKBACK:
				return 2;
			case self::TYPE_BOW_FLAME:
			case self::TYPE_BOW_INFINITY:
				return 1;
			case self::TYPE_FISHING_FORTUNE:
			case self::TYPE_FISHING_LURE:
				return 3;
		}
		return 999;
	}

	private $id;
	private $level = 1;
	private $name;
	private $rarity;
	private $activationType;
	private $slot;

	private function __construct($id, $name, $rarity, $activationType, $slot){
		$this->id = (int) $id;
		$this->name = (string) $name;
		$this->rarity = (int) $rarity;
		$this->activationType = (int) $activationType;
		$this->slot = (int) $slot;
	}

	public function getId(){
		return $this->id;
	}

	public function getName() : string{
		return $this->name;
	}

	public function getRarity(){
		return $this->rarity;
	}

	public function getActivationType(){
		return $this->activationType;
	}

	public function getSlot(){
		return $this->slot;
	}

	public function hasSlot($slot){
		return ($this->slot & $slot) > 0;
	}

	public function getLevel(){
		return $this->level;
	}

	public function setLevel(int $level){
		$this->level = $level;

		return $this;
	}

	public function equals(Enchantment $ent){
		if($ent->getId() == $this->getId() and $ent->getLevel() == $this->getLevel() and $ent->getActivationType() == $this->getActivationType() and $ent->getRarity() == $this->getRarity()){
			return true;
		}
		return false;
	}

	public static function getRandomName(){
		$count = mt_rand(3, 6);
		$set = [];
		while(count($set) < $count){
			$set[] = self::$words[mt_rand(0, count(self::$words) - 1)];
		}
		return implode(" ", $set);
	}
}