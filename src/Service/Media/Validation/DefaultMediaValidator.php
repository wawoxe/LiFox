<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Service\Media\Validation;

use App\Service\Media\TransformedMedia;
use Override;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class DefaultMediaValidator implements MediaValidator
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    #[Override]
    public function validate(TransformedMedia $transformedMedia): string|true
    {
        $errors = $this->validator->validate($transformedMedia->media);

        if ($errors->count()) {
            return (string) $errors->get(0)->getMessage();
        }

        return true;
    }
}
