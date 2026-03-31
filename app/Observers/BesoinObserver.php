<?php

namespace App\Observers;

use App\Models\Besoin;

class BesoinObserver
{
    /**
     * Handle the Besoin "created" event.
     */
    public function created(Besoin $besoin): void
    {
        $this->updateEngagement($besoin);
    }

    /**
     * Handle the Besoin "updated" event.
     */
    public function updated(Besoin $besoin): void
    {
        $this->updateEngagement($besoin);
    }

    /**
     * Handle the Besoin "deleted" event.
     */
    public function deleted(Besoin $besoin): void
    {
        $this->updateEngagement($besoin);
    }

    /**
     * Recalculer les totaux de l'engagement parent.
     */
    protected function updateEngagement(Besoin $besoin): void
    {
        if ($besoin->engagement) {
            $besoin->engagement->calculerTotal();
            $besoin->engagement->save();
        }
    }
}
