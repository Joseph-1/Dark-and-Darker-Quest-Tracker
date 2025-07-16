<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class HighlightRarityExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('highlight_rarity', [$this, 'highlightRarity'], ['is_safe' => ['html']]),
        ];
    }

    public function highlightRarity(string $text): string
    {
        $replacements = [
            'any rarity'                   => '<span class="rarity-any">Any Rarity</span>',
            'poor', 'Poor'                 => '<span class="rarity-poor">Poor</span>',
            'Uncommon'         => '<span class="rarity-uncommon">Uncommon</span>',
            'rare', 'Rare'                 => '<span class="rarity-rare">Rare</span>',
            'epic', 'Epic'                 => '<span class="rarity-epic">Epic</span>',
            'legendary', 'Legendary', 'Legend'       => '<span class="rarity-legendary">Legendary</span>',
            'unique', 'Unique'             => '<span class="rarity-unique">Unique</span>',
            'artifact', 'Artifact'         => '<span class="rarity-artifact">Artifact</span>',
        ];

        return str_ireplace(array_keys($replacements), array_values($replacements), $text);
    }
}
