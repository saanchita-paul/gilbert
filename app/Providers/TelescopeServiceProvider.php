<?php

namespace App\Providers;

use App\Jobs\WaterAutoSubmitJob;
use App\Listeners\Agency\CreateHubSpotContact;
use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */

    public const TAGS_MAPPER = [
//        "SomeClass => 'class property'
        CreateHubSpotContact::class => 'applicationId'// this is default, it's for just an example
    ];
    public function register()
    {
        // Telescope::night();

        $this->hideSensitiveRequestDetails();

//        Telescope::filter(function (IncomingEntry $entry) {
//            if ($this->app->environment('local')) {
//                return true;
//            }
//
//            return $entry->isReportableException() ||
//                   $entry->isFailedRequest() ||
//                   $entry->isFailedJob() ||
//                   $entry->isScheduledTask() ||
//                   $entry->hasMonitoredTag();
//        });
        $this->tagJob();
    }

    private function tagJob()
    {
        Telescope::tag(function (IncomingEntry $entry) {
            if ($entry->type === 'job') {
//                dd($entry->content);
                $tag =  $entry->content['name'];
                $id = $entry->content['data']['applicationId'] ?? null;

                if (!$id && isset(TelescopeServiceProvider::TAGS_MAPPER[$tag])) {
                    $id = $entry->content['data'][TelescopeServiceProvider::TAGS_MAPPER[$tag]] ?? null;
                }

                $tag = $id ? $tag . ":$id" : $tag;
                $tags = $tag ? [$tag] : [];
            }
            return array_merge($entry->tags, $tags ?? []);
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewTelescope', function ($user) {
            return in_array($user->email, $this->getAuthorizedEmails());
        });
    }

    /**
     * Get authorized telescope emails.
     *
     * @return array
     */
    private function getAuthorizedEmails(): array
    {
        return explode(',', config('telescope.authorized_emails'));
    }
}
