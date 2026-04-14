<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subscriber;
use App\Models\Transaction;
use Carbon\Carbon;

class SubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample subscribers
        $subscribers = [
            [
                'msisdn' => '+962799123456',
                'status' => 'active',
                'subscription_plan' => 'daily',
                'rate' => 1.00,
                'subscribed_at' => Carbon::now()->subDays(30),
                'last_active_at' => Carbon::now()->subHours(2),
                'operator' => 'Orange Jordan',
            ],
            [
                'msisdn' => '+962788234567',
                'status' => 'active',
                'subscription_plan' => 'daily',
                'rate' => 1.00,
                'subscribed_at' => Carbon::now()->subDays(15),
                'last_active_at' => Carbon::now()->subHours(5),
                'operator' => 'Zain Jordan',
            ],
            [
                'msisdn' => '+962777345678',
                'status' => 'inactive',
                'subscription_plan' => 'daily',
                'rate' => 1.00,
                'subscribed_at' => Carbon::now()->subDays(45),
                'last_active_at' => Carbon::now()->subDays(10),
                'unsubscribed_at' => Carbon::now()->subDays(5),
                'operator' => 'Umniah',
            ],
            [
                'msisdn' => '+962766456789',
                'status' => 'active',
                'subscription_plan' => 'daily',
                'rate' => 1.00,
                'subscribed_at' => Carbon::now()->subDays(7),
                'last_active_at' => Carbon::now()->subMinutes(30),
                'operator' => 'Orange Jordan',
            ],
            [
                'msisdn' => '+962755567890',
                'status' => 'active',
                'subscription_plan' => 'daily',
                'rate' => 1.00,
                'subscribed_at' => Carbon::now()->subDays(60),
                'last_active_at' => Carbon::now()->subHours(1),
                'operator' => 'Zain Jordan',
            ],
        ];

        foreach ($subscribers as $subscriberData) {
            $subscriber = Subscriber::create($subscriberData);

            // Create subscription transaction
            Transaction::create([
                'transaction_id' => 'TXN' . time() . rand(1000, 9999),
                'subscriber_id' => $subscriber->id,
                'msisdn' => $subscriber->msisdn,
                'type' => 'subscription',
                'status' => 'success',
                'amount' => 1.00,
                'currency' => 'IQD',
                'operator' => $subscriber->operator,
                'processed_at' => $subscriber->subscribed_at,
            ]);

            // Create some renewal transactions for active subscribers
            if ($subscriber->status === 'active') {
                $days = $subscriber->subscribed_at->diffInDays(Carbon::now());
                $renewalCount = min($days, 10); // Create up to 10 renewals

                for ($i = 0; $i < $renewalCount; $i++) {
                    Transaction::create([
                        'transaction_id' => 'TXN' . time() . rand(1000, 9999) . $i,
                        'subscriber_id' => $subscriber->id,
                        'msisdn' => $subscriber->msisdn,
                        'type' => 'renewal',
                        'status' => 'success',
                        'amount' => 1.00,
                        'currency' => 'IQD',
                        'operator' => $subscriber->operator,
                        'processed_at' => $subscriber->subscribed_at->copy()->addDays($i + 1),
                    ]);
                }
            }

            // Create unsubscribe transaction for inactive subscribers
            if ($subscriber->status === 'inactive' && $subscriber->unsubscribed_at) {
                Transaction::create([
                    'transaction_id' => 'TXN' . time() . rand(1000, 9999),
                    'subscriber_id' => $subscriber->id,
                    'msisdn' => $subscriber->msisdn,
                    'type' => 'unsubscribe',
                    'status' => 'success',
                    'amount' => 0.00,
                    'currency' => 'IQD',
                    'operator' => $subscriber->operator,
                    'processed_at' => $subscriber->unsubscribed_at,
                ]);
            }
        }

        $this->command->info('Created ' . count($subscribers) . ' subscribers with transactions.');
    }
}
