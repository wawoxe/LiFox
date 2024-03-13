<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Service\Media\Exception;

use Exception;

final class NotUploadedException extends Exception
{
    /** @phpstan-ignore-next-line */
    protected $message = 'Media is not uploaded.';
}
