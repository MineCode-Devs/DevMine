<?php



/**
 * Network-related classes
 */
namespace devmine\server\network;

use devmine\server\network\protocol\AddEntityPacket;
use devmine\server\network\protocol\AddHangingEntityPacket;
use devmine\server\network\protocol\AddItemEntityPacket;
use devmine\server\network\protocol\AddItemPacket;
use devmine\server\network\protocol\AddPaintingPacket;
use devmine\server\network\protocol\AddPlayerPacket;
use devmine\server\network\protocol\AdventureSettingsPacket;
use devmine\server\network\protocol\AnimatePacket;
use devmine\server\network\protocol\AvailableCommandsPacket;
use devmine\server\network\protocol\BatchPacket;
use devmine\server\network\protocol\BossEventPacket;
use devmine\server\network\protocol\ChunkRadiusUpdatedPacket;
use devmine\server\network\protocol\CommandStepPacket;
use devmine\server\network\protocol\ContainerClosePacket;
use devmine\server\network\protocol\ContainerOpenPacket;
use devmine\server\network\protocol\ContainerSetContentPacket;
use devmine\server\network\protocol\ContainerSetDataPacket;
use devmine\server\network\protocol\ContainerSetSlotPacket;
use devmine\server\network\protocol\CraftingDataPacket;
use devmine\server\network\protocol\CraftingEventPacket;
use devmine\server\network\protocol\ChangeDimensionPacket;
use devmine\server\network\protocol\DataPacket;
use devmine\server\network\protocol\DropItemPacket;
use devmine\server\network\protocol\FullChunkDataPacket;
use devmine\server\network\protocol\Info;
use devmine\server\network\protocol\ItemFrameDropItemPacket;
use devmine\server\network\protocol\RequestChunkRadiusPacket;
use devmine\server\network\protocol\SetEntityLinkPacket;
use devmine\server\network\protocol\BlockEntityDataPacket;
use devmine\server\network\protocol\EntityEventPacket;
use devmine\server\network\protocol\ExplodePacket;
use devmine\server\network\protocol\HurtArmorPacket;
use devmine\server\network\protocol\Info as ProtocolInfo;
use devmine\server\network\protocol\InteractPacket;
use devmine\server\network\protocol\InventoryActionPacket;
use devmine\server\network\protocol\LevelEventPacket;
use devmine\server\network\protocol\LevelSoundEventPacket;
use devmine\server\network\protocol\DisconnectPacket;
use devmine\server\network\protocol\LoginPacket;
use devmine\server\network\protocol\PlayStatusPacket;
use devmine\server\network\protocol\TextPacket;
use devmine\server\network\protocol\MoveEntityPacket;
use devmine\server\network\protocol\MovePlayerPacket;
use devmine\server\network\protocol\PlayerActionPacket;
use devmine\server\network\protocol\MobArmorEquipmentPacket;
use devmine\server\network\protocol\MobEquipmentPacket;
use devmine\server\network\protocol\RemoveBlockPacket;
use devmine\server\network\protocol\RemoveEntityPacket;
use devmine\server\network\protocol\RemovePlayerPacket;
use devmine\server\network\protocol\ReplaceItemInSlotPacket;
use devmine\server\network\protocol\ResourcePackClientResponsePacket;
use devmine\server\network\protocol\ResourcePacksInfoPacket;
use devmine\server\network\protocol\RespawnPacket;
use devmine\server\network\protocol\SetCommandsEnabledPacket;
use devmine\server\network\protocol\SetDifficultyPacket;
use devmine\server\network\protocol\SetEntityDataPacket;
use devmine\server\network\protocol\SetEntityMotionPacket;
use devmine\server\network\protocol\SetHealthPacket;
use devmine\server\network\protocol\SetPlayerGameTypePacket;
use devmine\server\network\protocol\SetSpawnPositionPacket;
use devmine\server\network\protocol\SetTimePacket;
use devmine\server\network\protocol\SpawnExperienceOrbPacket;
use devmine\server\network\protocol\StartGamePacket;
use devmine\server\network\protocol\TakeItemEntityPacket;
use devmine\server\network\protocol\BlockEventPacket;
use devmine\server\network\protocol\UpdateBlockPacket;
use devmine\server\network\protocol\PlayerFallPacket;
use devmine\server\network\protocol\UseItemPacket;
use devmine\server\network\protocol\PlayerListPacket;
use devmine\server\network\protocol\PlayerInputPacket;
use devmine\server\network\protocol\ShowCreditsPacket;
use devmine\creatures\player;
use devmine\server\server;
use devmine\utilities\main\Binary;
use devmine\utilities\main\BinaryStream;
use devmine\utilities\main\MainLogger;

