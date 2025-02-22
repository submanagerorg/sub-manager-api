<?php

namespace App\Models;

use App\Services\ServicePayment\SubSyncService;
use App\Services\ServicePayment\TvSubscriptionService\DstvService;
use App\Services\ServicePayment\TvSubscriptionService\GotvService;
use App\Services\ServicePayment\TvSubscriptionService\ShowmaxService;
use App\Services\ServicePayment\TvSubscriptionService\StartimesService;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory, Filterable;

    protected $guarded = ['id'];

    protected $casts = [
        'is_payment_supported' => 'boolean',
    ];

    const DEFAULT_SERVICE = 'SubSync';
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'category_id'
    ];

    protected $with = ['category'];

    /**
     * Returns the category of the service.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public static function categorize($data)
    {
        // Loop through each record and check if the service name is contained within the argument
        foreach (self::all() as $service) {
            if (stripos($data['name'], $service->name) !== false) {
                return $service->category;
            }
        }

        $category = Category::where('name', 'others')->first();

        self::create([
            'uid' => Str::orderedUuid(),
            'name' => $data['name'],
            'url' =>  isset($data['url']) ? $data['url'] : null,
            'category_id' => $category->id
        ]);

        return $category;
    }

    public static function getServiceClass(string $service)
    {
        $service = strtolower($service);
         
        $services = [
            'dstv' => DstvService::class,
            'showmax' => ShowmaxService::class,
            'gotv' => GotvService::class,
            'startimes' => StartimesService::class,
            'subsync' =>  SubSyncService::class,
        ];

        if (!array_key_exists($service, $services)) {
            throw new \Exception('Invalid service specified');
        }

        return new $services[$service]();
    }

    public static function getByName(string $name)
    {
        return self::where('name', $name)->first();
    }
}
