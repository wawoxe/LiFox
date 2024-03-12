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

use App\Entity\Basic\Media;
use Error;
use Override;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UploadedFileWriter implements MediaWriter
{
    #[Override]
    public function write(mixed $notWrittenMedia, Media $createdMedia): Media
    {
        if (false === $notWrittenMedia instanceof UploadedFile) {
            throw new Error('Media must be instance of UploadedFile');
        }

        try {
            if (null === $createdMedia->uploadDir) {
                throw new FileException('Upload directory is not found.');
            }

            $notWrittenMedia->move(
                $createdMedia->uploadDir,
                sprintf('%s.%s', $createdMedia->id, $createdMedia->extension),
            );
            $createdMedia->uploaded = true;
        } catch (FileException $exception) {
            $createdMedia->uploadError = $exception->getMessage();
            $createdMedia->uploaded    = false;
        }

        return $createdMedia;
    }
}
