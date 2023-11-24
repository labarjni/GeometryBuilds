<?php

declare(strict_types=1);

namespace Labarjni\GeometryBuild;

use Labarjni\GeometryBuild\builder\BuilderManager;
use Labarjni\GeometryBuild\command\BuildCommand;
use Labarjni\GeometryBuild\command\UndoCommand;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    private static BuilderManager $builderManager;

    protected function onEnable(): void
    {
        $this->getServer()->getCommandMap()->register($this->getName(), new BuildCommand($this));
        $this->getServer()->getCommandMap()->register($this->getName(), new UndoCommand($this));
        self::$builderManager = new BuilderManager($this);
    }

    public function getBuilderManager(): BuilderManager
    {
        return self::$builderManager;
    }
}
