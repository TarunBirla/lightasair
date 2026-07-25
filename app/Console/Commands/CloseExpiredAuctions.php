<?php

namespace App\Console\Commands;

use App\Models\Auction;
use Illuminate\Console\Command;

class CloseExpiredAuctions extends Command
{
    protected $signature = 'auctions:close-expired';
    protected $description = 'Closes all active auctions whose end_time has passed';

    public function handle(): int
    {
        $expiredAuctions = Auction::where('status', 'active')
                                  ->where('end_time', '<=', now())
                                  ->get();

        $count = 0;
        foreach ($expiredAuctions as $auction) {
            $highestBid = $auction->highestBid;

            $winnerId   = null;
            $winningBid = null;

            if ($highestBid && $auction->current_bid >= $auction->reserve_price) {
                $winnerId   = $highestBid->user_id;
                $winningBid = $highestBid->amount;
            }

            $auction->update([
                'status'      => 'ended',
                'winner_id'   => $winnerId,
                'winning_bid' => $winningBid,
            ]);

            $count++;
        }

        $this->info("Closed {$count} expired auction(s).");
        return 0;
    }
}
