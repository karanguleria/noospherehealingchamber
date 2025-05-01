<?php

namespace App\Nova\Dashboards;

use Laravel\Nova\Dashboard;

class UsersRedirect extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            //
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'users-redirect';
    }

    public static function redirect()
    {
        return '/resources/users'; // <--- important
    }
}
