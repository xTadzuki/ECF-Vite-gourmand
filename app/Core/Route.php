<?php
// app/Core/Route.php

final class Route
{
    public const HOME = 'home';
    public const MENUS = 'menus';
    public const MENU_SHOW = 'menu_show';
    public const MENUS_JSON = 'menus_json';

    public const CONTACT = 'contact';
    public const MENTIONS = 'mentions';
    public const CGV = 'cgv';

    public const REGISTER = 'register';
    public const LOGIN = 'login';
    public const LOGOUT = 'logout';
    public const FORGOT = 'forgot';
    public const RESET = 'reset';

    public const ORDER_CREATE = 'order_create';

    public const USER_ORDERS = 'user_orders';
    public const USER_ORDER_SHOW = 'user_order_show';
    public const USER_ORDER_EDIT = 'user_order_edit';
    public const USER_ORDER_CANCEL = 'user_order_cancel';
    public const USER_PROFILE = 'user_profile';

    public const EMPLOYEE = 'employee';
    public const EMPLOYEE_UPDATE = 'employee_update';
    public const EMPLOYEE_CANCEL = 'employee_cancel';
    public const EMPLOYEE_REVIEWS = 'employee_reviews';
    public const EMPLOYEE_REVIEW_ACTION = 'employee_review_action';

    public const ADMIN = 'admin';
    public const ADMIN_STATS_JSON = 'admin_stats_json';

    public const ADMIN_MENUS = 'admin_menus';
    public const ADMIN_MENU_CREATE = 'admin_menu_create';
    public const ADMIN_MENU_STORE = 'admin_menu_store';
    public const ADMIN_MENU_EDIT = 'admin_menu_edit';
    public const ADMIN_MENU_UPDATE = 'admin_menu_update';
    public const ADMIN_MENU_DELETE = 'admin_menu_delete';
    public const ADMIN_CREATE = 'admin_create';
    public const ADMIN_TOGGLE = 'admin_toggle';
}
