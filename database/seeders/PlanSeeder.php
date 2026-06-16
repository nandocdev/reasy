<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Central\Billing\Models\Plan;
use App\Modules\Central\Features\Models\Feature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlanSeeder extends Seeder {
   public function run(): void {
      if (!\Illuminate\Support\Facades\Schema::hasColumn('plans', 'slug')) {
         echo "plans table does not have 'slug' column; skipping PlanSeeder.\n";
         return;
      }
      $plans = [
         [
            'name' => 'Starter',
            'slug' => 'starter',
            'price_monthly' => 4900,
            'price_yearly' => 47000,
            'is_active' => true,
            'features' => [
               'stripe_id' => 'price_starter_monthly',
               'display_features' => ['Up to 200 bookings/mo', '3 staff members', '1 location'],
               'quotas' => ['locations' => 1, 'staff' => 3, 'bookings' => 200, 'invitations' => 5, 'api_keys' => 2],
            ],
         ],
         [
            'name' => 'Professional',
            'slug' => 'professional',
            'price_monthly' => 14900,
            'price_yearly' => 143000,
            'is_active' => true,
            'features' => [
               'stripe_id' => 'price_professional_monthly',
               'display_features' => ['Up to 1,000 bookings/mo', '10 staff members', '3 locations'],
               'quotas' => ['locations' => 3, 'staff' => 10, 'bookings' => 1000, 'invitations' => 20, 'api_keys' => 10],
            ],
         ],
         [
            'name' => 'Business',
            'slug' => 'business',
            'price_monthly' => 39900,
            'price_yearly' => 383000,
            'is_active' => true,
            'features' => [
               'stripe_id' => 'price_business_monthly',
               'display_features' => ['Up to 5,000 bookings/mo', '25 staff members', 'Unlimited locations'],
               'quotas' => ['locations' => -1, 'staff' => 25, 'bookings' => 5000, 'invitations' => 100, 'api_keys' => 50],
            ],
         ],
         [
            'name' => 'Enterprise',
            'slug' => 'enterprise',
            'price_monthly' => 99900,
            'price_yearly' => 959000,
            'is_active' => true,
            'features' => [
               'stripe_id' => 'price_enterprise_monthly',
               'display_features' => ['Unlimited everything', 'Advanced Analytics', 'Priority Support'],
               'quotas' => ['locations' => -1, 'staff' => -1, 'bookings' => -1, 'invitations' => -1, 'api_keys' => -1],
            ],
         ],
      ];

      foreach ($plans as $p) {
         $plan = Plan::updateOrCreate(
            ['slug' => $p['slug']],
            [
               'name' => $p['name'],
               'price_monthly' => $p['price_monthly'],
               'price_yearly' => $p['price_yearly'],
               'amount' => $p['price_monthly'] / 100,
               'currency' => 'USD',
               'interval' => 'month',
               'interval_count' => 1,
               'provider_plan_id' => "REASY_".strtoupper($p['slug']),
               'is_active' => $p['is_active'],
               'features' => $p['features'],
            ]
         );

         // Attach catalog features where applicable
         $featureKeys = [];
         if (in_array($p['slug'], ['professional', 'business', 'enterprise'])) {
            $featureKeys = ['api.access', 'branding.custom_domain'];
         }

         if (in_array($p['slug'], ['business', 'enterprise'])) {
            $featureKeys[] = 'reports.advanced';
         }

         if (! empty($featureKeys)) {
            $featureIds = Feature::whereIn('key', $featureKeys)->pluck('id')->toArray();
            $plan->catalogFeatures()->sync($featureIds);
         }
      }
   }
}
