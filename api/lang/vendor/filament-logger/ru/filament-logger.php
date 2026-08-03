<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Navigation & Resource
    |--------------------------------------------------------------------------
    */

    'nav.group' => 'Администрирование',
    'nav.log.label' => 'Лог действий',
    'nav.log.icon' => 'heroicon-o-clipboard-document-list',
    'resource.label.log' => 'Запись журнала',
    'resource.label.logs' => 'Журнал активности',
    'resource.label.user' => 'Пользователь',
    'resource.label.subject' => 'Объект',
    'resource.label.subject_type' => 'Тип объекта',
    'resource.label.description' => 'Описание',
    'resource.label.type' => 'Тип',
    'resource.label.event' => 'Событие',
    'resource.label.risk' => 'Риск',
    'resource.label.logged_at' => 'Время',
    'resource.label.search' => 'Поиск',
    'resource.placeholder.search' => 'Поиск по описанию, объекту, пользователю или тегам',
    'resource.label.properties' => 'Свойства',
    'resource.label.old' => 'Было',
    'resource.label.new' => 'Стало',
    'resource.label.old_value' => 'Прежнее значение',
    'resource.label.new_value' => 'Новое значение',
    'resource.label.properties_hint' => 'Можно указать ключ или значение',
    'resource.label.old_attributes' => 'Прежний атрибут или значение: ',
    'resource.label.new_attributes' => 'Новый атрибут или значение: ',

    /*
    |--------------------------------------------------------------------------
    | Tabs
    |--------------------------------------------------------------------------
    */

    'tab.all' => 'Вся активность',
    'tab.high_risk' => 'Высокий риск',
    'tab.destructive' => 'Удаления',
    'tab.auth_issues' => 'Проблемы входа',
    'tab.failed_logins' => 'Неудачные входы',
    'tab.destructive_recent' => 'Недавние удаления',
    'tab.auth_anomalies' => 'Аномалии входа',

    /*
    |--------------------------------------------------------------------------
    | Stored values
    |--------------------------------------------------------------------------
    */

    'risk.high' => 'Высокий',
    'risk.medium' => 'Средний',
    'risk.low' => 'Низкий',

    'log_name.resource' => 'Ресурс',
    'log_name.model' => 'Модель',
    'log_name.access' => 'Доступ',
    'log_name.notification' => 'Уведомление',
    'log_name.custom' => 'Прочее',

    'event.created' => 'Создание',
    'event.updated' => 'Изменение',
    'event.deleted' => 'Удаление',
    'event.force_deleted' => 'Полное удаление',
    'event.restored' => 'Восстановление',
    'event.replicated' => 'Копирование',
    'event.login' => 'Вход',
    'event.logout' => 'Выход',
    'event.failed_login' => 'Неудачный вход',
    'event.lockout' => 'Блокировка',
    'event.password_reset' => 'Сброс пароля',
    'event.two_factor_recovery' => 'Восстановление 2FA',
    'event.sent' => 'Отправлено',
    'event.failed' => 'Ошибка отправки',

    /*
    |--------------------------------------------------------------------------
    | Widgets
    |--------------------------------------------------------------------------
    */

    'widget.overview.heading' => 'Обзор активности',
    'widget.overview.total' => 'Всего событий',
    'widget.overview.total_description' => 'За последние :days дн.',
    'widget.overview.high_risk' => 'Высокий риск',
    'widget.overview.high_risk_description' => 'Обнаружено действий с высоким риском',
    'widget.overview.failed_logins' => 'Неудачные входы',
    'widget.overview.failed_logins_description' => 'Ошибки аутентификации',
    'widget.overview.unique_actors' => 'Уникальных пользователей',
    'widget.overview.unique_actors_description' => 'Различных инициаторов действий',
    'widget.trend.heading' => 'Динамика активности',
    'widget.trend.dataset' => 'Активность',
    'widget.top_users.heading' => 'Топ пользователей',
    'widget.top_events.heading' => 'Топ событий',
    'widget.events_dataset' => 'События',
    'widget.high_risk.heading' => 'Действия с высоким риском',
    'widget.high_risk.dataset' => 'События высокого риска',
];
