<?php

namespace Quant\Invitaion;

use Laravel\Nova\Card;

class Invitaion extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = 'full';

     /**
     * Indicates that the analytics should show current visitors.
     *
     * @return $this
     */
    public function currentVisitors()
    {
        return $this->withMeta(['currentVisitors' => true]);
    }
    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'invitaion';
    }
}
