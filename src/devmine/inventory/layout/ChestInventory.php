<?php



namespace devmine\inventory\layout;

use devmine\inventory\blocks\TrappedChest;
use devmine\levels\Level;
use devmine\server\network\protocol\BlockEventPacket;
use devmine\Player;

use devmine\inventory\solidentity\Chest;

class ChestInventory extends ContainerInventory{
	public function __construct(Chest $solidentity){
		parent::__construct($solidentity, InventoryType::get(InventoryType::CHEST));
	}

	/**
	 * @return Chest
	 */
	public function getHolder(){
		return $this->holder;
	}

	public function onOpen(Player $who){
		parent::onOpen($who);

		if(count($this->getViewers()) === 1){
			$pk = new BlockEventPacket();
			$pk->x = $this->getHolder()->getX();
			$pk->y = $this->getHolder()->getY();
			$pk->z = $this->getHolder()->getZ();
			$pk->case1 = 1;
			$pk->case2 = 2;
			if(($level = $this->getHolder()->getLevel()) instanceof Level){
				$level->addChunkPacket($this->getHolder()->getX() >> 4, $this->getHolder()->getZ() >> 4, $pk);
			}
		}

		if($this->getHolder()->getLevel() instanceof Level){
			/** @var TrappedChest $block */
			$block = $this->getHolder()->getBlock();
			if($block instanceof TrappedChest){
				if(!$block->isActivated()){
					$block->activate();
				}
			}
		}
	}

	public function onClose(Player $who){
		if($this->getHolder()->getLevel() instanceof Level){
			/** @var TrappedChest $block */
			$block = $this->getHolder()->getBlock();
			if($block instanceof TrappedChest){
				if($block->isActivated()){
					$block->deactivate();
				}
			}
		}

		if(count($this->getViewers()) === 1){
			$pk = new BlockEventPacket();
			$pk->x = $this->getHolder()->getX();
			$pk->y = $this->getHolder()->getY();
			$pk->z = $this->getHolder()->getZ();
			$pk->case1 = 1;
			$pk->case2 = 0;
			if(($level = $this->getHolder()->getLevel()) instanceof Level){
				$level->addChunkPacket($this->getHolder()->getX() >> 4, $this->getHolder()->getZ() >> 4, $pk);
			}
		}
		parent::onClose($who);
	}
}