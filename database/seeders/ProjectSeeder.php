<?php

namespace Database\Seeders;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * 30 specific residential project definitions. Dates (project_code,
     * created_at, start_date, expected_end_date) are computed in run()
     * rather than hardcoded, so created_at is guaranteed to land between
     * 2026-03-01 and 2026-09-02, spread evenly across all 30 records.
     */
    private array $projects = [
        ['name' => 'Talomo Single-Storey Bungalow', 'description' => 'Construction of a single-storey bungalow with an open-plan living and dining area, two bedrooms, and a covered carport. The project covers foundation works, CHB masonry, roofing, electrical, and plumbing installations.', 'client_id' => 1, 'project_type' => 'New build', 'site_address' => 'Talomo, Davao City, Davao del Sur', 'contract_value' => 4200000, 'status' => 'In progress', 'duration_months' => 6, 'lead_days' => 10],
        ['name' => 'Catalunan Grande Two-Storey Family Home', 'description' => 'Construction of a two-storey single-detached family home with four bedrooms, a family lounge, and a landscaped backyard. Scope includes structural framing, roofing, electrical, plumbing, and interior finishing works.', 'client_id' => 2, 'project_type' => 'New build', 'site_address' => 'Catalunan Grande, Davao City, Davao del Sur', 'contract_value' => 8700000, 'status' => 'In progress', 'duration_months' => 10, 'lead_days' => 14],
        ['name' => 'Matina Duplex (Twin House) Development', 'description' => 'Construction of a two-storey duplex with mirrored twin units, each with three bedrooms and a private garage. The project covers shared-wall structural works, roofing, and independent electrical and plumbing systems per unit.', 'client_id' => 3, 'project_type' => 'New build', 'site_address' => 'Matina, Davao City, Davao del Sur', 'contract_value' => 9500000, 'status' => 'In progress', 'duration_months' => 9, 'lead_days' => 12],
        ['name' => 'Bajada Ancestral House Restoration', 'description' => 'Restoration of a decades-old ancestral house, reinforcing the original wooden structure while upgrading the roof, electrical wiring, and plumbing to current standards without altering the traditional façade.', 'client_id' => 4, 'project_type' => 'Renovation', 'site_address' => 'J.P. Laurel Avenue, Bajada, Davao City, Davao del Sur', 'contract_value' => 3100000, 'status' => 'In progress', 'duration_months' => 5, 'lead_days' => 7],
        ['name' => 'Lanang 4-Unit Rental Apartment Building', 'description' => 'Construction of a four-unit, two-storey rental apartment building with individual meters, shared parking, and a common laundry area. Includes full structural, electrical, plumbing, and finishing works per unit.', 'client_id' => 5, 'project_type' => 'Infrastructure', 'site_address' => 'Lanang, Davao City, Davao del Sur', 'contract_value' => 13800000, 'status' => 'In progress', 'duration_months' => 12, 'lead_days' => 15],
        ['name' => 'Sasa Single-Attached Row House', 'description' => 'Construction of a single-attached row house unit with three bedrooms and a small home office nook, sharing one party wall with the adjacent lot. Covers foundation, masonry, roofing, and MEP rough-ins.', 'client_id' => 6, 'project_type' => 'New build', 'site_address' => 'Sasa, Davao City, Davao del Sur', 'contract_value' => 5600000, 'status' => 'In progress', 'duration_months' => 7, 'lead_days' => 9],
        ['name' => 'Toril Bungalow Extension and Renovation', 'description' => 'Renovation and rear extension of an existing bungalow to add a bedroom and expanded kitchen, including roof tie-in work, electrical rerouting, and repainting of the whole unit.', 'client_id' => 7, 'project_type' => 'Renovation', 'site_address' => 'Toril District, Davao City, Davao del Sur', 'contract_value' => 2400000, 'status' => 'In progress', 'duration_months' => 4, 'lead_days' => 8],
        ['name' => 'Buhangin High-End Custom Residence', 'description' => 'Construction of a high-end custom two-storey residence with a home theater, walk-in closets, and a swimming pool. Scope includes architectural finishes, premium electrical systems, and full landscaping.', 'client_id' => 8, 'project_type' => 'New build', 'site_address' => 'Buhangin, Davao City, Davao del Sur', 'contract_value' => 16500000, 'status' => 'In progress', 'duration_months' => 14, 'lead_days' => 18],
        ['name' => 'Panacan Semi-Furnished Contractor-Built House', 'description' => 'Construction of a semi-furnished single-detached house with fitted kitchen cabinetry and built-in closets included in the contract scope, alongside standard structural, electrical, and plumbing works.', 'client_id' => 9, 'project_type' => 'New build', 'site_address' => 'Panacan, Davao City, Davao del Sur', 'contract_value' => 6900000, 'status' => 'In progress', 'duration_months' => 8, 'lead_days' => 10],
        ['name' => 'Ecoland Townhouse Unit Interior Renovation', 'description' => 'Interior renovation of a single townhouse unit, covering new flooring, kitchen and bathroom fixture upgrades, lighting improvements, and full repainting of interior walls and ceilings.', 'client_id' => 10, 'project_type' => 'Renovation', 'site_address' => 'Ecoland, Davao City, Davao del Sur', 'contract_value' => 2850000, 'status' => 'On hold', 'duration_months' => 4, 'lead_days' => 6],
        ['name' => 'Mintal Two-Storey Suburban Residence', 'description' => 'Construction of a two-storey suburban residence with wide window openings for natural lighting, a two-car garage, and a landscaped front yard. Covers full structural, MEP, and finishing scope.', 'client_id' => 11, 'project_type' => 'New build', 'site_address' => 'Mintal, Tugbok District, Davao City, Davao del Sur', 'contract_value' => 10200000, 'status' => 'Planning', 'duration_months' => 11, 'lead_days' => 16],
        ['name' => 'Obrero Row House Compound (4 Units)', 'description' => 'Construction of a four-unit row house compound with shared perimeter fencing and a common driveway. Each unit includes independent electrical and plumbing connections and identical two-bedroom layouts.', 'client_id' => 12, 'project_type' => 'Infrastructure', 'site_address' => 'Obrero, Davao City, Davao del Sur', 'contract_value' => 14700000, 'status' => 'Planning', 'duration_months' => 13, 'lead_days' => 20],
        ['name' => 'Communal Bungalow Repair and Repainting', 'description' => 'Minor repair and repainting works on an existing single-storey bungalow, including patching of hairline cracks, gutter repair, and full exterior and interior repainting.', 'client_id' => 13, 'project_type' => 'Renovation', 'site_address' => 'Communal, Buhangin, Davao City, Davao del Sur', 'contract_value' => 1800000, 'status' => 'In progress', 'duration_months' => 3, 'lead_days' => 5],
        ['name' => 'Cabantian Two-Storey Duplex Residence', 'description' => 'Construction of a two-storey duplex residence for two related households, with a shared entry gate but fully independent living spaces, kitchens, and utility connections.', 'client_id' => 14, 'project_type' => 'New build', 'site_address' => 'Cabantian, Davao City, Davao del Sur', 'contract_value' => 9900000, 'status' => 'In progress', 'duration_months' => 10, 'lead_days' => 12],
        ['name' => 'Calinan Upland Farmhouse', 'description' => 'Construction of a rustic upland farmhouse with a wraparound porch, wide eaves for rain protection, and a detached storage shed, designed for occasional weekend use.', 'client_id' => 15, 'project_type' => 'New build', 'site_address' => 'Calinan District, Davao City, Davao del Sur', 'contract_value' => 5300000, 'status' => 'Bidding', 'duration_months' => 7, 'lead_days' => 14],
        ['name' => 'Bago Aplaya Garden-Style Residence', 'description' => 'Construction of a garden-style residence with extensive outdoor living areas, a covered patio, and landscaped grounds surrounding a single-storey main house with high ceilings.', 'client_id' => 16, 'project_type' => 'New build', 'site_address' => 'Bago Aplaya, Davao City, Davao del Sur', 'contract_value' => 11400000, 'status' => 'In progress', 'duration_months' => 12, 'lead_days' => 15],
        ['name' => 'Maa Bungalow Roof and Structural Repair', 'description' => 'Structural repair and roof replacement of an aging bungalow following water damage, including truss reinforcement, new G.I. sheet roofing, and ceiling replacement throughout.', 'client_id' => 17, 'project_type' => 'Renovation', 'site_address' => 'Maa, Davao City, Davao del Sur', 'contract_value' => 2150000, 'status' => 'In progress', 'duration_months' => 4, 'lead_days' => 6],
        ['name' => 'Shrine Hills Two-Storey Hillside Residence', 'description' => 'Construction of a two-storey hillside residence with a reinforced retaining wall, split-level layout, and panoramic city-view terraces. Includes slope stabilization and standard MEP works.', 'client_id' => 18, 'project_type' => 'New build', 'site_address' => 'Shrine Hills, Davao City, Davao del Sur', 'contract_value' => 12600000, 'status' => 'Planning', 'duration_months' => 12, 'lead_days' => 17],
        ['name' => 'GSIS Heights Duplex Renovation and Expansion', 'description' => 'Renovation and second-floor expansion of an existing single-storey duplex, adding two additional bedrooms per unit along with electrical upgrades and full repainting.', 'client_id' => 19, 'project_type' => 'Renovation', 'site_address' => 'GSIS Heights, Davao City, Davao del Sur', 'contract_value' => 3600000, 'status' => 'In progress', 'duration_months' => 5, 'lead_days' => 8],
        ['name' => 'Victoria Park Homes Apartment Extension', 'description' => 'Construction of a two-unit extension to an existing apartment building, matching the original architectural style and tying into the existing electrical and water supply systems.', 'client_id' => 20, 'project_type' => 'New build', 'site_address' => 'Victoria Park Homes, Davao City, Davao del Sur', 'contract_value' => 7800000, 'status' => 'In progress', 'duration_months' => 9, 'lead_days' => 11],
        ['name' => 'Doña Vicenta Village Single-Storey Bungalow', 'description' => 'Construction of a single-storey bungalow with a modern minimalist façade, three bedrooms, and an attached carport, built on a standard subdivision lot.', 'client_id' => 21, 'project_type' => 'New build', 'site_address' => 'Doña Vicenta Village, Davao City, Davao del Sur', 'contract_value' => 4600000, 'status' => 'In progress', 'duration_months' => 6, 'lead_days' => 9],
        ['name' => 'Bangkal Two-Storey Family Residence', 'description' => 'Construction of a two-storey family residence with a maid\'s quarters, dirty kitchen, and rooftop laundry area, alongside standard structural, electrical, and plumbing scope.', 'client_id' => 22, 'project_type' => 'New build', 'site_address' => 'Bangkal, Davao City, Davao del Sur', 'contract_value' => 8900000, 'status' => 'In progress', 'duration_months' => 10, 'lead_days' => 13],
        ['name' => 'Los Amigos Row House Unit Renovation', 'description' => 'Renovation of a single row house unit including bathroom retiling, kitchen counter replacement, new vinyl flooring, and repainting of all interior walls.', 'client_id' => 23, 'project_type' => 'Renovation', 'site_address' => 'Los Amigos, Davao City, Davao del Sur', 'contract_value' => 2700000, 'status' => 'On hold', 'duration_months' => 4, 'lead_days' => 7],
        ['name' => 'Juna Subdivision Duplex Residence', 'description' => 'Construction of a two-storey duplex residence within a gated subdivision, subject to the homeowners\' association design guidelines, with independent utility metering per unit.', 'client_id' => 24, 'project_type' => 'New build', 'site_address' => 'Juna Subdivision, Matina, Davao City, Davao del Sur', 'contract_value' => 10800000, 'status' => 'Planning', 'duration_months' => 11, 'lead_days' => 14],
        ['name' => 'Agdao Ancestral House Restoration', 'description' => 'Restoration of a heritage-style ancestral house, preserving original wooden posts and capiz windows while upgrading the electrical system and reinforcing the foundation.', 'client_id' => 25, 'project_type' => 'Renovation', 'site_address' => 'Agdao, Davao City, Davao del Sur', 'contract_value' => 3300000, 'status' => 'In progress', 'duration_months' => 5, 'lead_days' => 8],
        ['name' => 'Waan Single-Detached Bungalow', 'description' => 'Construction of a compact single-detached bungalow designed for a small family, with two bedrooms, one bathroom, and a covered front porch.', 'client_id' => 26, 'project_type' => 'New build', 'site_address' => 'Waan, Davao City, Davao del Sur', 'contract_value' => 4100000, 'status' => 'Bidding', 'duration_months' => 6, 'lead_days' => 10],
        ['name' => 'Puan Two-Storey Custom Family Residence', 'description' => 'Construction of a two-storey custom family residence with a home office, family lounge, and covered outdoor dining area, including full architectural finishing.', 'client_id' => 27, 'project_type' => 'New build', 'site_address' => 'Puan, Davao City, Davao del Sur', 'contract_value' => 9200000, 'status' => 'In progress', 'duration_months' => 10, 'lead_days' => 12],
        ['name' => 'Bunawan Rental Apartment Renovation', 'description' => 'Renovation of an existing three-unit rental apartment building, including plumbing line replacement, electrical panel upgrades, and repainting of all common areas and units.', 'client_id' => 28, 'project_type' => 'Renovation', 'site_address' => 'Bunawan, Davao City, Davao del Sur', 'contract_value' => 3950000, 'status' => 'In progress', 'duration_months' => 6, 'lead_days' => 9],
        ['name' => 'Mandug Two-Storey Duplex Development', 'description' => 'Construction of a two-storey duplex development on a corner lot, with separate driveways per unit and shared perimeter fencing, including full MEP and finishing works.', 'client_id' => 29, 'project_type' => 'Infrastructure', 'site_address' => 'Mandug, Davao City, Davao del Sur', 'contract_value' => 10500000, 'status' => 'Planning', 'duration_months' => 11, 'lead_days' => 15],
        ['name' => 'Catalunan Pequeño High-End Custom Residence', 'description' => 'Construction of a high-end custom residence with an entertainment wing, wine cellar, and landscaped infinity-edge pool, built to premium architectural and structural specifications.', 'client_id' => 30, 'project_type' => 'Infrastructure', 'site_address' => 'Catalunan Pequeño, Davao City, Davao del Sur', 'contract_value' => 17200000, 'status' => 'Bidding', 'duration_months' => 15, 'lead_days' => 20],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rangeStart = Carbon::parse('2026-03-01');
        $rangeEnd = Carbon::parse('2026-09-02');
        $totalDays = $rangeStart->diffInDays($rangeEnd);
        $lastIndex = count($this->projects) - 1;

        foreach ($this->projects as $index => $data) {
            // Spread created_at evenly across the range, first record on
            // rangeStart and last record exactly on rangeEnd.
            $offsetDays = intdiv($index * $totalDays, $lastIndex);
            $createdAt = $rangeStart->copy()
                ->addDays($offsetDays)
                ->setTime(mt_rand(8, 18), mt_rand(0, 59));

            $startDate = $createdAt->copy()->startOfDay()->addDays($data['lead_days']);
            $expectedEndDate = $startDate->copy()->addMonths($data['duration_months']);

            Project::create([
                'project_code' => 'PRJ-' . Carbon::now()->addMinutes($index + 1)->format('mdy-Hi'),
                'name' => $data['name'],
                'description' => $data['description'],
                'client_id' => $data['client_id'],
                'project_type' => $data['project_type'],
                'site_address' => $data['site_address'],
                'start_date' => $startDate->toDateString(),
                'expected_end_date' => $expectedEndDate->toDateString(),
                'actual_end_date' => null,
                'status' => $data['status'],
                'contract_value' => $data['contract_value'],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
