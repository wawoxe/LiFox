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

final class UploadDirectoryException extends Exception
{
    /** @phpstan-ignore-next-line */
    protected $message = 'media.upload_dir must be string.';
}
