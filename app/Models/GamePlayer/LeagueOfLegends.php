<?php

namespace App\Models\GamePlayer;

use App\Models\GamePlayer;

class LeagueOfLegends extends GamePlayer
{
    public ?string $name = "League Of Legends";

    const MODIFIER_DAMAGE_DEAL_POS = 0;
    const MODIFIER_HEAL_DEAL_POS = 1;

    protected array $ratingModifiersByPos = [
        'T' => [0.03, 0.01],
        'B' => [0.03, 0.01],
        'M' => [0.03, 0.01],
        'J' => [0.02, 0.02],
        'S' => [0.01, 0.03],
    ];

    protected $fieldNames = [
        "player name",
        "nickname",
        "team name",
        "winner",
        "position",
        "kills",
        "deads",
        "assists",
        "damage deal",
        "heal deal"
    ];

    private function getAssists() : int
    {
        return (int)$this->fields["assists"];
    }

    private function getDeads() : int
    {
        return (int)$this->fields["deads"];
    }

    public function getDeaths() : int
    {
        return $this->getDeads();
    }

    private function getDamageDeal() : int
    {
        return (int)$this->fields["damage deal"];
    }

    private function getHealDeal() : int
    {
        return (int)$this->fields["heal deal"];
    }

    private function getKDA() : float
    {
        // 00_Code Test-Best Multi-eSports Player.docx: 0 Deaths is not a valid value
        return $this->getDeads() != 0 ? ($this->getKills() + $this->getAssists()) / $this->getDeads() : 0;
    }

    private function getModifiers() {
        return $this->ratingModifiersByPos[$this->fields["position"]];
    }

    private function getDamageModifier() : float
    {
        return $this->getModifiers()[self::MODIFIER_DAMAGE_DEAL_POS];
    }

    private function getHealModifier() : float
    {
        return $this->getModifiers()[self::MODIFIER_HEAL_DEAL_POS];
    }

    public function getScore() : float
    {
        $damageScore = $this->getDamageDeal() * $this->getDamageModifier();
        $healScore = $this->getHealDeal() * $this->getHealModifier();

        return $this->getDeads() != 0
            ? $this->getKDA() + $damageScore + $healScore + $this->getWinnerBonusScore()
            : 0;
    }
}
