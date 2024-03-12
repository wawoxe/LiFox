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
use App\Service\Media\Transformer\MediaTransformer;
use App\Service\Media\Validation\MediaValidator;
use App\Service\Media\Writer\MediaWriter;
use Doctrine\ORM\EntityManagerInterface;
use Error;

final readonly class MediaService
{
    public function __construct(
        private MediaTransformer $mediaTransformer,
        private MediaWriter $mediaWriter,
        private MediaValidator $mediaValidator,
    ) {
    }

    public function transformMedia(mixed $media): Media
    {
        return $this->mediaTransformer->transform($media);
    }

    public function writeMedia(mixed $notWrittenMedia, Media $createdMedia): Media
    {
        return $this->mediaWriter->write($notWrittenMedia, $createdMedia);
    }

    public function validateMedia(Media $media): string|true
    {
        return $this->mediaValidator->validate($media);
    }

    public function createMedia(
        EntityManagerInterface $manager,
        mixed $file,
        bool $flush,
        bool $throwUploadError,
    ): Media|string {
        $media        = $this->transformMedia($file);
        $errorMessage = $this->validateMedia($media);

        if (true !== $errorMessage) {
            return $errorMessage;
        }

        $manager->persist($media);

        $writtenMedia = $this->writeMedia($file, $media);

        if (true === $flush) {
            $manager->flush();
        }

        if (true === $throwUploadError && false === $writtenMedia->uploaded) {
            if ($writtenMedia->uploadError) {
                throw new Error($writtenMedia->uploadError);
            }

            throw new Error('Error handled during upload.');
        }

        return $writtenMedia;
    }
}
