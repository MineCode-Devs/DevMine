# DevMine
The new mcpe software that does not use pm code base! 

# Why is it called DevMine?
Simply because you need to understand php in order to convert PocketMine plugins into DevMine plugins!

# Why would you completely change the code base?
It allows for much better organization so it is easier to edit. Also, we automatically remove unneeded code.

# What is DevMine's folder structure?
DevMine's folder structure is basically the ImagicalMine project (https://github.com/Inactive-to-Reactive/ImagicalMine) with a different folder structure! Everything else is basically the same. Also, a few additional features were added in, that were originally from plugins. Have fun using it! It is expected to be in the beta stage by July 19th, 2016.

# PocketMine Structure to DevMine Structure
pocketmine\block --> devmine\inventory\blocks <br>
pocketmine\item --> devmine\inventory\items <br>
pocketmine\inventory --> devmine\inventory\layout <br>
pocketmine\tile --> devmine\inventory\solidentity <br>
pocketmine\plugin --> devmine\consumer\plugin <br>
pocketmine\resourcepacks --> devmine\consumer\resourcepacks <br>
pocketmine\nbt--> devmine\creatures\player <br>
player.php and iplayer.php and offlineplayer.php --> devmine\creatures\player  <br>
pocketmine\entity --> devmine\creatures\entities <br>
pocketmine not in folder (exclude player and iplayer and offlineplayer) --> devmine\server <br>
pocketmine\network --> devmine\server\network <br>
pocketmine\permission --> devmine\server\perms <br>
pocketmine\resources --> devmine\server\resources <br>
pocketmine\scheduler --> devmine\server\tasks <br>
pocketmine\command --> devmine\server\commands <br>
pocketmine\metadata --> devmine\server\meta<br>
pocketmine\math --> devmine\server\calculations <br>
pocketmine\lang --> devmine\utilities\languages <br>
pocketmine\wizard --> devmine\utilities\installer <br>
pocketmine\utils --> devmine\utilities\main <br>
pocketmine\plugin --> devmine\plugin-features <br>
pocketmine\level --> devmine\worlds <br>
pocketmine\event --> devmine\events <br>
raklib --> raklib <br>
spl --> spl <br>

# Files Renamed/individual files changed
pocketmine\PocketMine.php --> devmine\server\DevMine.php <br>
pocketmine\tile\Tile.php --> devmine\inventory\solidentity\SolidEntity.php <br>
pocketmine\player.php --> devmine\creatures\player\Player.php  <br>
pocketmine\iplayer.php --> devmine\creatures\player\iPlayer.php  <br>
pocketmine\OfflinePlayer.php --> devmine\creatures\player\OfflinePlayer.php  <br>

# THINGS TO CHANGE
remove all instances of pocketmine, rename to devmine <br>
remove all instances of tiles, rename to solidentity <br>
remove all instances of genysis and tesseract and pocketmine, rename to devmine <br>
remove all instances of scheduler, rename to tasks <br>
remove all instances of metadata, rename to meta <br>
