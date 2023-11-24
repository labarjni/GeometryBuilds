<?php

namespace Labarjni\GeometryBuild\builder;

use Labarjni\GeometryBuild\Main;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;

class BuilderManager
{

    private static array $lastBuild;

    public function __construct(private readonly Main $plugin){}

    private function getPlugin(): Main
    {
        return $this->plugin;
    }

    public function buildFromGeometry(Player $player, string $name): bool
    {
        $dataFolder = $this->getPlugin()->getDataFolder();
        $position = $player->getPosition();
        $geometry = file_get_contents($dataFolder . $name . ".json");
        $geometry = json_decode($geometry, true);
        $bones = 0;

        foreach ($geometry["minecraft:geometry"][0]["bones"] as $bone) {
            $block = LegacyStringToItemParser::getInstance()->parse($bone['name']);
            foreach ($bone['cubes'] as $cube) {
                $newPosition = new Vector3($position->getX() + ($cube['origin'][0] / 2), $position->getY() + ($cube['origin'][1] / 2), $position->getZ() + ($cube['origin'][2] / 2));
                self::$lastBuild[strtolower($player->getName())]["blocks"][] = [
                    "id" => $player->getWorld()->getBlock($newPosition)->getTypeId(),
                    "position" => ["x" => $newPosition->x, "y" => $newPosition->y, "z" => $newPosition->z]
                ];
                $player->getWorld()->setBlock($newPosition, $block->getBlock());
            }
            $bones++;
        }

        if ($bones == count($geometry["minecraft:geometry"][0]["bones"])) {
            return true;
        }

        return false;
    }

    public function undoChanges(Player $player): bool
    {
        if (isset(self::$lastBuild[strtolower($player->getName())])) {
            foreach (self::$lastBuild[strtolower($player->getName())]["blocks"] as $block) {
                $blocks = [];

                foreach (VanillaBlocks::getAll() as $item) {
                    $blocks[$item->getTypeId()] = $item;
                }
                $oldBlock = $blocks[$block["id"]];
                $position = new Vector3($block["position"]["x"], $block["position"]["y"], $block["position"]["z"]);
                Server::getInstance()->getWorldManager()->getDefaultWorld()->setBlock($position, $oldBlock);
            }
            unset(self::$lastBuild[strtolower($player->getName())]);
            return true;
        }
        return false;
    }
}