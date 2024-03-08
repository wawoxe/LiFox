<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Controller;

use function is_iterable;
use function is_string;

use function sprintf;

use function str_replace;

use App\Entity\Basic\Media;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(
    path: '/media',
    name: 'app_media_',
)]
final class MediaController extends AbstractController
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly EntityManagerInterface $manager,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route(
        path: '/create',
        name: 'create',
        methods: [Request::METHOD_POST],
    )]
    public function create(Request $request): Response
    {
        $uploadedFiles = $request->files->get('files');
        $uploadDir     = $this->parameterBag->get('app.upload_dir');

        if (
            $request->files->count() &&
            is_iterable($uploadedFiles) &&
            is_string($uploadDir)
        ) {
            foreach ($uploadedFiles as $uploadedFile) {
                if ($uploadedFile instanceof UploadedFile) {
                    $media = new Media(
                        originalName: str_replace(
                            '.' . $uploadedFile->getClientOriginalExtension(),
                            '',
                            $uploadedFile->getClientOriginalName(),
                        ),
                        extension: $uploadedFile->getClientOriginalExtension(),
                        type: $uploadedFile->getClientMimeType(),
                        uploadDir: $uploadDir,
                        size: $uploadedFile->getSize(),
                        public: false,
                        generated: false,
                    );

                    $errors = $this->validator->validate($media);

                    if ($errors->count()) {
                        return $this->json(
                            ['message' => (string) $errors->get(0)->getMessage()],
                            Response::HTTP_BAD_REQUEST,
                        );
                    }

                    $this->manager->persist($media);

                    try {
                        $uploadedFile->move($uploadDir, sprintf('%s.%s', $media->id, $media->extension));
                        $media->uploaded = true;
                    } catch (FileException $exception) {
                        $media->uploadError = $exception->getMessage();
                        $media->uploaded    = false;
                        $this->manager->flush();

                        return $this->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
                    }

                    $this->manager->flush();
                }
            }

            return $this->json(['message' => 'response.media.created'], Response::HTTP_OK);
        }

        return $this->json(['message' => 'response.media.empty'], Response::HTTP_BAD_REQUEST);
    }

    #[Route(
        path: '/file/{id}',
        name: 'get_file',
        methods: [Request::METHOD_GET],
    )]
    public function getFile(string $id): Response
    {
        $media = $this->manager->getRepository(Media::class)->findOneBy(['id' => $id]);

        if ($media instanceof Media) {
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
