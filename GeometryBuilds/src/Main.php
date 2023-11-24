<?php

declare(strict_types=1);

namespace Labarjni\GeometryBuild;

use Labarjni\GeometryBuild\command\BuildCommand;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{
    protected function onEnable(): void
    {
        $this->getServer()->getCommandMap()->register($this->getName(), new BuildCommand($this));
    }
}
