<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Service\Media;

use App\Service\Media\Transformer\MediaTransformer;
use App\Service\Media\Validation\MediaValidator;
use App\Service\Media\Writer\MediaWriter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final readonly class MediaService
{
    public function __construct(
        private MediaTransformer $mediaTransformer,
        private MediaWriter $mediaWriter,
        private MediaValidator $mediaValidator,
    ) {
    }

    public function transformMedia(mixed $media): TransformedMedia
    {
        return $this->mediaTransformer->transform($media);
    }

    public function writeMedia(TransformedMedia $transformedMedia): TransformedMedia
    {
        return $this->mediaWriter->write($transformedMedia);
    }

    public function validateMedia(TransformedMedia $media): string|true
    {
        return $this->mediaValidator->validate($media);
    }

    public function createMedia(
        EntityManagerInterface $manager,
        mixed $file,
        bool $flush,
        bool $throwUploadError,
    ): string|TransformedMedia {
        $transformedMedia = $this->transformMedia($file);
        $errorMessage     = $this->validateMedia($transformedMedia);

        if (true !== $errorMessage) {
            return $errorMessage;
        }

        $manager->persist($transformedMedia->media);

        $transformedMedia = $this->writeMedia($transformedMedia);

        if (true === $flush) {
            $manager->flush();
        }

        if (true === $throwUploadError && false === $transformedMedia->media->uploaded) {
            if ($transformedMedia->media->uploadError) {
                throw new FileException($transformedMedia->media->uploadError);
            }

            throw new FileException('Error handled during upload.');
        }

        return $transformedMedia;
    }
}
