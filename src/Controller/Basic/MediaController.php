<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Controller\Basic;

use function is_iterable;
use function sprintf;

use App\Entity\Basic\Media;

use App\Service\Media\Exception\NotUploadedException;

use App\Service\Media\MediaService;
use App\Service\Media\TransformedMedia;
use App\Service\Media\Transformer\UploadedFileTransformer;
use App\Service\Media\Validation\DefaultMediaValidator;
use App\Service\Media\Writer\UploadedFileWriter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(
    path: '/basic/media',
    name: 'app_basic_media_',
)]
final class MediaController extends AbstractController
{
    private readonly MediaService $mediaService;

    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly EntityManagerInterface $manager,
        private readonly ValidatorInterface $validator,
    ) {
        $this->mediaService = new MediaService(
            new UploadedFileTransformer($this->parameterBag),
            new UploadedFileWriter,
            new DefaultMediaValidator($this->validator),
        );
    }

    #[Route(
        path: '/create',
        name: 'create',
        methods: [Request::METHOD_POST],
    )]
    public function create(Request $request): Response
    {
        $this->denyAccessUnlessGranted($this->parameterBag->get('media.create.allowed_role'));

        $uploadedFiles = $request->files->get('files');

        if ($request->files->count() && is_iterable($uploadedFiles)) {
            foreach ($uploadedFiles as $uploadedFile) {
                $errorMessage = $this->mediaService->createMedia(
                    $this->manager,
                    $uploadedFile,
                    true,
                    true,
                );

                if (false === $errorMessage instanceof TransformedMedia) {
                    return $this->json(['message' => $errorMessage], Response::HTTP_BAD_REQUEST);
                }
            }

            return $this->json(['message' => 'response.media.created'], Response::HTTP_OK);
        }

        return $this->json(['message' => 'response.media.empty'], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @throws NotUploadedException
     */
    #[Route(
        path: '/file/{id}',
        name: 'get_file',
        methods: [Request::METHOD_GET],
    )]
    public function getFile(string $id): Response
    {
        $this->denyAccessUnlessGranted($this->parameterBag->get('media.get.allowed_role'));

        $media = $this->manager->getRepository(Media::class)->findOneBy(['id' => $id]);

        if ($media instanceof Media) {
            if (true !== $media->uploaded) {
                throw new NotUploadedException;
            }

            return $this->file(
                sprintf(
                    '%s%s.%s',
                    $media->uploadDir,
                    $media->id,
                    $media->extension,
                ),
                sprintf('%s.%s', $media->originalName, $media->extension),
            );
        }

        return $this->json(['message' => 'response.media.not_found'], Response::HTTP_NOT_FOUND);
    }
}
