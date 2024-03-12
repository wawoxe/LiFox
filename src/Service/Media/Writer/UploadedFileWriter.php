<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Service\Media\Writer;

use function sprintf;

use App\Service\Media\TransformedMedia;

use Error;
use Override;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UploadedFileWriter implements MediaWriter
{
    #[Override]
    public function write(TransformedMedia $transformedMedia): TransformedMedia
    {
        if (false === $transformedMedia->originalFile instanceof UploadedFile) {
            throw new Error('Media must be instance of UploadedFile');
        }

        try {
            $transformedMedia->originalFile->move(
                $transformedMedia->uploadDir,
                sprintf('%s.%s', $transformedMedia->media->id, $transformedMedia->media->extension),
            );
            $transformedMedia->media->uploaded = true;
        } catch (FileException $exception) {
            $transformedMedia->media->uploadError = $exception->getMessage();
            $transformedMedia->media->uploaded    = false;
        }

        return $transformedMedia;
    }
}
