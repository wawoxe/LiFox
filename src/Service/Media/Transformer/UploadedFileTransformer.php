<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Service\Media\Transformer;

use function is_string;
use function str_replace;

use App\Entity\Basic\Media;
use App\Service\Media\Exception\UploadDirectoryException;

use App\Service\Media\Exception\UploadedFileException;

use App\Service\Media\TransformedMedia;
use Override;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UploadedFileTransformer implements MediaTransformer
{
    public function __construct(private ParameterBagInterface $parameterBag)
    {
    }

    /**
     * @throws UploadDirectoryException
     * @throws UploadedFileException
     */
    #[Override]
    public function transform(mixed $notProcessedMedia): TransformedMedia
    {
        if (false === $notProcessedMedia instanceof UploadedFile) {
            throw new UploadedFileException;
        }

        $uploadDir = $this->parameterBag->get('media.upload_dir');

        if (false === is_string($uploadDir)) {
            throw new UploadDirectoryException;
        }

        return new TransformedMedia(
            new Media(
                originalName: str_replace(
                    '.' . $notProcessedMedia->getClientOriginalExtension(),
                    '',
                    $notProcessedMedia->getClientOriginalName(),
                ),
                extension: $notProcessedMedia->getClientOriginalExtension(),
                type: $notProcessedMedia->getClientMimeType(),
                uploadDir: $uploadDir,
                size: $notProcessedMedia->getSize(),
                public: false,
                generated: false,
            ),
            $notProcessedMedia,
            $uploadDir,
        );
    }
}
