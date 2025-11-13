<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations for high-performance indexing (500k users)
     */
    public function up(): void
    {
        // Optimize users table
        Schema::table('users', function (Blueprint $table) {
            $table->index(['email'], 'idx_users_email');
            $table->index(['department_id'], 'idx_users_department');
            $table->index(['role'], 'idx_users_role');
            $table->index(['created_at'], 'idx_users_created');
            $table->index(['updated_at'], 'idx_users_updated');
            $table->index(['department_id', 'role'], 'idx_users_dept_role');
        });

        // Optimize courses table
        Schema::table('courses', function (Blueprint $table) {
            $table->index(['department_id'], 'idx_courses_department');
            $table->index(['facilitator_id'], 'idx_courses_facilitator');
            $table->index(['type'], 'idx_courses_type');
            $table->index(['is_previewable'], 'idx_courses_previewable');
            $table->index(['created_at'], 'idx_courses_created');
            $table->index(['department_id', 'type'], 'idx_courses_dept_type');
            $table->index(['facilitator_id', 'department_id'], 'idx_courses_fac_dept');
        });

        // Optimize enrollments table (critical for performance)
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index(['user_id'], 'idx_enrollments_user');
            $table->index(['course_id'], 'idx_enrollments_course');
            $table->index(['status'], 'idx_enrollments_status');
            $table->index(['created_at'], 'idx_enrollments_created');
            $table->index(['user_id', 'course_id'], 'idx_enrollments_user_course');
            $table->index(['user_id', 'status'], 'idx_enrollments_user_status');
            $table->index(['course_id', 'status'], 'idx_enrollments_course_status');
        });

        // Optimize submissions table
        if (Schema::hasTable('submissions')) {
            Schema::table('submissions', function (Blueprint $table) {
                $table->index(['user_id'], 'idx_submissions_user');
                $table->index(['assessment_id'], 'idx_submissions_assessment');
                $table->index(['created_at'], 'idx_submissions_created');
                $table->index(['score'], 'idx_submissions_score');
                $table->index(['user_id', 'assessment_id'], 'idx_submissions_user_assessment');
            });
        }

        // Optimize assessments table
        if (Schema::hasTable('assessments')) {
            Schema::table('assessments', function (Blueprint $table) {
                $table->index(['course_id'], 'idx_assessments_course');
                $table->index(['facilitator_id'], 'idx_assessments_facilitator');
                $table->index(['created_at'], 'idx_assessments_created');
            });
        }

        // Add database-level optimizations
        $this->addDatabaseOptimizations();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_email');
            $table->dropIndex('idx_users_department');
            $table->dropIndex('idx_users_role');
            $table->dropIndex('idx_users_created');
            $table->dropIndex('idx_users_updated');
            $table->dropIndex('idx_users_dept_role');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('idx_courses_department');
            $table->dropIndex('idx_courses_facilitator');
            $table->dropIndex('idx_courses_type');
            $table->dropIndex('idx_courses_previewable');
            $table->dropIndex('idx_courses_created');
            $table->dropIndex('idx_courses_dept_type');
            $table->dropIndex('idx_courses_fac_dept');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('idx_enrollments_user');
            $table->dropIndex('idx_enrollments_course');
            $table->dropIndex('idx_enrollments_status');
            $table->dropIndex('idx_enrollments_created');
            $table->dropIndex('idx_enrollments_user_course');
            $table->dropIndex('idx_enrollments_user_status');
            $table->dropIndex('idx_enrollments_course_status');
        });

        if (Schema::hasTable('submissions')) {
            Schema::table('submissions', function (Blueprint $table) {
                $table->dropIndex('idx_submissions_user');
                $table->dropIndex('idx_submissions_assessment');
                $table->dropIndex('idx_submissions_created');
                $table->dropIndex('idx_submissions_score');
                $table->dropIndex('idx_submissions_user_assessment');
            });
        }

        if (Schema::hasTable('assessments')) {
            Schema::table('assessments', function (Blueprint $table) {
                $table->dropIndex('idx_assessments_course');
                $table->dropIndex('idx_assessments_facilitator');
                $table->dropIndex('idx_assessments_created');
            });
        }
    }

    /**
     * Add MySQL-specific optimizations
     */
    private function addDatabaseOptimizations(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // Set optimal MySQL settings for high concurrency
            $optimizations = [
                "SET GLOBAL innodb_buffer_pool_size = '2G'",
                "SET GLOBAL innodb_log_file_size = '512M'",
                "SET GLOBAL innodb_flush_log_at_trx_commit = 2",
                "SET GLOBAL innodb_flush_method = 'O_DIRECT'",
                "SET GLOBAL query_cache_size = '256M'",
                "SET GLOBAL query_cache_type = 1",
                "SET GLOBAL max_connections = 2000",
                "SET GLOBAL thread_cache_size = 100",
                "SET GLOBAL table_open_cache = 4000",
            ];

            foreach ($optimizations as $sql) {
                try {
                    DB::statement($sql);
                } catch (\Exception $e) {
                    // Log but don't fail migration if setting can't be applied
                    \Log::warning("Could not apply MySQL optimization: {$sql}. Error: " . $e->getMessage());
                }
            }
        }
    }
};
