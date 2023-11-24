<?php

declare(strict_types=1);

namespace Labarjni\GeometryBuild;

use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{
    protected function onEnable(): void
    {
        $this->getServer()->getCommandMap()->register($this->getName(), new BuildCommand($this));
    }
}