class Network {

	public static $BATCH_THRESHOLD = 512;

	/** @var \SplFixedArray */
	private $packetPool;

	/** @var Server */
	private $server;

	/** @var SourceInterface[] */
	private $interfaces = [];

	/** @var AdvancedSourceInterface[] */
	private $advancedInterfaces = [];

	private $upload = 0;
	private $download = 0;

	private $name;

	public function __construct(Server $server) {

		$this->registerPackets();

		$this->server = $server;
	}

	public function addStatistics($upload, $download) {
		$this->upload += $upload;
		$this->download += $download;
	}

	public function getUpload() {
		return $this->upload;
	}

	public function getDownload() {
		return $this->download;
	}

	public function resetStatistics() {
		$this->upload = 0;
		$this->download = 0;
	}

	/**
	 * @return SourceInterface[]
	 */
	public function getInterfaces() {
		return $this->interfaces;
	}

	public function processInterfaces() {
		foreach ($this->interfaces as $interface) {
			try {
				$interface->process();
			} catch (\Throwable $e) {
				$logger = $this->server->getLogger();
				if (\DevMine\DEBUG > 1) {
					if ($logger instanceof MainLogger) {
						$logger->logException($e);
					}
				}

				$interface->emergencyShutdown();
				$this->unregisterInterface($interface);
				$logger->critical($this->server->getLanguage()->translateString("DevMine.server.networkError", [get_class($interface), $e->getMessage()]));
			}
		}
	}

	/**
	 * @param SourceInterface $interface
	 */
	public function registerInterface(SourceInterface $interface) {
		$this->interfaces[$hash = spl_object_hash($interface)] = $interface;
		if ($interface instanceof AdvancedSourceInterface) {
			$this->advancedInterfaces[$hash] = $interface;
			$interface->setNetwork($this);
		}
		$interface->setName($this->name);
	}

	/**
	 * @param SourceInterface $interface
	 */
	public function unregisterInterface(SourceInterface $interface) {
		unset($this->interfaces[$hash = spl_object_hash($interface)],
			$this->advancedInterfaces[$hash]);
	}

	/**
	 * Sets the server name shown on each interface Query
	 *
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = (string)$name;
		foreach ($this->interfaces as $interface) {
			$interface->setName($this->name);
		}
	}

	public function getName() {
		return $this->name;
	}

	public function updateName() {
		foreach ($this->interfaces as $interface) {
			$interface->setName($this->name);
		}
	}

	/**
	 * @param int        $id 0-255
	 * @param DataPacket $class
	 */
	public function registerPacket($id, $class) {
		$this->packetPool[$id] = new $class;
	}

	public function getServer() {
		return $this->server;
	}

	public function processBatch(BatchPacket $packet, Player $p){
		try{
			if(strlen($packet->payload) === 0){
				//prevent zlib_decode errors for incorrectly-decoded packets
				throw new \InvalidArgumentException("BatchPacket payload is empty or packet decode error");
			}
			$str = zlib_decode($packet->payload, 1024 * 1024 * 64); //Max 64MB
			$len = strlen($str);
			if($len === 0){
				throw new \InvalidStateException("Decoded BatchPacket payload is empty");
			}
			$stream = new BinaryStream($str);
			while($stream->offset < $len){
				$buf = $stream->getString();
				if(($pk = $this->getPacket(ord($buf{0}))) !== null){
					if($pk::NETWORK_ID === Info::BATCH_PACKET){
						throw new \InvalidStateException("Invalid BatchPacket inside BatchPacket");
					}
					$pk->setBuffer($buf, 1);
					$pk->decode();
					assert($pk->feof(), "Still " . strlen(substr($pk->buffer, $pk->offset)) . " bytes unread in " . get_class($pk));
					$p->handleDataPacket($pk);
				}
			}
		}catch(\Throwable $e){
			if(\DevMine\DEBUG > 1){
				$logger = $this->server->getLogger();
				$logger->debug("BatchPacket " . " 0x" . bin2hex($packet->payload));
				$logger->logException($e);
			}
		}
	}

	/**
	 * @param $id
	 *
	 * @return DataPacket
	 */
	public function getPacket($id) {
		/** @var DataPacket $class */
		$class = $this->packetPool[$id];
		if ($class !== null) {
			return clone $class;
		}
		return null;
	}


	/**
	 * @param string $address
	 * @param int    $port
	 * @param string $payload
	 */
	public function sendPacket($address, $port, $payload) {
		foreach ($this->advancedInterfaces as $interface) {
			$interface->sendRawPacket($address, $port, $payload);
		}
	}

