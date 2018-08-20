<?php

/**
 * @name CreateZombie
 * @author alvin0319
 * @main alvin0319\CreateZombie
 * @version 1.0.0
 * @api 4.0.0
 */
 
namespace alvin0319;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\Player;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\scheduler\Task;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\item\Item;
use pocketmine\entity\Zombie;
use pocketmine\utils\Config;
//한글깨짐방지

class CreateZombie extends PluginBase implements Listener {
	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents ($this, $this);
		//$this->cmd = new \pocketmine\command\PluginCommand("보스", $this);
		//$this->cmd->setDescription ("Boss Command");
		//$this->getServer()->getCommandMap()->register ("보스", $this->cmd);
		if (! $this->getServer()->isLevelGenerated("BossLevel")) $this->getServer()->generateLevel("BossLevel",null,"FLAT");
        if (! $this->getServer()->isLevelLoaded("BossLevel")) $this->getServer()->loadLevel("BossLevel");
		$this->getScheduler()->scheduleRepeatingTask (new class ($this) extends Task {
			private $owner;
			
			public function __construct(CreateZombie $owner) {
				$this->owner = $owner;
			}
			public function getOwner() {
				return $this->owner;
			}
			public function onRun(int $currentTick) {
				$nbt = new CompoundTag('', [
    'Pos' => new ListTag('Pos', [
        new DoubleTag('', 253),
        new DoubleTag('', 4),
        new DoubleTag('', 253)
    ]),
    'Motion' => new ListTag('Motion', [
        new DoubleTag('', 0),
        new DoubleTag('', 0),
        new DoubleTag('', 0)
    ]),
    'Rotation' => new ListTag('Rotation', [
        new FloatTag('', lcg_value() * 360),
        new FloatTag('', 0)
    ]),
]);
        $entity = Entity::createEntity(Entity::ZOMBIE, $this->owner->getServer()->getLevelByName("BossLevel"), $nbt);    
        $entity->setHealth(100);
        $entity->spawnToAll();
        $this->owner->getServer()->broadcastMessage ("§b§l[ Boss ] §f보스가 스폰되었습니다");
        }
        }, 2000);
    }
    public function entity(EntityDamageEvent $event){

        if($event instanceof EntityDamageByEntityEvent){

            $damager = $event->getDamager();

            $entity = $event->getEntity();

            if($damager instanceof Player){

            $entity->attack(new EntityDamageEvent($damager, EntityDamageEvent::CAUSE_ENTITY_ATTACK, 1));
            }
        }
    }
    /**
    public function onCommand(\pocketmine\command\CommandSender $sender, \pocketmine\command\Command $command, string $label, array $args) : bool{
    	if ($command->getName() === "보스") {
    	    if (! isset ($args[0])) {
    	        $sender->sendMessage ("/보스 소환");
                return true;
            }
            if ($args[0] === "소환") {
            	if (! $sender->isOp()) {
            	return true;
                }
                $nbt = new CompoundTag('', [
    'Pos' => new ListTag('Pos', [
        new DoubleTag('', 253),
        new DoubleTag('', 4),
        new DoubleTag('', 253)
    ]),
    'Motion' => new ListTag('Motion', [
        new DoubleTag('', 0),
        new DoubleTag('', 0),
        new DoubleTag('', 0)
    ]),
    'Rotation' => new ListTag('Rotation', [
        new FloatTag('', lcg_value() * 360),
        new FloatTag('', 0)
    ]),
]);
        $entity = Entity::createEntity(Entity::ZOMBIE, $this->getServer()->getLevelByName("BossLevel")->getSafeSpawn(), $nbt);    
        $entity->setHealth(100);
        $entity->spawnToAll();
        $this->getServer()->broadcastMessage ("§b§l[ Boss ] §f보스가 스폰되었습니다");
        }
    }
    return true;
}
*/
}