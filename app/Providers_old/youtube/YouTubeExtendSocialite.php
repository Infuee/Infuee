<?php

namespace App\Providers\YouTube;

use SocialiteProviders\Manager\SocialiteWasCalled;

class YouTubeExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('youtube', Provider::class);
    }
}
