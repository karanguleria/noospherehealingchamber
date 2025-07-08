<?php

namespace Quant\Main;

use Laravel\Nova\Card;
use Illuminate\Http\Request;
class Main extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = 'full';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'main';
    }

    public function __construct()
    {

        parent::__construct();
        $typeId = auth()->user() ? auth()->user()->type_id : null;
        \Log::info('Main Card type_id: ' . $typeId); // Debug log
        $this->withMeta([
            'type_id' => (int) $typeId, // Cast to integer
        ]);
        
    }
}
