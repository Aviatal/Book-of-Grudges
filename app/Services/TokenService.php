<?php

namespace App\Services;

use App\Repositories\TokensRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Storage;
use function PHPUnit\Framework\fileExists;

readonly class TokenService
{
    public function __construct(private TokensRepository $tokensRepository){}
    public function storeToken(array $data): \App\Models\Token
    {
        if (isset($data['file'])) {
            $data['image'] = $this->storeTokenImage($data['file']);
        }
        return $this->tokensRepository->createToken($data);
    }
    public function updateToken(int $tokenId, array $data): \App\Models\Token
    {
        $token = $this->tokensRepository->getToken($tokenId, ['hero.user']);
        if (isset($data['file'])) {
            $data['image'] = $this->storeTokenImage($data['file'], $tokenId,  $token->getAttribute('image'));
        }
        return $this->tokensRepository->updateAndRequestToken($token, $data);
    }
    public function deleteToken(int $tokenId): bool
    {
        $token = $this->tokensRepository->getToken($tokenId);
        if ($token->getAttribute('image') && fileExists("tokens/" . $token->getAttribute('image'))) {
            $this->deleteImage($token->getAttribute('image'));
        }
        return $this->tokensRepository->deleteToken($token);
    }
    private function storeTokenImage(UploadedFile $file, ?int $tokenId = null, string $oldFileName = ''): string
    {
        $image = Image::decode($file);
        $image->cover(128, 128);
        $encoded = $image->encodeUsingFileExtension('webp', quality: 80);
        $fileName = uniqid("$tokenId-", true) .  '.webp';
        File::ensureDirectoryExists(public_path('tokens'));
        Storage::disk('public')->put("tokens/$fileName", $encoded);
        if (fileExists("tokens/$oldFileName")) {
            $this->deleteImage($oldFileName);
        }
        return $fileName;
    }
    private function deleteImage(string $filename): void
    {
        Storage::disk('public')->delete("tokens/$filename");
    }
}
