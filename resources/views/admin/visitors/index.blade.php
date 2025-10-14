@extends('layouts.admin')

@section('title', __('admin.visitor_statistics'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-eye"></i> {{ __('admin.visitor_statistics') }}
            </h1>
            <p class="text-muted">{{ __('admin.track_analyze_website_visitors') }}</p>
        </div>
        <div>
            <button class="btn btn-primary" onclick="refreshStats()">
                <i class="bi bi-arrow-clockwise"></i> {{ __('admin.refresh') }}
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Visitors -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ __('admin.total_visitors') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalVisitors">
                                <span class="spinner-border spinner-border-sm"></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unique IPs -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ __('admin.unique_ips') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="uniqueIps">
                                <span class="spinner-border spinner-border-sm"></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-globe fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Visits -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                {{ __('admin.total_visits') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalVisits">
                                <span class="spinner-border spinner-border-sm"></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Visitors Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('admin.recent_visitors') }}</h6>
            <button class="btn btn-sm btn-outline-danger" onclick="confirmClearLogs()">
                <i class="bi bi-trash"></i> {{ __('admin.clear_old_logs') }}
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="visitorsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('admin.id') }}</th>
                            <th>{{ __('admin.ip_address') }}</th>
                            <th>{{ __('admin.user_agent') }}</th>
                            <th>{{ __('admin.visited_at') }}</th>
                            <th>{{ __('admin.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="visitorsTableBody">
                        <tr>
                            <td colspan="5" class="text-center">
                                <span class="spinner-border spinner-border-sm"></span> {{ __('admin.loading') }}...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const API_BASE = '{{ url('/api') }}';
    const TRANSLATIONS = {
        failedToLoadStatistics: @json(__('admin.failed_to_load_statistics')),
        noVisitorsYet: @json(__('admin.no_visitors_yet')),
        nA: @json(__('admin.n_a')),
        failedToLoadVisitors: @json(__('admin.failed_to_load_visitors')),
        statisticsRefreshed: @json(__('admin.statistics_refreshed')),
        confirmDeleteVisitorLog: @json(__('admin.confirm_delete_visitor_log')),
        visitorLogDeleted: @json(__('admin.visitor_log_deleted')),
        failedToDeleteVisitorLog: @json(__('admin.failed_to_delete_visitor_log')),
        confirmClearLogsOlderThan90Days: @json(__('admin.confirm_clear_logs_older_than_90_days')),
        deleted: @json(__('admin.deleted')),
        oldLogs: @json(__('admin.old_logs')),
        failedToClearOldLogs: @json(__('admin.failed_to_clear_old_logs'))
    };

    // Load statistics on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadStats();
        loadRecentVisitors();
    });

    // Load visitor statistics
    async function loadStats() {
        try {
            const response = await fetch(`${API_BASE}/visitor/stats`);
            const data = await response.json();

            document.getElementById('totalVisitors').textContent = data.total_visitors.toLocaleString();
            document.getElementById('uniqueIps').textContent = data.unique_ips.toLocaleString();
            document.getElementById('totalVisits').textContent = data.total_visits.toLocaleString();
        } catch (error) {
            console.error('Error loading stats:', error);
            showError(TRANSLATIONS.failedToLoadStatistics);
        }
    }

    // Load recent visitors
    async function loadRecentVisitors() {
        try {
            const response = await fetch(`{{ route('admin.visitors.recent') }}`);
            const data = await response.json();

            const tbody = document.getElementById('visitorsTableBody');
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">' + TRANSLATIONS.noVisitorsYet + '</td></tr>';
                return;
            }

            tbody.innerHTML = data.map(visitor => `
                <tr>
                    <td>${visitor.id}</td>
                    <td><code>${visitor.ip_address}</code></td>
                    <td><small>${visitor.user_agent || TRANSLATIONS.nA}</small></td>
                    <td>${new Date(visitor.visited_at).toLocaleString()}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="deleteVisitor(${visitor.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        } catch (error) {
            console.error('Error loading visitors:', error);
            document.getElementById('visitorsTableBody').innerHTML = 
                '<tr><td colspan="5" class="text-center text-danger">' + TRANSLATIONS.failedToLoadVisitors + '</td></tr>';
        }
    }

    // Refresh statistics
    function refreshStats() {
        loadStats();
        loadRecentVisitors();
        showSuccess(TRANSLATIONS.statisticsRefreshed);
    }

    // Delete visitor log
    async function deleteVisitor(id) {
        if (!confirm(TRANSLATIONS.confirmDeleteVisitorLog)) {
            return;
        }

        try {
            const response = await fetch(`{{ url('/admin/visitors') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                showSuccess(TRANSLATIONS.visitorLogDeleted);
                loadRecentVisitors();
                loadStats();
            } else {
                showError(TRANSLATIONS.failedToDeleteVisitorLog);
            }
        } catch (error) {
            console.error('Error deleting visitor:', error);
            showError('Failed to delete visitor log');
        }
    }

    // Confirm clear old logs
    function confirmClearLogs() {
        if (confirm(TRANSLATIONS.confirmClearLogsOlderThan90Days)) {
            clearOldLogs();
        }
    }

    // Clear old logs
    async function clearOldLogs() {
        try {
            const response = await fetch(`{{ route('admin.visitors.clear-old') }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                showSuccess(TRANSLATIONS.deleted + ' ' + data.deleted + ' ' + TRANSLATIONS.oldLogs);
                loadRecentVisitors();
                loadStats();
            } else {
                showError(TRANSLATIONS.failedToClearOldLogs);
            }
        } catch (error) {
            console.error('Error clearing logs:', error);
            showError(TRANSLATIONS.failedToClearOldLogs);
        }
    }

    // Show success message
    function showSuccess(message) {
        // You can use your existing notification system
        alert(message);
    }

    // Show error message
    function showError(message) {
        // You can use your existing notification system
        alert(message);
    }
</script>
@endpush
@endsection

