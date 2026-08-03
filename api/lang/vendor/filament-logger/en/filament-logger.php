<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Navigation & Resource
    |--------------------------------------------------------------------------
    */

    'nav.group' => 'Administration',
    'nav.log.label' => 'Activity Log',
    'nav.log.icon' => 'heroicon-o-clipboard-document-list',
    'resource.label.log' => 'Activity log',
    'resource.label.logs' => 'Activity logs',
    'resource.label.user' => 'User',
    'resource.label.subject' => 'Subject',
    'resource.label.subject_type' => 'Subject type',
    'resource.label.description' => 'Description',
    'resource.label.type' => 'Type',
    'resource.label.event' => 'Event',
    'resource.label.risk' => 'Risk',
    'resource.label.logged_at' => 'Logged At',
    'resource.label.search' => 'Search',
    'resource.placeholder.search' => 'Search description, subject, user or tags',
    'resource.label.properties' => 'Properties',
    'resource.label.old' => 'Old',
    'resource.label.new' => 'New',
    'resource.label.old_value' => 'Old Value',
    'resource.label.new_value' => 'New Value',
    'resource.label.properties_hint' => 'Can be key or value',
    'resource.label.old_attributes' => 'Old Attribute or Value: ',
    'resource.label.new_attributes' => 'New Attribute or Value: ',

    /*
    |--------------------------------------------------------------------------
    | Tabs
    |--------------------------------------------------------------------------
    */

    'tab.all' => 'All Activity',
    'tab.high_risk' => 'High Risk',
    'tab.destructive' => 'Deletes',
    'tab.auth_issues' => 'Auth Issues',
    'tab.failed_logins' => 'Failed Logins',
    'tab.destructive_recent' => 'Recent Destructive',
    'tab.auth_anomalies' => 'Auth Anomalies',

    /*
    |--------------------------------------------------------------------------
    | Stored values
    |--------------------------------------------------------------------------
    */

    'risk.high' => 'High',
    'risk.medium' => 'Medium',
    'risk.low' => 'Low',

    'log_name.resource' => 'Resource',
    'log_name.model' => 'Model',
    'log_name.access' => 'Access',
    'log_name.notification' => 'Notification',
    'log_name.custom' => 'Custom',

    'event.created' => 'Created',
    'event.updated' => 'Updated',
    'event.deleted' => 'Deleted',
    'event.force_deleted' => 'Force Deleted',
    'event.restored' => 'Restored',
    'event.replicated' => 'Replicated',
    'event.login' => 'Login',
    'event.logout' => 'Logout',
    'event.failed_login' => 'Failed Login',
    'event.lockout' => 'Lockout',
    'event.password_reset' => 'Password Reset',
    'event.two_factor_recovery' => 'Two Factor Recovery',
    'event.sent' => 'Sent',
    'event.failed' => 'Failed',

    /*
    |--------------------------------------------------------------------------
    | Widgets
    |--------------------------------------------------------------------------
    */

    'widget.overview.heading' => 'Activity Overview',
    'widget.overview.total' => 'Total Activity',
    'widget.overview.total_description' => 'Last :days days',
    'widget.overview.high_risk' => 'High Risk',
    'widget.overview.high_risk_description' => 'High-risk actions detected',
    'widget.overview.failed_logins' => 'Failed Logins',
    'widget.overview.failed_logins_description' => 'Authentication failures',
    'widget.overview.unique_actors' => 'Unique Actors',
    'widget.overview.unique_actors_description' => 'Distinct causers recorded',
    'widget.trend.heading' => 'Activity Trend',
    'widget.trend.dataset' => 'Activity',
    'widget.top_users.heading' => 'Top Users',
    'widget.top_events.heading' => 'Top Events',
    'widget.events_dataset' => 'Events',
    'widget.high_risk.heading' => 'High-Risk Actions',
    'widget.high_risk.dataset' => 'High-Risk Events',
];