	/**
	 * Blocks an IP address from the main interface. Setting timeout to -1 will block it forever
	 *
	 * @param string $address
	 * @param int    $timeout
	 */
	public function blockAddress($address, $timeout = 300) {
		foreach ($this->advancedInterfaces as $interface) {
			$interface->blockAddress($address, $timeout);
		}
	}

	/**
	 * Unblocks an IP address from the main interface.
	 *
	 * @param string $address
	 */
	public function unblockAddress($address) {
		foreach ($this->advancedInterfaces as $interface) {
			$interface->unblockAddress($address);
		}
	}

	private function registerPackets() {
		$this->packetPool = new \SplFixedArray(256);

		$this->registerPacket(ProtocolInfo::ADD_ENTITY_PACKET, AddEntityPacket::class);
		$this->registerPacket(ProtocolInfo::ADD_HANGING_ENTITY_PACKET, AddHangingEntityPacket::class);
		$this->registerPacket(ProtocolInfo::ADD_ITEM_ENTITY_PACKET, AddItemEntityPacket::class);
		$this->registerPacket(ProtocolInfo::ADD_ITEM_PACKET, AddItemPacket::class);
		$this->registerPacket(ProtocolInfo::ADD_PAINTING_PACKET, AddPaintingPacket::class);
		$this->registerPacket(ProtocolInfo::ADD_PLAYER_PACKET, AddPlayerPacket::class);
		$this->registerPacket(ProtocolInfo::ADVENTURE_SETTINGS_PACKET, AdventureSettingsPacket::class);
		$this->registerPacket(ProtocolInfo::ANIMATE_PACKET, AnimatePacket::class);
		$this->registerPacket(ProtocolInfo::INVENTORY_ACTION_PACKET, InventoryActionPacket::class);
		$this->registerPacket(ProtocolInfo::AVAILABLE_COMMANDS_PACKET, AvailableCommandsPacket::class);
		$this->registerPacket(ProtocolInfo::BATCH_PACKET, BatchPacket::class);
		$this->registerPacket(ProtocolInfo::BLOCK_ENTITY_DATA_PACKET, BlockEntityDataPacket::class);
		$this->registerPacket(ProtocolInfo::BLOCK_EVENT_PACKET, BlockEventPacket::class);
		$this->registerPacket(ProtocolInfo::BOSS_EVENT_PACKET, BossEventPacket::class);
		$this->registerPacket(ProtocolInfo::CHANGE_DIMENSION_PACKET, ChangeDimensionPacket::class);
		$this->registerPacket(ProtocolInfo::CHUNK_RADIUS_UPDATED_PACKET, ChunkRadiusUpdatedPacket::class);
		$this->registerPacket(ProtocolInfo::COMMAND_STEP_PACKET, CommandStepPacket::class);
		$this->registerPacket(ProtocolInfo::CONTAINER_CLOSE_PACKET, ContainerClosePacket::class);
		$this->registerPacket(ProtocolInfo::CONTAINER_OPEN_PACKET, ContainerOpenPacket::class);
		$this->registerPacket(ProtocolInfo::CONTAINER_SET_CONTENT_PACKET, ContainerSetContentPacket::class);
		$this->registerPacket(ProtocolInfo::CONTAINER_SET_DATA_PACKET, ContainerSetDataPacket::class);
		$this->registerPacket(ProtocolInfo::CONTAINER_SET_SLOT_PACKET, ContainerSetSlotPacket::class);
		$this->registerPacket(ProtocolInfo::CRAFTING_DATA_PACKET, CraftingDataPacket::class);
		$this->registerPacket(ProtocolInfo::CRAFTING_EVENT_PACKET, CraftingEventPacket::class);
		$this->registerPacket(ProtocolInfo::DISCONNECT_PACKET, DisconnectPacket::class);
		$this->registerPacket(ProtocolInfo::DROP_ITEM_PACKET, DropItemPacket::class);
		$this->registerPacket(ProtocolInfo::ENTITY_EVENT_PACKET, EntityEventPacket::class);
		$this->registerPacket(ProtocolInfo::EXPLODE_PACKET, ExplodePacket::class);
		$this->registerPacket(ProtocolInfo::PLAYER_FALL_PACKET, PlayerFallPacket::class);
		$this->registerPacket(ProtocolInfo::FULL_CHUNK_DATA_PACKET, FullChunkDataPacket::class);
		$this->registerPacket(ProtocolInfo::SET_COMMANDS_ENABLED_PACKET, SetCommandsEnabledPacket::class);
		$this->registerPacket(ProtocolInfo::HURT_ARMOR_PACKET, HurtArmorPacket::class);
		$this->registerPacket(ProtocolInfo::INTERACT_PACKET, InteractPacket::class);
		$this->registerPacket(ProtocolInfo::INVENTORY_ACTION_PACKET, InventoryActionPacket::class);
		$this->registerPacket(ProtocolInfo::ITEM_FRAME_DROP_ITEM_PACKET, ItemFrameDropItemPacket::class);
		$this->registerPacket(ProtocolInfo::LEVEL_EVENT_PACKET, LevelEventPacket::class);
		$this->registerPacket(ProtocolInfo::LEVEL_SOUND_EVENT_PACKET, LevelSoundEventPacket::class);
		$this->registerPacket(ProtocolInfo::LOGIN_PACKET, LoginPacket::class);
		$this->registerPacket(ProtocolInfo::SHOW_CREDITS_PACKET, ShowCreditsPacket::class);
		$this->registerPacket(ProtocolInfo::MOB_ARMOR_EQUIPMENT_PACKET, MobArmorEquipmentPacket::class);
		$this->registerPacket(ProtocolInfo::MOB_EQUIPMENT_PACKET, MobEquipmentPacket::class);
		$this->registerPacket(ProtocolInfo::MOVE_ENTITY_PACKET, MoveEntityPacket::class);
		$this->registerPacket(ProtocolInfo::MOVE_PLAYER_PACKET, MovePlayerPacket::class);
		$this->registerPacket(ProtocolInfo::PLAYER_ACTION_PACKET, PlayerActionPacket::class);
		$this->registerPacket(ProtocolInfo::PLAYER_INPUT_PACKET, PlayerInputPacket::class);
		$this->registerPacket(ProtocolInfo::PLAYER_LIST_PACKET, PlayerListPacket::class);
		$this->registerPacket(ProtocolInfo::PLAY_STATUS_PACKET, PlayStatusPacket::class);
		$this->registerPacket(ProtocolInfo::REMOVE_BLOCK_PACKET, RemoveBlockPacket::class);
		$this->registerPacket(ProtocolInfo::REMOVE_ENTITY_PACKET, RemoveEntityPacket::class);
		$this->registerPacket(ProtocolInfo::REPLACE_SELECTED_ITEM_PACKET, ReplaceItemInSlotPacket::class);
		$this->registerPacket(ProtocolInfo::REQUEST_CHUNK_RADIUS_PACKET, RequestChunkRadiusPacket::class);
		$this->registerPacket(ProtocolInfo::RESOURCE_PACK_CLIENT_RESPONSE_PACKET, ResourcePackClientResponsePacket::class);
		$this->registerPacket(ProtocolInfo::RESOURCE_PACKS_INFO_PACKET, ResourcePacksInfoPacket::class);
		$this->registerPacket(ProtocolInfo::RESPAWN_PACKET, RespawnPacket::class);
		$this->registerPacket(ProtocolInfo::SET_COMMANDS_ENABLED_PACKET, SetCommandsEnabledPacket::class);
		$this->registerPacket(ProtocolInfo::SET_DIFFICULTY_PACKET, SetDifficultyPacket::class);
		$this->registerPacket(ProtocolInfo::SET_ENTITY_DATA_PACKET, SetEntityDataPacket::class);
		$this->registerPacket(ProtocolInfo::SET_ENTITY_LINK_PACKET, SetEntityLinkPacket::class);
		$this->registerPacket(ProtocolInfo::SET_ENTITY_MOTION_PACKET, SetEntityMotionPacket::class);
		$this->registerPacket(ProtocolInfo::SET_HEALTH_PACKET, SetHealthPacket::class);
		$this->registerPacket(ProtocolInfo::SET_PLAYER_GAME_TYPE_PACKET, SetPlayerGameTypePacket::class);
		$this->registerPacket(ProtocolInfo::SET_SPAWN_POSITION_PACKET, SetSpawnPositionPacket::class);
		$this->registerPacket(ProtocolInfo::SET_TIME_PACKET, SetTimePacket::class);
		$this->registerPacket(ProtocolInfo::SPAWN_EXPERIENCE_ORB_PACKET, SpawnExperienceOrbPacket::class);
		$this->registerPacket(ProtocolInfo::START_GAME_PACKET, StartGamePacket::class);
		$this->registerPacket(ProtocolInfo::TAKE_ITEM_ENTITY_PACKET, TakeItemEntityPacket::class);
		$this->registerPacket(ProtocolInfo::TEXT_PACKET, TextPacket::class);
		$this->registerPacket(ProtocolInfo::UPDATE_BLOCK_PACKET, UpdateBlockPacket::class);
		$this->registerPacket(ProtocolInfo::USE_ITEM_PACKET, UseItemPacket::class);
		$this->registerPacket(ProtocolInfo::ITEM_FRAME_DROP_ITEM_PACKET, ItemFrameDropItemPacket::class);
	}
}
