@extends('layouts.app')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Activity Logs</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="activityLogsTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Event</th>
                        <th>Subject</th>
                        <th>Caused By</th>
                        <th>Properties</th>
                        <th>Created At</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#activityLogsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/api/logs',
                    type: 'GET',
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'event', name: 'event', render: function(data, type, row) {
                            return `<span class="badge badge-${data === 'created' ? 'success' : (data === 'updated' ? 'warning' : 'secondary')}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                        }},
                    { data: 'subject', name: 'subject', render: function(data, type, row) {
                            if (row.subject_type === 'App\\Models\\Product') {
                                return `<strong>${data ? data.name : 'N/A'}</strong><small class="d-block text-muted">Product</small>`;
                            } else if (row.subject_type === 'App\\Models\\Ingredient') {
                                return `<strong>${data ? data.name : 'N/A'}</strong><small class="d-block text-muted">Ingredient</small>`;
                            } else {
                                return `<strong>${data ? data.name : 'N/A'}</strong><small class="d-block text-muted">${row.subject_type.split('\\').pop()}</small>`;
                            }
                        }},
                    { data: 'causer', name: 'causer', render: function(data, type, row) {
                            return data ? data.name : 'System';
                        }},
                    { data: 'properties', name: 'properties', render: function(data, type, row) {
                            return `<button class="btn btn-sm btn-info" onclick="viewLogProperties(${JSON.stringify(data).replace(/"/g, '&quot;')})">View Properties</button>`;
                        }},
                    { data: 'created_at', name: 'created_at', render: function(data, type, row) {
                            return moment(data).format('MMMM DD YYYY HH:mm');
                        }}
                ]
            });
        });

        function viewLogProperties(properties) {
            let formattedHtml = '<div class="log-properties-container">';

            try {
                if (typeof properties === 'string') {
                    properties = JSON.parse(properties);
                }

                if (properties.attributes && !properties.old) {
                    formattedHtml += `
                        <div class="created-event">
                            <h4>Created Record Details</h4>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Attribute</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${Object.entries(properties.attributes).map(([key, value]) => `
                                        <tr>
                                            <td><strong>${key}</strong></td>
                                            <td>${value !== null ? value : '<em>null</em>'}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    `;
                } else if (properties.old && properties.attributes) {
                    formattedHtml += `
                        <div class="updated-event">
                            <h4>Updated Record Details</h4>
                            <table class="table table-bordered comparison-table">
                                <thead>
                                    <tr>
                                        <th class="text-danger w-50">Old Values</th>
                                        <th class="text-success w-50">New Values</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${(() => {
                        const oldKeys = Object.keys(properties.old);
                        const newKeys = Object.keys(properties.attributes);
                        const allKeys = [...new Set([...oldKeys, ...newKeys])];

                        return allKeys.map(key => `
                                            <tr>
                                                <td class="text-danger">
                                                    <strong>${key}:</strong>
                                                    ${properties.old[key] !== undefined
                            ? (properties.old[key] !== null ? properties.old[key] : '<em>null</em>')
                            : '<em>No previous value</em>'}
                                                </td>
                                                <td class="text-success">
                                                    <strong>${key}:</strong>
                                                    ${properties.attributes[key] !== undefined
                            ? (properties.attributes[key] !== null ? properties.attributes[key] : '<em>null</em>')
                            : '<em>Value removed</em>'}
                                                </td>
                                            </tr>
                                        `).join('');
                    })()}
                                </tbody>
                            </table>
                        </div>
                    `;
                } else if (properties.old) {
                    formattedHtml += `
                        <div class="deleted-event">
                            <h4>Deleted Record Details</h4>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Attribute</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${Object.entries(properties.old).map(([key, value]) => `
                                        <tr>
                                            <td><strong>${key}</strong></td>
                                            <td>${value !== null ? value : '<em>null</em>'}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    `;
                } else {
                    formattedHtml += '<pre>' + JSON.stringify(properties, null, 2) + '</pre>';
                }
            } catch (error) {
                formattedHtml += '<pre>Unable to parse properties: ' + error.message + '</pre>';
            }

            formattedHtml += '</div>';

            Swal.fire({
                title: 'Log Properties',
                html: formattedHtml,
                width: 800,
                padding: '2em',
                background: '#f4f4f4',
                backdrop: 'rgba(0,0,0,0.1)',
                showCloseButton: true,
                customClass: {
                    popup: 'log-properties-popup'
                }
            });
        }
    </script>
@endpush
