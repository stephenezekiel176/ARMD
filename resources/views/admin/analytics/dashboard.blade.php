@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Analytics Dashboard</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Monitor system performance and user engagement</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="refreshAnalytics()" 
                        class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg hover-transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh
                </button>
                <a href="{{ route('admin.analytics.export') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg hover-transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export
                </a>
            </div>
        </div>
    </div>

    <!-- System Health -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">System Health</h2>
        <div id="health-status" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Health status will be loaded here -->
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-primary-100 dark:bg-primary-900 rounded-lg p-3">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</h3>
                    <p id="total-users" class="text-2xl font-bold text-gray-900 dark:text-gray-100">-</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-lg p-3">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Today</h3>
                    <p id="active-users-today" class="text-2xl font-bold text-gray-900 dark:text-gray-100">-</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 rounded-lg p-3">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Courses</h3>
                    <p id="total-courses" class="text-2xl font-bold text-gray-900 dark:text-gray-100">-</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900 rounded-lg p-3">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg Response Time</h3>
                    <p id="avg-response-time" class="text-2xl font-bold text-gray-900 dark:text-gray-100">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Activity Chart -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">User Activity Trends</h2>
            <div id="user-activity-chart" class="h-64">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        <!-- Course Engagement Chart -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Course Engagement</h2>
            <div id="course-engagement-chart" class="h-64">
                <canvas id="engagementChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Roles -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">User Roles</h2>
            <div id="user-roles" class="space-y-3">
                <!-- User roles will be loaded here -->
            </div>
        </div>

        <!-- Department Distribution -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Department Distribution</h2>
            <div id="department-distribution" class="space-y-3">
                <!-- Department distribution will be loaded here -->
            </div>
        </div>

        <!-- System Performance -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">System Performance</h2>
            <div id="system-performance" class="space-y-3">
                <!-- System performance will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
// Analytics Dashboard JavaScript
let analyticsData = {};

async function loadAnalytics() {
    try {
        const response = await fetch('/admin/analytics/api');
        analyticsData = await response.json();
        
        updateMetrics();
        updateHealthStatus();
        updateCharts();
        updateDetailedMetrics();
    } catch (error) {
        console.error('Error loading analytics:', error);
    }
}

function updateMetrics() {
    document.getElementById('total-users').textContent = analyticsData.user_activity?.total_users || 0;
    document.getElementById('active-users-today').textContent = analyticsData.user_activity?.active_users_today || 0;
    document.getElementById('total-courses').textContent = analyticsData.course_engagement?.total_courses || 0;
    document.getElementById('avg-response-time').textContent = (analyticsData.system_performance?.application?.average_response_time || 0) + 'ms';
}

function updateHealthStatus() {
    const healthContainer = document.getElementById('health-status');
    const health = analyticsData.system_performance?.health || { checks: {} };
    
    const checks = [
        { key: 'database', label: 'Database', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
        { key: 'cache', label: 'Cache', icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10' },
        { key: 'disk', label: 'Storage', icon: 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4' },
        { key: 'memory', label: 'Memory', icon: 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z' },
    ];
    
    healthContainer.innerHTML = checks.map(check => {
        const status = health.checks[check.key]?.status || 'unknown';
        const statusColor = {
            healthy: 'text-green-600 bg-green-100 dark:bg-green-900',
            warning: 'text-yellow-600 bg-yellow-100 dark:bg-yellow-900',
            critical: 'text-red-600 bg-red-100 dark:bg-red-900',
            unknown: 'text-gray-600 bg-gray-100 dark:bg-gray-900',
        }[status];
        
        return `
            <div class="flex items-center justify-between p-3 rounded-lg border ${statusColor}">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${check.icon}"/>
                    </svg>
                    <span class="text-sm font-medium">${check.label}</span>
                </div>
                <span class="text-xs font-medium uppercase">${status}</span>
            </div>
        `;
    }).join('');
}

function updateCharts() {
    // User Activity Chart
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    const activityData = analyticsData.user_activity?.login_trends || [];
    
    new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: activityData.slice(-7).map(d => new Date(d.date).toLocaleDateString()),
            datasets: [{
                label: 'Active Users',
                data: activityData.slice(-7).map(d => d.active_users),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    
    // Course Engagement Chart
    const engagementCtx = document.getElementById('engagementChart').getContext('2d');
    const activity = analyticsData.course_engagement?.course_activity || [];
    
    new Chart(engagementCtx, {
        type: 'bar',
        data: {
            labels: activity.slice(-7).map(d => new Date(d.date).toLocaleDateString()),
            datasets: [{
                label: 'Enrollments',
                data: activity.slice(-7).map(d => d.enrollments),
                backgroundColor: 'rgba(34, 197, 94, 0.8)'
            }, {
                label: 'Submissions',
                data: activity.slice(-7).map(d => d.submissions),
                backgroundColor: 'rgba(168, 85, 247, 0.8)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

function updateDetailedMetrics() {
    // User Roles
    const rolesContainer = document.getElementById('user-roles');
    const roles = analyticsData.user_activity?.user_roles || {};
    
    rolesContainer.innerHTML = Object.entries(roles).map(([role, count]) => `
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400 capitalize">${role}</span>
            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">${count}</span>
        </div>
    `).join('');
    
    // Department Distribution
    const deptContainer = document.getElementById('department-distribution');
    const departments = analyticsData.user_activity?.department_distribution || {};
    
    deptContainer.innerHTML = Object.entries(departments).map(([dept, count]) => `
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">${dept}</span>
            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">${count}</span>
        </div>
    `).join('');
    
    // System Performance
    const perfContainer = document.getElementById('system-performance');
    const perf = analyticsData.system_performance?.application || {};
    
    perfContainer.innerHTML = `
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">Uptime</span>
            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">${perf.uptime || 'N/A'}</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">Requests (1h)</span>
            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">${perf.request_count || 0}</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">Memory Usage</span>
            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">${formatBytes(perf.memory_usage || 0)}</span>
        </div>
    `;
}

function formatBytes(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function refreshAnalytics() {
    loadAnalytics();
}

// Load analytics on page load
document.addEventListener('DOMContentLoaded', loadAnalytics);

// Auto-refresh every 5 minutes
setInterval(loadAnalytics, 300000);
</script>

<!-- Include Chart.js for charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

