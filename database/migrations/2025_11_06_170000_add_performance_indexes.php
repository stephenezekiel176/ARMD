<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Performance indexes for users table
            $table->index(['role'], 'idx_users_role');
            $table->index(['department_id'], 'idx_users_department_id');
            $table->index(['email'], 'idx_users_email');
            $table->index(['role', 'department_id'], 'idx_users_role_department');
            $table->index(['created_at'], 'idx_users_created_at');
        });

        Schema::table('departments', function (Blueprint $table) {
            // Performance indexes for departments table
            $table->index(['name'], 'idx_departments_name');
            $table->index(['created_at'], 'idx_departments_created_at');
        });

        Schema::table('courses', function (Blueprint $table) {
            // Performance indexes for courses table
            $table->index(['facilitator_id'], 'idx_courses_facilitator_id');
            $table->index(['department_id'], 'idx_courses_department_id');
            $table->index(['created_at'], 'idx_courses_created_at');
            $table->index(['type'], 'idx_courses_type');
            $table->index(['facilitator_id', 'department_id'], 'idx_courses_facilitator_department');
            $table->index(['department_id', 'type'], 'idx_courses_department_type');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            // Performance indexes for enrollments table
            $table->index(['user_id'], 'idx_enrollments_user_id');
            $table->index(['course_id'], 'idx_enrollments_course_id');
            $table->index(['status'], 'idx_enrollments_status');
            $table->index(['created_at'], 'idx_enrollments_created_at');
            $table->index(['user_id', 'course_id'], 'idx_enrollments_user_course');
            $table->index(['course_id', 'status'], 'idx_enrollments_course_status');
        });

        Schema::table('assessments', function (Blueprint $table) {
            // Performance indexes for assessments table
            $table->index(['course_id'], 'idx_assessments_course_id');
            $table->index(['facilitator_id'], 'idx_assessments_facilitator_id');
            $table->index(['type'], 'idx_assessments_type');
            $table->index(['due_date'], 'idx_assessments_due_date');
            $table->index(['created_at'], 'idx_assessments_created_at');
        });

        Schema::table('submissions', function (Blueprint $table) {
            // Performance indexes for submissions table
            $table->index(['user_id'], 'idx_submissions_user_id');
            $table->index(['assessment_id'], 'idx_submissions_assessment_id');
            $table->index(['graded_at'], 'idx_submissions_graded_at');
            $table->index(['created_at'], 'idx_submissions_created_at');
            $table->index(['user_id', 'assessment_id'], 'idx_submissions_user_assessment');
        });

        // Messages table indexes commented out - adjust based on actual table structure
        // Schema::table('messages', function (Blueprint $table) {
        //     $table->index(['sender_id'], 'idx_messages_sender_id');
        //     $table->index(['recipient_id'], 'idx_messages_recipient_id');
        //     $table->index(['department_id'], 'idx_messages_department_id');
        //     $table->index(['created_at'], 'idx_messages_created_at');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role');
            $table->dropIndex('idx_users_department_id');
            $table->dropIndex('idx_users_email');
            $table->dropIndex('idx_users_role_department');
            $table->dropIndex('idx_users_created_at');
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropIndex('idx_departments_name');
            $table->dropIndex('idx_departments_created_at');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('idx_courses_facilitator_id');
            $table->dropIndex('idx_courses_department_id');
            $table->dropIndex('idx_courses_created_at');
            $table->dropIndex('idx_courses_type');
            $table->dropIndex('idx_courses_facilitator_department');
            $table->dropIndex('idx_courses_department_type');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('idx_enrollments_user_id');
            $table->dropIndex('idx_enrollments_course_id');
            $table->dropIndex('idx_enrollments_status');
            $table->dropIndex('idx_enrollments_created_at');
            $table->dropIndex('idx_enrollments_user_course');
            $table->dropIndex('idx_enrollments_course_status');
        });

        Schema::table('assessments', function (Blueprint $table) {
            $table->dropIndex('idx_assessments_course_id');
            $table->dropIndex('idx_assessments_facilitator_id');
            $table->dropIndex('idx_assessments_type');
            $table->dropIndex('idx_assessments_due_date');
            $table->dropIndex('idx_assessments_created_at');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropIndex('idx_submissions_user_id');
            $table->dropIndex('idx_submissions_assessment_id');
            $table->dropIndex('idx_submissions_graded_at');
            $table->dropIndex('idx_submissions_created_at');
            $table->dropIndex('idx_submissions_user_assessment');
        });

        // Schema::table('messages', function (Blueprint $table) {
        //     $table->dropIndex('idx_messages_sender_id');
        //     $table->dropIndex('idx_messages_recipient_id');
        //     $table->dropIndex('idx_messages_department_id');
        //     $table->dropIndex('idx_messages_created_at');
        // });
    }
};
