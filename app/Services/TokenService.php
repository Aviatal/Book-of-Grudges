<?php

namespace App\Services;

use App\Repositories\TokensRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

readonly class TokenService
{
    public function __construct(private TokensRepository $tokensRepository){}
    public function storeToken(array $data, int $campaignId): \App\Models\Token
    {
        if (isset($data['file'])) {
            if (empty($data['name'])) {
                $data['name'] = pathinfo($data['file']->getClientOriginalName(), PATHINFO_FILENAME);
            }
            $data['image'] = $this->storeTokenImage($data['file']);
        }
        // Tokeny NPC (bez bohatera) zaczynają w schowku, nie na mapie
        if (empty($data['hero_id'])) {
            $data['on_map'] = false;
        }
        if (isset($data['sheet']) && is_string($data['sheet'])) {
            $decoded = json_decode($data['sheet'], true);
            $data['sheet'] = $decoded ?? null;
        }
        $data['campaign_id'] = $campaignId;
        return $this->tokensRepository->createToken($data);
    }
    public function updateToken(int $tokenId, int $campaignId, array $data): \App\Models\Token
    {
        $token = $this->tokensRepository->getToken($tokenId, $campaignId, ['hero.user']);
        if (isset($data['file'])) {
            $data['image'] = $this->storeTokenImage($data['file'], $tokenId,  $token->getAttribute('image'));
        }
        if (isset($data['sheet']) && is_string($data['sheet'])) {
            $decoded = json_decode($data['sheet'], true);
            $data['sheet'] = $decoded ?? null;
        }
        return $this->tokensRepository->updateAndRequestToken($token, $data);
    }
    public function deleteToken(int $tokenId, int $campaignId): bool
    {
        $token = $this->tokensRepository->getToken($tokenId, $campaignId);
        if ($token->getAttribute('image')) {
            $this->deleteImage($token->getAttribute('image'));
        }
        return $this->tokensRepository->deleteToken($token);
    }
    public function duplicateToken(int $tokenId, int $campaignId): \App\Models\Token
    {
        $original = $this->tokensRepository->getToken($tokenId, $campaignId);

        $newImage = null;
        if ($original->getAttribute('image')) {
            $newFilename = uniqid('dup-', true) . '.webp';
            $this->mediaDisk()->copy('tokens/' . $original->getAttribute('image'), 'tokens/' . $newFilename);
            $newImage = $newFilename;
        }

        $newName = $this->nextTokenName($original->getAttribute('name'), $campaignId);

        return $this->tokensRepository->createToken([
            'name'        => $newName,
            'image'       => $newImage,
            'hero_id'     => null,
            'on_map'      => false,
            'x'           => $original->getAttribute('x'),
            'y'           => $original->getAttribute('y'),
            'scale'       => $original->getAttribute('scale'),
            'sheet'       => $original->getAttribute('sheet'),
            'campaign_id' => $campaignId,
        ]);
    }

    private function nextTokenName(string $originalName, int $campaignId): string
    {
        // Wyciągnij nazwę bazową — usuń końcowy numer (np. "Mutant 3" → "Mutant")
        $baseName = preg_replace('/\s+\d+$/', '', $originalName);

        // Znajdź wszystkie tokeny NPC o tej samej nazwie bazowej w tej kampanii
        $existing = \App\Models\Token::whereNull('hero_id')
            ->where('campaign_id', $campaignId)
            ->where('name', 'like', $baseName . '%')
            ->pluck('name');

        // Znajdź najwyższy użyty numer ("Mutant" liczy się jako 1)
        $max = 1;
        $escaped = preg_quote($baseName, '/');
        foreach ($existing as $name) {
            if ($name === $baseName) {
                $max = max($max, 1);
            } elseif (preg_match('/^' . $escaped . '\s+(\d+)$/', $name, $m)) {
                $max = max($max, (int) $m[1]);
            }
        }

        return $baseName . ' ' . ($max + 1);
    }

    private function mediaDisk(): \Illuminate\Contracts\Filesystem\Filesystem
    {
        return Storage::disk(config('filesystems.media'));
    }

    private function storeTokenImage(UploadedFile $file, ?int $tokenId = null, string $oldFileName = ''): string
    {
        $image = Image::decode($file);
        $image->cover(128, 128);
        $encoded = $image->encodeUsingFileExtension('webp', quality: 80);
        $fileName = uniqid("$tokenId-", true) . '.webp';
        $this->mediaDisk()->put("tokens/$fileName", $encoded);
        if ($oldFileName) {
            $this->deleteImage($oldFileName);
        }
        return $fileName;
    }

    private function deleteImage(string $filename): void
    {
        $this->mediaDisk()->delete("tokens/$filename");
    }
}
