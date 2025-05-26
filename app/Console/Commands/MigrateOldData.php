<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
// use App\Models\User; // Example: if using Eloquent events disable/enable

class MigrateOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-old-data
                            {--tables=all : Comma-separated list of tables to migrate (e.g., users,categories,products) or "all"}
                            {--truncate : Truncate new tables before migration (USE WITH CAUTION - DEVELOPMENT ONLY)}
                            {--chunk=100 : Number of records to process per chunk}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates data from the old database to the new Laravel database schema.';

    /**
     * A cache for old ID to new ID mappings.
     * Example: ['old_categories' => ['old_id_1' => 'new_id_x', ...]]
     *
     * @var array
     */
    protected $idCache = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting old data migration process...');
        Log::info('Starting old data migration process...');

        $tablesToMigrateOption = $this->option('tables');
        $truncateTables = $this->option('truncate');
        $chunkSize = (int)$this->option('chunk');

        if ($truncateTables && app()->environment('production')) {
            $this->error('ABORTING: Truncate option is NOT allowed in production environment for safety.');
            Log::error('Migration aborted: Truncate option used in production.');
            return 1;
        }

        // Define the order of migration
        $migrationOrder = [
            'categories', // Example: Old table name might be 'old_categories'
            'users',      // Example: Old table name might be 'tbl_users'
            'customers',
            'products',
            'orders',
            'order_items',
        ];

        $selectedTables = [];
        if ($tablesToMigrateOption === 'all') {
            $selectedTables = $migrationOrder;
        } else {
            $selectedTables = array_map('trim', explode(',', $tablesToMigrateOption));
            // Validate that selected tables are in the defined migration order to avoid issues
            // For simplicity, we'll process them in the order given if they are valid table keys
        }

        foreach ($selectedTables as $tableKey) {
            if (!in_array($tableKey, $migrationOrder)) {
                $this->warn("Skipping table '{$tableKey}': Not a recognized table for migration or check migration order definition.");
                Log::warning("Skipping table '{$tableKey}': Not a recognized table for migration.");
                continue;
            }

            // Dynamically call migration methods based on table key
            // e.g., migrateCategories, migrateUsers
            $methodName = 'migrate' . ucfirst(str_replace('_', '', $tableKey)); // Assumes snake_case table keys mapped to camelCase methods

            if (method_exists($this, $methodName)) {
                $this->line('');
                $this->info("Processing table: {$tableKey}");
                // Consider disabling Eloquent events for the relevant model
                // Example: if ($tableKey === 'users') { User::unsetEventDispatcher(); }

                if ($truncateTables) {
                    // IMPORTANT: Ensure you are on the correct NEW database connection
                    DB::connection(config('database.default'))->table($tableKey)->truncate(); // Assumes new table name matches key
                    $this->warn("Truncated new '{$tableKey}' table (dev mode).");
                    Log::info("Truncated new '{$tableKey}' table (dev mode).");
                }

                $this->$methodName($chunkSize);

                // Re-enable Eloquent events if they were disabled
                // Example: if ($tableKey === 'users') { User::setEventDispatcher(app('events')); }
                $this->info("Finished processing table: {$tableKey}");
                Log::info("Finished processing table: {$tableKey}");

            } else {
                $this->warn("Skipping table '{$tableKey}': Migration method '{$methodName}' not found.");
                Log::warning("Skipping table '{$tableKey}': Migration method '{$methodName}' not found.");
            }
        }

        $this->info('Old data migration process completed.');
        Log::info('Old data migration process completed.');
        return 0;
    }

    /**
     * Placeholder for migrating categories.
     *
     * @param int $chunkSize
     */
    protected function migrateCategories(int $chunkSize)
    {
        $oldTableName = 'old_categories_table'; // ** ADJUST THE ACTUAL OLD TABLE NAME **
        $newTableName = 'categories';
        $this->info("Migrating data from '{$oldTableName}' to '{$newTableName}'...");
        Log::info("Starting migration for '{$oldTableName}' to '{$newTableName}'.");
        $totalMigrated = 0;
        $skippedCount = 0;

        // Pre-load any necessary parent ID caches if this table depends on others (not typical for categories)

        try {
            DB::connection('old_mysql')->table($oldTableName)
                ->orderBy('id') // Assuming 'id' is the primary key in the old table
                ->chunk($chunkSize, function ($records) use ($newTableName, &$totalMigrated, &$skippedCount) {
                    $batchToInsert = [];
                    foreach ($records as $record) {
                        try {
                            // Data Transformation
                            $transformedRecord = [
                                'name' => $record->category_name, // Example: map old column 'category_name' to 'name'
                                'description' => $record->category_description, // Example
                                // 'old_id' => $record->id, // Good practice to keep track of old ID
                                'created_at' => $record->date_created ?? Carbon::now(), // Preserve or set timestamps
                                'updated_at' => $record->date_modified ?? Carbon::now(),
                            ];

                            // Add more transformation logic here (type casting, etc.)

                            $batchToInsert[] = $transformedRecord;
                        } catch (\Exception $e) {
                            Log::error("Error transforming record from {$oldTableName} with old ID {$record->id}: " . $e->getMessage(), (array)$record);
                            $skippedCount++;
                            continue;
                        }
                    }

                    if (!empty($batchToInsert)) {
                        // IMPORTANT: Ensure you are on the correct NEW database connection for insert
                        DB::connection(config('database.default'))->table($newTableName)->insert($batchToInsert);
                        $totalMigrated += count($batchToInsert);
                        $this->line("Inserted " . count($batchToInsert) . " records into {$newTableName}. Total migrated so far: {$totalMigrated}");
                    }
                });
        } catch (\Exception $e) {
            $this->error("ERROR during migration of '{$oldTableName}': " . $e->getMessage());
            Log::error("FATAL ERROR during migration of '{$oldTableName}': " . $e->getMessage());
            return; // Or handle more gracefully
        }

        // Store ID mappings if other tables depend on this one
        // Example: $this->loadIdCache('categories', 'old_id_column_name');

        Log::info("Finished migration for '{$oldTableName}' to '{$newTableName}'. Total migrated: {$totalMigrated}, Skipped: {$skippedCount}.");
        $this->info("Completed migrating {$newTableName}. Total: {$totalMigrated}, Skipped: {$skippedCount}.");
    }

    /**
     * Placeholder for migrating users.
     *
     * @param int $chunkSize
     */
    protected function migrateUsers(int $chunkSize)
    {
        $oldTableName = 'old_users_table'; // ** ADJUST THE ACTUAL OLD TABLE NAME **
        $newTableName = 'users';
        $this->info("Migrating data from '{$oldTableName}' to '{$newTableName}'...");
        Log::info("Starting migration for '{$oldTableName}' to '{$newTableName}'.");
        $totalMigrated = 0;
        $skippedCount = 0;

        try {
            DB::connection('old_mysql')->table($oldTableName)
                ->orderBy('user_id') // ** ADJUST old primary key **
                ->chunk($chunkSize, function ($records) use ($newTableName, &$totalMigrated, &$skippedCount) {
                    $batchToInsert = [];
                    foreach ($records as $record) {
                        try {
                            $transformedRecord = [
                                'name' => $record->full_name, // Example
                                'email' => $record->email_address, // Example
                                'password' => $record->password_hash, // Note: Passwords might need rehashing if not compatible
                                'role' => strtolower($record->user_role ?? 'customer'), // Example, with default
                                'email_verified_at' => $record->is_verified ? Carbon::now() : null, // Example
                                // 'old_id' => $record->user_id, // Good practice
                                'created_at' => $record->registration_date ?? Carbon::now(),
                                'updated_at' => $record->last_login_date ?? Carbon::now(),
                            ];

                            // Add more transformation logic here
                            // Password considerations: If old hashes are bcrypt, they might be usable.
                            // Otherwise, you might need to set all passwords to a temporary one and force reset.

                            $batchToInsert[] = $transformedRecord;
                        } catch (\Exception $e) {
                            Log::error("Error transforming record from {$oldTableName} with old ID {$record->user_id}: " . $e->getMessage(), (array)$record);
                            $skippedCount++;
                            continue;
                        }
                    }

                    if (!empty($batchToInsert)) {
                        DB::connection(config('database.default'))->table($newTableName)->insert($batchToInsert);
                        $totalMigrated += count($batchToInsert);
                        $this->line("Inserted " . count($batchToInsert) . " records into {$newTableName}. Total migrated so far: {$totalMigrated}");
                    }
                });
        } catch (\Exception $e) {
            $this->error("ERROR during migration of '{$oldTableName}': " . $e->getMessage());
            Log::error("FATAL ERROR during migration of '{$oldTableName}': " . $e->getMessage());
            return;
        }

        // Example: $this->loadIdCache('users', 'old_id'); // If 'old_id' was stored

        Log::info("Finished migration for '{$oldTableName}' to '{$newTableName}'. Total migrated: {$totalMigrated}, Skipped: {$skippedCount}.");
        $this->info("Completed migrating {$newTableName}. Total: {$totalMigrated}, Skipped: {$skippedCount}.");
    }

    /**
     * Placeholder for migrating products.
     * This will require foreign key lookups for categories.
     *
     * @param int $chunkSize
     */
    protected function migrateProducts(int $chunkSize)
    {
        $oldTableName = 'old_products_table'; // ** ADJUST **
        $newTableName = 'products';
        $this->info("Migrating data from '{$oldTableName}' to '{$newTableName}'...");
        Log::info("Starting migration for '{$oldTableName}' to '{$newTableName}'.");
        $totalMigrated = 0;
        $skippedCount = 0;

        // Pre-load category ID cache
        // Assumes 'old_id' was saved during category migration, and 'id' is new PK
        if (empty($this->idCache['categories'])) {
             $this->idCache['categories'] = DB::table('categories')->pluck('id', 'old_id')->all();
             $this->info("Cached category IDs for mapping: " . count($this->idCache['categories']) . " found.");
             if(count($this->idCache['categories']) == 0) {
                $this->warn("Warning: Category ID cache is empty. Ensure categories are migrated and 'old_id' column exists and is populated in the new 'categories' table.");
             }
        }


        try {
            DB::connection('old_mysql')->table($oldTableName)
                ->orderBy('product_id') // ** ADJUST old primary key **
                ->chunk($chunkSize, function ($records) use ($newTableName, &$totalMigrated, &$skippedCount) {
                    $batchToInsert = [];
                    foreach ($records as $record) {
                        try {
                            $newCategoryId = null;
                            if (isset($record->old_category_id_fk) && isset($this->idCache['categories'][$record->old_category_id_fk])) {
                                $newCategoryId = $this->idCache['categories'][$record->old_category_id_fk];
                            } else if (isset($record->old_category_id_fk)) {
                                Log::warning("Missing new category ID for old category ID: {$record->old_category_id_fk} from product with old ID {$record->product_id}");
                                // Decide: skip product, set category_id to null if nullable, or assign a default?
                            }

                            $transformedRecord = [
                                'name' => $record->product_title,
                                'description' => $record->product_details,
                                'price' => $record->sale_price,
                                'image_path' => $record->image_url, // May need path adjustments
                                'stock_quantity' => $record->qty_on_hand,
                                'category_id' => $newCategoryId,
                                // 'old_id' => $record->product_id,
                                'created_at' => $record->created_date ?? Carbon::now(),
                                'updated_at' => $record->modified_date ?? Carbon::now(),
                            ];
                            $batchToInsert[] = $transformedRecord;
                        } catch (\Exception $e) {
                            Log::error("Error transforming record from {$oldTableName} with old ID {$record->product_id}: " . $e->getMessage(), (array)$record);
                            $skippedCount++;
                            continue;
                        }
                    }
                    if (!empty($batchToInsert)) {
                        DB::connection(config('database.default'))->table($newTableName)->insert($batchToInsert);
                        $totalMigrated += count($batchToInsert);
                         $this->line("Inserted " . count($batchToInsert) . " records into {$newTableName}. Total migrated so far: {$totalMigrated}");
                    }
                });
        } catch (\Exception $e) {
            $this->error("ERROR during migration of '{$oldTableName}': " . $e->getMessage());
            Log::error("FATAL ERROR during migration of '{$oldTableName}': " . $e->getMessage());
            return;
        }

        Log::info("Finished migration for '{$oldTableName}' to '{$newTableName}'. Total migrated: {$totalMigrated}, Skipped: {$skippedCount}.");
        $this->info("Completed migrating {$newTableName}. Total: {$totalMigrated}, Skipped: {$skippedCount}.");
    }


    // Add more protected methods for other tables:
    // protected function migrateCustomers(int $chunkSize) { ... }
    // protected function migrateOrders(int $chunkSize) { ... } // Will need user_id, customer_id lookups
    // protected function migrateOrderItems(int $chunkSize) { ... } // Will need order_id, product_id lookups

    /**
     * Helper to load and cache Old ID -> New ID mappings.
     * Assumes the new table has an 'old_id' column storing the original ID.
     *
     * @param string $tableKey The key for the idCache (e.g., 'categories')
     * @param string $oldIdColumnNameInNewTable The column name in the NEW table that stores the OLD ID.
     * @param string $newIdColumnNameInNewTable The primary key of the NEW table (usually 'id').
     */
    protected function loadIdCache(string $tableKey, string $oldIdColumnNameInNewTable, string $newIdColumnNameInNewTable = 'id')
    {
        $this->info("Caching IDs for '{$tableKey}'...");
        // Ensure we're connecting to the new database for this lookup
        $this->idCache[$tableKey] = DB::connection(config('database.default'))
                                        ->table($tableKey) // Assumes new table name matches key
                                        ->whereNotNull($oldIdColumnNameInNewTable)
                                        ->pluck($newIdColumnNameInNewTable, $oldIdColumnNameInNewTable)
                                        ->all();
        $this->info("Cached " . count($this->idCache[$tableKey]) . " ID mappings for '{$tableKey}'.");
        if(count($this->idCache[$tableKey]) == 0) {
            $this->warn("Warning: ID cache for '{$tableKey}' is empty. Ensure '{$oldIdColumnNameInNewTable}' column exists and is populated in the new '{$tableKey}' table.");
        }
    }
}
