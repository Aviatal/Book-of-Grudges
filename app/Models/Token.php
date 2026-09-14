<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Token extends Model
{
    protected $guarded = ['id'];
    protected $appends = ['image_url'];
    protected $casts = [
        'sheet' => 'array',
    ];

    public function hero(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Hero::class);
    }

    public function campaign(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Route-model binding zawsze zawęża do bieżącej kampanii — dzięki temu żadna trasa
     * przyjmująca {token} nie musi ręcznie sprawdzać, czy token należy do innej kampanii.
     *
     * Czyta bezpośrednio z sesji (a nie z kontenerowego bindingu CurrentCampaign) — SubstituteBindings
     * wykonuje się w kolejności middleware WCZEŚNIEJ niż nasz EnsureCampaignSelected, więc
     * `CurrentCampaign` nie byłby jeszcze związany w kontenerze na tym etapie potoku.
     */
    public function resolveRouteBinding($value, $field = null): ?self
    {
        $query = $this->where($field ?? $this->getRouteKeyName(), $value);

        if ($campaignId = session('current_campaign_id')) {
            $query->where('campaign_id', $campaignId);
        }

        return $query->first();
    }

    public function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->image) {
                    return null;
                }
                return Storage::disk(config('filesystems.media'))->url('tokens/' . $this->image);
            }
        );
    }
}
