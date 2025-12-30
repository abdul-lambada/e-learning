<?php

namespace App\Traits;

use App\Models\LogPoin;

trait EarnsPoints
{
    /**
     * Award points to the user.
     * 
     * @param int $amount
     * @param string $description
     * @return void
     */
    public function awardPoints(int $amount, string $description)
    {
        $this->increment('poin', $amount);

        LogPoin::create([
            'user_id' => $this->id,
            'jumlah_poin' => $amount,
            'keterangan' => $description,
        ]);
    }
}
