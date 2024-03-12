<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Service\Media\Validation;

use App\Entity\Basic\Media;

interface MediaValidator
{
    public function validate(Media $media): string|true;
}
