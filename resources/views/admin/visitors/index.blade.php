@extends('layouts.admin')

@section('title', 'Visitor Statistics')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-eye"></i> Visitor Statistics
            </h1>
            <p class="text-muted">Track and analyze website visitors</p>
        </div>
        <div>
            <button class="btn btn-primary" onclick="refreshStats()">
                <i class="bi bi-arrow-clockwise"></i> Refresh
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
                                Total Visitors
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
                                Unique IPs
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
                                Total Visits
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
            <h6 class="m-0 font-weight-bold text-primary">Recent Visitors</h6>
            <button class="btn btn-sm btn-outline-danger" onclick="confirmClearLogs()">
                <i class="bi bi-trash"></i> Clear Old Logs
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="visitorsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>IP Address</th>
                            <th>User Agent</th>
                            <th>Visited At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="visitorsTableBody">
                        <tr>
                            <td colspan="5" class="text-center">
                                <span class="spinner-border spinner-border-sm"></span> Loading...
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
            showError('Failed to load statistics');
        }
    }

    // Load recent visitors
    async function loadRecentVisitors() {
        try {
            const response = await fetch(`{{ route('admin.visitors.recent') }}`);
            const data = await response.json();

            const tbody = document.getElementById('visitorsTableBody');
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">No visitors yet</td></tr>';
                return;
            }

            tbody.innerHTML = data.map(visitor => `
                <tr>
                    <td>${visitor.id}</td>
                    <td><code>${visitor.ip_address}</code></td>
                    <td><small>${visitor.user_agent || 'N/A'}</small></td>
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
                '<tr><td colspan="5" class="text-center text-danger">Failed to load visitors</td></tr>';
        }
    }

    // Refresh statistics
    function refreshStats() {
        loadStats();
        loadRecentVisitors();
        showSuccess('Statistics refreshed');
    }

    // Delete visitor log
    async function deleteVisitor(id) {
        if (!confirm('Are you sure you want to delete this visitor log?')) {
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
                showSuccess('Visitor log deleted');
                loadRecentVisitors();
                loadStats();
            } else {
                showError('Failed to delete visitor log');
            }
        } catch (error) {
            console.error('Error deleting visitor:', error);
            showError('Failed to delete visitor log');
        }
    }

    // Confirm clear old logs
    function confirmClearLogs() {
        if (confirm('Are you sure you want to clear logs older than 90 days?')) {
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
                showSuccess(`Deleted ${data.deleted} old logs`);
                loadRecentVisitors();
                loadStats();
            } else {
                showError('Failed to clear old logs');
            }
        } catch (error) {
            console.error('Error clearing logs:', error);
            showError('Failed to clear old logs');
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

