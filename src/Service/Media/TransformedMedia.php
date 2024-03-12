<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Service\Media;

use App\Entity\Basic\Media;

final readonly class TransformedMedia
{
    public function __construct(
        public Media $media,
        public mixed $originalFile,
        public string $uploadDir,
    ) {
    }
}
