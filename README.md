# DevMine
The new mcpe software that does not use pm code base! 

# Why is it called DevMine?
Simply because you need to understand php in order to convert PocketMine plugins into DevMine plugins!

# Why would you completely change the code base?
It allows for much better organization so it is easier to edit. Also, we automatically remove unneeded code.

# What is DevMine's folder structure?
DevMine's folder structure is basically the ImagicalMine project (https://github.com/Inactive-to-Reactive/ImagicalMine) with a different folder structure! Everything else is basically the same. Also, a few additional features were added in, that were originally from plugins. Have fun using it! It is expected to be in the beta stage by mid-August 2016 at the max, if not late July 2016.

# ImagicalMine Structure to DevMine Structure
pocketmine\block --> devmine\inventory\blocks <br>
pocketmine\item --> devmine\inventory\items <br>
pocketmine\inventory --> devmine\inventory\layout <br>
pocketmine\tile --> devmine\inventory\solidentity <br>
pocketmine\katana --> devmine\server\logger <br>
pocketmine\utils --> devmine\utilities\main <br>
pocketmine not in folder (exclude player and iplayer) --> devmine\server <br>
pocketmine\network --> devmine\server\network <br>
pocketmine\lang --> devmine\utilities\languages <br>
pocketmine\wizard --> devmine\utilities\installer <br>
pocketmine\nbt--> devmine\creatures <br>
player.php and iplayer.php --> devmine\creatures  <br>
pocketmine\entity --> devmine\creatures\entities <br>
pocketmine\command --> devmine\server\commands <br>
pocketmine\plugin --> devmine\plugin-features <br>
pocketmine\level --> devmine\worlds <br>
pocketmine\metadata --> devmine\server\epilogos <br>
pocketmine\math --> devmine\server\calculations <br>
pocketmine\event --> devmine\events <br>
pocketmine\permission --> devmine\server\permissions <br>
pocketmine\resources --> devmine\server\resources <br>
pocketmine\scheduler --> devmine\server\tasks <br>
raklib --> raklib <br>
spl --> spl <br>

# Files Renamed
pocketmine\PocketMine.php --> devmine\server\DevMine.php <br>
pocketmine\tile\Tile.php --> devmine\inventory\solidentity\SolidEntity.php
