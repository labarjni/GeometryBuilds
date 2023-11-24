<?php

namespace Labarjni\GeometryBuild\command;

use Labarjni\GeometryBuild\Main;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class UndoCommand extends Command
{
    public function __construct(private readonly Main $plugin)
    {
        parent::__construct("undo", "Undo latest build");
        $this->setPermission("UndoCommand.use");
    }

    private function getPlugin(): Main
    {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $this->testPermission($sender);
        if (!$sender instanceof Player) return false;

        if ($this->getPlugin()->getBuilderManager()->undoChanges($sender)) {
            $sender->sendMessage("§aSuccessfully canceled the last action");
        } else {
            $sender->sendMessage("§cNo recent activities found");
        }

        return true;
    }
}