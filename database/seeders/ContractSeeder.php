<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    /**
     * Philippine construction contract types.
     */
    private array $contractTypes = [
        'Lump sum',
        'Lump sum',
        'Lump sum',
        'Lump sum',
        'Lump sum',
        'Unit price',
        'Unit price',
        'Cost plus',
        'Time and material',
    ];

    /**
     * Common Philippine construction payment terms.
     */
    private array $paymentTermsOptions = [
        '20% down payment, 70% progress billing, 10% retention',
        '30% down payment, 60% progress billing, 10% retention upon completion',
        '15% mobilization fee, 75% progress billing, 10% retention',
        '50% down payment, 40% progress billing, 10% final payment upon turnover',
        'Monthly progress billing based on accomplishment, 10% retention',
        '25% down payment, 65% progress billing, 10% retention',
        'Net 30 days upon billing',
        '10% mobilization, 80% progress billing, 10% retention upon turnover',
        '30% initial payment, 60% progress billing, 10% retention',
        '20% mobilization, monthly progress billing, 10% retention',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
         * Get all projects from the latest ProjectSeeder.
         *
         * This keeps the ContractSeeder synchronized with the
         * current projects table.
         */
        $projects = Project::query()
            ->orderBy('id')
            ->get();

        if ($projects->isEmpty()) {
            $this->command?->warn(
                'No projects found. Run ProjectSeeder before ContractSeeder.'
            );

            return;
        }

        /*
         * Clear existing contracts when re-running the seeder.
         *
         * This prevents duplicate contract data.
         */
        Contract::query()->delete();

        /*
         * ---------------------------------------------------------
         * 30 PRIMARY CONTRACTS
         * ---------------------------------------------------------
         *
         * One primary contract for every project.
         */
        foreach ($projects as $index => $project) {
            $this->createPrimaryContract(
                project: $project,
                index: $index + 1
            );
        }

        /*
         * ---------------------------------------------------------
         * 40 ADDITIONAL CONTRACTS
         * ---------------------------------------------------------
         *
         * These represent:
         *
         * - Variation orders
         * - Supplementary works
         * - Additional construction scope
         * - Material adjustments
         * - Additional labor
         * - Design modifications
         *
         * 30 primary + 40 additional = 70 contracts.
         */
        $additionalContracts = 40;

        /*
         * Prefer projects that are currently In progress because
         * these are the most realistic candidates for variations.
         */
        $eligibleProjects = $projects
            ->where('status', 'In progress')
            ->values();

        /*
         * If there are not enough In progress projects, use
         * all projects as a fallback.
         */
        if ($eligibleProjects->isEmpty()) {
            $eligibleProjects = $projects->values();
        }

        for ($i = 1; $i <= $additionalContracts; $i++) {

            /*
             * Cycle through the eligible projects.
             *
             * This distributes the 40 additional contracts
             * instead of randomly concentrating them on a few
             * projects.
             */
            $project = $eligibleProjects[($i - 1) % $eligibleProjects->count()];

            $this->createVariationContract(
                project: $project,
                index: $projects->count() + $i
            );
        }

        $this->command?->info(
            'Successfully seeded ' . (30 + $additionalContracts) .
                ' contracts.'
        );
    }

    /**
     * Create the primary contract for a project.
     */
    private function createPrimaryContract(
        Project $project,
        int $index
    ): void {
        $contractType = $this->contractTypes[array_rand($this->contractTypes)];

        $status = $this->primaryContractStatus(
            $project->status
        );

        /*
         * Primary contract is close to the project's
         * declared contract value.
         *
         * 90% - 100% of project contract value.
         */
        $originalValue = round(
            (float) $project->contract_value
                * (mt_rand(90, 100) / 100),
            2
        );

        $currentValue = $this->currentValue(
            $originalValue,
            $status
        );

        /*
         * Keep contract creation near the project's
         * created_at date.
         */
        $createdAt = $this->contractCreatedAt(
            $project->created_at,
            $index
        );

        Contract::create([
            'project_id' => $project->id,

            'contract_no' => $this->generateContractNo(
                $createdAt,
                $index
            ),

            'contract_type' => $contractType,

            'original_value' => $originalValue,

            'current_value' => $currentValue,

            'retention_percentage' =>
            $this->randomRetentionPercentage(),

            'payment_terms' =>
            $this->paymentTermsOptions[array_rand($this->paymentTermsOptions)],

            'effective_date' =>
            $this->effectiveDate(
                $project->start_date,
                $createdAt
            ),

            'status' => $status,

            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
    }

    /**
     * Create a supplementary / variation contract.
     */
    private function createVariationContract(
        Project $project,
        int $index
    ): void {
        /*
         * Variation contracts are normally smaller than
         * the primary contract.
         *
         * 5% - 30% of the project's contract value.
         */
        $variationPercentage = mt_rand(5, 30) / 100;

        $originalValue = round(
            (float) $project->contract_value
                * $variationPercentage,
            2
        );

        /*
         * Variation contract status.
         */
        $status = $this->variationContractStatus(
            $project->status
        );

        /*
         * Variation contracts are normally created after
         * the project was initially created.
         */
        $baseCreatedAt = Carbon::parse(
            $project->created_at
        );

        $createdAt = $baseCreatedAt
            ->copy()
            ->addDays(mt_rand(7, 90))
            ->setTime(
                mt_rand(8, 17),
                mt_rand(0, 59)
            );

        /*
         * Never create a future record.
         */
        if ($createdAt->greaterThan(now())) {
            $createdAt = now()
                ->copy()
                ->subDays(mt_rand(1, 10))
                ->setTime(
                    mt_rand(8, 17),
                    mt_rand(0, 59)
                );
        }

        $currentValue = $this->currentValue(
            $originalValue,
            $status
        );

        Contract::create([
            'project_id' => $project->id,

            'contract_no' => $this->generateContractNo(
                $createdAt,
                $index
            ),

            'contract_type' => $this->contractTypes[array_rand($this->contractTypes)],

            'original_value' => $originalValue,

            'current_value' => $currentValue,

            'retention_percentage' =>
            $this->randomRetentionPercentage(),

            'payment_terms' =>
            $this->paymentTermsOptions[array_rand($this->paymentTermsOptions)],

            'effective_date' =>
            $this->effectiveDate(
                $project->start_date,
                $createdAt
            ),

            'status' => $status,

            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
    }

    /**
     * Determine the primary contract status
     * based on the project status.
     */
    private function primaryContractStatus(
        string $projectStatus
    ): string {
        return match ($projectStatus) {

            'Bidding' => 'Draft',

            'Planning' => mt_rand(1, 4) === 1
                ? 'Active'
                : 'Draft',

            'In progress' => 'Active',

            'On hold' => mt_rand(1, 2) === 1
                ? 'Active'
                : 'Draft',

            'Substantially complete' => 'Completed',

            'Cancelled' => 'Terminated',

            default => 'Draft',
        };
    }

    /**
     * Determine variation contract status.
     */
    private function variationContractStatus(
        string $projectStatus
    ): string {
        return match ($projectStatus) {

            'In progress' => mt_rand(1, 6) === 1
                ? 'Completed'
                : 'Active',

            'On hold' => mt_rand(1, 2) === 1
                ? 'Active'
                : 'Draft',

            'Planning' => 'Draft',

            'Bidding' => 'Draft',

            'Substantially complete' => 'Completed',

            'Cancelled' => 'Terminated',

            default => 'Draft',
        };
    }

    /**
     * Calculate the current contract value.
     *
     * Active and completed contracts may have approved
     * variations or change orders.
     */
    private function currentValue(
        float $originalValue,
        string $status
    ): float {
        /*
         * Draft contracts have not yet been modified.
         */
        if (!in_array($status, ['Active', 'Completed'], true)) {
            return $originalValue;
        }

        /*
         * Approved change orders can increase the contract
         * by 0% - 10%.
         */
        $increasePercentage = mt_rand(0, 10) / 100;

        return round(
            $originalValue * (1 + $increasePercentage),
            2
        );
    }

    /**
     * Generate a realistic retention percentage.
     */
    private function randomRetentionPercentage(): float
    {
        $options = [
            5.00,
            5.00,
            5.00,
            5.00,
            7.50,
            10.00,
            10.00,
        ];

        return $options[array_rand($options)];
    }

    /**
     * Generate contract effective date.
     */
    private function effectiveDate(
        string|Carbon $projectStartDate,
        Carbon $createdAt
    ): string {
        $startDate = Carbon::parse(
            $projectStartDate
        );

        $effectiveDate = $startDate
            ->copy()
            ->addDays(mt_rand(-10, 10));

        /*
         * Prevent an unrealistic effective date.
         */
        if (
            $effectiveDate->greaterThan(
                $createdAt->copy()->addDays(30)
            )
        ) {
            $effectiveDate = $createdAt->copy();
        }

        return $effectiveDate->toDateString();
    }

    /**
     * Generate contract created_at.
     *
     * Primary contracts are created close to the project's
     * created_at date.
     */
    private function contractCreatedAt(
        mixed $projectCreatedAt,
        int $index
    ): Carbon {
        $createdAt = Carbon::parse(
            $projectCreatedAt
        )
            ->copy()
            ->addDays(mt_rand(-3, 5))
            ->setTime(
                mt_rand(8, 17),
                mt_rand(0, 59)
            );

        /*
         * Prevent future dates.
         */
        if ($createdAt->greaterThan(now())) {
            $createdAt = now()
                ->copy()
                ->subMinutes($index)
                ->setTime(
                    mt_rand(8, 17),
                    mt_rand(0, 59)
                );
        }

        return $createdAt;
    }

    /**
     * Generate unique contract number.
     *
     * Format:
     * CON-MMDDYY-HHmm
     *
     * Example:
     * CON-083026-0832
     */
    private function generateContractNo(
        Carbon $createdAt,
        int $index
    ): string {
        /*
         * Start with the contract creation date.
         */
        $base = $createdAt
            ->copy()
            ->addSeconds($index);

        $contractNo = 'CON-' .
            $base->format('mdy-Hi');

        /*
         * Ensure uniqueness.
         */
        while (
            Contract::where(
                'contract_no',
                $contractNo
            )->exists()
        ) {
            $base->addMinute();

            $contractNo = 'CON-' .
                $base->format('mdy-Hi');
        }

        return $contractNo;
    }
}
