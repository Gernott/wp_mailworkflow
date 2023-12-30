<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'module-mailworkflow' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:wp_mailworkflow/Resources/Public/Icons/user_mod_mailworkflow.svg',
    ],
];
